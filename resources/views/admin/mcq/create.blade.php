@extends('layouts.admin.app', [$pageTitle ?? 'MCQ' => 'Create MCQ'])
@section('content')
    <div class="breadcrumb-header justify-content-between">
        <div class="d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex my-auto"></div>
    </div>

    <div class="main-content-body">
        <div class="row row-sm">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <p class="card-title my-0">{{ $pageTitle ?? 'Create MCQ' }}</p>
                    <div class="d-flex">
                        <a href="{{ route('admin.mcq.index') }}" class="btn btn-danger me-2">
                            <i class="fas fa-list d-inline"></i> MCQ List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.mcq.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="title">Title: <span class="text-danger">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" id="title"
                                        class="form-control" placeholder="Enter Title">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="exam_datetime">Exam Date & Time: <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="exam_datetime" id="exam_datetime" class="form-control"
                                    value="{{ old('exam_datetime') }}">
                                @error('exam_datetime')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="name">Exam Duration/Time (In Minutes): <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min="0" name="exam_duration"
                                        value="{{ old('exam_duration') }}" id="exam_duration" class="form-control"
                                        placeholder="Ex:10 minutes">
                                    @error('exam_duration')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label for="exam_mark">Exam Mark: <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="exam_mark" value="{{ old('exam_mark') }}"
                                        id="exam_mark" class="form-control" placeholder="Ex:25">
                                    @error('exam_mark')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- Number of Questions --}}
                            <div class="form-group col-xl-4 col-lg-4 col-md-4">
                                <label for="total_questions">How many questions?</label>
                                <input type="number" id="total_questions" class="form-control" min="1"
                                    max="50" value="{{ old('total_questions') }}"
                                    placeholder="Enter number of questions" required>
                            </div>

                            {{-- Questions will be generated here --}}
                            <div id="questions-wrapper" class="col-12 mt-3">
                                @if (old('questions'))
                                    @foreach (old('questions') as $qIndex => $qData)
                                        <div class="card mb-4 p-3 question-block">
                                            <h5>Question {{ $qIndex + 1 }}</h5>
                                            <div class="form-group mb-2">
                                                <label>Question:</label>
                                                <textarea name="questions[{{ $qIndex }}][text]" class="form-control" placeholder="Enter question" required>{{ $qData['text'] }}</textarea>
                                                @error("questions.$qIndex.text")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                @foreach ([0, 1, 2, 3] as $i)
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-2 option-item">
                                                            <input type="text"
                                                                name="questions[{{ $qIndex }}][answers][{{ $i }}][answer]"
                                                                class="form-control"
                                                                placeholder="Option {{ $i + 1 }}"
                                                                value="{{ $qData['answers'][$i]['answer'] ?? '' }}"
                                                                required>
                                                            <div class="input-group-text">
                                                                <input type="radio"
                                                                    name="questions[{{ $qIndex }}][correct_answer]"
                                                                    value="{{ $i }}"
                                                                    {{ old("questions.$qIndex.correct_answer") == $i ? 'checked' : '' }}
                                                                    style="cursor: pointer; margin-right:5px;"> Correct
                                                            </div>
                                                        </div>
                                                        @error("questions.$qIndex.answers.$i.answer")
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="col-xl-12 col-lg-6 col-md-6 col-sm-12 mt-3">
                                <button type="submit" class="add-to-cart btn btn-success btn-block">
                                    <i class="fas fa-plus"></i> Add MCQ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('admin')
    <script>
        const oldQuestions = @json(old('questions', []));
    </script>

    {{-- JavaScript --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mcqType = document.getElementById("mcq_type");

            function toggleFields(type) {
                document.querySelectorAll(
                        '#admission_box, #department_box, #group_box, #subject_box, #topic_box, #paper_final_box, #model_box'
                    )
                    .forEach(el => el.classList.add("d-none"));
                if (type === "1") { // MCQ Topic Wise
                    admission_box.classList.remove("d-none");
                    department_box.classList.remove("d-none");
                    subject_box.classList.remove("d-none");
                    topic_box.classList.remove("d-none");
                } else if (type === "2") { // MCQ Study Question Topic Wise
                    admission_box.classList.remove("d-none");
                    department_box.classList.remove("d-none");
                    subject_box.classList.remove("d-none");
                    topic_box.classList.remove("d-none");
                    paper_final_box.classList.remove("d-none");
                } else if (type === "3") { // MCQ Paper Final Exam
                    admission_box.classList.remove("d-none");
                    department_box.classList.remove("d-none");
                    group_box.classList.remove("d-none");
                    subject_box.classList.remove("d-none");
                    paper_final_box.classList.remove("d-none");
                } else if (type === "4") { // MCQ Final Model Test
                    admission_box.classList.remove("d-none");
                    department_box.classList.remove("d-none");
                    group_box.classList.remove("d-none");
                }
            }

            // load time old value handle
            toggleFields(mcqType.value);

            // onchange event
            mcqType.addEventListener("change", function() {
                toggleFields(this.value);
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const wrapper = document.getElementById('questions-wrapper');
            const totalInput = document.getElementById('total_questions');

            function generateQuestions(total) {
                wrapper.innerHTML = "";
                for (let q = 0; q < total; q++) {
                    const question = oldQuestions[q] || {};
                    const text = question.text || '';
                    const answers = question.answers || [];
                    const correct = question.correct_answer;

                    let html = `
                    <div class="card mb-4 p-3 question-block">
                        <h5>Question ${q + 1}</h5>
                        <div class="form-group mb-2">
                            <label>Question:</label>
                            <textarea name="questions[${q}][text]" class="form-control" placeholder="Enter question" required>${text}</textarea>
                            @error('questions.${q}.text')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            ${[0, 1, 2, 3].map(i => {
                                const ans = answers[i]?.answer || '';
                                const checked = correct == i ? 'checked' : '';
                                return `
                                        <div class="col-md-6">
                                            <div class="input-group mb-2 option-item">
                                                <input type="text" name="questions[${q}][answers][${i}][answer]"
                                                    class="form-control" placeholder="Option ${i + 1}"
                                                    value="${ans}" required>
                                                <div class="input-group-text">
                                                    <input type="radio" name="questions[${q}][correct_answer]" value="${i}" ${checked}>
                                                    Correct
                                                </div>
                                            </div>
                                            @error('questions.${q}.answers.${i}.answer')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    `;
                            }).join('')}
                        </div>
                    </div>
                `;
                    wrapper.insertAdjacentHTML('beforeend', html);
                }
            }

            totalInput.addEventListener('input', function() {
                const total = parseInt(this.value) || 0;
                generateQuestions(total);
            });

            // On page load, if old questions exist, regenerate
            if (oldQuestions.length > 0) {
                totalInput.value = oldQuestions.length;
                generateQuestions(oldQuestions.length);
            }
        });
    </script>
@endpush
