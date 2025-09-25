@extends('layouts.frontend.app', [$pageTitle => ''])
@section('content')
<section class="py-5">
    <div class="container">
        <div class="card shadow border-0 p-4">
            <!-- Program Name -->
            <h2 class="fw-bold mb-3">{{ $program->name }}</h2>

            <!-- Program Photo -->
            @if($program->image)
                <img src="{{ $program->image ? url('upload/program/' . $program->image) : url('upload/no_image.jpg') }}" 
                     alt="{{ $program->name }}" 
                     class="img-fluid rounded mb-4">
            @endif

            <!-- Program Description -->
            <div class="mb-4 description-html">
                {!! $program->description !!}
            </div>

           

<h4 class="fw-bold mt-4 mb-3">Exam Plan</h4>

<!-- Subject Tabs -->
<ul class="nav nav-tabs mb-3" id="subjectTabs" role="tablist">
    @foreach($program->subjects as $index => $subject)
        <li class="nav-item" role="presentation">
            <button class="nav-link @if($index === 0) active @endif" 
                id="subject-{{ $subject->id }}-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#subject-{{ $subject->id }}" 
                type="button" role="tab">
                {{ $subject->name }}
            </button>
        </li>
    @endforeach
</ul>

<!-- Tab Content -->
<div class="tab-content" id="subjectTabsContent">
    @foreach($program->subjects as $index => $subject)
        <div class="tab-pane fade @if($index === 0) show active @endif" 
             id="subject-{{ $subject->id }}" 
             role="tabpanel">

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Topic Name</th>
                            <th>Total MCQ</th>
                            <th>Time (mins)</th>
                            <th>Exam Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subject->topics as $topic)
                            <tr>
                                <td>{{ $topic->topic_name }}</td>
                                <td>{{ $topic->total_mcq }}</td>
                                <td>{{ $topic->time }}</td>
                                <td>{{ $topic->exam_fee }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    No topics available for {{ $subject->name }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    @endforeach
</div>
s


            <!-- Back Button -->
            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
                ← Back to Program List
            </a>
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".subject-btn");
        const rows = document.querySelectorAll("#topicTableBody tr");
        const noMsg = document.getElementById("noSubjectMsg");

        buttons.forEach(btn => {
            btn.addEventListener("click", function () {
                const subjectId = this.getAttribute("data-subject");

                // Hide all rows
                rows.forEach(row => row.style.display = "none");

                // Show only selected subject rows
                let found = false;
                rows.forEach(row => {
                    if (row.getAttribute("data-subject") === subjectId) {
                        row.style.display = "";
                        found = true;
                    }
                });

                noMsg.style.display = found ? "none" : "block";
            });
        });
    });
</script>

@endsection
