@extends('layouts.user.app', ['pageTitle' => $pageTitle])
@section('content')
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/dashboard.css') }}">
<!-- MCQ Content -->
<div class="section active" id="selection-section">
    <div class="row mb-4 g-3">
        <div class="col-lg-12">
            <div class="exam-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-book-open me-2"></i>MCQ Exam Preparation</span>
                        <span class="badge bg-light text-primary" id="step-badge">Step 1 of 5</span>
                    </div>
                </div>

                @php
                    $currentStep = 1;
                    $totalSteps = 5;

                    // Determine current step based on selected values and admission type
                    if ($selectedAdmissionData) {
                        if ($selectedAdmissionData->name == 'ভার্সিটি এডমিশন') {
                            if ($selectedAdmission && !$selectedDepartment) {
                                $currentStep = 2;
                            } elseif ($selectedDepartment && !$selectedSubject) {
                                $currentStep = 3;
                            } elseif ($selectedSubject && !$selectedTopic) {
                                $currentStep = 4;
                            } elseif ($selectedTopic) {
                                $currentStep = 5;
                            }
                        } elseif ($selectedAdmissionData->name == 'পেপার ফাইনাল এক্সাম') {
                            if ($selectedAdmission && !$selectedDepartment) {
                                $currentStep = 2;
                            } elseif ($selectedDepartment && !$selectedGroup) {
                                $currentStep = 3;
                            } elseif ($selectedGroup && !$selectedSubject) {
                                $currentStep = 4;
                            } elseif ($selectedSubject && !$selectedPaperFinal) {
                                $currentStep = 4;
                            } elseif ($selectedPaperFinal) {
                                $currentStep = 5;
                            }
                        } elseif ($selectedAdmissionData->name == 'ফাইনাল মডেল টেস্ট এক্সাম') {
                            if ($selectedAdmission && !$selectedDepartment) {
                                $currentStep = 2;
                            } elseif ($selectedDepartment && !$selectedGroup) {
                                $currentStep = 3;
                            } elseif ($selectedGroup && !$selectedModelTest) {
                                $currentStep = 4;
                            } elseif ($selectedModelTest) {
                                $currentStep = 5;
                            }
                        }
                    }

                    // Map step to percentage
                    $progressPercent = ($currentStep / $totalSteps) * 100;
                @endphp

                <div class="progress-container mb-2">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar"
                            style="width: {{ $progressPercent }}%; padding: 0.25rem 0.5rem;">
                            {{ round($progressPercent) }}%
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Step Indicator -->
                    <div class="step-indicator mb-3">
                        @for($i = 1; $i <= $totalSteps; $i++)
                            <div class="step {{ $currentStep == $i ? 'active' : ($currentStep > $i ? 'completed' : '') }}"
                                id="step-{{ $i }}">{{ $i }}</div>
                        @endfor
                    </div>

                    <form action="{{ route('user.mcq.exam') }}" id="exam-form" method="GET">
                        <!-- Step 1: Admission Selection -->
                        @if (!$selectedAdmission)
                            <div class="step-content active" id="step-admission">
                                <h5 class="step-title">
                                    <i class="fas fa-university me-2"></i> Select Admission
                                </h5>
                                <div class="mb-4">
                                    @foreach ($admissions as $admission)
                                        <label for="admission{{ $admission->id }}"
                                            class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                            style="cursor: pointer;"
                                            onclick="document.getElementById('admission{{ $admission->id }}').checked = true; this.closest('form').submit();">

                                            <img src="{{ !empty($admission->image) ? url('upload/admission/' . $admission->image) : url('upload/mcq.png') }}"
                                                alt="ICON" class="me-2"
                                                style="width:40px;height:40px;object-fit:cover;">

                                            <input type="radio" name="admission" id="admission{{ $admission->id }}"
                                                value="{{ $admission->id }}" class="d-none">

                                            <span>{{ $admission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Step 2: Department Selection (Common for all admission types) -->
                        @if ($selectedAdmission && !$selectedDepartment)
                            <div class="step-content active" id="step-department">
                                <h5 class="step-title"><i class="fas fa-building me-2"></i>Select Department</h5>
                                <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                <div class="mb-4">
                                    @foreach ($departments as $department)
                                        <label for="department{{ $department->id }}"
                                            class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                            style="cursor: pointer;"
                                            onclick="document.getElementById('department{{ $department->id }}').checked = true; this.closest('form').submit();">
                                            <img src="{{ !empty($department->image) ? url('upload/department/' . $department->image) : url('upload/mcq.png') }}"
                                                alt="ICON" style="width:40px;height:40px;object-fit:cover;">
                                            <input type="radio" name="department"
                                                id="department{{ $department->id }}" value="{{ $department->id }}"
                                                class="d-none">
                                            <span>{{ $department->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Step 3: Different workflows based on admission type -->
                        @if ($selectedAdmission && $selectedDepartment)
                            <!-- ভার্সিটি এডমিশন workflow -->
                            @if ($selectedAdmissionData && $selectedAdmissionData->name == 'ভার্সিটি এডমিশন')
                                @if (!$selectedSubject)
                                    <div class="step-content active" id="step-subject">
                                        <h5 class="step-title"><i class="fas fa-book me-2"></i>Select Subject</h5>
                                        <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                        <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                        <div class="mb-4">
                                            @foreach ($subjects as $subject)
                                                <label for="subject{{ $subject->id }}"
                                                    class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                    style="cursor: pointer;"
                                                    onclick="document.getElementById('subject{{ $subject->id }}').checked = true; this.closest('form').submit();">
                                                    <img src="{{ !empty($subject->image) ? url('upload/subject/' . $subject->image) : url('upload/mcq.png') }}"
                                                        alt="ICON" style="width:40px;height:40px;object-fit:cover;">
                                                    <input type="radio" name="subject" id="subject{{ $subject->id }}"
                                                        value="{{ $subject->id }}" class="d-none">
                                                    <span>{{ $subject->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($selectedSubject && !$selectedTopic)
                                    <div class="step-content active" id="step-topic">
                                        <h5 class="step-title"><i class="fas fa-tag me-2"></i>Select Topic</h5>
                                        <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                        <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                        <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                                        <div class="mb-4">
                                            @foreach ($topics as $topic)
                                                <label for="topic{{ $topic->id }}"
                                                    class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                    style="cursor: pointer;"
                                                    onclick="document.getElementById('topic{{ $topic->id }}').checked = true; this.closest('form').submit();">
                                                    <img src="{{ !empty($topic->image) ? url('upload/topic/' . $topic->image) : url('upload/mcq.png') }}"
                                                        alt="ICON" style="width:40px;height:40px;object-fit:cover;">
                                                    <input type="radio" name="topic" id="topic{{ $topic->id }}"
                                                        value="{{ $topic->id }}" class="d-none">
                                                    <span>{{ $topic->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- পেপার ফাইনাল এক্সাম workflow - UPDATED -->
                            @if ($selectedAdmissionData && $selectedAdmissionData->name == 'পেপার ফাইনাল এক্সাম')
                                @if (!$selectedGroup)
                                    <div class="step-content active" id="step-group">
                                        <h5 class="step-title"><i class="fas fa-users me-2"></i>Select Group</h5>
                                        <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                        <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                        <div class="mb-4">
                                            @foreach ($groups as $group)
                                                <label for="group{{ $group->id }}"
                                                    class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                    style="cursor: pointer;"
                                                    onclick="document.getElementById('group{{ $group->id }}').checked = true; this.closest('form').submit();">
                                                    <img src="{{ !empty($group->image) ? url('upload/group/' . $group->image) : url('upload/mcq.png') }}"
                                                        alt="ICON" style="width:40px;height:40px;object-fit:cover;">
                                                    <input type="radio" name="group" id="group{{ $group->id }}"
                                                        value="{{ $group->id }}" class="d-none">
                                                    <span>{{ $group->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($selectedGroup && !$selectedSubject)
                                    <div class="step-content active" id="step-subject">
                                        <h5 class="step-title"><i class="fas fa-book me-2"></i>Select Subject</h5>
                                        <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                        <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                        <input type="hidden" name="group" value="{{ $selectedGroup }}">
                                        <div class="mb-4">
                                            @foreach ($subjects as $subject)
                                                <label for="subject{{ $subject->id }}"
                                                    class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                    style="cursor: pointer;"
                                                    onclick="document.getElementById('subject{{ $subject->id }}').checked = true; this.closest('form').submit();">
                                                    <img src="{{ !empty($subject->image) ? url('upload/subject/' . $subject->image) : url('upload/mcq.png') }}"
                                                        alt="ICON" style="width:40px;height:40px;object-fit:cover;">
                                                    <input type="radio" name="subject" id="subject{{ $subject->id }}"
                                                        value="{{ $subject->id }}" class="d-none">
                                                    <span>{{ $subject->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($selectedSubject && !$selectedPaperFinal)
                                    <div class="step-content active" id="step-paper-final">
                                        <h5 class="step-title"><i class="fas fa-file-alt me-2"></i>Select Paper Final</h5>
                                        <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                        <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                        <input type="hidden" name="group" value="{{ $selectedGroup }}">
                                        <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                                        <div class="mb-4">
                                            @foreach ($paperFinals as $paperFinal)
                                                <label for="paper_final{{ $paperFinal->id }}"
                                                    class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                    style="cursor: pointer;"
                                                    onclick="document.getElementById('paper_final{{ $paperFinal->id }}').checked = true; this.closest('form').submit();">
                                                    <img src="{{ !empty($paperFinal->image) ? url('upload/paper_final/' . $paperFinal->image) : url('upload/mcq.png') }}"
                                                        alt="ICON" style="width:40px;height:40px;object-fit:cover;">
                                                    <input type="radio" name="paper_final" id="paper_final{{ $paperFinal->id }}"
                                                        value="{{ $paperFinal->id }}" class="d-none">
                                                    <span>{{ $paperFinal->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif

                            <!-- ফাইনাল মডেল টেস্ট এক্সাম workflow -->
                            @if ($selectedAdmissionData && $selectedAdmissionData->name == 'ফাইনাল মডেল টেস্ট এক্সাম')
                                @if (!$selectedGroup)
                                    <div class="step-content active" id="step-group">
                                        <h5 class="step-title"><i class="fas fa-users me-2"></i>Select Group</h5>
                                        <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                        <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                        <div class="mb-4">
                                            @foreach ($groups as $group)
                                                <label for="group{{ $group->id }}"
                                                    class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                    style="cursor: pointer;"
                                                    onclick="document.getElementById('group{{ $group->id }}').checked = true; this.closest('form').submit();">
                                                    <img src="{{ !empty($group->image) ? url('upload/group/' . $group->image) : url('upload/mcq.png') }}"
                                                        alt="ICON" style="width:40px;height:40px;object-fit:cover;">
                                                    <input type="radio" name="group" id="group{{ $group->id }}"
                                                        value="{{ $group->id }}" class="d-none">
                                                    <span>{{ $group->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($selectedGroup && !$selectedModelTest)
                                    <div class="step-content active" id="step-model-test">
                                        <h5 class="step-title"><i class="fas fa-vial me-2"></i>Select Model Test</h5>
                                        <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                        <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                        <input type="hidden" name="group" value="{{ $selectedGroup }}">
                                        <div class="mb-4">
                                            @foreach ($modelTests as $modelTest)
                                                <label for="model_test{{ $modelTest->id }}"
                                                    class="selection-item d-flex align-items-center p-2 border rounded mb-2"
                                                    style="cursor: pointer;"
                                                    onclick="document.getElementById('model_test{{ $modelTest->id }}').checked = true; this.closest('form').submit();">
                                                    <img src="{{ !empty($modelTest->image) ? url('upload/model_test/' . $modelTest->image) : url('upload/mcq.png') }}"
                                                        alt="ICON" style="width:40px;height:40px;object-fit:cover;">
                                                    <input type="radio" name="model_test" id="model_test{{ $modelTest->id }}"
                                                        value="{{ $modelTest->id }}" class="d-none">
                                                    <span>{{ $modelTest->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif

                        <!-- Final Step: Exam/Study Options -->
                        @if (($selectedTopic && $selectedAdmissionData && $selectedAdmissionData->name == 'ভার্সিটি এডমিশন') || 
                             ($selectedPaperFinal && $selectedAdmissionData && $selectedAdmissionData->name == 'পেপার ফাইনাল এক্সাম') || 
                             ($selectedModelTest && $selectedAdmissionData && $selectedAdmissionData->name == 'ফাইনাল মডেল টেস্ট এক্সাম'))
                            
                            @php
                                $selectedItem = null;
                                $itemType = '';
                                $itemName = '';
                                
                                if ($selectedAdmissionData->name == 'ভার্সিটি এডমিশন') {
                                    $selectedItem = $topics->where('id', $selectedTopic)->first();
                                    $itemType = 'topic';
                                    $itemName = 'টপিক';
                                } elseif ($selectedAdmissionData->name == 'পেপার ফাইনাল এক্সাম') {
                                    $selectedItem = $paperFinals->where('id', $selectedPaperFinal)->first();
                                    $itemType = 'paper_final';
                                    $itemName = 'পেপার ফাইনাল';
                                } elseif ($selectedAdmissionData->name == 'ফাইনাল মডেল টেস্ট এক্সাম') {
                                    $selectedItem = $modelTests->where('id', $selectedModelTest)->first();
                                    $itemType = 'model_test';
                                    $itemName = 'মডেল টেস্ট';
                                }
                            @endphp

                            @if ($selectedTopic && $mcqs->isEmpty())
                                <div class="card border-success shadow-sm mb-4">
                                    <div class="card-header bg-success text-white fw-bold">
                                        <i class="fa fa-info-circle me-2"></i> পরীক্ষার নোটিশ
                                    </div>
                                    <div class="card-body">
                                        <!-- পরীক্ষার বিষয়সমূহ -->
                                        <div class="d-flex align-items-start mb-3">
                                            <i class="fa fa-book text-success fs-4 me-3"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">পরীক্ষার বিষয়সমূহ</h6>
                                                <p class="mb-0 small">
                                                    নিচের তালিকা থেকে আপনার পছন্দের {{ $itemName }} নির্বাচন করুন এবং পরীক্ষা শুরু করুন।
                                                </p>
                                            </div>
                                        </div>

                                        <!-- পরীক্ষার সময় -->
                                        <div class="d-flex align-items-start mb-3">
                                            <i class="fa fa-clock text-success fs-4 me-3"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">পরীক্ষার সময়</h6>
                                                <p class="mb-0 small">
                                                    প্রতিটি {{ $itemName }} এর জন্য আলাদা পরীক্ষার সময় নির্ধারিত আছে যা {{ $itemName }} এর পাশে দেখানো হয়েছে।
                                                    নির্দিষ্ট সময়ের মধ্যে আপনাকে পরীক্ষা সম্পন্ন করতে হবে।
                                                </p>
                                            </div>
                                        </div>

                                        <!-- পরীক্ষার মার্ক -->
                                        <div class="d-flex align-items-start mb-3">
                                            <i class="fa fa-star text-success fs-4 me-3"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">পরীক্ষার মার্ক</h6>
                                                <p class="mb-0 small">
                                                    প্রতিটি পরীক্ষার জন্য সর্বোচ্চ মার্ক নির্ধারিত আছে যা {{ $itemName }} এর পাশে দেখানো হয়েছে।
                                                    পরীক্ষার ফলাফল এই মার্ক অনুযায়ী নির্ধারিত হবে।
                                                </p>
                                            </div>
                                        </div>

                                        <!-- পরীক্ষার ফি -->
                                        <div class="d-flex align-items-start mb-3">
                                            <i class="fa fa-money-bill text-success fs-4 me-3"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">পরীক্ষার ফি</h6>
                                                <p class="mb-0 small text-danger">
                                                    এখনই বাটনে ক্লিক করলে পরীক্ষা শুরু হবে এবং আপনার ওয়ালেট থেকে স্বয়ংক্রিয়ভাবে
                                                    পরীক্ষার ফি কেটে নেয়া হবে।
                                                </p>
                                                <p class="mb-0 small">
                                                    প্রতিটি {{ $itemName }} এর জন্য আলাদা ফি নির্ধারিত আছে যা {{ $itemName }} এর পাশে দেখানো হয়েছে।
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Warning -->
                                        <div class="alert alert-warning mt-3 small mb-0">
                                            দয়া করে নিশ্চিত হয়ে নিন যে আপনার ওয়ালেটে পর্যাপ্ত ব্যালেন্স আছে।
                                            অন্যথায় পরীক্ষা শুরু করা সম্ভব হবে না।
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected Item Card -->
                                <div class="card shadow-lg border-success mb-4">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-success mb-3 text-center">সিলেক্টেড {{ $itemName }} → MCQ শুরু</h6>
                                        <div class="d-flex align-items-center border rounded p-3">
                                            @if($selectedItem)
                                                <img src="{{ !empty($selectedItem->image) ? url('upload/' . $itemType . '/' . $selectedItem->image) : url('upload/mcq.png') }}"
                                                    class="rounded-circle me-3" width="60" alt="{{ $itemName }} icon">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $selectedItem->name }}</h6>
                                                    <small class="d-block">সময়: {{ $selectedItem->exam_duration ?? 'N/A' }} মিনিট</small>
                                                    <small class="d-block">মার্ক: {{ $selectedItem->exam_mark ?? 'N/A' }}</small>
                                                    <small class="d-block">পরীক্ষার ফি: {{ number_format($selectedItem->fee, 2) }} টাকা</small>
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @php
                                                        $studyParams = [
                                                            'admission' => $selectedAdmission,
                                                            'department' => $selectedDepartment,
                                                            'study' => 1
                                                        ];
                                                        
                                                        $examParams = [
                                                            'admission' => $selectedAdmission,
                                                            'department' => $selectedDepartment,
                                                            'exam' => 1
                                                        ];
                                                        
                                                        if ($selectedAdmissionData->name == 'ভার্সিটি এডমিশন') {
                                                            $studyParams['subject'] = $selectedSubject;
                                                            $studyParams['topic'] = $selectedTopic;
                                                            $examParams['subject'] = $selectedSubject;
                                                            $examParams['topic'] = $selectedTopic;
                                                        } elseif ($selectedAdmissionData->name == 'পেপার ফাইনাল এক্সাম') {
                                                            $studyParams['group'] = $selectedGroup;
                                                            $studyParams['subject'] = $selectedSubject;
                                                            $studyParams['paper_final'] = $selectedPaperFinal;
                                                            $examParams['group'] = $selectedGroup;
                                                            $examParams['subject'] = $selectedSubject;
                                                            $examParams['paper_final'] = $selectedPaperFinal;
                                                        } elseif ($selectedAdmissionData->name == 'ফাইনাল মডেল টেস্ট এক্সাম') {
                                                            $studyParams['group'] = $selectedGroup;
                                                            $studyParams['model_test'] = $selectedModelTest;
                                                            $examParams['group'] = $selectedGroup;
                                                            $examParams['model_test'] = $selectedModelTest;
                                                        }
                                                    @endphp

                                                    <a href="{{ route('user.mcq.exam', $studyParams) }}" class="btn btn-danger btn-sm flex-fill text-center">📖 স্টাডি</a>
                                                    <a href="{{ route('user.mcq.exam', $examParams) }}" class="btn btn-success btn-sm flex-fill text-center">📝 এক্সাম</a>
                                                </div>
                                            @else
                                                <h6 class="mb-1">কোনো {{ $itemName }} সিলেক্ট করা হয়নি</h6>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </form>

                    <!-- Exam and Study modes remain the same as your original code -->
                    @if ($examStart && $mcqs->isNotEmpty())
                        <!-- Exam mode code... -->
                        <!-- ✅ Exam Header with Countdown -->
                            <div class="card shadow-sm border-success mb-4">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-clock me-2"></i> পরীক্ষার সময় চলছে</span>
                                    <span id="exam-timer" class="fw-bold">00:00</span>
                                </div>
                            </div>

                            <!-- ✅ MCQ Form -->
                            <form action="{{ route('user.exam.submit') }}" method="POST" id="mcq-form">
                                @csrf
                                <input type="hidden" name="time_taken" id="time_taken">
                                <input type="hidden" name="admission" value="{{ $selectedAdmission }}">
                                <input type="hidden" name="department" value="{{ $selectedDepartment }}">
                                <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                                <input type="hidden" name="topic" value="{{ $selectedTopic }}">

                                @foreach ($mcqs as $index => $mcq)
                                    <div class="question-container" data-question="{{ $index + 1 }}" @if ($index != 0) style="display:none;" @endif>
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="question-number">{{ $index + 1 }}</div>
                                            <h5 class="m-0">{{ $mcq->question }}</h5>
                                        </div>
                                        <div class="options-container">
                                            @foreach ($mcq->answers as $answer)
                                                <div class="option-item p-2 rounded mb-2" style="cursor:pointer;"
                                                    data-correct="{{ $answer->is_correct ? '1' : '0' }}">
                                                    <div class="d-flex align-items-center">
                                                        <input class="form-check-input me-2" type="radio"
                                                            name="answers[{{ $mcq->id }}]"
                                                            id="option{{ $answer->id }}"
                                                            value="{{ $answer->id }}" required hidden>
                                                        <label class="form-check-label flex-grow-1 mb-0"
                                                            for="option{{ $answer->id }}">
                                                            {{ $answer->answer }}
                                                        </label>
                                                        <span class="feedback ms-2" style="display:none;"></span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Navigation Buttons -->
                                <div class="navigation-buttons mt-4">
                                    <button type="button" class="btn btn-outline-secondary" id="prev-btn" disabled>
                                        <i class="fas fa-arrow-left me-2"></i>Previous
                                    </button>
                                    <button type="button" class="btn btn-primary" id="next-btn">
                                        Next<i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                                        Submit Exam<i class="fas fa-check-circle ms-2"></i>
                                    </button>
                                </div>
                            </form>
                    @endif

                    @if ($studyMode && $mcqs->isNotEmpty())
                        <!-- Study mode code... -->
                         <div class="study-container">
                                @foreach($mcqs as $index => $mcq)
                                    <div class="card shadow-sm mb-4">
                                        
                                        {{-- Card Header with color and shadow --}}
                                        <div class="card-header bg-danger text-white shadow-sm">
                                            প্রশ্ন নং {{ $index + 1 }}
                                        </div>

                                        <div class="card-body">
                                            <p class="mb-3"><strong>প্রশ্ন:</strong> {{ $mcq->question }}</p>

                                            <div class="options mb-3">
                                                @foreach($mcq->answers->take(4) as $answer)
                                                    <p class="mb-1 {{ $answer->is_correct ? 'text-success fw-bold' : '' }}">
                                                        {{ chr(65 + $loop->index) }}. {{ $answer->answer }}
                                                    </p>
                                                @endforeach
                                            </div>

                                            {{-- সঠিক উত্তর দেখানো --}}
                                            @php
                                                $correctAnswer = $mcq->answers->firstWhere('is_correct', 1);
                                            @endphp
                                            @if($correctAnswer)
                                                <p class="mt-2 text-success fw-bold"><strong>সঠিক উত্তর:</strong> {{ $correctAnswer->answer }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'নোটিশ',
                text: '{{ session("error") }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    
    <script>
        let currentQuestion = 0;
        const questions = document.querySelectorAll('.question-container');
        const totalQuestions = questions.length;
        const progressBar = document.getElementById('question-progress');

        document.getElementById('next-btn').addEventListener('click', function() {
            if (currentQuestion < totalQuestions - 1) {
                questions[currentQuestion].style.display = 'none';
                currentQuestion++;
                questions[currentQuestion].style.display = 'block';
                updateProgress();
            }
            toggleButtons();
        });

        document.getElementById('prev-btn').addEventListener('click', function() {
            if (currentQuestion > 0) {
                questions[currentQuestion].style.display = 'none';
                currentQuestion--;
                questions[currentQuestion].style.display = 'block';
                updateProgress();
            }
            toggleButtons();
        });

        function updateProgress() {
            const percent = ((currentQuestion + 1) / totalQuestions) * 100;
            progressBar.style.width = percent + '%';
            document.getElementById('current-question').innerText = currentQuestion + 1;
        }

        function toggleButtons() {
            document.getElementById('prev-btn').disabled = currentQuestion === 0;
            document.getElementById('next-btn').style.display = (currentQuestion === totalQuestions - 1) ? 'none' :
                'inline-block';
            document.getElementById('submit-btn').style.display = (currentQuestion === totalQuestions - 1) ?
                'inline-block' : 'none';
        }
    </script>
    <script>
        document.querySelectorAll('.option-item').forEach(option => {
            option.addEventListener('click', function () {
                if (this.dataset.answered === "1") return;

                const isCorrect = this.dataset.correct === "1";
                const feedback = this.querySelector('.feedback');
                const input = this.querySelector('input[type="radio"]');
                const questionId = input.name.match(/\d+/)[0]; // gets MCQ id
                const answerId = input.value;

                // Store answer in localStorage
                let savedAnswers = JSON.parse(localStorage.getItem('mcq_answers')) || {};
                savedAnswers[questionId] = answerId;
                localStorage.setItem('mcq_answers', JSON.stringify(savedAnswers));

                // Feedback logic
                if (isCorrect) {
                    this.classList.add('bg-success', 'text-white');
                    feedback.innerHTML = '✔ Correct';
                } else {
                    this.classList.add('bg-danger', 'text-white');
                    feedback.innerHTML = '✖ Wrong';
                }
                feedback.style.display = 'inline';

                input.checked = true;
                this.dataset.answered = "1";

                // Disable other options
                const siblings = this.closest('.options-container').querySelectorAll('.option-item');
                siblings.forEach(sib => {
                    sib.dataset.answered = "1";
                    sib.style.pointerEvents = "none";
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let duration = {{ $selectedTopicData->exam_duration ?? 10 }} * 60; // total seconds
            let startTime = Date.now(); // exam start timestamp
            let timerDisplay = document.getElementById("exam-timer");

            function updateTimer() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;
                timerDisplay.textContent = `${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;

                if (duration <= 0) {
                    clearInterval(timerInterval);
                    alert("⏳ Time's up! Your exam will be submitted automatically.");
                    
                    // Calculate time taken in minutes
                    let endTime = Date.now();
                    let diffInSeconds = Math.floor((endTime - startTime) / 1000);
                    let diffInMinutes = (diffInSeconds / 60).toFixed(2); // fractional minutes
                    document.getElementById("time_taken").value = diffInMinutes;

                    document.getElementById("mcq-form").submit();
                }
                duration--;
            }

            let timerInterval = setInterval(updateTimer, 1000);

            document.getElementById("mcq-form").addEventListener("submit", function () {
                let endTime = Date.now();
                let diffInSeconds = Math.floor((endTime - startTime) / 1000);
                let diffInMinutes = (diffInSeconds / 60).toFixed(2); // fractional minutes
                document.getElementById("time_taken").value = diffInMinutes;
            });
        });
    </script>
@endsection
