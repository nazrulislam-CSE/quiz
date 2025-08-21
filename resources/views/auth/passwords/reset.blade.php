<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Reset Password' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            overflow-x: hidden;
        }
        
        body {
            background: linear-gradient(-45deg, #e3f2fd, #bbdefb, #90caf9, #64b5f6);
            background-size: 400% 400%;
            animation: gradient 12s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
            font-family: 'Segoe UI', sans-serif;
            position: relative;
        }
        
        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #6a89cc;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #4a69bd;
        }
        
        /* Animated background elements */
        .bg-bubbles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .bg-bubbles li {
            position: absolute;
            list-style: none;
            display: block;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.15);
            bottom: -160px;
            animation: square 25s infinite;
            transition-timing-function: linear;
            border-radius: 50%;
        }
        
        .bg-bubbles li:nth-child(1) {
            left: 10%;
            animation-delay: 0s;
            width: 80px;
            height: 80px;
        }
        
        .bg-bubbles li:nth-child(2) {
            left: 20%;
            animation-delay: 2s;
            animation-duration: 17s;
            width: 60px;
            height: 60px;
        }
        
        .bg-bubbles li:nth-child(3) {
            left: 25%;
            animation-delay: 4s;
        }
        
        .bg-bubbles li:nth-child(4) {
            left: 40%;
            animation-delay: 0s;
            animation-duration: 22s;
            width: 70px;
            height: 70px;
        }
        
        .bg-bubbles li:nth-child(5) {
            left: 70%;
            animation-delay: 3s;
        }
        
        .bg-bubbles li:nth-child(6) {
            left: 80%;
            animation-delay: 2s;
            width: 90px;
            height: 90px;
        }
        
        .bg-bubbles li:nth-child(7) {
            left: 32%;
            animation-delay: 6s;
            width: 50px;
            height: 50px;
        }
        
        .bg-bubbles li:nth-child(8) {
            left: 55%;
            animation-delay: 8s;
            animation-duration: 18s;
            width: 65px;
            height: 65px;
        }
        
        .bg-bubbles li:nth-child(9) {
            left: 25%;
            animation-delay: 9s;
            animation-duration: 20s;
            width: 75px;
            height: 75px;
        }
        
        .bg-bubbles li:nth-child(10) {
            left: 90%;
            animation-delay: 11s;
            width: 45px;
            height: 45px;
        }
        
        @keyframes square {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 50%;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 10%;
            }
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .reset-container {
            max-width: 500px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .reset-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .reset-header {
            background: linear-gradient(to right, #6a89cc, #4a69bd);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .reset-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            transform: rotate(30deg);
            animation: shine 6s infinite linear;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) rotate(30deg); }
            100% { transform: translateX(100%) rotate(30deg); }
        }
        
        .reset-body {
            padding: 30px;
        }
        
        .form-control {
            padding: 12px 20px 12px 45px;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(74, 105, 189, 0.2);
            border-color: #4a69bd;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 13px;
            color: #4a69bd;
            transition: all 0.3s ease;
        }
        
        .form-control:focus + i {
            color: #1e3799;
            transform: scale(1.1);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 13px;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #4a69bd;
        }
        
        .btn-reset {
            background: linear-gradient(to right, #6a89cc, #4a69bd);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-reset::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
            z-index: 1;
        }
        
        .btn-reset:hover::before {
            left: 100%;
        }
        
        .btn-reset:hover {
            background: linear-gradient(to right, #5a79bc, #3a59ad);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(74, 105, 189, 0.4);
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #ddd, transparent);
            margin: 30px 0;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        
        .logo i {
            background: white;
            color: #4a69bd;
            border-radius: 50%;
            padding: 10px;
            margin-right: 10px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .password-strength {
            height: 5px;
            margin-top: -15px;
            margin-bottom: 20px;
            border-radius: 5px;
            background: #eee;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            border-radius: 5px;
            transition: width 0.3s ease, background 0.3s ease;
        }
        
        .password-rules {
            background-color: #f8f9fa;
            border-left: 4px solid #4a69bd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .password-rules ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        
        .password-rules li {
            margin-bottom: 5px;
            color: #495057;
            font-size: 14px;
        }
        
        .password-rules li.valid {
            color: #28a745;
        }
        
        .password-rules li.valid::before {
            content: "✓ ";
            font-weight: bold;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
                align-items: flex-start;
            }
            
            .reset-container {
                margin: 20px auto;
            }
            
            .reset-body {
                padding: 20px;
            }
            
            .reset-header {
                padding: 20px;
            }
            
            .form-control {
                padding: 10px 15px 10px 40px;
            }
            
            .input-icon i {
                left: 12px;
                top: 11px;
            }
            
            .password-toggle {
                right: 12px;
                top: 11px;
            }
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }
        
        .success-message {
            display: none;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .success-message i {
            font-size: 50px;
            color: #28a745;
            margin-bottom: 15px;
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            position: relative;
        }
        
        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }
        
        .step.active .step-number {
            background: #4a69bd;
            color: white;
        }
        
        .step.completed .step-number {
            background: #28a745;
            color: white;
        }
        
        .step-text {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        
        .step.active .step-text {
            color: #4a69bd;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </div>
    
    <div class="container">
        <div class="reset-container">
            <div class="reset-header">
                <div class="logo">
                    <i class="fas fa-user-graduate"></i>MCQ Admission
                </div>
                <h2>Reset Your Password</h2>
                <p class="mb-0">Secure your account with a new password</p>
            </div>
            
            <div class="reset-body">
                <div class="step-indicator">
                    <div class="step completed">
                        <div class="step-number">1</div>
                        <div class="step-text">Verify Email</div>
                    </div>
                    <div class="step active">
                        <div class="step-number">2</div>
                        <div class="step-text">New Password</div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-text">Complete</div>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <div class="password-rules">
                        <p class="fw-bold">Password requirements:</p>
                        <ul>
                            <li id="rule-length">At least 8 characters</li>
                            <li id="rule-uppercase">One uppercase letter</li>
                            <li id="rule-number">One number</li>
                            <li id="rule-special">One special character</li>
                        </ul>
                    </div>

                    <div class="input-icon">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" id="email" placeholder="Your email address" readonly>
                        <i class="fas fa-envelope"></i>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                    <div class="input-icon">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="newPassword" placeholder="New password" required>
                        <i class="fas fa-lock"></i>
                        <span class="password-toggle" id="toggleNewPassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    
                    <div class="input-icon">
                        <input type="password" name="password_confirmation" class="form-control" id="confirmPassword" placeholder="Confirm new password" required>
                        <i class="fas fa-lock"></i>
                        <span class="password-toggle" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    
                    <button type="submit" class="btn btn-reset">
                        <i class="fas fa-key me-2"></i> RESET PASSWORD
                    </button>
                    
                    <div class="success-message" id="successMessage">
                        <i class="fas fa-check-circle"></i>
                        <h4>Password Reset Successful!</h4>
                        <p>Your password has been successfully reset.</p>
                        <a href="#" class="btn btn-reset mt-3">Continue to Login</a>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="back-to-login">
                        <p>Remember your password? <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password visibility toggling
        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            const passwordField = document.getElementById('newPassword');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const passwordField = document.getElementById('confirmPassword');
            const icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Password strength checking
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const rules = {
                length: document.getElementById('rule-length'),
                uppercase: document.getElementById('rule-uppercase'),
                number: document.getElementById('rule-number'),
                special: document.getElementById('rule-special')
            };
            
            // Check password rules
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
            
            // Update rule indicators
            rules.length.classList.toggle('valid', hasLength);
            rules.uppercase.classList.toggle('valid', hasUppercase);
            rules.number.classList.toggle('valid', hasNumber);
            rules.special.classList.toggle('valid', hasSpecial);
            
            // Calculate strength (0-100)
            let strength = 0;
            if (hasLength) strength += 25;
            if (hasUppercase) strength += 25;
            if (hasNumber) strength += 25;
            if (hasSpecial) strength += 25;
            
            // Update strength bar
            strengthBar.style.width = strength + '%';
            
            // Set color based on strength
            if (strength < 50) {
                strengthBar.style.background = '#dc3545'; // Red
            } else if (strength < 100) {
                strengthBar.style.background = '#ffc107'; // Yellow
            } else {
                strengthBar.style.background = '#28a745'; // Green
            }
        });
        
        // Form submission
        // document.getElementById('resetForm').addEventListener('submit', function(e) {
        //     e.preventDefault();
            
        //     const newPassword = document.getElementById('newPassword').value;
        //     const confirmPassword = document.getElementById('confirmPassword').value;
            
        //     // Validate passwords match
        //     if (newPassword !== confirmPassword) {
        //         alert('Passwords do not match!');
        //         return;
        //     }
            
        //     // Validate password strength
        //     const strengthBar = document.getElementById('passwordStrengthBar');
        //     if (parseInt(strengthBar.style.width) < 100) {
        //         alert('Please ensure your password meets all requirements');
        //         return;
        //     }
            
        //     // Simulate successful password reset
        //     const btn = document.querySelector('.btn-reset');
        //     const originalText = btn.innerHTML;
            
        //     btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> PROCESSING...';
        //     btn.disabled = true;
            
        //     setTimeout(function() {
        //         // Update step indicator
        //         document.querySelectorAll('.step')[1].classList.remove('active');
        //         document.querySelectorAll('.step')[2].classList.add('active');
                
        //         document.getElementById('resetForm').style.display = 'none';
        //         document.getElementById('successMessage').style.display = 'block';
        //     }, 1500);
        // });
    </script>
</body>
</html>