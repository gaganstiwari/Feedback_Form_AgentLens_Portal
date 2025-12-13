    @extends('feedbackform.app')

    @section('content')
  

        <div class="container-fluid banner-container">
            <div class="banner-row">

                <!-- Logo (Left) -->
                <div class="banner-logo-container">
                    <img src="{{ asset('asset/logo.png') }}" class="banner-logo" alt="Logo">
                </div>

                <!-- Title (Centered Always) -->
                <div class="banner-title">
                    <h4 class="page_title">MEDICAL APPOINTMENT FEEDBACK</h4>
                </div>

            </div>
        </div>
    </section>


    <section class="section py-5">
        <div class="container" style="max-width:900px;">
            <div class="form-card">
                {{-- Success/Error Messages --}}
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- 🔹 Static NPS Rating View --}}
                <div class="text-center mb-4">
                    <p class="rate-us-title">We'd love to hear from you! 😊</p>
                    <p class="rate-us-subtitle">How likely are you to rate our services between 0 and 10 based on your experience?</p>

                    <div class="nps-container">
                        @for ($i = 0; $i <= 10; $i++)
                            <div class="nps-box 
                                {{ $i <= 6 ? 'red' : ($i <= 8 ? 'yellow' : 'green') }}"
                                onclick="Livewire.dispatch('npsSelected', { score: {{ $i }} })"
                                style="cursor: pointer;">
                                {{ $i }}
                            </div>
                        @endfor
                    </div>

                    <div class="nps-labels-wrapper">
                        <div class="nps-label red-label">😞 Bad</div>
                        <div class="nps-label yellow-label">🙂 Good</div>
                        <div class="nps-label green-label">🤩 Excellent</div>
                    </div>
                </div>

