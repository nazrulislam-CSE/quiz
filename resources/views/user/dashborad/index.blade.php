@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
    <!-- Welcome Card -->
    <div class="card text-white bg-primary mb-4">
        <div class="card-body">
            <h4 class="card-title">
                Welcome back, {{ ucfirst(Auth::user()->name ?? '') }}!
            </h4>
            <p class="card-text">Here's what's happening with your platform today.</p>
        </div>
    </div>


    <!-- Dashboard Buttons -->
    <div class="row mb-4 g-3">
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-primary w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                <h5 class="mb-0">Balance Request</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-info w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <h5 class="mb-0">Report</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-warning w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-list fa-2x mb-2"></i>
                <h5 class="mb-0">Porikhar List</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-success w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                <h5 class="mb-0">Withdraw</h5>
            </a>
        </div>
        <div class="col-md-4 col-sm-6">
            <a href="#" class="btn btn-secondary w-100 text-white d-flex flex-column align-items-center py-4">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5 class="mb-0">Generation Income</h5>
            </a>
        </div>
    </div>


    <!-- Total Balance & Quick Stats -->
    <div class="row mb-4 g-3">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Balance</h5>
                    <h2 class="text-primary">$12,567.89</h2>

                    <!-- Bootstrap Button Group -->
                    <div class="btn-group mt-3 w-100" role="group" aria-label="Balance Actions">
                        <button type="button" class="btn btn-sm btn-primary"><i class="fas fa-plus me-2"></i>Add
                            Funds</button>
                        <button type="button" class="btn btn-sm btn-success"><i
                                class="fas fa-download me-2"></i>Withdraw</button>
                        <button type="button" class="btn btn-sm btn-info"><i
                                class="fas fa-exchange-alt me-2"></i>Transfer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Selection -->
    <div class="row mb-4 g-3">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Select Department</h5>
                    <form action="#" method="POST">
                        @csrf
                        <div class="mb-3">
                            <select class="form-select" name="department" required>
                                <option value="" selected>Select Department</option>
                                <option value="science">Science & Technology</option>
                                <option value="business">Business & Management</option>
                                <option value="arts">Arts & Humanities</option>
                                <option value="health">Health Sciences</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
