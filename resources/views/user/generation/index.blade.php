@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<div class="container">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="row">

        <!-- First Generation -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white fw-bold">First Generation</div>
                <div class="card-body">
                    @forelse($firstGen as $user)
                        <p>{{ $user->username }} ({{ $user->email }})</p>
                    @empty
                        <p>No users found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Second Generation -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-success">
                <div class="card-header bg-success text-white fw-bold">Second Generation</div>
                <div class="card-body">
                    @forelse($secondGen as $user)
                        <p>{{ $user->username }} ({{ $user->email }})</p>
                    @empty
                        <p>No users found.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Third Generation -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-white fw-bold">Third Generation</div>
                <div class="card-body">
                    @forelse($thirdGen as $user)
                        <p>{{ $user->username }} ({{ $user->email }})</p>
                    @empty
                        <p>No users found.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
