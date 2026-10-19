<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Maintenance • SY 2027–2028 Preparation - Al Munawwara Islamic School</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://aes.amis.edu.ph/images/AMIS_Logo.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* CSS variables & dark emerald theme */
        :root {
            --emerald-50: #ecfdf5;
            --emerald-100: #d1fae5;
            --emerald-400: #34d399;
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-900: #064e3b;
            --gold-400: #fbbf24;
            --gold-500: #f59e0b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #022c22;
            color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Beautiful glowing dynamic backgrounds */
        .bg-glow-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, rgba(0, 0, 0, 0) 70%);
            z-index: 1;
            pointer-events: none;
            filter: blur(80px);
        }

        .bg-glow-2 {
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, rgba(0, 0, 0, 0) 70%);
            z-index: 1;
            pointer-events: none;
            filter: blur(80px);
        }

        /* Container & Glassmorphism Card */
        .container {
            width: 100%;
            max-width: 680px;
            padding: 24px;
            z-index: 2;
            text-align: center;
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card {
            background: rgba(2, 44, 34, 0.55);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 44px 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 40px rgba(5, 150, 105, 0.1);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--emerald-500), var(--gold-400), var(--emerald-500));
            background-size: 200% auto;
            animation: gradientMove 6s linear infinite;
        }

        /* Logo styling & animation */
        .logo-wrapper {
            margin-bottom: 24px;
            position: relative;
            display: inline-block;
        }

        .logo-bg-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 130px;
            height: 130px;
            background: rgba(16, 185, 129, 0.25);
            border-radius: 50%;
            filter: blur(24px);
            z-index: -1;
            animation: pulseGlow 3s ease-in-out infinite alternate;
        }

        .logo {
            width: 105px;
            height: 105px;
            object-fit: contain;
            animation: floatLogo 4s ease-in-out infinite alternate;
        }

        /* Typography */
        .arabic-school-name {
            font-family: 'Amiri', serif;
            font-size: 2.1rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 0.5px;
            direction: rtl;
        }

        .english-school-name {
            font-family: 'Tajawal', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold-400);
            margin-bottom: 24px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            margin: 20px auto;
            width: 80%;
        }

        .maintenance-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(245, 158, 11, 0.12);
            border: 1px solid rgba(245, 158, 11, 0.25);
            color: var(--gold-400);
            padding: 8px 16px;
            border-radius: 99px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: var(--gold-500);
            border-radius: 50%;
            display: inline-block;
            box-shadow: 0 0 8px var(--gold-400);
            animation: blinkDot 1.5s ease-in-out infinite;
        }

        .heading-ar {
            font-family: 'Tajawal', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 10px;
            direction: rtl;
        }

        .heading-en {
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 18px;
            line-height: 1.35;
        }

        /* Announcement Highlight Card */
        .reopen-banner {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.18) 0%, rgba(245, 158, 11, 0.16) 100%);
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 16px;
            padding: 20px 22px;
            margin: 22px 0 24px;
            text-align: center;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.25);
        }

        .reopen-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--gold-500);
            color: #022c22;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 99px;
            margin-bottom: 10px;
        }

        .reopen-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.2px;
            margin-bottom: 6px;
        }

        .reopen-title-ar {
            font-family: 'Tajawal', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gold-400);
            direction: rtl;
        }

        .description-ar {
            font-family: 'Tajawal', sans-serif;
            font-size: 0.96rem;
            color: #cbd5e1;
            line-height: 1.8;
            margin-bottom: 12px;
            direction: rtl;
        }

        .description-en {
            font-size: 0.92rem;
            color: #cbd5e1;
            line-height: 1.65;
            margin-bottom: 28px;
        }

        /* Decorative Animated Progress Bar */
        .progress-container {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 99px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .progress-bar {
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, var(--emerald-500), var(--gold-400));
            border-radius: 99px;
            animation: loadingProgress 2.5s ease-in-out infinite;
        }

        /* Info / Contact Footer */
        .footer-info {
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
            font-size: 0.85rem;
            color: #94a3b8;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 22px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .info-item svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .info-link {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.2s;
            font-weight: 500;
        }

        .info-link:hover {
            color: var(--emerald-400);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes floatLogo {
            0% { transform: translateY(0px) rotate(0deg); }
            100% { transform: translateY(-8px) rotate(1deg); }
        }

        @keyframes pulseGlow {
            0% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.5; }
            100% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.8; }
        }

        @keyframes blinkDot {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        @keyframes loadingProgress {
            0% { transform: translateX(-100%); }
            50% { transform: translateX(150%); }
            100% { transform: translateX(250%); }
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            .card {
                padding: 32px 18px;
            }
            .arabic-school-name {
                font-size: 1.75rem;
            }
            .heading-ar {
                font-size: 1.25rem;
            }
            .heading-en {
                font-size: 1.15rem;
            }
            .reopen-title {
                font-size: 1.02rem;
            }
            .reopen-title-ar {
                font-size: 0.98rem;
            }
            .footer-info {
                flex-direction: column;
                gap: 12px;
                align-items: center;
            }
        }
    </style>
</head>
<body>

    <div class="bg-glow-1"></div>
    <div class="bg-glow-2"></div>

    <div class="container">
        <div class="card">
            
            <!-- Logo Section -->
            <div class="logo-wrapper">
                <div class="logo-bg-glow"></div>
                <img src="https://aes.amis.edu.ph/images/AMIS_Logo.png" alt="AMIS Logo" class="logo" onerror="this.onerror=null; this.src='https://admin.amis.edu.ph/images/AMIS_Logo.svg';">
            </div>

            <!-- School Title Calligraphy and English -->
            <h1 class="arabic-school-name">المدرسة المنورة الإسلامية</h1>
            <div class="english-school-name">Al Munawwara Islamic School</div>

            <div class="maintenance-status">
                <span class="status-dot"></span>
                <span>Scheduled Maintenance / أعمال الصيانة والتحديث</span>
            </div>

            <!-- Maintenance Messaging (Arabic & English) -->
            <div class="heading-ar">بوابة التسجيل الإلكتروني مغلقة لأعمال الصيانة والتجهيز</div>
            <div class="heading-en">Online Enrollment Closed for Scheduled Maintenance</div>

            <!-- Highlight Reopening Announcement -->
            <div class="reopen-banner">
                <div class="reopen-badge">Notice • إعلان هام</div>
                <div class="reopen-title">Reopening in December for New School Year 2027–2028</div>
                <div class="reopen-title-ar">سيتم استئناف التسجيل في شهر ديسمبر للعام الدراسي الجديد 2027–2028</div>
            </div>

            <div class="divider"></div>

            <p class="description-ar">
                تخضع بوابة التسجيل الإلكتروني لمدرسة المنورة الإسلامية حالياً لأعمال صيانة وتحديثات شاملة استعداداً لبدء القبول والتسجيل للعام الدراسي الجديد 2027-2028. نتطلع لخدمة طلابنا وأولياء الأمور الكرام فور إعادة فتح التسجيل في شهر ديسمبر إن شاء الله. شكراً لحسن تعاونكم وصبركم.
            </p>
            <p class="description-en">
                The AMIS Online Enrollment Portal is temporarily offline as our team prepares the system for the upcoming School Year 2027–2028 admissions. Online applications will reopen this December. We look forward to welcoming new and returning learners. Thank you for your patience and understanding.
            </p>

            <!-- Premium Progress Bar -->
            <div class="progress-container">
                <div class="progress-bar"></div>
            </div>

            <!-- Support / Contact Info -->
            <div class="footer-info">
                <div class="info-item">
                    <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    <a href="mailto:info@amis.edu.ph" class="info-link">info@amis.edu.ph</a>
                </div>
                <div class="info-item">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                    <a href="https://amis.edu.ph" target="_blank" class="info-link">amis.edu.ph</a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
