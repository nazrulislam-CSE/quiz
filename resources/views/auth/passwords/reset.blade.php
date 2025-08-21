<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Reset Password' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/reset.css') }}">
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