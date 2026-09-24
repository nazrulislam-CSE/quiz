@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="fas fa-mobile-alt me-2"></i>
                মোবাইল রিচার্জ করুন
            </h5>

            <div class="d-flex align-items-center gap-2">

                {{-- Income Wallet --}}
                <span class="badge bg-light text-dark px-3 py-2">
                    <i class="fas fa-wallet text-success me-1"></i>
                    Wallet:
                    <strong>৳{{ number_format(auth()->user()->income_wallet, 2) }}</strong>
                </span>

                {{-- Recharge History --}}
                <a href="{{ route('user.recharge.history') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-history me-1"></i>
                    রিচার্জ হিস্টোরি দেখুন
                </a>

            </div>

        </div>



        <div class="card-body">

            <form method="POST" action="{{ route('user.recharge.store') }}" id="rechargeForm">
                @csrf

                {{-- মোবাইল নম্বর --}}
                <div class="mb-3">
                    <label for="number" class="form-label">
                        মোবাইল নম্বর
                    </label>

                    <input type="text" name="number" id="number"
                        class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}"
                        placeholder="01XXXXXXXXX" maxlength="11" inputmode="numeric" autocomplete="off" required>

                    @error('number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- অপারেটর --}}
                <div class="mb-3">
                    <label for="operator" class="form-label">
                        অপারেটর নির্বাচন করুন
                    </label>

                    <select name="operator" id="operator" class="form-select @error('operator') is-invalid @enderror"
                        required>
                        <option value="">-- অপারেটর নির্বাচন করুন --</option>

                        <option value="GP" {{ old('operator')=='GP' ? 'selected' : '' }}>
                            গ্রামীণফোন
                        </option>

                        <option value="BL" {{ old('operator')=='BL' ? 'selected' : '' }}>
                            বাংলালিংক
                        </option>

                        <option value="RB" {{ old('operator')=='RB' ? 'selected' : '' }}>
                            রবি
                        </option>

                        <option value="AT" {{ old('operator')=='AT' ? 'selected' : '' }}>
                            এয়ারটেল
                        </option>

                        <option value="TT" {{ old('operator')=='TT' ? 'selected' : '' }}>
                            টেলিটক
                        </option>
                    </select>

                    @error('operator')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- রিচার্জের পরিমাণ --}}
                <div class="mb-3">
                    <label for="amount" class="form-label">
                        রিচার্জের পরিমাণ
                    </label>

                    <div class="input-group">
                        <input type="number" name="amount" id="amount"
                            class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}"
                            min="20" max="10000" placeholder="রিচার্জের পরিমাণ লিখুন" required>

                        <span class="input-group-text">
                            ৳
                        </span>
                    </div>

                    @error('amount')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                {{-- Submit --}}
                <div class="d-grid mt-4">

                    <button type="submit" id="rechargeButton" class="btn btn-primary btn-lg" disabled>
                        <i class="fas fa-mobile-alt me-2" id="rechargeIcon"></i>

                        <span id="buttonText">
                            রিচার্জ করুন
                        </span>
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

    const numberInput = document.getElementById('number');
    const operatorSelect = document.getElementById('operator');
    const amountInput = document.getElementById('amount');
    const form = document.getElementById('rechargeForm');
    const rechargeButton = document.getElementById('rechargeButton');
    const buttonText = document.getElementById('buttonText');
    const rechargeIcon = document.getElementById('rechargeIcon');

    let operatorValid = false;
    let isProcessing = false;


    /*
    |--------------------------------------------------------------------------
    | Operator Map
    |--------------------------------------------------------------------------
    */

    const operatorMap = {
        '013': 'BL',
        '014': 'BL',
        '015': 'TT',
        '016': 'AT',
        '017': 'GP',
        '018': 'RB',
        '019': 'BL'
    };


    const operatorNames = {
        'GP': 'গ্রামীণফোন',
        'BL': 'বাংলালিংক',
        'RB': 'রবি',
        'AT': 'এয়ারটেল',
        'TT': 'টেলিটক'
    };


    /*
    |--------------------------------------------------------------------------
    | Toastr Settings
    |--------------------------------------------------------------------------
    */

    if (typeof toastr !== 'undefined') {

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3500,
            extendedTimeOut: 1000,
            preventDuplicates: true
        };

    }


    /*
    |--------------------------------------------------------------------------
    | Check Operator
    |--------------------------------------------------------------------------
    */

    function checkOperator(showMessage = true) {

        const number = numberInput.value.trim();
        const selectedOperator = operatorSelect.value;

        operatorValid = false;

        if (number.length !== 11) {
            updateButton();
            return false;
        }

        const prefix = number.substring(0, 3);
        const requiredOperator = operatorMap[prefix];

        if (!requiredOperator) {

            if (showMessage && typeof toastr !== 'undefined') {
                toastr.error(
                    'সঠিক মোবাইল নম্বর দিন।',
                    'ভুল নম্বর'
                );
            }

            updateButton();
            return false;
        }


        if (selectedOperator !== requiredOperator) {

            if (showMessage && typeof toastr !== 'undefined') {
                toastr.error(
                    'এই নম্বরের জন্য ' +
                    operatorNames[requiredOperator] +
                    ' নির্বাচন করতে হবে।',
                    'ভুল অপারেটর'
                );
            }

            operatorValid = false;
            updateButton();

            return false;
        }


        operatorValid = true;
        updateButton();

        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Check All Fields
    |--------------------------------------------------------------------------
    */

    function updateButton() {

        const number = numberInput.value.trim();
        const operator = operatorSelect.value;
        const amount = parseFloat(amountInput.value);

        const numberValid =
            /^01[3-9][0-9]{8}$/.test(number);

        const amountValid =
            !isNaN(amount) &&
            amount >= 20 &&
            amount <= 10000;

        const allValid =
            numberValid &&
            operator !== '' &&
            operatorValid &&
            amountValid &&
            !isProcessing;

        rechargeButton.disabled = !allValid;
    }


    /*
    |--------------------------------------------------------------------------
    | Number Input
    |--------------------------------------------------------------------------
    */

    numberInput.addEventListener('input', function () {

        this.value = this.value.replace(/\D/g, '');

        operatorValid = false;

        if (this.value.length === 11) {
            checkOperator(false);
        }

        updateButton();
    });


    /*
    |--------------------------------------------------------------------------
    | Operator Change
    |--------------------------------------------------------------------------
    */

    operatorSelect.addEventListener('change', function () {

        checkOperator(true);
        updateButton();

    });


    /*
    |--------------------------------------------------------------------------
    | Amount Change
    |--------------------------------------------------------------------------
    */

    amountInput.addEventListener('input', function () {

        let amount = parseFloat(this.value);

        if (amount < 20) {
            operatorValid = operatorValid;
        }

        updateButton();

    });


    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener('submit', function (e) {

        if (isProcessing) {
            e.preventDefault();
            return false;
        }


        // Final validation
        if (!checkOperator(true)) {
            e.preventDefault();
            return false;
        }


        const amount = parseFloat(amountInput.value);

        if (isNaN(amount) || amount < 20 || amount > 10000) {

            e.preventDefault();

            if (typeof toastr !== 'undefined') {
                toastr.error(
                    'রিচার্জের পরিমাণ 2০ থেকে ১০,০০০ টাকার মধ্যে হতে হবে।',
                    'ভুল পরিমাণ'
                );
            }

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Processing State
        |--------------------------------------------------------------------------
        */

        isProcessing = true;

        rechargeButton.disabled = true;

        rechargeIcon.className =
            'fas fa-spinner fa-spin me-2';

        buttonText.textContent =
            'প্রসেসিং হচ্ছে...';

    });


    /*
    |--------------------------------------------------------------------------
    | Initial Button State
    |--------------------------------------------------------------------------
    */

    updateButton();

});
</script>

@endsection