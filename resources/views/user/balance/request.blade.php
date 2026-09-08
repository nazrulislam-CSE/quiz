@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container">

    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="card shadow-lg border-0">

        {{-- Card Header --}}
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">

            <span>
                <i class="fas fa-wallet me-2"></i>
                অনলাইনে ব্যালান্স রিচার্জ করুন
            </span>

            <a href="{{ route('user.balance.request.report') }}"
               class="btn btn-light btn-sm">

                <i class="fas fa-history me-1"></i>
                রিপোর্ট দেখুন

            </a>

        </div>


        {{-- Card Body --}}
        <div class="card-body p-4">

            <div class="row justify-content-center">

                <div class="col-lg-7">

                    {{-- Payment Info --}}
                    <div class="text-center mb-4">

                        <div class="payment-icon mb-3">
                            <i class="fas fa-credit-card"></i>
                        </div>

                        <h4 class="fw-bold mb-2">
                            Online Payment Gateway
                        </h4>

                        <p class="text-muted mb-0">
                            নিরাপদে অনলাইনে আপনার অ্যাকাউন্ট ব্যালান্স রিচার্জ করুন
                        </p>

                    </div>


                    {{-- Error Message --}}
                    @if(session('error'))

                        <div class="alert alert-danger">

                            <i class="fas fa-exclamation-circle me-2"></i>

                            {{ session('error') }}

                        </div>

                    @endif


                    {{-- Success Message --}}
                    @if(session('success'))

                        <div class="alert alert-success">

                            <i class="fas fa-check-circle me-2"></i>

                            {{ session('success') }}

                        </div>

                    @endif


                    {{-- Validation Error --}}
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
                    <form action="{{ route('user.eps.payment.initialize') }}"
                          method="POST"
                          id="paymentForm">

                        @csrf


                        {{-- Amount --}}
                        <div class="mb-4">

                            <label for="amount"
                                   class="form-label fw-semibold">

                                <i class="fas fa-money-bill-wave text-success me-1"></i>

                                রিচার্জের পরিমাণ

                            </label>


                            <div class="input-group input-group-lg">

                                <span class="input-group-text bg-success text-white">
                                    ৳
                                </span>


                                <input type="number"
                                       class="form-control"
                                       id="amount"
                                       name="amount"
                                       value="{{ old('amount') }}"
                                       min="5"
                                       step="1"
                                       placeholder="যেমন: 100"
                                       required>

                            </div>


                            <small class="text-muted">

                                সর্বনিম্ন রিচার্জের পরিমাণ ৳100

                            </small>

                        </div>


                        {{-- Submit Button --}}
                        <button type="submit"
                                class="btn btn-success w-100 btn-lg payment-btn"
                                id="payButton">

                            <span id="buttonText">

                                <i class="fas fa-lock me-2"></i>

                                পেমেন্ট করুন

                            </span>

                        </button>

                    </form>
                </div>

            </div>

        </div>

    </div>

</div>


{{-- Custom Style --}}
<style>

    .payment-icon {
        width: 75px;
        height: 75px;
        margin: auto;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e8f5e9;
        color: #198754;

        border-radius: 50%;

        font-size: 30px;
    }


    .payment-btn {
        height: 55px;

        font-weight: 600;

        transition: all .3s ease;
    }


    .payment-btn:hover:not(:disabled) {
        transform: translateY(-2px);

        box-shadow: 0 8px 20px rgba(25, 135, 84, .25);
    }


    .payment-btn:disabled {
        cursor: not-allowed;

        opacity: .75;
    }


    .form-control:focus {
        box-shadow: none;

        border-color: #198754;
    }


    .secure-info {
        border-top: 1px solid #e9ecef;

        padding-top: 20px;
    }

</style>


{{-- Double Click Prevent + Spinner Loader --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const paymentForm = document.getElementById('paymentForm');

    const payButton = document.getElementById('payButton');

    const buttonText = document.getElementById('buttonText');


    // Prevent Multiple Form Submission
    let isSubmitting = false;


    paymentForm.addEventListener('submit', function (event) {


        /*
        |--------------------------------------------------------------------------
        | Prevent Double Submit
        |--------------------------------------------------------------------------
        */

        if (isSubmitting) {

            event.preventDefault();

            return false;

        }


        /*
        |--------------------------------------------------------------------------
        | Check Form Validation
        |--------------------------------------------------------------------------
        */

        if (!paymentForm.checkValidity()) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Mark Form As Submitting
        |--------------------------------------------------------------------------
        */

        isSubmitting = true;


        /*
        |--------------------------------------------------------------------------
        | Disable Payment Button
        |--------------------------------------------------------------------------
        */

        payButton.disabled = true;


        /*
        |--------------------------------------------------------------------------
        | Show Spinner Loader
        |--------------------------------------------------------------------------
        */

        buttonText.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"
                  role="status"
                  aria-hidden="true">
            </span>

            পেমেন্ট প্রসেস হচ্ছে...
        `;


        /*
        |--------------------------------------------------------------------------
        | Optional: Disable Amount Input
        |--------------------------------------------------------------------------
        */

        document.getElementById('amount').readOnly = true;

    });


    /*
    |--------------------------------------------------------------------------
    | Browser Back Button Handling
    |--------------------------------------------------------------------------
    */

    window.addEventListener('pageshow', function () {

        // যদি user back button দিয়ে আবার page এ আসে
        isSubmitting = false;

        payButton.disabled = false;

        buttonText.innerHTML = `
            <i class="fas fa-lock me-2"></i>
            পেমেন্ট করুন
        `;

        document.getElementById('amount').readOnly = false;

    });

});

</script>

@endsection