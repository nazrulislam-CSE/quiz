<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>ChalkboardBD API Documentation - Complete Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #1f2937;
            --light: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 15px;
            position: relative;
            overflow-x: hidden;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            pointer-events: none;
            animation: slide 20s linear infinite;
        }

        @keyframes slide {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .api-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .header {
                padding: 20px 15px;
            }
        }

        .header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: white;
            padding: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        @media (max-width: 768px) {
            .logo {
                width: 60px;
                height: 60px;
            }
        }

        .logo:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
            animation: slideInLeft 0.8s ease-out;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.5rem;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .header p {
            font-size: 1rem;
            opacity: 0.95;
            animation: slideInRight 0.8s ease-out;
        }

        @media (max-width: 768px) {
            .header p {
                font-size: 0.9rem;
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .base-url {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 12px 20px;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .base-url {
                padding: 10px 15px;
                flex-direction: column;
            }
        }

        .base-url:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .base-url code {
            background: rgba(0, 0, 0, 0.3);
            padding: 6px 12px;
            border-radius: 8px;
            color: white;
            font-size: 0.9rem;
            word-break: break-all;
        }

        @media (max-width: 768px) {
            .base-url code {
                font-size: 0.8rem;
            }
        }

        .nav-tabs {
            border-bottom: 2px solid #e5e7eb;
            padding: 0 15px;
            margin-top: 20px;
            overflow-x: auto;
            overflow-y: hidden;
            flex-wrap: nowrap;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {
            .nav-tabs {
                padding: 0 10px;
            }
        }

        .nav-tabs::-webkit-scrollbar {
            height: 3px;
        }

        .nav-tabs::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .nav-tabs::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6b7280;
            font-weight: 600;
            padding: 12px 16px;
            transition: all 0.3s;
            position: relative;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .nav-tabs .nav-link {
                padding: 10px 12px;
                font-size: 0.85rem;
            }
        }

        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom: 3px solid var(--primary);
            background: transparent;
        }

        .content {
            padding: 25px;
        }

        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }
        }

        .endpoint-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-bottom: 20px;
            transition: all 0.3s;
            overflow: hidden;
        }

        .endpoint-card:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .endpoint-header {
            padding: 15px 20px;
            background: var(--light);
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .endpoint-header {
                padding: 12px 15px;
                flex-direction: column;
                align-items: flex-start;
            }
        }

        .endpoint-header:hover {
            background: #f3f4f6;
        }

        .method {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: bold;
            font-family: monospace;
            transition: transform 0.3s ease;
        }

        .method:hover {
            transform: scale(1.05);
        }

        .method-get {
            background: #10b981;
            color: white;
        }

        .method-post {
            background: #3b82f6;
            color: white;
        }

        .method-put {
            background: #f59e0b;
            color: white;
        }

        .method-delete {
            background: #ef4444;
            color: white;
        }

        .endpoint-url {
            font-family: monospace;
            font-size: 0.9rem;
            color: var(--dark);
            font-weight: 500;
            word-break: break-all;
        }

        @media (max-width: 768px) {
            .endpoint-url {
                font-size: 0.85rem;
            }
        }

        .endpoint-body {
            padding: 20px;
            display: none;
            animation: fadeIn 0.5s ease-out;
        }

        @media (max-width: 768px) {
            .endpoint-body {
                padding: 15px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .endpoint-body.show {
            display: block;
        }

        .table-custom {
            background: white;
            border-radius: 8px;
            overflow-x: auto;
            display: block;
            width: 100%;
        }

        .table-custom table {
            width: 100%;
            min-width: 500px;
        }

        .table-custom th {
            background: var(--light);
            color: var(--dark);
            font-weight: 600;
            border: none;
            padding: 10px;
        }

        .table-custom td {
            padding: 10px;
        }

        .required {
            color: var(--danger);
            font-size: 0.75rem;
            font-weight: bold;
        }

        .optional {
            color: var(--warning);
            font-size: 0.75rem;
            font-weight: bold;
        }

        .badge-auth {
            background: var(--primary);
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
        }

        .badge-public {
            background: var(--secondary);
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
        }

        .badge-referral {
            background: #f59e0b;
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
        }

        pre {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 12px;
            border-radius: 8px;
            overflow-x: auto;
            font-size: 0.8rem;
            position: relative;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            pre {
                font-size: 0.7rem;
                padding: 10px;
            }
        }

        pre:hover {
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .response-example {
            background: #f8f9fa;
            border-left: 4px solid var(--primary);
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }

        .response-example:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .copy-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .copy-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        .footer {
            background: linear-gradient(135deg, var(--dark) 0%, #111827 100%);
            color: white;
            padding: 25px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            animation: loading 3s infinite;
        }

        @keyframes loading {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .footer-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            padding: 6px;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .footer-logo {
                width: 45px;
                height: 45px;
            }
        }

        .footer-logo:hover {
            transform: rotate(360deg) scale(1.1);
        }

        .footer-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .developer-info {
            margin-top: 15px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .developer-info:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        .tech-stack {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 12px;
        }

        .tech-badge {
            background: rgba(79, 70, 229, 0.2);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .tech-badge {
                font-size: 0.7rem;
                padding: 3px 8px;
            }
        }

        .tech-badge:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }

        .toast-success {
            background-color: #10b981 !important;
        }

        .toast-error {
            background-color: #ef4444 !important;
        }

        .toast-info {
            background-color: #3b82f6 !important;
        }

        .scroll-top-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            cursor: pointer;
            display: none;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .scroll-top-btn {
                width: 40px;
                height: 40px;
                bottom: 15px;
                right: 15px;
            }
        }

        .scroll-top-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
        }

        .alert {
            font-size: 0.9rem;
            padding: 12px;
        }

        @media (max-width: 768px) {
            .alert {
                font-size: 0.85rem;
                padding: 10px;
            }
        }

        .flow-diagram {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e5e7eb;
        }

        .flow-step {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .step {
            background: white;
            border-radius: 10px;
            padding: 12px 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            min-width: 180px;
        }

        .step-number {
            background: var(--primary);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .arrow {
            font-size: 24px;
            color: var(--primary);
        }

        @media (max-width: 768px) {
            .arrow {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="api-container">
        <div class="header">
            <div class="logo-container" data-aos="zoom-in">
                <div class="logo">
                    <img src="https://chalkboardbd.com/upload/setting/logo/1759778293538.png" alt="ChalkboardBD Logo">
                </div>
            </div>
            <h1 data-aos="fade-up"><i class="fas fa-chalkboard"></i> ChalkboardBD API</h1>
            <p data-aos="fade-up" data-aos-delay="100">Complete API Documentation for Seamless Integration</p>
            <div class="base-url" data-aos="flip-up" data-aos-delay="200">
                <i class="fas fa-link"></i> Base URL:
                <code id="baseUrl">https://chalkboardbd.com/api/v1</code>
                <button class="copy-btn" onclick="copyBaseUrl()"><i class="fas fa-copy"></i> Copy URL</button>
            </div>
            <div class="mt-3" data-aos="fade-up" data-aos-delay="300">
                <span class="badge bg-light text-dark me-2"><i class="fas fa-shield-alt"></i> bktoken Required</span>
                <span class="badge bg-light text-dark me-2"><i class="fas fa-key"></i> Sanctum Authentication</span>
            </div>
        </div>

        <ul class="nav nav-tabs" id="apiTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#auth"><i
                        class="fas fa-user"></i> Authentication</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#user"><i
                        class="fas fa-user-circle"></i> User Panel</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#admin"><i
                        class="fas fa-user-shield"></i> Admin Panel</a></li>
        </ul>

        <div class="tab-content content">
            <!-- Authentication Tab -->
            <div class="tab-pane fade show active" id="auth">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <strong>Important:</strong> All API requests require
                    <code>bktoken</code> in headers. Authentication is done via Phone OTP.
                </div>

                <!-- Login Flow Diagram -->
                <div class="flow-diagram" data-aos="fade-up">
                    <h5 class="text-center mb-3"><i class="fas fa-chart-line"></i> Phone OTP Login Flow</h5>
                    <div class="flow-step">
                        <div class="step">
                            <div class="step-number">1</div>POST /user/login<br><small>Send phone number</small>
                        </div>
                        <div class="arrow"><i class="fas fa-arrow-right"></i></div>
                        <div class="step">
                            <div class="step-number">2</div>Receive OTP via SMS<br><small>6-digit code</small>
                        </div>
                        <div class="arrow"><i class="fas fa-arrow-right"></i></div>
                        <div class="step">
                            <div class="step-number">3</div>POST /user/verify-otp<br><small>Verify & get token</small>
                        </div>
                    </div>
                </div>

                <!-- Register -->
                <div class="endpoint-card" data-aos="fade-up">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/register</span>
                        <span class="badge-public">Public</span>
                        <span class="badge-referral">Referral Available</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📝 Description:</h6>
                        <p>Register a new user account with phone number. Optional referral by username.</p>

                        <h6>📋 Headers:</h6>
                        <pre>{
    "bktoken": "your_bktoken_value",
    "Content-Type": "application/json"
}</pre>

                        <h6>📦 Request Body:</h6>
                        <div class="table-responsive">
                            <table class="table table-custom table-sm">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>phone</td>
                                        <td>numeric</td>
                                        <td><span class="required">Required</span></td>
                                        <td>Phone number (min 11 digits, unique)</td>
                                    </tr>
                                    <tr>
                                        <td>full_name</td>
                                        <td>string</td>
                                        <td><span class="required">Required</span></td>
                                        <td>User's full name</td>
                                    </tr>
                                    <tr>
                                        <td>email</td>
                                        <td>email</td>
                                        <td><span class="optional">Optional</span></td>
                                        <td>Email address (unique)</td>
                                    </tr>
                                    <tr>
                                        <td>username</td>
                                        <td>string</td>
                                        <td><span class="optional">Optional</span></td>
                                        <td>Unique username (auto-generated if empty)</td>
                                    </tr>
                                    <tr>
                                        <td>company_name</td>
                                        <td>string</td>
                                        <td><span class="optional">Optional</span></td>
                                        <td>Company name</td>
                                    </tr>
                                    <tr>
                                        <td>owner_name</td>
                                        <td>string</td>
                                        <td><span class="optional">Optional</span></td>
                                        <td>Owner name</td>
                                    </tr>
                                    <tr>
                                        <td>refer_by</td>
                                        <td>string</td>
                                        <td><span class="optional">Optional</span></td>
                                        <td>Referral username (must exist)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6>💡 Example Request:</h6>
                        <pre>{
    "phone": "8801783465103",
    "full_name": "John Doe",
    "email": "john@example.com",
    "refer_by": "existing_user"
}</pre>

                        <h6>✅ Success Response (201):</h6>
                        <div class="response-example">
                            <pre>{
    "success": true,
    "message": "User registered successfully with referral bonus!",
    "data": {
        "user": {
            "id": 1,
            "full_name": "John Doe",
            "phone": "8801783465103",
            "email": "john@example.com",
            "username": "john_doe"
        },
        "referral": {
            "referred_by": "existing_user",
            "referred_by_name": "Existing User"
        }
    }
}</pre>
                        </div>
                    </div>
                </div>

                <!-- Send OTP (Login) -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/login</span>
                        <span class="badge-public">Public</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📝 Description:</h6>
                        <p>Send OTP to registered phone number for login</p>

                        <h6>📦 Request Body:</h6>
                        <div class="table-responsive">
                            <table class="table table-custom table-sm">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>phone</td>
                                        <td>numeric</td>
                                        <td><span class="required">Required</span></td>
                                        <td>Registered phone number</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6>💡 Example Request:</h6>
                        <pre>{
    "phone": "8801783465103"
}</pre>

                        <h6>✅ Success Response (200):</h6>
                        <div class="response-example">
                            <pre>{
    "success": true,
    "message": "OTP sent successfully to 8801783465103",
    "data": {
        "phone": "8801783465103",
        "otp": "123456"
    }
}</pre>
                        </div>
                    </div>
                </div>

                <!-- Verify OTP -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/verify-otp</span>
                        <span class="badge-public">Public</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📝 Description:</h6>
                        <p>Verify OTP and get authentication token</p>

                        <h6>📦 Request Body:</h6>
                        <div class="table-responsive">
                            <table class="table table-custom table-sm">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>phone</td>
                                        <td>numeric</td>
                                        <td><span class="required">Required</span></td>
                                        <td>Phone number</td>
                                    </tr>
                                    <tr>
                                        <td>otp</td>
                                        <td>numeric</td>
                                        <td><span class="required">Required</span></td>
                                        <td>6-digit OTP received via SMS</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <h6>✅ Success Response (200):</h6>
                        <div class="response-example">
                            <pre>{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "full_name": "John Doe",
            "email": "john@example.com",
            "phone": "8801783465103",
            "username": "john_doe",
            "image": null,
            "main_wallet": 0,
            "income_wallet": 0,
            "withdraw_wallet": 0,
            "refer_bonus": 0,
            "status": 1
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}</pre>
                        </div>
                    </div>
                </div>

                <!-- Resend OTP -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/resend-otp</span>
                        <span class="badge-public">Public</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📝 Description:</h6>
                        <p>Resend OTP to phone number</p>

                        <h6>📦 Request Body:</h6>
                        <pre>{
    "phone": "8801783465103"
}</pre>
                    </div>
                </div>

                <!-- Forgot Password -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/forgot-password</span>
                        <span class="badge-public">Public</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📝 Description:</h6>
                        <p>Send password reset code to phone via SMS</p>

                        <h6>📦 Request Body:</h6>
                        <pre>{
    "phone": "8801783465103"
}</pre>
                    </div>
                </div>

                <!-- Verify Reset Code -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/verify-code</span>
                        <span class="badge-public">Public</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📝 Description:</h6>
                        <p>Verify password reset code</p>

                        <h6>📦 Request Body:</h6>
                        <pre>{
    "phone": "8801783465103",
    "otp": "123456"
}</pre>
                    </div>
                </div>

                <!-- Reset Password -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/reset-password</span>
                        <span class="badge-public">Public</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📝 Description:</h6>
                        <p>Reset password using verification code</p>

                        <h6>📦 Request Body:</h6>
                        <pre>{
    "phone": "8801783465103",
    "password": "newpassword123",
    "password_confirmation": "newpassword123",
    "otp": "123456"
}</pre>
                    </div>
                </div>
            </div>

            <!-- User Panel Tab -->
            <div class="tab-pane fade" id="user">
                <div class="alert alert-warning">
                    <i class="fas fa-lock"></i> <strong>Authentication Required:</strong> All endpoints require Bearer
                    token in Authorization header.
                </div>

                <!-- Logout -->
                <div class="endpoint-card" data-aos="fade-up">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/logout</span>
                        <span class="badge-auth">Auth Required</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📋 Headers:</h6>
                        <pre>{
    "bktoken": "your_bktoken_value",
    "Authorization": "Bearer {user_token}"
}</pre>
                    </div>
                </div>

                <!-- Get Profile -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-get">GET</span>
                        <span class="endpoint-url">/user/profile</span>
                        <span class="badge-auth">Auth Required</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>✅ Success Response:</h6>
                        <div class="response-example">
                            <pre>{
    "success": true,
    "data": {
        "id": 1,
        "full_name": "John Doe",
        "email": "john@example.com",
        "phone": "8801783465103",
        "username": "john_doe",
        "main_wallet": 1000.50,
        "income_wallet": 500.25,
        "withdraw_wallet": 200.00,
        "refer_bonus": 150.00,
        "status": 1
    }
}</pre>
                        </div>
                    </div>
                </div>

                <!-- Update Profile -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-put">PUT</span>
                        <span class="endpoint-url">/user/profile</span>
                        <span class="badge-auth">Auth Required</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📦 Request Body (All Optional):</h6>
                        <div class="table-responsive">
                            <table class="table table-custom table-sm">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>full_name</td>
                                        <td>string</td>
                                        <td>Full name</td>
                                    </tr>
                                    <tr>
                                        <td>email</td>
                                        <td>email</td>
                                        <td>Email address</td>
                                    </tr>
                                    <tr>
                                        <td>username</td>
                                        <td>string</td>
                                        <td>Username</td>
                                    </tr>
                                    <tr>
                                        <td>city_name</td>
                                        <td>string</td>
                                        <td>City name</td>
                                    </tr>
                                    <tr>
                                        <td>present_address</td>
                                        <td>string</td>
                                        <td>Present address</td>
                                    </tr>
                                    <tr>
                                        <td>parmanent_address</td>
                                        <td>string</td>
                                        <td>Permanent address</td>
                                    </tr>
                                    <tr>
                                        <td>date_of_birth</td>
                                        <td>string</td>
                                        <td>Date of birth</td>
                                    </tr>
                                    <tr>
                                        <td>blood_group</td>
                                        <td>string</td>
                                        <td>Blood group</td>
                                    </tr>
                                    <tr>
                                        <td>gender</td>
                                        <td>string</td>
                                        <td>Gender (Male/Female/Other)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Upload Avatar -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/avatar</span>
                        <span class="badge-auth">Auth Required</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📝 Description:</h6>
                        <p>Upload user profile picture</p>
                        <h6>📦 Request (multipart/form-data):</h6>
                        <pre>avatar: (image file) jpeg,png,jpg,gif (max 2MB)</pre>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/user/change-password</span>
                        <span class="badge-auth">Auth Required</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📦 Request Body:</h6>
                        <pre>{
    "current_password": "oldpassword",
    "new_password": "newpassword123",
    "new_password_confirmation": "newpassword123"
}</pre>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-delete">DELETE</span>
                        <span class="endpoint-url">/user/delete-account</span>
                        <span class="badge-auth">Auth Required</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📦 Request Body (Optional):</h6>
                        <pre>{
    "reason": "Reason for account deletion"
}</pre>
                    </div>
                </div>

                <!-- Dashboard -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="600">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-get">GET</span>
                        <span class="endpoint-url">/user/dashboard</span>
                        <span class="badge-auth">Auth Required</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>✅ Success Response:</h6>
                        <div class="response-example">
                            <pre>{
    "success": true,
    "data": {
        "user_info": {...},
        "wallets": {
            "main_wallet": 1000.50,
            "income_wallet": 500.25,
            "withdraw_wallet": 200.00,
            "refer_bonus": 150.00
        }
    }
}</pre>
                        </div>
                    </div>
                </div>
            </div>

           

            <!-- Admin Panel Tab -->
            <div class="tab-pane fade" id="admin">
                <div class="alert alert-warning">
                    <i class="fas fa-lock"></i> <strong>Admin Authentication Required:</strong> Admin token required.
                </div>

                <!-- Admin Login -->
                <div class="endpoint-card" data-aos="fade-up">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/admin/login</span>
                        <span class="badge-public">Public</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📦 Request Body:</h6>
                        <pre>{
    "email": "admin@example.com",
    "password": "admin123"
}</pre>
                    </div>
                </div>

                <!-- Get All Users -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-get">GET</span>
                        <span class="endpoint-url">/admin/users</span>
                        <span class="badge-auth">Admin Only</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📋 Headers:</h6>
                        <pre>{
    "bktoken": "your_bktoken_value",
    "Authorization": "Bearer {admin_token}"
}</pre>
                        <h6>📊 Query Parameters:</h6>
                        <pre>per_page=15  // Items per page (optional)</pre>
                    </div>
                </div>

                <!-- Update User Status -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-put">PUT</span>
                        <span class="endpoint-url">/admin/users/{id}/status</span>
                        <span class="badge-auth">Admin Only</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📦 Request Body:</h6>
                        <pre>{
    "status": 1  // 1=Active, 0=Inactive
}</pre>
                    </div>
                </div>

                <!-- Update User Wallets -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-put">PUT</span>
                        <span class="endpoint-url">/admin/users/{id}/wallets</span>
                        <span class="badge-auth">Admin Only</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📦 Request Body:</h6>
                        <pre>{
    "main_wallet": 1000.00,
    "income_wallet": 500.00,
    "withdraw_wallet": 200.00,
    "refer_bonus": 50.00
}</pre>
                    </div>
                </div>

                <!-- Delete User -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-delete">DELETE</span>
                        <span class="endpoint-url">/admin/users/{id}</span>
                        <span class="badge-auth">Admin Only</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>⚠️ Warning:</h6>
                        <p>This action permanently deletes the user and all associated data.</p>
                    </div>
                </div>

                <!-- Create Admin -->
                <div class="endpoint-card" data-aos="fade-up" data-aos-delay="500">
                    <div class="endpoint-header" onclick="toggleEndpoint(this)">
                        <span class="method method-post">POST</span>
                        <span class="endpoint-url">/admin/admins</span>
                        <span class="badge-auth">Admin Only</span>
                        <i class="fas fa-chevron-down float-end mt-1"></i>
                    </div>
                    <div class="endpoint-body">
                        <h6>📦 Request Body:</h6>
                        <pre>{
    "name": "New Admin",
    "username": "admin2",
    "email": "admin2@example.com",
    "phone": 1234567890,
    "password": "password123",
    "password_confirmation": "password123"
}</pre>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-logo" data-aos="zoom-in">
                <img src="https://snazrul.speakupbd.com/uploads/slidelogo/1756054484nazrul.jpg"
                    alt="Nazrul Islam Suzon">
            </div>
            <div class="developer-info" data-aos="fade-up">
                <h5><i class="fas fa-code"></i> Developed by Nazrul Islam Suzon</h5>
                <p class="mb-2"><strong>Full Stack Web Developer</strong></p>
                <div class="tech-stack">
                    <span class="tech-badge">HTML5</span> <span class="tech-badge">CSS3</span> <span
                        class="tech-badge">Bootstrap</span>
                    <span class="tech-badge">Tailwind</span> <span class="tech-badge">JavaScript</span> <span
                        class="tech-badge">jQuery</span>
                    <span class="tech-badge">React JS</span> <span class="tech-badge">Next JS</span> <span
                        class="tech-badge">PHP</span>
                    <span class="tech-badge">Laravel</span> <span class="tech-badge">Livewire</span>
                </div>
            </div>
            <p class="mt-3 mb-0" data-aos="fade-up" data-aos-delay="100">
                <small>
                    <i class="fas fa-clock"></i> API Version: 2.0.0 |
                    <i class="fas fa-shield-alt"></i> Authentication: Sanctum + bktoken |
                    <i class="fas fa-gift"></i> Referral System: Active
                </small>
            </p>
        </div>
    </div>

    <button class="scroll-top-btn" onclick="scrollToTop()"><i class="fas fa-arrow-up"></i></button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000
        };

        function copyBaseUrl() {
            const baseUrl = document.getElementById("baseUrl").innerText;
            navigator.clipboard.writeText(baseUrl).then(() => toastr.success("✅ Base URL copied successfully!", "Success"))
                .catch(() => toastr.error("❌ Failed to copy URL", "Error"));
        }

        function toggleEndpoint(element) {
            const body = element.nextElementSibling;
            const icon = element.querySelector(".fa-chevron-down");
            if (body.classList.contains("show")) {
                body.classList.remove("show");
                if (icon) icon.style.transform = "rotate(0deg)";
            } else {
                body.classList.add("show");
                if (icon) icon.style.transform = "rotate(180deg)";
            }
        }

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
            toastr.info("Back to top!", "Navigation");
        }

        const scrollBtn = document.querySelector(".scroll-top-btn");
        window.addEventListener("scroll", () => scrollBtn.style.display = window.scrollY > 300 ? "block" : "none");

        document.querySelectorAll("pre").forEach((pre) => {
            const copyBtn = document.createElement("button");
            copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy';
            copyBtn.className = "copy-btn";
            copyBtn.style.cssText = "position:absolute; top:10px; right:10px; font-size:0.7rem; padding:4px 8px";
            pre.style.position = "relative";
            copyBtn.onclick = () => {
                navigator.clipboard.writeText(pre.innerText).then(() => toastr.success("Code copied!",
                    "Success"));
            };
            pre.appendChild(copyBtn);
        });

        // Disable Right Click
        document.addEventListener("contextmenu", function(e) {
            e.preventDefault();
            toastr.error("❌ Right click is disabled!", "Error");
        });
    </script>
</body>

</html>
