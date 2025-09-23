@extends('layouts.frontend.app', [$pageTitle => $pageTitle])

@section('content')
  <style>
        /* Print Styling */
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area, #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                background: white;
                padding: 20px;
            }

            .no-print {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                page-break-inside: avoid;
            }

            .badge {
                font-size: 12px !important;
            }
        }
    </style>
    <div class="container" id="print-area">
        <!-- Exam Header -->
        <div class="row mb-4 g-3">
            <div class="col-lg-12">
                <h3 class="text-center text-success fw-bold mt-5 mb-4">পরীক্ষার ফলাফল</h3>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-12 text-center">
                <div class="card shadow-sm border-success p-3">
                    <h5>স্কোর: {{ $correct }} এর মধ্যে {{ $totalQuestions }}</h5>
                    <p>সঠিক: {{ $correct }} | ভুল: {{ $wrong }} | উত্তর দেয়া হয়নি: {{ $notAnswered }}</p>
                </div>
            </div>
        </div>

        @foreach ($results as $res)
            <div class="row mb-2">
                <div class="col-lg-12">
                    <div class="card shadow-sm 
                        @if ($res['status'] == 'correct') border-success 
                        @elseif($res['status'] == 'wrong') border-danger 
                        @else border-secondary @endif
                    ">
                        <div class="card-body">
                            {{-- প্রশ্ন --}}
                            <h6 class="fw-bold">{{ $res['question'] }}</h6>

                            {{-- স্ট্যাটাস ব্যাজ --}}
                            <span class="badge 
                                @if ($res['status'] == 'correct') bg-success 
                                @elseif($res['status'] == 'wrong') bg-danger 
                                @else bg-secondary @endif
                            ">
                                @if ($res['status'] == 'correct')
                                    ✅ সঠিক উত্তর
                                @elseif($res['status'] == 'wrong')
                                    ❌ ভুল উত্তর
                                @else
                                    ⚠️ উত্তর দেয়া হয়নি
                                @endif
                            </span>

                            {{-- সব অপশন দেখানো --}}
                            <div class="mt-3">
                                @foreach ($res['options'] as $opt)
                                    @php
                                        $class = 'bg-light text-dark';
                                        if ($opt['id'] == $res['user_answer_id'] && $res['status'] == 'wrong') {
                                            $class = 'bg-danger text-white';
                                        } elseif ($opt['id'] == $res['user_answer_id'] && $res['status'] == 'correct') {
                                            $class = 'bg-success text-white';
                                        } elseif ($opt['id'] == $res['correct_answer_id']) {
                                            $class = 'bg-success text-white';
                                        }
                                    @endphp

                                    <p class="p-2 rounded mb-1 {{ $class }}">
                                        {{ $opt['answer'] }}
                                        @if ($opt['id'] == $res['user_answer_id'])
                                            <span class="badge bg-dark">আপনার উত্তর</span>
                                        @endif
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Footer buttons, exclude from print --}}
    <div class="row mt-4 no-print">
        <div class="col-lg-6 text-center mb-2">
            <a href="{{ route('online.quiz') }}" class="btn btn-primary">পরীক্ষায় ফিরে যান</a>
        </div>
        <div class="col-lg-6 text-center mb-2">
            <button class="btn btn-success" onclick="printResult()">Print Result</button>
        </div>
    </div>
       <script>
        function printResult() {
            window.print();
        }
    </script>
@endsection
