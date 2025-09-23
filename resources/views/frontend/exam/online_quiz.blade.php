@extends('layouts.frontend.app', [$pageTitle => ''])
@section('content')
    <style>
        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        .animate-marquee span {
            display: inline-block;
            min-width: 100%;
        }
    </style>

    <div class="container">
        <div class="row mb-4 g-3">
            <div class="col-lg-12">
                <div class="row">
                    <h5 class="step-title fw-bold text-success text-center mt-5 mb-4">কুইজ সিলেক্ট করুন</h5>
                    @forelse($quizs as $quiz)
                        @php
                            $examStart = \Carbon\Carbon::parse($quiz->exam_datetime);
                        @endphp

                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card shadow border-success h-100">
                                <div class="card-header bg-success text-white fw-bold">
                                    {{ $quiz->title }}
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>সময়:</strong> {{ $quiz->exam_duration }} মিনিট</p>
                                    <p class="mb-1"><strong>মার্ক:</strong> {{ $quiz->exam_mark }}</p>
                                </div>

                                <div class="card-footer d-flex justify-content-center">
                                    @if (now()->lt($examStart))
                                        <div class="w-100">
                                            <!-- Scrolling notice -->
                                            <div class="alert alert-warning p-2 animate-marquee text-center mb-2"
                                                style="white-space: nowrap; overflow: hidden;">
                                                <span class="d-inline-block"
                                                    style="animation: scroll-left 15s linear infinite;">
                                                    🔒 এই এক্সামটি {{ $examStart->format('d M, h:i A') }} থেকে আনলক হবে। তখন
                                                    আপনি এক্সাম দিতে পারবেন।
                                                </span>
                                            </div>

                                            <!-- Locked button -->
                                            <button class="btn btn-secondary w-100" disabled>
                                                🔒 লক - {{ $examStart->format('d M, h:i A') }} পর্যন্ত
                                            </button>
                                        </div>
                                    @else
                                        <!-- Normal unlocked quiz button -->
                                        <a href="{{ route('user.online.quiz.exam', $quiz->id) }}"
                                            class="btn btn-danger w-100">
                                            এক্সাম দিতে ক্লিক করুন
                                        </a>
                                    @endif
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
