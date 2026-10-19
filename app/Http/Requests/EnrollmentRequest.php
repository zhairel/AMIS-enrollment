<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $studentType = strtoupper((string) $this->input('student_type'));
        $isOld = $studentType === 'OLD';
        $isNewOrTransferee = in_array($studentType, ['NEW', 'TRANSFEREE'], true);

        return [
            'student_type' => ['required', 'string', Rule::in(['OLD', 'NEW', 'TRANSFEREE', 'old', 'new', 'transferee'])],
            'grade_level' => ['required_without:year_level', 'nullable', 'string', 'max:50'],
            'year_level' => ['required_without:grade_level', 'nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', Rule::in(['DRAFT', 'SUBMITTED', 'ENROLLED', 'REJECTED'])],

            // Conditional rule: If student_type == 'OLD', requires a valid, existing student_number
            'student_number' => [
                Rule::requiredIf($isOld),
                'nullable',
                'string',
                'max:50',
                Rule::exists('students', 'student_number'),
            ],

            // Conditional rule: If student_type == 'NEW', requires full demographic details and guardian info
            'first_name' => [Rule::requiredIf($isNewOrTransferee), 'nullable', 'string', 'max:100'],
            'last_name' => [Rule::requiredIf($isNewOrTransferee), 'nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'email' => [Rule::requiredIf($isNewOrTransferee), 'nullable', 'email', 'max:255'],
            'contact_number' => [Rule::requiredIf($isNewOrTransferee), 'nullable', 'string', 'max:50'],

            // Guardian info
            'guardian_name' => [Rule::requiredIf($isNewOrTransferee), 'nullable', 'string', 'max:255'],
            'guardian_contact' => [Rule::requiredIf($isNewOrTransferee), 'nullable', 'string', 'max:50'],
            'guardian_relationship' => ['nullable', 'string', 'max:100'],
            'guardian_email' => ['nullable', 'email', 'max:255'],

            // Optional demographic details
            'gender' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'birthdate' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'student_type.required' => 'Student type is required (OLD, NEW, or TRANSFEREE).',
            'student_type.in' => 'Student type must be one of: OLD, NEW, or TRANSFEREE.',
            'grade_level.required_without' => 'The grade level or year level is required.',
            'year_level.required_without' => 'The grade level or year level is required.',
            'student_number.required_if' => 'The student number is required for returning (OLD) students.',
            'student_number.exists' => 'The specified student number was not found in our records.',
            'first_name.required_if' => 'First name is required for new students.',
            'last_name.required_if' => 'Last name is required for new students.',
            'email.required_if' => 'Email address is required for new students.',
            'contact_number.required_if' => 'Contact number is required for new students.',
            'guardian_name.required_if' => 'Guardian name is required for new students.',
            'guardian_contact.required_if' => 'Guardian contact number is required for new students.',
        ];
    }
}