<livewire:feedback-form :requestid="$requestid ?? null" />

            </div>
        </div>
    </section>
    @endsection

    @push('styles')
    <style>
        
        /* ============================
    GLOBAL BANNER
    =============================== */
    .banner {
        width: 100%;
        padding: 20px 0;
    }

    .banner-container {
        width: 100%;
        background: radial-gradient(circle at right, #e63946, #0f2650 100%);
    }

    .banner-row {
        display: flex;
        align-items: center;
        justify-content: center; /* centers title perfectly */
        position: relative;
    }

    /* ============================
    LOGO (LEFT)
    =============================== */
    .banner-logo-container {
        position: absolute;
        left: 20px;
    }

    .banner-logo {
        width: 120px;
        height: auto;
    }

    /* ============================
    TITLE (CENTERED ALWAYS)
    =============================== */
    .banner-title {
        background: #ffffff;
        padding: 6px 14px;          /* smaller padding = tighter box */
        border-radius: 10px;
        display: inline-flex;       /* shrinks to content width */
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .page_title {
        margin: 0;                 /* remove extra spacing */
        padding: 0;                /* ensure no hidden padding */
        font-size: 32px;
        font-weight: 700;
        color: #0f2650;
        white-space: nowrap;       /* prevents stretching line breaks */
    }
    /* ============================
    RESPONSIVE (TABLETS / NOTE)
    =============================== */
    @media (max-width: 992px) {
        .banner-logo {
            width: 100px;
        }

        .page_title {
            font-size: 20px;
        }

        .banner-row {
            padding: 15px;
        }
    }

    /* ============================
    RESPONSIVE (MOBILE)
    =============================== */
    @media (max-width: 576px) {
        .banner-logo {
            width: 80px;
        }

        .page_title {
            font-size: 18px;
        }
    }

    /* === Banner Section === */

    /* ===========================================
    UNIVERSAL WRAPPER
    =========================================== */
    .dynamic-wrapper {
        width: 100%;
        margin: 0 auto;
    }

    /* ===========================================
    QUESTION TITLE
    =========================================== */
    .dynamic-question {
        font-size: 20px;
        font-weight: 700;
        color: #0f2650;
        margin-bottom: 8px;
        line-height: 1.5;
    }

    /* ===========================================
    CHECKBOX AREA
    =========================================== */
    .checkbox-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding-left: 8px;
    }

    .checkbox-container .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .checkbox-container input[type="checkbox"] {
        transform: scale(1.25);
        cursor: pointer;
    }

    .checkbox-container label {
        font-size: 16px;
        color: #333;
        cursor: pointer;
    }

    /* ===========================================
    TEXTAREA
    =========================================== */
    .comment-box {
        width: 100%;
        border-radius: 12px;
        border: 1px solid #cfcfcf;
        padding: 12px 15px;
        font-size: 15px;
        resize: vertical;
        min-height: 110px;
    }

    /* ===========================================
    RESPONSIVE DESIGN
    =========================================== */

    /* 📱 Mobile (up to 599px) */
    @media (max-width: 599px) {
        .dynamic-question {
            font-size: 17px;
        }

        .checkbox-container {
            gap: 8px;
        }

        .checkbox-container label {
            font-size: 14px;
        }

        .comment-box {
            font-size: 14px;
            padding: 10px;
        }
    }

    /* 📟 Tablets / iPad (600px – 991px) */
    @media (min-width: 600px) and (max-width: 991px) {

        .dynamic-wrapper {
            padding: 5px 10px;
        }

        .dynamic-question {
            font-size: 18px;
            line-height: 1.4;
        }

        .checkbox-container label {
            font-size: 15px;
        }

        .comment-box {
            font-size: 15px;
            padding: 12px;
        }
    }

    /* 💻 Small Laptops (992px – 1365px) */
    @media (min-width: 992px) and (max-width: 1365px) {

        .dynamic-wrapper {
            width: 90%;
        }

        .dynamic-question {
            font-size: 19px;
        }

        .checkbox-container label {
            font-size: 15.5px;
        }
    }

    /* 🖥️ Large Desktops (1366px+) */
    @media (min-width: 1366px) {
        .dynamic-question {
            font-size: 20px;
        }
    }

    .section {
        width: 100%;
        min-height: 100vh; /* full viewport height */
        background: radial-gradient(circle at right, #e63946, #0f2650 100%);
        display: flex;  
        justify-content: center;
    }

    .banner {
        background: rgb(123, 116, 153);
    }
    .background-overflow {
        background: radial-gradient(circle at right, #e63946, #0f2650 100%);
        padding: 30px 20px;
    }
    .banner-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: nowrap;
        gap: 20px;
    }
    .imagecontainer {
        background: #fff;
        padding: 8px 12px;
        border-radius: 12px;
        width: 180px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .banner-logo {
        width: 160px;
    }
    .title-block {
        background-color: #ffffff;
        padding: 12px 25px;
        border-radius: 10px;
        flex-grow: 1;
        margin: 0 300px 0 90px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .page_title {
        font-size: 40px;
        font-weight: 700;
    
    }

    /* ✅ Responsive Banner */
    @media (max-width: 768px) {
        .banner-row {
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .title-block {
            margin: 10px 0;
            width: 100%;
            text-align: center;
        }
        .banner-logo {
            width: 120px;
        }
        .page_title {
            font-size: 24px;
            padding: 8px;
        }
    }

    /* === Section Background === */
    .section {
        background: radial-gradient(circle at right, #e63946, #0f2650 100%);
    }

    /* === Form Card === */
    .form-card {
        background: #fff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* Responsive form */
    @media (max-width: 768px) {
        .form-card {
            padding: 25px;
            margin: 0 10px;
        }
    }

    /* === Text + Subtitle === */
    .rate-us-title {
        font-size: 22px;
        font-weight: 700;
        color: #0f2650;
        margin-bottom: 10px;
    }
    .rate-us-subtitle {
        font-size: 16px;
        font-weight: 600;
        color: #0f2650;
        margin-bottom: 20px;
    }

    /* === NPS === */
    .nps-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .nps-box {
        width: 50px;
        height: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 600;
        border-radius: 6px;
        color: #fff;
        font-size: 16px;
    }
    .nps-box.red { background-color: #f87171; }
    .nps-box.yellow { background-color: #facc15; color: #000; }
    .nps-box.green { background-color: #4ade80; color: #000; }

    .nps-box:hover {
        transform: scale(1.05);
    }
    .nps-box.active {
        border: 3px solid #0f2650;
        transform: scale(1.1);
        transition: all 0.2s ease;
    }

    /* === Labels === */
    .nps-labels-wrapper {
        position: relative;
        width: 100%;
        height: 30px;
        margin-top: 15px;
    }
    .red-label {
        color: #b91c1c;
        position: absolute;
        left: 25%;
        transform: translateX(-50%);
        font-weight: 600;
    }
    .yellow-label {
        color: #a16207;
        position: absolute;
        left: 69%;
        transform: translateX(-50%);
        font-weight: 600;
    }
    .green-label {
        color: #15803d;
        position: absolute;
        right: 12%;
        transform: translateX(50%);
        font-weight: 600;
    }

    /* Hide labels on phones */
    @media (max-width: 768px) {
        .nps-labels-wrapper {
            display: none;
        }
    }

    /* === Checkbox Area === */
    .checkbox-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
        gap: 6px;
        margin-top: 10px;
        padding-left: 5px;
        width: 100%;
    }
    .checkbox-container .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
    }
    .checkbox-container input[type="checkbox"] {
        transform: scale(1.1);
        cursor: pointer;
    }
    .checkbox-container label {
        font-size: 15px;
        color: #333;
        cursor: pointer;
        margin: 0;
    }
    .customlabel {
        font-weight: 600;
        font-size: 16px;
        color: #0f2650;
        margin-bottom: 8px;
        text-align: left;
        display: block;
    }

    /* === Textarea === */
    textarea.form-control {
        width: 100%;
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 8px 10px;
        font-size: 15px;
        resize: vertical;
    }

    /* === Submit Button === */
    .form-btn {
        background:rgb(12, 37, 82);
        color: #fff;
        font-weight: bold;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 25px;
        width: 200px;
        transition: 0.3s;
    }
    .form-btn:hover {
        background:rgb(35, 81, 160);
    }

    /* === Mobile Tweaks === */
    @media (max-width: 480px) {
        .nps-container {
            gap: 4px;
        }
        .nps-box {
            width: 35px;
            height: 35px;
            font-size: 13px;
        }
        .rate-us-title {
            font-size: 18px;
        }
        .form-btn {
            width: 100%;
        }
    }
    /* ============================
    BANNER WRAPPER
    =============================== */
    .banner {
        width: 100%;
        background: #f8fafc;
        padding: 20px 0;
        
    }

    /* Align items center + keep title always centered */
    .banner-row {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 10px 20px;
    }

    /* ============================
    LOGO BOX (White background)
    =============================== */
    .banner-logo-container {
        position: absolute;
        left: 20px;
        background: #ffffff;
        padding: 8px 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .banner-logo {
        width: 120px;
        height: auto;
    }

    /* ============================
    TITLE BOX (White background)
    =============================== */
    .banner-title {
    
        padding: 10px 25px;
        border-radius: 12px;
        display: inline-block;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .page_title {
        font-size: 22px;
        font-weight: bold;
        margin: 0;
    }

    /* ============================
    RESPONSIVE
    =============================== */
    @media (max-width: 992px) {
        .banner-logo-container {
            left: 10px;
            padding: 6px 10px;
        }
        .banner-logo {
            width: 95px;
        }
        .page_title {
            font-size: 20px;
        }
    }

    @media (max-width: 576px) {
        .banner-row {
            flex-direction: column;
            gap: 12px;
        }

        /* Move logo from left to top on phone */
        .banner-logo-container {
            position: relative;
            left: 0;
            margin-bottom: 8px;
        }

        .banner-logo {
            width: 80px;
        }

        .banner-title {
            padding: 8px 15px;
        }

        .page_title {
            font-size: 18px;
        }
    }
    /* ============================
    FULL PAGE BACKGROUND (NO GAPS)
    =============================== */
    body {
        background: radial-gradient(circle at right, #e63946, #0f2650 100%);
        margin: 0;
        padding: 0;
    }

    /* ============================
    BANNER SECTION (MATCHES BACKGROUND)
    =============================== */
    .banner {
        width: 100%;
        padding: 20px 0;
        background: transparent; /* No separate color */
        margin: 0;
    }

    .banner-container {
        background: transparent;
        padding: 0;
    }

    .banner-row {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 10px 20px;
    }

    /* ============================
    LOGO BOX (White Background)
    =============================== */
    .banner-logo-container {
        position: absolute;
        left: 20px;
        background: #ffffff;
        padding: 10px 14px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .banner-logo {
        width: 120px;
    }

    /* ============================
    TITLE BOX (White Background)
    =============================== */
    .banner-title {
        background: #ffffff;
        padding: 10px 25px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .page_title {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        color: #0f2650;
    }

    /* ============================
    SECTION
    =============================== */
    .section {
        width: 100%;
        min-height: 100vh;
        padding-top: 0;
        background: transparent;  /* So gradient from BODY covers everything */
        margin-top: 0;
    }

    /* Remove gap between banner & section */
    .banner + .section {
        margin-top: -10px;
    }

    /* ============================
    RESPONSIVE
    =============================== */
    @media (max-width: 576px) {

        .banner-row {
            flex-direction: column;
            gap: 12px;
        }

        .banner-logo-container {
            position: relative;
            left: 0;
            margin-bottom: 10px;
        }

        .banner-logo {
            width: 85px;
        }

        .banner-title {
            padding: 8px 15px;
        }

        .page_title {
            font-size: 18px;
        }
    }  


        /* Thank You Overlay Styles */
        .thank-you-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-in;
        }

        .thank-you-content {
            background: #ffffff;
            padding: 60px 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            animation: slideUp 0.5s ease-out;
        }

        .thank-you-icon {
            width: 100px;
            height: 100px;
            background: #4ade80;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 60px;
            color: #ffffff;
            font-weight: bold;
            animation: scaleIn 0.5s ease-out;
        }

        .thank-you-title {
            font-size: 36px;
            font-weight: 700;
            color: #0f2650;
            margin-bottom: 15px;
        }

        .thank-you-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .thank-you-countdown {
            font-size: 16px;
            color: #0f2650;
            font-weight: 600;
        }

        .thank-you-countdown #countdown {
            font-size: 24px;
            font-weight: 700;
            color: #e63946;
            display: inline-block;
            min-width: 30px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .thank-you-content {
                padding: 40px 30px;
            }

            .thank-you-icon {
                width: 80px;
                height: 80px;
                font-size: 50px;
                margin-bottom: 20px;
            }

            .thank-you-title {
                font-size: 28px;
            }

            .thank-you-message {
                font-size: 16px;
            }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const npsBoxes = document.querySelectorAll('.nps-box');
        
        // Handle NPS selection
        window.addEventListener('npsSelected', (event) => {
            const score = event.detail?.score ?? event.detail;
            if (score !== undefined) {
                npsBoxes.forEach((box, index) => {
                    box.classList.toggle('active', index === score);
                });
            }
        });

        // Save data when user is about to leave the page
        window.addEventListener('beforeunload', function(e) {
            // Trigger auto-save via Livewire
            if (typeof Livewire !== 'undefined') {
                // Livewire will auto-save on property changes, but we can force a save
                // The autoSave() method is already called on every update, so data should be saved
                console.log('Page unloading - data should be auto-saved');
            }
        });

        // Function to start countdown
        function startCountdown() {
            // Wait a bit for Livewire to update the DOM
            setTimeout(() => {
                const countdownElement = document.getElementById('countdown');
                if (!countdownElement) {
                    console.log('Countdown element not found, retrying...');
                    // Retry after a short delay
                    setTimeout(startCountdown, 500);
                    return;
                }

                let seconds = 4;
                countdownElement.textContent = seconds;

                const countdownInterval = setInterval(() => {
                    seconds--;
                    const element = document.getElementById('countdown');
                    if (element) {
                        element.textContent = seconds;
                    }

                    if (seconds <= 0) {
                        clearInterval(countdownInterval);
                        // Redirect to callmedilife.com
                        window.location.href = 'https://callmedilife.com/';
                    }
                }, 1000);
            }, 300);
        }

        // Use MutationObserver to watch for DOM changes (when Livewire updates)
        const observer = new MutationObserver((mutations) => {
            const thankYouMessage = document.getElementById('thankYouMessage');
            const countdown = document.getElementById('countdown');
            if (thankYouMessage && countdown && !countdown.dataset.countdownStarted) {
                countdown.dataset.countdownStarted = 'true';
                console.log('Thank you message appeared in DOM, starting countdown');
                startCountdown();
            }
        });

        // Start observing
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Also check periodically as a backup
        setInterval(() => {
            const thankYouMessage = document.getElementById('thankYouMessage');
            const countdown = document.getElementById('countdown');
            if (thankYouMessage && countdown && !countdown.dataset.countdownStarted) {
                countdown.dataset.countdownStarted = 'true';
                console.log('Thank you message found via interval check, starting countdown');
                startCountdown();
            }
        }, 500);
    });
    </script>
    @endpush
