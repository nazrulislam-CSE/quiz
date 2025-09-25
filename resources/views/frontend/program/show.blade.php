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

            <!-- Subject-wise Topics -->
           <h4 class="fw-bold mt-4 mb-3">Exam Plan</h4>
@php
    $colors = ['primary','success','warning','danger','info','secondary','dark'];
@endphp
<!-- Subject Buttons -->
<div class="mb-3">
     @foreach($program->subjects as $index => $subject)
        @php
            $color = $colors[$index % count($colors)];
        @endphp
        <button class="btn btn-{{ $color }} me-2 mb-2 subject-btn" data-subject="{{ $subject->id }}">
            {{ $subject->name }}
        </button>
         @endforeach
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Subject</th>
                <th>Topic Name</th>
                <th>Total MCQ</th>
                <th>Time (mins)</th>
                <th>Exam Fee</th>
            </tr>
        </thead>
        <tbody id="topicTableBody">
            @foreach($program->topics as $topic)
                <tr data-subject="{{ $topic->subject_id }}" style="display:none;">
                    <td>{{ $topic->subject->name ?? 'N/A' }}</td>
                    <td>{{ $topic->topic_name }}</td>
                    <td>{{ $topic->total_mcq }}</td>
                    <td>{{ $topic->time }}</td>
                    <td>{{ $topic->exam_fee }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- যদি কোন subject select না থাকে -->
<p id="noSubjectMsg" class="text-center text-muted mt-3">
    Please select a subject to view topics.
</p>


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

                // Show/hide "no data" message
                noMsg.style.display = found ? "none" : "block";
            });
        });
    });
</script>

@endsection
