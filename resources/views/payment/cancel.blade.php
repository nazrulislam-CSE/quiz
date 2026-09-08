<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Payment Cancelled</title>

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

        .cancel-card {
            width: 100%;
            max-width: 520px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .08);
        }

        .cancel-header {
            padding: 35px 25px;
            text-align: center;
            background: linear-gradient(135deg, #fd7e14, #c85a00);
            color: white;
        }

        .cancel-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto 18px;

            background: rgba(255,255,255,.20);
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 42px;
            font-weight: bold;
        }

        .cancel-header h2 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .cancel-header p {
            margin: 0;
            opacity: .9;
        }

        .cancel-body {
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

            background: #fff3cd;
            color: #856404;

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

        .cancel-message {
            margin-top: 20px;
            padding: 16px;

            background: #fff8e1;
            border: 1px solid #ffe69c;

            border-radius: 10px;

            color: #856404;
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

            <div class="card cancel-card">


                {{-- Header --}}
                <div class="cancel-header">

                    <div class="cancel-icon">
                        !
                    </div>

                    <h2>Payment Cancelled</h2>

                    <p>
                        You cancelled the payment process.
                    </p>

                </div>


                {{-- Body --}}
                <div class="cancel-body">


                    {{-- Status --}}
                    <div class="status-box">

                        <span class="status-badge">

                            ! Payment Cancelled

                        </span>

                    </div>


                    {{-- Transaction Details --}}
                    <div class="payment-info">


                        {{-- Status --}}
                        <div class="payment-row">

                            <span class="payment-label">
                                Payment Status
                            </span>

                            <span class="payment-value text-warning">

                                {{ $status ?? 'Cancelled' }}

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


                    {{-- Cancel Message --}}
                    <div class="cancel-message">

                        <strong>Payment Cancelled:</strong>

                        <br>

                        {{ $message ?? 'No payment was completed. You can try again whenever you are ready.' }}

                    </div>


                    {{-- Buttons --}}
                    <div class="row mt-4 g-3">

                        <div class="col-6">

                            <a
                                href="{{ url()->previous() }}"
                                class="btn btn-warning w-100 btn-retry"
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

                        Your payment was not processed.

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