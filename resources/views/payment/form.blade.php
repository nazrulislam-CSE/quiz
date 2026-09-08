<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>EPS Payment Gateway</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: #f4f7fb;
            display: flex;
            align-items: center;
        }

        .payment-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .08);
        }

        .payment-header {
            padding: 28px;
            text-align: center;
            background: linear-gradient(135deg, #0d6efd, #084298);
            color: white;
        }

        .payment-icon {
            width: 65px;
            height: 65px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .18);

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 28px;
        }

        .payment-header h3 {
            margin-bottom: 5px;
            font-weight: 700;
        }

        .payment-header p {
            margin-bottom: 0;
            opacity: .85;
            font-size: 14px;
        }

        .payment-body {
            padding: 35px;
        }

        .form-label {
            font-weight: 600;
            color: #343a40;
        }

        .amount-input {
            height: 55px;
            font-size: 20px;
            font-weight: 600;
            border-radius: 10px;
        }

        .amount-input:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }

        .currency-box {
            height: 55px;
            display: flex;
            align-items: center;
            font-weight: 600;
            background: #eef3ff;
            border-radius: 10px 0 0 10px;
        }

        .pay-btn {
            height: 55px;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 600;
            transition: .3s;
        }

        .pay-btn:hover {
            transform: translateY(-2px);
        }

        .secure-info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;

            text-align: center;
            color: #6c757d;
            font-size: 13px;
        }

        .secure-icon {
            font-size: 18px;
            color: #198754;
        }

        .test-badge {
            display: inline-block;
            padding: 5px 12px;
            margin-bottom: 15px;

            font-size: 12px;
            font-weight: 600;

            background: #fff3cd;
            color: #856404;

            border-radius: 30px;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-5 col-md-7">

                <div class="card payment-card">

                    {{-- Header --}}
                    <div class="payment-header">

                        <div class="payment-icon">
                            💳
                        </div>

                        <h3>EPS Payment Gateway</h3>

                        <p>Secure Online Payment</p>

                    </div>


                    {{-- Body --}}
                    <div class="payment-body">

                        <div class="text-center">

                            <span class="test-badge">
                                PAYMENT TEST MODE
                            </span>

                        </div>


                        {{-- Error --}}
                        @if(session('error'))

                        <div class="alert alert-danger">

                            {{ session('error') }}

                        </div>

                        @endif


                        {{-- Success --}}
                        @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                        @endif


                        {{-- Validation Errors --}}
                        @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                        @endif


                        {{-- Payment Form --}}
                        <form action="{{ route('eps.payment.initialize') }}" method="POST" id="paymentForm">

                            @csrf


                            {{-- Amount --}}
                            <div class="mb-4">

                                <label for="amount" class="form-label">
                                    Payment Amount
                                </label>


                                <div class="input-group">

                                    <span class="input-group-text currency-box">
                                        ৳
                                    </span>


                                    <input type="number" class="form-control amount-input" id="amount" name="amount"
                                        value="1700" min="1" step="0.01" placeholder="Enter amount" required>

                                </div>


                                <div class="form-text mt-2">

                                    Enter the amount you want to pay.

                                </div>

                            </div>


                            {{-- Button --}}
                            <button type="submit" class="btn btn-primary w-100 pay-btn" id="payButton">

                                <span id="buttonText">

                                    Proceed to Payment →

                                </span>

                            </button>

                        </form>


                        {{-- Security Info --}}
                        <div class="secure-info">

                            <div class="secure-icon">
                                🔒
                            </div>

                            <strong>Secure Payment</strong>

                            <br>

                            Your payment information is securely processed
                            through EPS Payment Gateway.

                        </div>

                    </div>

                </div>


                {{-- Footer --}}
                <div class="text-center mt-3 text-muted small">

                    © {{ date('Y') }} Easy Payment System

                </div>

            </div>

        </div>

    </div>


    <script>
        document
        .getElementById('paymentForm')
        .addEventListener('submit', function () {

            const button =
                document.getElementById('payButton');

            const text =
                document.getElementById('buttonText');


            button.disabled = true;

            text.innerHTML =
                'Processing Payment...';

        });

    </script>

</body>

</html>