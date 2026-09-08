<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>Payment Successful</title>

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

        .success-card {
            width: 100%;
            max-width: 520px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .08);
        }

        .success-header {
            padding: 35px 25px;
            text-align: center;
            background: linear-gradient(135deg, #198754, #146c43);
            color: white;
        }

        .success-icon {
            width: 85px;
            height: 85px;
            margin: 0 auto 18px;

            background: rgba(255,255,255,.2);
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 45px;
        }

        .success-header h2 {
            font-weight: 700;
            margin-bottom: 8px;
        }

        .success-header p {
            margin: 0;
            opacity: .9;
        }

        .success-body {
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

            background: #d1e7dd;
            color: #146c43;

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

        .amount {
            font-size: 22px;
            color: #198754;
        }

        .footer-text {
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            margin-top: 25px;
        }

        .btn-home {
            height: 50px;
            border-radius: 10px;
            font-weight: 600;
        }

    </style>

</head>


<body>

<div class="container">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card success-card">


                {{-- Header --}}
                <div class="success-header">

                    <div class="success-icon">
                        ✓
                    </div>

                    <h2>Payment Successful!</h2>

                    <p>
                        Your payment has been completed successfully.
                    </p>

                </div>


                {{-- Body --}}
                <div class="success-body">


                    {{-- Status --}}
                    <div class="status-box">

                        <span class="status-badge">

                            ✓ Payment Completed

                        </span>

                    </div>


                    {{-- Payment Details --}}
                    <div class="payment-info">


                        {{-- Status --}}
                        <div class="payment-row">

                            <span class="payment-label">
                                Payment Status
                            </span>

                            <span class="payment-value text-success">

                                {{ $status ?? 'Success' }}

                            </span>

                        </div>


                        {{-- EPS Transaction --}}
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


                        {{-- Merchant Transaction --}}
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

                                <span class="payment-value amount">

                                    ৳ {{ number_format($amount, 2) }}

                                </span>

                            </div>

                        @endif


                        {{-- Payment Method --}}
                        @if(!empty($paymentData['FinancialEntity']))

                            <div class="payment-row">

                                <span class="payment-label">
                                    Payment Method
                                </span>

                                <span class="payment-value">

                                    {{ $paymentData['FinancialEntity'] }}

                                </span>

                            </div>

                        @endif


                        {{-- Payment Date --}}
                        @if(!empty($paymentData['TransactionDate']))

                            <div class="payment-row">

                                <span class="payment-label">
                                    Payment Date
                                </span>

                                <span class="payment-value">

                                    {{ $paymentData['TransactionDate'] }}

                                </span>

                            </div>

                        @endif


                    </div>


                    {{-- Button --}}
                    <div class="mt-4">

                        <a
                            href="{{ url('/') }}"
                            class="btn btn-success w-100 btn-home"
                        >

                            ← Back to Home

                        </a>

                    </div>


                    {{-- Footer --}}
                    <div class="footer-text">

                        Thank you for your payment.

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