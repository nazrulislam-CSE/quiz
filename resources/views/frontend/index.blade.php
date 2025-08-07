@extends('layouts.frontend.app')
@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <!-- Animated Quiz Title -->
            <h2 class="mb-4 animate__animated animate__bounceIn text-center bg-success text-light p-3 rounded">Speakup Quiz</h2>

            <!-- Quiz Card -->
            <div class="card shadow-lg quiz-card animate__animated animate__fadeInUp">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0 text-light">Quiz Question</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">What is the capital of France?</p>

                    <div class="list-group">
                        <button class="list-group-item list-group-item-action">A. Berlin</button>
                        <button class="list-group-item list-group-item-action">B. Madrid</button>
                        <button class="list-group-item list-group-item-action">C. Paris</button>
                        <button class="list-group-item list-group-item-action">D. Rome</button>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-success">Submit</button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Animate.css CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<!-- Optional: Custom Style -->
<style>
    .quiz-card {
        transition: transform 0.3s ease-in-out;
    }

    .quiz-card:hover {
        transform: scale(1.02);
    }
</style>

@endsection
