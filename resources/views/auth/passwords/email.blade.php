<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Forgot Password' }}</title>
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
        
        .forgot-container {
            max-width: 450px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .forgot-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        .forgot-header {
            background: linear-gradient(to right, #6a89cc, #4a69bd);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .forgot-header::before {
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
        
        .forgot-body {
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
        
        .instruction-box {
            background-color: #f8f9fa;
            border-left: 4px solid #4a69bd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .instruction-box p {
            margin-bottom: 5px;
            color: #495057;
        }
        
        .step-number {
            display: inline-block;
            width: 25px;
            height: 25px;
            background: #4a69bd;
            color: white;
            text-align: center;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 14px;
            line-height: 25px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
                align-items: flex-start;
            }
            
            .forgot-container {
                margin: 20px auto;
            }
            
            .forgot-body {
                padding: 20px;
            }
            
            .forgot-header {
                padding: 20px;
            }
            
            .form-control {
                padding: 10px 15px 10px 40px;
            }
            
            .input-icon i {
                left: 12px;
                top: 11px;
            }
        }
        
        .back-to-login {
            text-align: center;
            margin-top: 20px;
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