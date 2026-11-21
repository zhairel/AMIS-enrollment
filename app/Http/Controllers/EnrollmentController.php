<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentApplicant;
use App\Http\Requests\Enrollment\SaveDraftRequest;
use App\Http\Requests\Enrollment\SubmitEnrollmentRequest;
use App\Services\Enrollment\AffidavitPdfService;
use App\Services\Enrollment\EnrollmentApplicationService;
use App\Services\Enrollment\EnrollmentNotificationService;
use App\Services\Workflow\WorkflowEngineService;
use Illuminate\Http\Request;
use App\Services\Enrollment\GradeShiftService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class EnrollmentController extends Controller
{
    public function __construct(
        private EnrollmentApplicationService $applications,
        private GradeShiftService $gradeShifts,
        private AffidavitPdfService $affidavitPdf,
    ) {
    }

    public function showEnrollmentForm(Request $request)
    {
        $user = Auth::user();
        $startFresh = $request->boolean('fresh')
            && !$request->route('applicant')
            && !$request->query('applicant')
            && !$request->input('applicant_id');

        if ($startFresh) {
            session()->forget('current_enrollment_applicant_id');
            $applicant = null;
        } else {
            $applicant = $this->applications->resolveForUser($user, $request, editableFirst: true);
        }

        if ($applicant && !in_array($applicant->status, EnrollmentApplicationService::EDITABLE_STATUSES, true)) {
            return redirect()->route('enrollment.dashboard')
                ->with('info', 'This application has already been finalized and is locked for review.');
        }

        $hasErrors = session()->has('errors') && session('errors')->any();
        $rejectionFixSteps = $this->rejectionFixSteps($applicant);
        $initialStep = $hasErrors
            ? 8
            : ($rejectionFixSteps ? min(array_keys($rejectionFixSteps)) : ($applicant ? min((int) ($applicant->last_step ?? 1), 8) : 1));
        $completedSteps = $hasErrors
            ? range(1, 8)
            : ($applicant ? range(1, min((int) ($applicant->last_step ?? 1), 8)) : []);

        $completedGradeLevel = null;
        $disabledGradeLevels = [];
        if ($applicant && $applicant->student_type === 'Old') {
            $identifier = $applicant->amis_student_id ?: ($applicant->lrn !== 'NA' ? $applicant->lrn : null);
            if ($identifier) {
                $oldStudent = \App\Models\Student::where('student_number', $identifier)
                    ->orWhere('student_number', str_replace('-', '', $identifier))
                    ->orWhereHas('applicant', fn($q) => $q->where('lrn', $identifier))
                    ->first();
                if ($oldStudent && $oldStudent->grade_level) {
                    $prog = self::getGradeProgression($oldStudent->grade_level);
                    $completedGradeLevel = $prog['previous'];
                    $disabledGradeLevels = $prog['disabled'];
                }
            }
        }

        return view('enrollment.form', [
            'gradeLevels' => $this->gradeShifts->getGradeLevels(),
            'shifts' => $this->gradeShifts->getShifts(),
            'applicant' => $applicant,
            'initialStep' => $initialStep,
            'completedSteps' => $completedSteps,
            'rejectionFixSteps' => $rejectionFixSteps,
            'startFresh' => $startFresh,
            'siblingData' => $this->applications->getSiblingReusableData($user, $applicant),
            'completedGradeLevel' => $completedGradeLevel,
            'disabledGradeLevels' => $disabledGradeLevels,
        ]);
    }

    private function rejectionFixSteps($applicant): array
    {
        if (!$applicant || $applicant->status !== 'rejected') {
            return [];
        }

        $stepLabels = [
            'registration_form' => [2, 'Registration form'],
            'student_information' => [2, 'Student information'],
            'address' => [3, 'Address or contact details'],
            'parent_information' => [4, 'Parent or guardian details'],
            'medical_information' => [5, 'Medical or emergency details'],
            'documents' => [6, 'Documents'],
            'photo_2x2' => [6, '2x2 picture'],
            'birth_cert' => [6, 'Birth certificate'],
            'report_card' => [6, 'Report card'],
            'marriage_contract' => [6, 'Marriage contract'],
            'medical_record' => [6, 'Medical record'],
            'affidavit' => [6, 'Affidavit'],
            'payment_proof' => [7, 'Payment proof'],
        ];

        $fixSteps = [];

        foreach (($applicant->document_statuses ?? []) as $key => $status) {
            if ($status !== 'rejected' || !isset($stepLabels[$key])) {
                continue;
            }

            [$step, $label] = $stepLabels[$key];
            $fixSteps[$step][] = $label;
        }

        if (!$fixSteps && filled($applicant->review_remarks)) {
            $fixSteps[2][] = 'Application details';
        }

        ksort($fixSteps);

        return collect($fixSteps)
            ->map(fn (array $labels) => array_values(array_unique($labels)))
            ->all();
    }

    /**
     * Determine next grade level and disabled completed grades for an old student.
     */
    public static function getGradeProgression(?string $completedGrade): array
    {
        $order = [
            'Kinder 1',
            'Kinder 2',
            'Grade 1',
            'Grade 2',
            'Grade 3',
            'Grade 4',
            'Grade 5',
            'Grade 6',
            'Grade 7',
            'Grade 8',
            'Grade 9',
            'Grade 10',
            'Grade 11',
            'Grade 12',
        ];

        if (empty($completedGrade)) {
            return [
                'previous' => null,
                'next' => null,
                'disabled' => [],
            ];
        }

        $cleanInput = trim(preg_replace('/\s+/', ' ', $completedGrade));
        $matchedIndex = false;

        foreach ($order as $i => $grade) {
            if (strcasecmp($grade, $cleanInput) === 0) {
                $matchedIndex = $i;
                break;
            }
        }

        if ($matchedIndex === false) {
            if (preg_match('/(?:k|kinder|kindergarten)\s*([12])/i', $cleanInput, $m)) {
                $matchedIndex = (int) $m[1] - 1;
            } elseif (preg_match('/(?:grade|g)\s*(\d+)/i', $cleanInput, $m)) {
                $num = (int) $m[1];
                if ($num >= 1 && $num <= 12) {
                    $matchedIndex = 1 + $num;
                }
            }
        }

        if ($matchedIndex === false) {
            return [
                'previous' => $completedGrade,
                'next' => $completedGrade,
                'disabled' => [$completedGrade],
            ];
        }

        $previousGrade = $order[$matchedIndex];
        $nextGrade = isset($order[$matchedIndex + 1]) ? $order[$matchedIndex + 1] : $order[$matchedIndex];
        $disabled = array_slice($order, 0, $matchedIndex + 1);

        return [
            'previous' => $previousGrade,
            'next' => $nextGrade,
            'disabled' => $disabled,
        ];
    }

    public function startNewApplication(Request $request)
    {
        $applicant = $this->applications->startNewFor(Auth::user());

        if (!$applicant) {
            return redirect()->route('enrollment.dashboard')
                ->with('info', 'Please complete your current child application first before adding another child.');
        }

        $params = ['applicant' => $applicant->id];
        if ($request->has('duplicate')) {
            $params['duplicate'] = $request->query('duplicate');
        }

        return redirect()->route('enrollment.form.child', $params)
            ->with('success', 'New child enrollment draft started. This stays grouped with your parent account.');
    }

    public function getShiftsForGrade(string $grade)
    {
        $shifts = $this->gradeShifts->getShiftsForGrade($grade);
        return response()->json($shifts);
    }

    public function showAffidavit(Request $request)
    {
        $user = Auth::user();
        $applicant = $this->applications->resolveForUser($user, $request, editableFirst: true);

        if (!$applicant || !in_array($applicant->status, EnrollmentApplicationService::EDITABLE_STATUSES, true)) {
            return redirect()->route('enrollment.form')
                ->with('info', 'Please save the student details first before preparing the affidavit.');
        }

        return view('enrollment.affidavit', [
            'applicant' => $applicant,
            'user' => $user,
            'storedAffidavitData' => $applicant->affidavit_data ?? [],
            'affidavitFields' => $this->affidavitPdf->fields(),
            'signatureField' => $this->affidavitPdf->signatureField(),
            'signatureNameField' => $this->affidavitPdf->signatureNameField(),
        ]);
    }

    public function saveAffidavitDraft(Request $request)
    {
        $user = $request->user();
        $applicant = $this->applications->resolveForUser($user, $request, editableFirst: true);

        if (!$applicant || !in_array($applicant->status, EnrollmentApplicationService::EDITABLE_STATUSES, true)) {
            return response()->json([
                'success' => false,
                'message' => 'This affidavit draft can no longer be edited.',
            ], 422);
        }

        $validated = $request->validate($this->affidavitRules(requireSignature: false));
        $normalized = $this->normalizeAffidavitData($validated);

        if (!empty($normalized['signature_data'])) {
            $signatureError = $this->signatureValidationError($normalized['signature_data']);

            if ($signatureError) {
                return response()->json([
                    'success' => false,
                    'message' => $signatureError,
                ], 422);
            }
        }

        $applicant->update([
            'affidavit_data' => $this->affidavitDraftData($normalized),
            'last_step' => max((int) ($applicant->last_step ?? 1), 6),
        ]);

        return response()->json([
            'success' => true,
            'last_saved' => $applicant->fresh()->updated_at?->diffForHumans(),
        ]);
    }

    public function storeAffidavit(Request $request)
    {
        $user = $request->user();
        $applicant = $this->applications->resolveForUser($user, $request, editableFirst: true);

        if (!$applicant || !in_array($applicant->status, EnrollmentApplicationService::EDITABLE_STATUSES, true)) {
            return redirect()->route('enrollment.form')
                ->with('info', 'Please save the student details first before preparing the affidavit.');
        }

        $validated = $this->normalizeAffidavitData($request->validate($this->affidavitRules(requireSignature: true)));
        $signatureError = $this->signatureValidationError($validated['signature_data']);

        if ($signatureError) {
            return back()
                ->withInput()
                ->withErrors(['signature_data' => $signatureError]);
        }

        $oldPath = $applicant->affidavit_url;
        $path = 'documents/' . $applicant->id . '/affidavit-undertaking-' . now()->format('YmdHis') . '.pdf';

        Storage::disk('public')->put($path, $this->affidavitPdf->build($validated));

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        $applicant->update([
            'affidavit_url' => $path,
            'affidavit_data' => $this->affidavitDraftData($validated),
            'last_step' => max((int) ($applicant->last_step ?? 1), 6),
        ]);

        return redirect()
            ->route('enrollment.form.child', ['applicant' => $applicant->id])
            ->with('success', 'Signed affidavit saved. You can continue the enrollment documents step.');
    }

    private function affidavitRules(bool $requireSignature): array
    {
        $required = $requireSignature ? 'required' : 'nullable';

        return [
            'guardian_name' => $required . '|string|max:255',
            'guardian_relationship' => $required . '|string|max:100',
            'guardian_address' => $required . '|string|max:500',
            'student_name' => $required . '|string|max:255',
            'grade_level' => $required . '|string|max:100',
            'school_year' => $required . '|string|max:20',
            'missing_credential' => $required . '|string|max:120',
            'other_missing_credential' => 'nullable|string|max:120',
            'reason' => $required . '|string|max:600',
            'commitment_date' => $required . '|string|max:120',
            'attested_day' => $required . '|string|max:10',
            'attested_month' => $required . '|string|max:30',
            'attested_place' => $required . '|string|max:120',
            'govt_id_presented' => 'nullable|string|max:120',
            'id_number' => 'nullable|string|max:120',
            'date_issued' => 'nullable|string|max:120',
            'govt_id_type' => 'nullable|string|max:120',
            'govt_id_number' => 'nullable|string|max:120',
            'govt_id_date' => 'nullable|string|max:120',
            'signature_data' => $required . '|string',
            'agreement' => $requireSignature ? 'accepted' : 'nullable',
        ];
    }

    private function normalizeAffidavitData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }

        if (($data['missing_credential'] ?? '') === 'Other') {
            $data['missing_credential'] = $data['other_missing_credential'] ?: 'Other required admission document';
        }

        return $data;
    }

    private function signatureValidationError(?string $signatureData): ?string
    {
        if (!is_string($signatureData) || !preg_match('/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/', $signatureData)) {
            return 'Please sign inside the signature box before saving.';
        }

        if (strlen($signatureData) > 2_000_000) {
            return 'The signature image is too large. Please clear and sign again.';
        }

        return null;
    }

    private function affidavitDraftData(array $data): array
    {
        return collect($data)
            ->only([
                'guardian_name',
                'guardian_relationship',
                'guardian_address',
                'student_name',
                'grade_level',
                'school_year',
                'missing_credential',
                'other_missing_credential',
                'reason',
                'commitment_date',
                'attested_day',
                'attested_month',
                'attested_place',
                'govt_id_presented',
                'id_number',
                'date_issued',
                'govt_id_type',
                'govt_id_number',
                'govt_id_date',
                'signature_data',
            ])
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->all();
    }

    public function saveDraft(SaveDraftRequest $request)
    {
        $result = $this->applications->saveDraft(
            $request->user(),
            $request,
            $request->draftData()
        );

        if (is_array($result) && ($result['duplicate'] ?? false)) {
            return response()->json([
                'success' => false,
                'duplicate' => true,
                'message' => $result['message'],
            ], 409);
        }

        return response()->json([
            'success' => true,
            'applicant_id' => $result->id,
            'percentage' => $result->completion_percentage,
            'last_step' => $result->last_step,
        ]);
    }

    public function discardDraft(Request $request)
    {
        $discardedApplicant = $this->applications->discardDraft($request->user(), $request);

        if (!$discardedApplicant) {
            return redirect()->route('enrollment.dashboard')
                ->with('error', 'We could not find that child draft. Please refresh and try again.');
        }

        return redirect()->route('enrollment.dashboard')
            ->with('success', 'Draft cleared. You can start a fresh enrollment form.')
            ->with('clear_draft_cache', true)
            ->with('discarded_draft_applicant_id', $discardedApplicant->id);
    }

    public function removeDraftDocument(Request $request, string $document)
    {
        $removed = $this->applications->removeDraftDocument($request->user(), $request, $document);

        if (!$removed) {
            return response()->json([
                'success' => false,
                'message' => 'This document can no longer be removed.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Document removed.',
        ]);
    }

    public function submitEnrollment(SubmitEnrollmentRequest $request)
    {
        $user = $request->user();

        try {
            $result = $this->applications->submit($user, $request, $request->enrollmentData());
        } catch (\Illuminate\Validation\ValidationException $e) {
            $isDuplicate = isset($e->errors()['duplicate']);
            $msg = collect($e->errors())->flatten()->first() ?? 'Please review your application details.';
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'duplicate' => $isDuplicate,
                    'code' => $isDuplicate ? 'DUPLICATE_ENROLLMENT' : 'VALIDATION_ERROR',
                    'message' => $msg,
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        if (is_array($result) && ($result['duplicate'] ?? false)) {
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'duplicate' => true,
                    'code' => 'DUPLICATE_ENROLLMENT',
                    'message' => $result['message'],
                ], 422);
            }

            return redirect()->back()
                ->withErrors(['duplicate' => $result['message']])
                ->withInput();
        }

        $applicant = $result;

        // Process payment proof if uploaded during Step 7 Preview
        if ($request->hasFile('payment_receipt') || $request->hasFile('receipt') || $request->filled('amount')) {
            if ($request->hasFile('payment_receipt')) {
                $request->files->set('receipts', [$request->file('payment_receipt')]);
            } elseif ($request->hasFile('receipt')) {
                $request->files->set('receipts', [$request->file('receipt')]);
            }

            $method = $request->input('method', 'gcash');
            $amount = (float) $request->input('amount', 4000.00);
            $referenceNo = $request->input('reference_no');

            $receiptPath = null;
            if ($request->hasFile('receipts')) {
                $yearFallback = \App\Services\Enrollment\GradeShiftService::activeSchoolYear();
                $familyFolder = 'family_' . strtolower(trim($applicant->last_name)) . '_' . str_replace(' ', '_', strtolower(trim($applicant->school_year ?? $yearFallback)));
                $familyFolder = preg_replace('/[^a-z0-9_\-]+/', '', $familyFolder);
                $lastnameSlug = preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($applicant->last_name)));
                
                $newPaths = [];
                foreach ($request->file('receipts') as $index => $file) {
                    $timestamp = time();
                    $ext = $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin';
                    $filename = 'payment_receipt_' . $lastnameSlug . '_' . $timestamp . '_' . $index . '.' . $ext;
                    
                    $path = $file->storeAs('documents/' . $familyFolder, $filename, 'public');
                    if ($path) {
                        $newPaths[] = $path;
                    }
                }

                if (!empty($newPaths)) {
                    $receiptPath = count($newPaths) === 1 ? $newPaths[0] : json_encode($newPaths);
                }
            }

            $paymentData = [
                'user_id' => $user->id,
                'enrollment_applicant_id' => $applicant->id,
                'method' => in_array($method, ['bdo', 'gcash', 'maya', 'remittance', 'other']) ? $method : 'gcash',
                'payment_provider' => $method,
                'amount' => $amount,
                'reference_no' => $referenceNo,
                'reference_number' => $referenceNo,
                'receipt_url' => $receiptPath,
                'status' => 'pending',
                'remarks' => $request->input('remarks'),
                'paid_at' => now(),
                'verified_at' => null,
            ];

            if ($receiptPath) {
                $applicant->enrollment_fee_receipt_url = $receiptPath;
                $applicant->save();
            }

            if (Schema::hasColumn('payments', 'reference_no')) {
                $paymentData['reference_no'] = $referenceNo;
            }

            $existingPayment = $applicant->payment;
            if ($existingPayment) {
                $existingPayment->update($paymentData);
            } else {
                $applicant->payment()->create($paymentData);
            }

            $documentStatuses = $applicant->document_statuses ?? [];
            $documentStatuses['payment_proof'] = 'pending';
            $applicant->forceFill(['document_statuses' => $documentStatuses])->save();
        }

        $applicant->update([
            'status' => EnrollmentApplicationService::STATUS_SUBMITTED,
            'submitted_at' => $applicant->submitted_at ?? now(),
            'review_remarks' => null,
        ]);

        if ((int) session('current_enrollment_applicant_id') === (int) $applicant->id) {
            session()->forget('current_enrollment_applicant_id');
        }

        try {
            $notifications = app(EnrollmentNotificationService::class);
            $notifications->sendSubmissionConfirmationToAll($applicant, $user->email);
            try {
                app(WorkflowEngineService::class)->fire('enrollment.submitted', $applicant);
            } catch (\Throwable $e) {
                Log::error('Workflow fire error [enrollment.submitted]: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send submission confirmation: ' . $e->getMessage());
        }

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your enrollment application and proof of payment have been submitted successfully!',
                'redirect' => route('enrollment.success', ['applicant' => $applicant->id]),
            ]);
        }

        return redirect()->route('enrollment.success', ['applicant' => $applicant->id])
            ->with('success', 'Your enrollment application and proof of payment have been submitted successfully!');
    }

    public function showFinalizePreview(Request $request)
    {
        $user = $request->user();
        $readyApplications = $this->applications->readyApplications($user);
        $draftApplications = $this->applications->draftApplications($user);

        if ($readyApplications->isEmpty()) {
            return redirect()->route('enrollment.dashboard')
                ->with('info', 'Complete at least one child application before finalizing enrollment.');
        }

        if ($draftApplications->isNotEmpty()) {
            return redirect()->route('enrollment.dashboard')
                ->with('info', 'Please complete all child drafts before finalizing enrollment.');
        }

        return redirect()->route('enrollment.dashboard');
    }

    public function confirmFinalize(
        Request $request,
        EnrollmentNotificationService $notifications
    ) {
        $user = $request->user();
        $readyApplications = $this->applications->readyApplications($user);
        $incompleteApplications = $this->applications->incompleteApplications($readyApplications);

        if ($readyApplications->isEmpty()) {
            return redirect()->route('enrollment.dashboard')
                ->with('info', 'No ready child applications found for final submission.');
        }

        if ($this->applications->draftApplications($user)->isNotEmpty()) {
            return redirect()->route('enrollment.dashboard')
                ->with('info', 'Please complete all child drafts before finalizing enrollment.');
        }

        if ($incompleteApplications->isNotEmpty()) {
            return redirect()->route('enrollment.finalize.preview')
                ->with('error', 'Please complete all required child application fields before final submission.');
        }

        $submittedApplications = $this->applications->finalizeReadyApplications($user);

        foreach ($submittedApplications as $submittedApplication) {
            $notifications->sendSubmissionConfirmationToAll($submittedApplication, $user->email);

            // Fire workflow: enrollment.submitted
            try {
                app(WorkflowEngineService::class)->fire('enrollment.submitted', $submittedApplication);
            } catch (\Throwable $e) {
                Log::error('Workflow fire error [enrollment.submitted]: ' . $e->getMessage());
            }
        }

        return redirect()->route('enrollment.dashboard')
            ->with('success', 'Your enrollment application has been submitted successfully. You may upload your payment proof from the dashboard.');
    }

    public function showSuccess(Request $request)
    {
        $user = Auth::user();
        $applicant = $this->applications->resolveForUser($user, $request);

        if (!$applicant || !in_array($applicant->status, EnrollmentApplicationService::FINAL_STATUSES, true)) {
            $applicant = $user->enrollmentApplicants()
                ->whereIn('status', EnrollmentApplicationService::FINAL_STATUSES)
                ->latest()
                ->first();
        }

        return view('enrollment.success', compact('applicant'));
    }

    public function showDashboard(Request $request)
    {
        $user = Auth::user();
        $applicants = $user->enrollmentApplicants()
            ->with(['payment', 'student'])
            ->oldest()
            ->get();
        $applicant = $this->applications->resolveForUser($user, $request) ?? $applicants->first();
        
        $payment = null;
        if ($applicant) {
            if (Schema::hasColumn('enrollment_applicants', 'family_application_id') && $applicant->family_application_id) {
                $payment = \App\Models\Payment::whereIn('enrollment_applicant_id', function ($query) use ($applicant) {
                    $query->select('id')->from('enrollment_applicants')
                        ->where('family_application_id', $applicant->family_application_id);
                })->first();
            }
            if (!$payment) {
                $payment = $applicant->payment;
            }
        }
        
        $student = $applicant?->student;
        $canAddAnotherChild = $this->applications->canAddAnotherChild($user);
        $readyApplications = $this->applications->readyApplications($user);
        $draftApplications = $this->applications->draftApplications($user);

        return view('enrollment.dashboard', compact(
            'user',
            'applicant',
            'applicants',
            'payment',
            'student',
            'canAddAnotherChild',
            'readyApplications',
            'draftApplications'
        ));
    }

    public function showPayment(Request $request)
    {
        $user = Auth::user();
        $applicant = $this->applications->resolveForUser($user, $request)
            ?? $user->enrollmentApplicants()->latest()->first();

        if (!$this->applications->canAccessPayment($applicant)) {
            return redirect()->route('enrollment.dashboard')
                ->with('info', 'Please complete your enrollment application first.');
        }

        $payment = null;
        if (Schema::hasColumn('enrollment_applicants', 'family_application_id') && $applicant->family_application_id) {
            $payment = \App\Models\Payment::whereIn('enrollment_applicant_id', function ($query) use ($applicant) {
                $query->select('id')->from('enrollment_applicants')
                    ->where('family_application_id', $applicant->family_application_id);
            })->first();
        }
        if (!$payment) {
            $applicant->loadMissing('payment');
            $payment = $applicant->payment;
        }
        $invoiceApplicants = collect([$applicant]);

        if (Schema::hasColumn('enrollment_applicants', 'family_application_id')) {
            $familyApplicationId = $applicant->family_application_id ?: $applicant->id;

            $allFamilyApplicants = $user->enrollmentApplicants()
                ->with('payment')
                ->where(function ($query) use ($familyApplicationId) {
                    $query->where('family_application_id', $familyApplicationId)
                        ->orWhere('id', $familyApplicationId);
                })
                ->oldest()
                ->get();

            $isNewPayment = $applicant->status === 'ready_for_submission';

            $invoiceApplicants = $allFamilyApplicants->filter(function ($item) use ($applicant, $isNewPayment) {
                // Unconditionally include the active applicant
                if ($item->id === $applicant->id) {
                    return true;
                }

                if ($isNewPayment) {
                    // For a new payment transaction, we only include other ready-to-complete sibling drafts
                    return $item->status === 'ready_for_submission';
                } else {
                    // For viewing a submitted/historical payment, we show the other pending/submitted/approved siblings
                    return in_array($item->status, ['pending', 'submitted', 'under_review', 'approved'], true);
                }
            })->values();

            if ($invoiceApplicants->isEmpty()) {
                $invoiceApplicants = collect([$applicant]);
            }
        }

        return view('enrollment.payment', compact('applicant', 'payment', 'invoiceApplicants'));
    }

    public function submitPayment(Request $request)
    {
        $user = Auth::user();
        $applicant = $this->applications->resolveForUser($user, $request)
            ?? $user->enrollmentApplicants()->latest()->first();

        if (!$this->applications->canAccessPayment($applicant)) {
            return redirect()->route('enrollment.dashboard')
                ->with('info', 'Please complete your enrollment application first.');
        }

        $existingPayment = null;
        if (Schema::hasColumn('enrollment_applicants', 'family_application_id') && $applicant->family_application_id) {
            $existingPayment = \App\Models\Payment::whereIn('enrollment_applicant_id', function ($query) use ($applicant) {
                $query->select('id')->from('enrollment_applicants')
                    ->where('family_application_id', $applicant->family_application_id);
            })->first();
        } else {
            $existingPayment = $applicant->payment;
        }

        if ($request->hasFile('receipt')) {
            $request->files->set('receipts', [$request->file('receipt')]);
        }

        $receiptRule = ($existingPayment?->receipt_url || $request->filled('remarks')) ? 'nullable' : 'required';
        $validated = $request->validate([
            'method' => 'required|in:gcash,maya,remittance,bdo,other',
            'reference_no' => 'nullable|string|max:100',
            'amount' => 'required|numeric|min:1|max:999999',
            'remarks' => 'nullable|string|max:1000',
            'receipts' => $receiptRule . '|array',
            'receipts.*' => 'file|mimes:png,jpg,jpeg,webp,pdf',
        ]);

        // Access raw receipt_url using getRawOriginal if possible, or fallback to direct attribute
        $receiptPath = $existingPayment ? ($existingPayment->getRawOriginal('receipt_url') ?? $existingPayment->receipt_url) : null;

        if ($request->hasFile('receipts')) {
            $yearFallback = \App\Services\Enrollment\GradeShiftService::activeSchoolYear();
            $familyFolder = 'family_' . strtolower(trim($applicant->last_name)) . '_' . str_replace(' ', '_', strtolower(trim($applicant->school_year ?? $yearFallback)));
            $familyFolder = preg_replace('/[^a-z0-9_\-]+/', '', $familyFolder);
            $lastnameSlug = preg_replace('/[^a-z0-9]+/', '_', strtolower(trim($applicant->last_name)));
            
            $newPaths = [];
            foreach ($request->file('receipts') as $index => $file) {
                $timestamp = time();
                $ext = $file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin';
                $filename = 'payment_receipt_' . $lastnameSlug . '_' . $timestamp . '_' . $index . '.' . $ext;
                
                $path = $file->storeAs('documents/' . $familyFolder, $filename, 'public');
                if ($path) {
                    $newPaths[] = $path;
                }
            }

            if (!empty($newPaths)) {
                if ($existingPayment) {
                    foreach ($existingPayment->receipt_urls as $oldUrl) {
                        Storage::disk('public')->delete($oldUrl);
                    }
                }
                $receiptPath = count($newPaths) === 1 ? $newPaths[0] : json_encode($newPaths);
            }
        }

        $paymentData = [
            'user_id' => $user->id,
            'enrollment_applicant_id' => $applicant->id,
            'method' => in_array($validated['method'], ['bdo', 'gcash', 'maya', 'remittance', 'other']) ? $validated['method'] : 'gcash',
            'payment_provider' => $validated['method'],
            'amount' => $validated['amount'],
            'reference_no' => $validated['reference_no'] ?? null,
            'reference_number' => $validated['reference_no'] ?? null,
            'receipt_url' => $receiptPath,
            'status' => 'pending',
            'remarks' => $validated['remarks'] ?? null,
            'paid_at' => now(),
            'verified_at' => null,
        ];

        if ($receiptPath) {
            if (Schema::hasColumn('enrollment_applicants', 'enrollment_fee_receipt_url')) {
                $applicant->enrollment_fee_receipt_url = $receiptPath;
                $applicant->save();
            }
        }

        if (Schema::hasColumn('payments', 'reference_no')) {
            $paymentData['reference_no'] = $validated['reference_no'] ?? null;
        }

        if ($existingPayment) {
            $existingPayment->update($paymentData);
        } else {
            $applicant->payment()->create($paymentData);
        }

        $familyChildren = collect([$applicant]);
        if (Schema::hasColumn('enrollment_applicants', 'family_application_id') && $applicant->family_application_id) {
            $familyChildren = EnrollmentApplicant::where('family_application_id', $applicant->family_application_id)->get();
        }

        foreach ($familyChildren as $child) {
            $documentStatuses = $child->document_statuses ?? [];
            $documentStatuses['payment_proof'] = 'pending';
            $child->forceFill(['document_statuses' => $documentStatuses])->save();
        }

        if ($applicant->status === 'ready_for_submission') {
            $this->applications->finalizeReadyApplications($user);
            
            try {
                $notifications = app(EnrollmentNotificationService::class);
                $freshApplicants = $user->enrollmentApplicants()->whereIn('status', ['pending', 'submitted', 'under_review'])->get();
                foreach ($freshApplicants as $submittedApplication) {
                    $notifications->sendSubmissionConfirmationToAll($submittedApplication, $user->email);
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send finalization emails from payment submit: ' . $e->getMessage());
            }
        }

        // Fire workflow: enrollment.payment_submitted
        try {
            app(WorkflowEngineService::class)->fire('enrollment.payment_submitted', $applicant->fresh());
        } catch (\Throwable $e) {
            Log::error('Workflow fire error [enrollment.payment_submitted]: ' . $e->getMessage());
        }

        return redirect()->route('enrollment.dashboard')
            ->with('success', 'Payment proof submitted successfully and applications finalized. The Finance Office will review it within 1-2 business days.');
    }

    public function showClosed()
    {
        return view('enrollment.closed');
    }

    public function checkApplicationStatus(Request $request)
    {
        $user = Auth::user();
        $applicant = $this->applications->resolveForUser($user, $request)
            ?? $user->enrollmentApplicants()->latest()->first();

        return response()->json([
            'status' => $applicant->status ?? 'not_found',
            'submitted_at' => $applicant->created_at ?? null,
            'percentage' => $applicant?->completion_percentage ?? 0,
            'last_step' => $applicant?->last_step ?? 1,
            'last_saved' => $applicant?->updated_at?->diffForHumans() ?? null,
        ]);
    }

    /**
     * Search for an old student record by Student ID / LRN and Date of Birth.
     */
    public function searchOldStudent(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $studentNumber = trim($validated['student_number']);
        $dob = !empty($validated['date_of_birth']) ? $validated['date_of_birth'] : null;

        // Search by student number or LRN
        $student = \App\Models\Student::where('student_number', $studentNumber)
            ->orWhere('student_number', str_replace('-', '', $studentNumber))
            ->orWhereHas('applicant', function ($q) use ($studentNumber) {
                $q->where('lrn', $studentNumber);
            })
            ->with('applicant')
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'No student record found with the given Student ID or LRN (' . $studentNumber . ').',
            ], 404);
        }

        $applicant = $student->applicant;
        if (!$applicant) {
            return response()->json([
                'success' => false,
                'message' => 'No application details found for this student record.',
            ], 404);
        }

        // Verify Date of Birth if provided
        $formattedDob = $applicant->date_of_birth ? $applicant->date_of_birth->format('Y-m-d') : null;
        if ($dob && $formattedDob && $formattedDob !== $dob) {
            return response()->json([
                'success' => false,
                'message' => 'The provided date of birth does not match our records for this student.',
            ], 422);
        }

        $photoUrl = null;
        if (!empty($applicant->photo_2x2_url)) {
            $photoPath = ltrim($applicant->photo_2x2_url, '/');
            if (file_exists(public_path('storage/' . $photoPath))) {
                $photoUrl = asset('storage/' . $photoPath);
            } else {
                $photoUrl = 'https://admin.amis.edu.ph/storage/' . $photoPath;
            }
        }

        $fullName = trim(
            ($applicant->first_name ?? '') . ' ' .
            ($applicant->middle_name ? $applicant->middle_name . ' ' : '') .
            ($applicant->last_name ?? '') .
            ($applicant->suffix ? ' ' . $applicant->suffix : '')
        );

        $previousGradeLevel = $applicant->grade_level ?: $student->grade_level;
        $progression = self::getGradeProgression($previousGradeLevel);
        $nextGradeLevel = $progression['next'];
        $disabledGrades = $progression['disabled'];

        // Normalize Gender ('male'/'MALE'/'M' -> 'Male', 'female'/'FEMALE'/'F' -> 'Female')
        $rawGender = strtolower(trim((string) $applicant->gender));
        $normalizedGender = match (true) {
            in_array($rawGender, ['male', 'm', 'boy'], true) => 'Male',
            in_array($rawGender, ['female', 'f', 'girl'], true) => 'Female',
            default => $applicant->gender ?: 'Male',
        };

        // Resolve sibling record for fallback if parent or address fields were left empty during 2026-2027 batch import
        $sibling = null;
        if (
            (empty($applicant->father_first_name) && empty($applicant->mother_first_name))
            || empty($applicant->place_of_birth)
        ) {
            $sibling = EnrollmentApplicant::where('id', '!=', $applicant->id)
                ->where('last_name', $applicant->last_name)
                ->where(function ($q) {
                    $q->whereNotNull('father_first_name')->where('father_first_name', '!=', '')
                        ->orWhere(function ($q2) {
                            $q2->whereNotNull('mother_first_name')->where('mother_first_name', '!=', '');
                        })
                        ->orWhere(function ($q3) {
                            $q3->whereNotNull('place_of_birth')->where('place_of_birth', '!=', '');
                        });
                })
                ->latest('id')
                ->first();
        }

        // Normalize Address, City, Region/Province, Postal Code, Country, and Place of Birth
        $rawAddress = trim((string) ($applicant->street_address ?: ($applicant->address ?: ($applicant->home_address ?: ($sibling?->street_address ?: $sibling?->address)))));
        $streetAddress = $rawAddress !== '' ? $rawAddress : 'DAVAO CITY';
        $country = trim((string) ($applicant->country ?: ($sibling?->country ?: 'Philippines')));
        $stateProvince = trim((string) ($applicant->state_province ?: ($sibling?->state_province ?? '')));
        $city = trim((string) ($applicant->city ?: ($sibling?->city ?? '')));
        $postalCode = trim((string) ($applicant->postal_code ?: ($sibling?->postal_code ?? '')));

        $upperAddr = strtoupper($streetAddress . ' ' . ($applicant->address ?? ''));
        if ($city === '') {
            if (preg_match('/\b(DAVAO|TALOMO|BUHANGIN|MA-A|MAA|BANGKAL|MATINA|TORIL|TUGBOK|BANKEROHAN|CATALUNAN|SASA|PANACAN|TIBUNGCO|CALINAN|MINTAL|BUCANA|WAAN|ISLA VERDE|AGDAO|CABANTIAN|INDANGAN|MANDUG|LANANG|BAJADA|OBRERO|ECOLAND|ULAS)\b/i', $upperAddr)) {
                $city = 'DAVAO CITY';
                $stateProvince = $stateProvince ?: 'DAVAO REGION (REGION XI)';
                $postalCode = $postalCode ?: '8000';
            } elseif (str_contains($upperAddr, 'COTABATO')) {
                $city = 'COTABATO CITY';
                $stateProvince = $stateProvince ?: 'BARMM';
            } elseif (str_contains($upperAddr, 'TAGUM')) {
                $city = 'TAGUM CITY';
                $stateProvince = $stateProvince ?: 'DAVAO REGION (REGION XI)';
            } elseif (str_contains($upperAddr, 'GENERAL SANTOS') || str_contains($upperAddr, 'GENSAN')) {
                $city = 'GENERAL SANTOS CITY';
                $stateProvince = $stateProvince ?: 'SOCCSKSARGEN (REGION XII)';
            } elseif (str_contains($upperAddr, 'MARAWI')) {
                $city = 'MARAWI CITY';
                $stateProvince = $stateProvince ?: 'BARMM';
            } elseif (str_contains($upperAddr, 'ZAMBOANGA')) {
                $city = 'ZAMBOANGA CITY';
                $stateProvince = $stateProvince ?: 'ZAMBOANGA PENINSULA (REGION IX)';
            } elseif (str_contains($upperAddr, 'QUEZON CITY')) {
                $city = 'QUEZON CITY';
                $stateProvince = $stateProvince ?: 'NATIONAL CAPITAL REGION (NCR)';
            } elseif (str_contains($upperAddr, 'MANILA')) {
                $city = 'MANILA';
                $stateProvince = $stateProvince ?: 'NATIONAL CAPITAL REGION (NCR)';
            } else {
                $city = 'DAVAO CITY';
                $stateProvince = $stateProvince ?: 'DAVAO REGION (REGION XI)';
            }
        }

        $placeOfBirth = trim((string) ($applicant->place_of_birth ?: ($sibling?->place_of_birth ?: ($city ?: 'DAVAO CITY'))));
        $religion = trim((string) ($applicant->religion ?: ($sibling?->religion ?: 'ISLAM')));

        // Normalize Father / Mother Names (recover from sibling or emergency_name if missing in 2026-2027 import)
        $fatherFirst = trim((string) ($applicant->father_first_name ?: ($sibling?->father_first_name ?? '')));
        $fatherMiddle = trim((string) ($applicant->father_middle_name ?: ($sibling?->father_middle_name ?? '')));
        $fatherLast = trim((string) ($applicant->father_last_name ?: ($sibling?->father_last_name ?? '')));
        $motherFirst = trim((string) ($applicant->mother_first_name ?: ($sibling?->mother_first_name ?? '')));
        $motherMiddle = trim((string) ($applicant->mother_middle_name ?: ($sibling?->mother_middle_name ?? '')));
        $motherLast = trim((string) ($applicant->mother_last_name ?: ($sibling?->mother_last_name ?? '')));

        if ($fatherFirst !== '' && $fatherLast === '') {
            $parts = preg_split('/\s+/', $fatherFirst) ?: [];
            if (count($parts) >= 2) {
                $fatherLast = array_pop($parts);
                $fatherFirst = implode(' ', $parts);
            } else {
                $fatherLast = (string) $applicant->last_name;
            }
        }
        if ($motherFirst !== '' && $motherLast === '') {
            $parts = preg_split('/\s+/', $motherFirst) ?: [];
            if (count($parts) >= 2) {
                $motherLast = array_pop($parts);
                $motherFirst = implode(' ', $parts);
            } else {
                $motherLast = (string) $applicant->last_name;
            }
        }

        if ($fatherFirst === '' && $motherFirst === '' && filled($applicant->emergency_name)) {
            $emName = trim(preg_replace('/^FAMILY\s+/i', '', (string) $applicant->emergency_name));
            $emRel = strtoupper(trim((string) $applicant->emergency_relationship));
            $tokens = array_values(array_filter(preg_split('/\s+/', $emName) ?: []));

            $parsedFirst = $tokens[0] ?? 'PARENT';
            $parsedMiddle = '';
            $parsedLast = (string) ($applicant->last_name ?: 'GUARDIAN');

            if (count($tokens) === 2) {
                $parsedFirst = $tokens[0];
                $parsedLast = $tokens[1];
            } elseif (count($tokens) >= 3) {
                $parsedLast = array_pop($tokens);
                $midCandidate = end($tokens);
                if ($midCandidate && (strlen(rtrim($midCandidate, '.')) <= 2 || str_ends_with($midCandidate, '.'))) {
                    $parsedMiddle = array_pop($tokens);
                }
                $parsedFirst = implode(' ', $tokens);
            }

            if (str_contains($emRel, 'FATHER') || str_contains($emRel, 'UNCLE') || str_contains($emRel, 'GRANDFATHER')) {
                $fatherFirst = mb_strtoupper($parsedFirst);
                $fatherMiddle = mb_strtoupper($parsedMiddle);
                $fatherLast = mb_strtoupper($parsedLast);
            } else {
                $motherFirst = mb_strtoupper($parsedFirst);
                $motherMiddle = mb_strtoupper($parsedMiddle);
                $motherLast = mb_strtoupper($parsedLast);
            }
        }

        $mobileCode = trim((string) ($applicant->mobile_country_code ?: '+63'));
        $parentCode = trim((string) ($applicant->parent_country_code ?: '+63'));
        $mobileNumber = trim((string) ($applicant->mobile_number ?: ($applicant->parent_mobile ?: ($applicant->emergency_phone ?: ''))));
        $parentMobile = trim((string) ($applicant->parent_mobile ?: ($applicant->mobile_number ?: ($applicant->emergency_phone ?: ''))));
        $emergencyName = trim((string) ($applicant->emergency_name ?: trim("{$motherFirst} {$motherLast}") ?: trim("{$fatherFirst} {$fatherLast}") ?: "FAMILY {$applicant->last_name}"));
        $emergencyRel = trim((string) ($applicant->emergency_relationship ?: ($motherFirst !== '' ? 'Mother' : 'Parent')));
        $emergencyPhone = trim((string) ($applicant->emergency_phone ?: ($parentMobile ?: $mobileNumber)));
        $medicalConcern = trim((string) ($applicant->medical_has_concern ?: 'No'));

        // Return applicant data for auto-filling
        return response()->json([
            'success' => true,
            'message' => 'Student record found!',
            'student' => [
                'amis_student_id' => $student->student_number,
                'lrn' => $applicant->lrn === 'NA' ? '' : $applicant->lrn,
                'first_name' => $applicant->first_name,
                'last_name' => $applicant->last_name,
                'middle_name' => $applicant->middle_name,
                'suffix' => $applicant->suffix,
                'full_name' => $fullName,
                'photo_url' => $photoUrl,
                'has_existing_photo' => !empty($applicant->photo_2x2_url),
                'previous_grade_level' => $progression['previous'] ?: $previousGradeLevel,
                'grade_level' => $nextGradeLevel,
                'next_grade_level' => $nextGradeLevel,
                'disabled_grade_levels' => $disabledGrades,
                'gender' => $normalizedGender,
                'date_of_birth' => $formattedDob,
                'place_of_birth' => $placeOfBirth,
                'religion' => $religion,
                'ethnicity' => $applicant->ethnicity ?: ($sibling?->ethnicity ?? ''),
                'country' => $country,
                'state_province' => $stateProvince,
                'city' => $city,
                'street_address' => $streetAddress,
                'postal_code' => $postalCode,
                'address' => $applicant->address ?: $streetAddress,
                'email' => $applicant->email,
                'mobile_country_code' => $mobileCode,
                'mobile_number' => $mobileNumber,
                'father_last_name' => $fatherLast,
                'father_first_name' => $fatherFirst,
                'father_middle_name' => $fatherMiddle,
                'father_occupation' => $applicant->father_occupation ?: ($sibling?->father_occupation ?? ''),
                'mother_last_name' => $motherLast,
                'mother_first_name' => $motherFirst,
                'mother_middle_name' => $motherMiddle,
                'mother_occupation' => $applicant->mother_occupation ?: ($sibling?->mother_occupation ?? ''),
                'home_address' => $applicant->home_address ?: $streetAddress,
                'home_state_province' => $applicant->home_state_province ?: $stateProvince,
                'home_city' => $applicant->home_city ?: $city,
                'home_street_address' => $applicant->home_street_address ?: $streetAddress,
                'home_postal_code' => $applicant->home_postal_code ?: $postalCode,
                'parent_country_code' => $parentCode,
                'parent_mobile' => $parentMobile,
                'parent_email' => $applicant->parent_email ?: ($sibling?->parent_email ?? ''),
                'facebook' => $applicant->facebook ?: ($sibling?->facebook ?? ''),
                'whatsapp' => $applicant->whatsapp ?: ($sibling?->whatsapp ?? ''),
                'emergency_name' => $emergencyName,
                'emergency_relationship' => $emergencyRel,
                'emergency_phone' => $emergencyPhone,
                'emergency_instructions' => $applicant->emergency_instructions,
                'medical_has_concern' => $medicalConcern,
                'allergies' => $applicant->allergies,
                'current_medications' => $applicant->current_medications,
                'health_conditions' => $applicant->health_conditions,
                'medical_history' => $applicant->medical_history,
                'med_explanation' => $applicant->med_explanation,
                'family_physician' => $applicant->family_physician,
                'physician_phone' => $applicant->physician_phone,
            ]
        ]);
    }

    /**
     * Handle enrollment submission for Academic Year 2026-2027 via API.
     * POST /api/v1/enrollment/2026-2027
     */
    public function store2026(
        \App\Http\Requests\EnrollmentRequest $request,
        \App\Services\Enrollment\Enrollment2026Service $service
    ): \Illuminate\Http\JsonResponse {
        $enrollment = $service->enroll($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Enrollment for Academic Year 2026-2027 processed successfully.',
            'data' => [
                'id' => $enrollment->id,
                'student_id' => $enrollment->student_id,
                'student_number' => $enrollment->student->student_number,
                'first_name' => $enrollment->student->first_name,
                'last_name' => $enrollment->student->last_name,
                'middle_name' => $enrollment->student->middle_name,
                'email' => $enrollment->student->email,
                'academic_year' => $enrollment->academic_year,
                'student_type' => $enrollment->student_type,
                'grade_level' => $enrollment->grade_level,
                'status' => $enrollment->status,
                'student' => $enrollment->student,
                'created_at' => $enrollment->created_at,
                'updated_at' => $enrollment->updated_at,
            ],
        ], 201);
    }
}

