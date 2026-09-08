<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Payment Failed</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            min-height: 100vh;
            background: #f4f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .failed-card {
            width: 100%;
            max-width: 520px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .08);
        }

        .failed-header {
            padding: 35px 25px;
            text-align: center;
            background: linear-gradient(135deg, #dc3545, #9b1c28);
            color: white;
        }

        .failed-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto 18px;

            background: rgba(255,255,255,.2);
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 45px;
            font-weight: bold;
        }

        .failed-header h2 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .failed-header p {
            margin: 0;
            opacity: .9;
        }

        .failed-body {
            padding: 35px;
        }

        .status-box {
            text-align: center;
            margin-bottom: 25px;
        }

        .status-badge {
            display: inline-block;
            padding: 7px 18px;
            border-radius: 50px;

            background: #f8d7da;
            color: #842029;

            font-size: 14px;
            font-weight: 600;
        }

        .payment-info {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 14px 16px;

            border-bottom: 1px solid #e9ecef;
        }

        .payment-row:last-child {
            border-bottom: none;
        }

        .payment-label {
            color: #6c757d;
            font-size: 14px;
        }

        .payment-value {
            font-weight: 600;
            color: #212529;

            max-width: 250px;
            text-align: right;

            word-break: break-all;
        }

        .error-message {
            margin-top: 20px;
            padding: 15px;

            background: #fff5f5;
            border: 1px solid #f5c2c7;

            border-radius: 10px;

            color: #842029;

            font-size: 14px;
        }

        .btn-retry {
            height: 52px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-home {
            height: 52px;
            border-radius: 10px;
            font-weight: 600;
        }

        .footer-text {
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            margin-top: 25px;
        }

    </style>

</head>


<body>

<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card failed-card">


                {{-- Header --}}
                <div class="failed-header">

                    <div class="failed-icon">
                        ✕
                    </div>

                    <h2>Payment Failed!</h2>

                    <p>
                        Unfortunately, your payment could not be completed.
                    </p>

                </div>


                {{-- Body --}}
                <div class="failed-body">


                    {{-- Status --}}
                    <div class="status-box">

                        <span class="status-badge">

                            ✕ Payment Failed

                        </span>

                    </div>


                    {{-- Transaction Information --}}
                    <div class="payment-info">


                        {{-- Status --}}
                        <div class="payment-row">

                            <span class="payment-label">
                                Payment Status
                            </span>

                            <span class="payment-value text-danger">

                                {{ $status ?? 'Failed' }}

                            </span>

                        </div>


                        {{-- EPS Transaction ID --}}
                        @if(!empty($transactionId))

                            <div class="payment-row">

                                <span class="payment-label">
                                    EPS Transaction ID
                                </span>

                                <span class="payment-value">

                                    {{ $transactionId }}

                                </span>

                            </div>

                        @endif


                        {{-- Merchant Transaction ID --}}
                        @if(!empty($merchantTransactionId))

                            <div class="payment-row">

                                <span class="payment-label">
                                    Merchant Transaction ID
                                </span>

                                <span class="payment-value">

                                    {{ $merchantTransactionId }}

                                </span>

                            </div>

                        @endif


                        {{-- Amount --}}
                        @if(!empty($amount))

                            <div class="payment-row">

                                <span class="payment-label">
                                    Amount
                                </span>

                                <span class="payment-value">

                                    ৳ {{ number_format($amount, 2) }}

                                </span>

                            </div>

                        @endif


                    </div>


                    {{-- Error Message --}}
                    <div class="error-message">

                        <strong>Payment Error:</strong>

                        <br>

                        {{ $errorMessage ?? 'Your payment was not successful. Please try again.' }}

                    </div>


                    {{-- Buttons --}}
                    <div class="row mt-4">

                        <div class="col-6">

                            <a
                                href="{{ url()->previous() }}"
                                class="btn btn-danger w-100 btn-retry"
                            >
                                Try Again
                            </a>

                        </div>


                        <div class="col-6">

                            <a
                                href="{{ url('/') }}"
                                class="btn btn-outline-secondary w-100 btn-home"
                            >
                                Back Home
                            </a>

                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="footer-text">

                        Please check your payment information and try again.

                        <br>

                        Powered by EPS Payment Gateway

                    </div>


                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>