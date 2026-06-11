<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes"
    />
    <title>ChalkboardBD API Documentation</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
    />
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

      /* Mobile Responsive Padding */
      @media (max-width: 768px) {
        body {
          padding: 10px;
        }
      }

      /* Animated Background */
      body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>')
          no-repeat bottom;
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
        background: radial-gradient(
          circle,
          rgba(255, 255, 255, 0.1) 0%,
          transparent 70%
        );
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
          word-break: break-all;
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
        overflow: hidden;
        font-size: 0.9rem;
      }

      @media (max-width: 768px) {
        .nav-tabs .nav-link {
          padding: 10px 12px;
          font-size: 0.85rem;
        }
      }

      .nav-tabs .nav-link::before {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--primary);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }

      .nav-tabs .nav-link:hover::before {
        transform: translateX(0);
      }

      .nav-tabs .nav-link:hover {
        color: var(--primary);
        border: none;
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
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
      }

      .endpoint-card:nth-child(1) {
        animation-delay: 0.1s;
      }
      .endpoint-card:nth-child(2) {
        animation-delay: 0.2s;
      }
      .endpoint-card:nth-child(3) {
        animation-delay: 0.3s;
      }
      .endpoint-card:nth-child(4) {
        animation-delay: 0.4s;
      }
      .endpoint-card:nth-child(5) {
        animation-delay: 0.5s;
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
        background: linear-gradient(
          90deg,
          transparent,
          var(--primary),
          transparent
        );
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

      /* Toastr Custom Styles */
      .toast-success {
        background-color: #10b981 !important;
      }

      .toast-error {
        background-color: #ef4444 !important;
      }

      .toast-info {
        background-color: #3b82f6 !important;
      }

      .toast-warning {
        background-color: #f59e0b !important;
      }

      /* Scroll to top button */
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
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
      }

      /* Alert Responsive */
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
    </style>
  </head>
  <body>
    <div class="api-container">
      <div class="header">
        <div class="logo-container" data-aos="zoom-in" data-aos-duration="1000">
          <div class="logo">
            <img
              src="https://chalkboardbd.com/upload/setting/logo/1759778293538.png"
              alt="ChalkboardBD Logo"
            />
          </div>
        </div>
        <h1 data-aos="fade-up">
          <i class="fas fa-chalkboard"></i> ChalkboardBD API
        </h1>
        <p data-aos="fade-up" data-aos-delay="100">
          Complete API Documentation for Seamless Integration
        </p>
        <div class="base-url" data-aos="flip-up" data-aos-delay="200">
          <i class="fas fa-link"></i> Base URL:
          <code id="baseUrl">https://chalkboardbd.com/api/v1</code>
          <button class="copy-btn" onclick="copyBaseUrl()">
            <i class="fas fa-copy"></i> Copy URL
          </button>
        </div>
        <div class="mt-3" data-aos="fade-up" data-aos-delay="300">
          <span class="badge bg-light text-dark me-2"
            ><i class="fas fa-shield-alt"></i> bktoken Required</span
          >
          <span class="badge bg-light text-dark"
            ><i class="fas fa-key"></i> Sanctum Authentication</span
          >
        </div>
      </div>

      <ul class="nav nav-tabs" id="apiTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-bs-toggle="tab" href="#userAuth">
            <i class="fas fa-user"></i> User Auth
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#userPanel">
            <i class="fas fa-user-circle"></i> User Panel
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#adminAuth">
            <i class="fas fa-user-shield"></i> Admin Auth
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#adminPanel">
            <i class="fas fa-cog"></i> Admin Panel
          </a>
        </li>
      </ul>

      <div class="tab-content content">
        <!-- User Authentication Tab -->
        <div class="tab-pane fade show active" id="userAuth">
          <div class="alert alert-info" data-aos="fade-right">
            <i class="fas fa-info-circle"></i> <strong>Important:</strong> All
            API requests require <code>bktoken</code> in headers.
          </div>

          <!-- Register -->
          <div class="endpoint-card" data-aos="fade-up">
            <div class="endpoint-header" onclick="toggleEndpoint(this)">
              <span class="method method-post">POST</span>
              <span class="endpoint-url">/user/register</span>
              <span class="badge-public">Public</span>
              <i class="fas fa-chevron-down float-end mt-1"></i>
            </div>
            <div class="endpoint-body">
              <h6>📝 Description:</h6>
              <p>Register a new user account</p>

              <h6>📋 Headers:</h6>
              <pre>
{
    "bktoken": "your_bktoken_value",
    "Content-Type": "application/json"
}</pre
              >

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
                      <td>email</td>
                      <td>string</td>
                      <td><span class="required">Required</span></td>
                      <td>Valid email address</td>
                    </tr>
                    <tr>
                      <td>password</td>
                      <td>string</td>
                      <td><span class="required">Required</span></td>
                      <td>Min 8 characters</td>
                    </tr>
                    <tr>
                      <td>password_confirmation</td>
                      <td>string</td>
                      <td><span class="required">Required</span></td>
                      <td>Must match password</td>
                    </tr>
                    <tr>
                      <td>full_name</td>
                      <td>string</td>
                      <td><span class="optional">Optional</span></td>
                      <td>User's full name</td>
                    </tr>
                    <tr>
                      <td>phone</td>
                      <td>integer</td>
                      <td><span class="optional">Optional</span></td>
                      <td>Phone number</td>
                    </tr>
                    <tr>
                      <td>username</td>
                      <td>string</td>
                      <td><span class="optional">Optional</span></td>
                      <td>Unique username</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <h6>💡 Example Request:</h6>
              <pre>
{
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "full_name": "John Doe"
}</pre
              >

              <h6>✅ Success Response (201):</h6>
              <div class="response-example">
                <pre>
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {...},
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}</pre
                >
              </div>
            </div>
          </div>

          <!-- Login -->
          <div class="endpoint-card" data-aos="fade-up" data-aos-delay="100">
            <div class="endpoint-header" onclick="toggleEndpoint(this)">
              <span class="method method-post">POST</span>
              <span class="endpoint-url">/user/login</span>
              <span class="badge-public">Public</span>
              <i class="fas fa-chevron-down float-end mt-1"></i>
            </div>
            <div class="endpoint-body">
              <h6>📝 Description:</h6>
              <p>Login user and get authentication token</p>

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
                      <td>email</td>
                      <td>string</td>
                      <td><span class="required">Required</span></td>
                      <td>Registered email</td>
                    </tr>
                    <tr>
                      <td>password</td>
                      <td>string</td>
                      <td><span class="required">Required</span></td>
                      <td>Account password</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <h6>✅ Success Response (200):</h6>
              <div class="response-example">
                <pre>
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {...},
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}</pre
                >
              </div>
            </div>
          </div>

          <!-- Forgot Password -->
          <div class="endpoint-card" data-aos="fade-up" data-aos-delay="200">
            <div class="endpoint-header" onclick="toggleEndpoint(this)">
              <span class="method method-post">POST</span>
              <span class="endpoint-url">/user/forgot-password</span>
              <span class="badge-public">Public</span>
              <i class="fas fa-chevron-down float-end mt-1"></i>
            </div>
            <div class="endpoint-body">
              <h6>📝 Description:</h6>
              <p>Send password reset code to email</p>
            </div>
          </div>

          <!-- Verify Code -->
          <div class="endpoint-card" data-aos="fade-up" data-aos-delay="300">
            <div class="endpoint-header" onclick="toggleEndpoint(this)">
              <span class="method method-post">POST</span>
              <span class="endpoint-url">/user/verify-code</span>
              <span class="badge-public">Public</span>
              <i class="fas fa-chevron-down float-end mt-1"></i>
            </div>
            <div class="endpoint-body">
              <h6>📝 Description:</h6>
              <p>Verify password reset code</p>
            </div>
          </div>

          <!-- Reset Password -->
          <div class="endpoint-card" data-aos="fade-up" data-aos-delay="400">
            <div class="endpoint-header" onclick="toggleEndpoint(this)">
              <span class="method method-post">POST</span>
              <span class="endpoint-url">/user/reset-password</span>
              <span class="badge-public">Public</span>
              <i class="fas fa-chevron-down float-end mt-1"></i>
            </div>
            <div class="endpoint-body">
              <h6>📝 Description:</h6>
              <p>Reset password using verification code</p>
            </div>
          </div>
        </div>

        <!-- User Panel Tab -->
        <div class="tab-pane fade" id="userPanel">
          <div class="alert alert-warning" data-aos="fade-right">
            <i class="fas fa-lock"></i>
            <strong>Authentication Required:</strong> Bearer token required
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
              <pre>
{
    "bktoken": "your_bktoken_value",
    "Authorization": "Bearer {user_token}"
}</pre
              >
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
                <pre>
{
    "success": true,
    "data": {
        "id": 1,
        "full_name": "John Doe",
        "email": "john@example.com",
        "main_wallet": 0
    }
}</pre
                >
              </div>
            </div>
          </div>

          <!-- Dashboard -->
          <div class="endpoint-card" data-aos="fade-up" data-aos-delay="200">
            <div class="endpoint-header" onclick="toggleEndpoint(this)">
              <span class="method method-get">GET</span>
              <span class="endpoint-url">/user/dashboard</span>
              <span class="badge-auth">Auth Required</span>
              <i class="fas fa-chevron-down float-end mt-1"></i>
            </div>
            <div class="endpoint-body">
              <h6>✅ Success Response:</h6>
              <div class="response-example">
                <pre>
{
    "success": true,
    "data": {
        "wallets": {
            "main_wallet": 1000.50,
            "income_wallet": 500.25
        }
    }
}</pre
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Admin Authentication Tab -->
        <div class="tab-pane fade" id="adminAuth">
          <div class="alert alert-info" data-aos="fade-right">
            <i class="fas fa-info-circle"></i> Admin endpoints require
            <code>bktoken</code> header
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
              <pre>
{
    "email": "admin@example.com",
    "password": "password123"
}</pre
              >
            </div>
          </div>
        </div>

        <!-- Admin Panel Tab -->
        <div class="tab-pane fade" id="adminPanel">
          <div class="alert alert-warning" data-aos="fade-right">
            <i class="fas fa-lock"></i>
            <strong>Admin Authentication Required</strong>
          </div>

          <!-- Get All Users -->
          <div class="endpoint-card" data-aos="fade-up">
            <div class="endpoint-header" onclick="toggleEndpoint(this)">
              <span class="method method-get">GET</span>
              <span class="endpoint-url">/admin/users</span>
              <span class="badge-auth">Admin Only</span>
              <i class="fas fa-chevron-down float-end mt-1"></i>
            </div>
            <div class="endpoint-body">
              <h6>📋 Headers:</h6>
              <pre>
{
    "bktoken": "your_bktoken_value",
    "Authorization": "Bearer {admin_token}"
}</pre
              >
            </div>
          </div>

          <!-- Update User Status -->
          <div class="endpoint-card" data-aos="fade-up" data-aos-delay="100">
            <div class="endpoint-header" onclick="toggleEndpoint(this)">
              <span class="method method-put">PUT</span>
              <span class="endpoint-url">/admin/users/{id}/status</span>
              <span class="badge-auth">Admin Only</span>
              <i class="fas fa-chevron-down float-end mt-1"></i>
            </div>
            <div class="endpoint-body">
              <h6>📦 Request Body:</h6>
              <pre>
{
    "status": 1  // 1=Active, 0=Inactive
}</pre
              >
            </div>
          </div>
        </div>
      </div>

      <div class="footer">
        <div class="footer-logo" data-aos="zoom-in">
          <img
            src="https://snazrul.speakupbd.com/uploads/slidelogo/1756054484nazrul.jpg"
            alt="Nazrul Islam Suzon"
          />
        </div>
        <div class="developer-info" data-aos="fade-up">
          <h5><i class="fas fa-code"></i> Developed by Nazrul Islam Suzon</h5>
          <p class="mb-2"><strong>Full Stack Web Developer</strong></p>
          <div class="tech-stack">
            <span class="tech-badge">HTML5</span>
            <span class="tech-badge">CSS3</span>
            <span class="tech-badge">Bootstrap</span>
            <span class="tech-badge">Tailwind</span>
            <span class="tech-badge">JavaScript</span>
            <span class="tech-badge">jQuery</span>
            <span class="tech-badge">React JS</span>
            <span class="tech-badge">Next JS</span>
            <span class="tech-badge">PHP</span>
            <span class="tech-badge">Laravel</span>
            <span class="tech-badge">Livewire</span>
          </div>
        </div>
        <p class="mt-3 mb-0" data-aos="fade-up" data-aos-delay="100">
          <small>
            <i class="fas fa-clock"></i> API Version: 1.0.0 |
            <i class="fas fa-shield-alt"></i> Authentication: Sanctum + bktoken
          </small>
        </p>
      </div>
    </div>

    <button class="scroll-top-btn" onclick="scrollToTop()">
      <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- jQuery (required for toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
      // Initialize AOS
      AOS.init({
        duration: 1000,
        once: true,
        offset: 100,
      });

      // Toastr configuration
      toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: false,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "1000",
        timeOut: "3000",
        extendedTimeOut: "1000",
        showEasing: "swing",
        hideEasing: "linear",
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
      };

      // Function to copy base URL with toastr notification
      function copyBaseUrl() {
        const baseUrl = document.getElementById("baseUrl").innerText;
        navigator.clipboard
          .writeText(baseUrl)
          .then(() => {
            toastr.success("✅ Base URL copied successfully!", "Success", {
              timeOut: 2000,
              progressBar: true,
            });
          })
          .catch(() => {
            toastr.error("❌ Failed to copy URL", "Error", {
              timeOut: 2000,
            });
          });
      }

      // Function to copy any text
      function copyToClipboard(text, element) {
        navigator.clipboard
          .writeText(text)
          .then(() => {
            toastr.success("📋 Copied to clipboard!", "Success", {
              timeOut: 1500,
            });
          })
          .catch(() => {
            toastr.error("Failed to copy", "Error");
          });
      }

      // Toggle endpoint body
      function toggleEndpoint(element) {
        const body = element.nextElementSibling;
        const icon = element.querySelector(".fa-chevron-down");

        if (body.classList.contains("show")) {
          body.classList.remove("show");
          if (icon) {
            icon.style.transform = "rotate(0deg)";
            icon.style.transition = "transform 0.3s ease";
          }
        } else {
          body.classList.add("show");
          if (icon) {
            icon.style.transform = "rotate(180deg)";
            icon.style.transition = "transform 0.3s ease";
          }
        }
      }

      // Scroll to top function
      function scrollToTop() {
        window.scrollTo({ top: 0, behavior: "smooth" });
        toastr.info("Back to top!", "Navigation");
      }

      // Show/hide scroll button
      const scrollBtn = document.querySelector(".scroll-top-btn");

      window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
          scrollBtn.style.display = "block";
        } else {
          scrollBtn.style.display = "none";
        }
      });

      // Add copy buttons to all pre tags
      document.querySelectorAll("pre").forEach((pre, index) => {
        const copyBtn = document.createElement("button");
        copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy';
        copyBtn.className = "copy-btn";
        copyBtn.style.position = "absolute";
        copyBtn.style.top = "10px";
        copyBtn.style.right = "10px";
        copyBtn.style.fontSize = "0.7rem";
        copyBtn.style.padding = "4px 8px";
        pre.style.position = "relative";

        copyBtn.onclick = () => {
          const text = pre.innerText;
          navigator.clipboard.writeText(text).then(() => {
            toastr.success("Code copied!", "Success", { timeOut: 1500 });
          });
        };

        pre.appendChild(copyBtn);
      });

      // Handle mobile touch events
      let touchStart = null;
      document.querySelectorAll(".endpoint-header").forEach((header) => {
        header.addEventListener("touchstart", (e) => {
          touchStart = e.touches[0].clientY;
        });

        header.addEventListener("touchend", (e) => {
          const touchEnd = e.changedTouches[0].clientY;
          if (Math.abs(touchStart - touchEnd) < 10) {
            toggleEndpoint(header);
          }
        });
      });
    </script>

    <script>
      // Disable Right Click
      document.addEventListener("contextmenu", function (e) {
        e.preventDefault();

        toastr.error("❌ Right click is disabled!", "Error", {
          timeOut: 2000,
        });
      });

      // Disable Copy
      document.addEventListener("copy", function (e) {
        e.preventDefault();

        toastr.error("❌ Copy is disabled!", "Error", {
          timeOut: 2000,
        });
      });

      // Disable Cut
      document.addEventListener("cut", function (e) {
        e.preventDefault();

        toastr.error("❌ Cut is disabled!", "Error", {
          timeOut: 2000,
        });
      });

      // Disable Paste
      document.addEventListener("paste", function (e) {
        e.preventDefault();

        toastr.error("❌ Paste is disabled!", "Error", {
          timeOut: 2000,
        });
      });

      // Disable Text Selection
      document.addEventListener("selectstart", function (e) {
        e.preventDefault();
      });

      // Disable Keyboard Shortcuts
      document.addEventListener("keydown", function (e) {
        // Ctrl+C
        if (e.ctrlKey && e.key === "c") {
          e.preventDefault();

          toastr.error("❌ Copy shortcut disabled!", "Error", {
            timeOut: 2000,
          });
        }

        // Ctrl+U
        if (e.ctrlKey && e.key === "u") {
          e.preventDefault();

          toastr.error("❌ View source disabled!", "Error", {
            timeOut: 2000,
          });
        }

        // Ctrl+S
        if (e.ctrlKey && e.key === "s") {
          e.preventDefault();

          toastr.error("❌ Save disabled!", "Error", {
            timeOut: 2000,
          });
        }

        // Ctrl+Shift+I
        if (e.ctrlKey && e.shiftKey && e.key === "I") {
          e.preventDefault();

          toastr.error("❌ Developer tools disabled!", "Error", {
            timeOut: 2000,
          });
        }

        // F12
        if (e.key === "F12") {
          e.preventDefault();

          toastr.error("❌ F12 disabled!", "Error", {
            timeOut: 2000,
          });
        }
      });
    </script>
  </body>
</html>
