<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Forgot Password' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/forgot.css') }}">
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
        <div class="forgot-container">
            <div class="forgot-header">
                <div class="logo">
                    <i class="fas fa-user-graduate"></i>MCQ Admission
                </div>
                <h2>Reset Your Password</h2>
                <p class="mb-0">We'll help you get back into your account</p>
            </div>
            
            <div class="forgot-body">
               <form method="POST" action="{{ route('password.email') }}">
                @csrf
                    <div class="instruction-box">
                        <p><span class="step-number">1</span> Enter your email address</p>
                        <p><span class="step-number">2</span> Check your inbox for a reset link</p>
                        <p><span class="step-number">3</span> Follow the instructions to reset your password</p>
                    </div>
                    
                    <div class="input-icon">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter your email address">
                        <i class="fas fa-envelope"></i>
                    </div>

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    
                    
                    <button type="submit" class="btn btn-reset">
                        <i class="fas fa-paper-plane me-2"></i> SEND RESET LINK
                    </button>
                    
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
        // Simple form validation
        // document.querySelector('form').addEventListener('submit', function(e) {
        //     e.preventDefault();
        //     const email = document.querySelector('input[type="email"]').value;
            
        //     if (!email) {
        //         alert('Please enter your email address');
        //         return;
        //     }
            
        //     if (!/\S+@\S+\.\S+/.test(email)) {
        //         alert('Please enter a valid email address');
        //         return;
        //     }
            
        //     // Simulate sending reset link
        //     const btn = document.querySelector('.btn-reset');
        //     const originalText = btn.innerHTML;
            
        //     btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> SENDING...';
        //     btn.disabled = true;
            
        //     setTimeout(function() {
        //         btn.innerHTML = '<i class="fas fa-check me-2"></i> LINK SENT!';
        //         btn.style.background = 'linear-gradient(to right, #4cd964, #2ecc71)';
                
        //         setTimeout(function() {
        //             btn.innerHTML = originalText;
        //             btn.disabled = false;
        //             btn.style.background = '';
        //             alert('Password reset link has been sent to your email!');
        //         }, 2000);
        //     }, 1500);
        // });
    </script>
</body>
</html>