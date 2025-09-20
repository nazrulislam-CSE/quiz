@extends('layouts.frontend.app', [$pageTitle => ''])
@section('content')
<div class="container">
    <div class="row mb-4 g-3">
        <div class="col-lg-12">
            <div class="row">
                <h5 class="step-title fw-bold text-success text-center mt-5 mb-4">কুইজ সিলেক্ট করুন</h5>
                @forelse($quizs as $quiz)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow border-success h-100 ">
                            <div class="card-header bg-success text-white fw-bold">
                                {{ $quiz->title }}
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>সময়:</strong> {{ $quiz->exam_duration }} মিনিট</p>
                                <p class="mb-1"><strong>মার্ক:</strong> {{ $quiz->exam_mark }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                <a href="{{ route('user.online.quiz.exam',$quiz->id) }}" class="btn btn-danger">
                                    এক্সাম দিতে ক্লিক করুন
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning">কোনো সক্রিয় কুইজ পাওয়া যায়নি।</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
