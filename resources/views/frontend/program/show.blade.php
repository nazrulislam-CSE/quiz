@extends('layouts.frontend.app', [$pageTitle => ''])
@section('content')
@php
        $colors1 = [
            '#f2f2f2',  // Light Gray
            '#e6f7ff',  // Light Blue
            '#fff0f5',  // Lavender Blush
            '#fef9e7',  // Light Yellow
            '#e8f5e9',  // Light Green
            '#f3e5f5',  // Light Purple
            '#fff3e0',  // Light Orange
            '#f1f8e9',  // Light Lime
            '#e0f7fa',  // Light Cyan
            '#ede7f6',  // Light Indigo
            '#fce4ec',  // Light Pink
            '#f9fbe7',  // Light Lime-Yellow
            '#fbe9e7',  // Light Coral
            '#e0f2f1',  // Aqua Light
            '#f3f3f3',  // Extra Light Gray
        ];
    @endphp
<section class="py-5">
    <div class="container">
        <div class="card shadow border-0 p-4">
           

            <!-- Program Photo -->
            @if($program->image)
                <img src="{{ $program->image ? url('upload/program/' . $program->image) : url('upload/no_image.jpg') }}" 
                     alt="{{ $program->name }}" 
                     class="img-fluid rounded mb-4">
            @endif

             <!-- Program Name -->
            <h2 class="fw-bold mb-3">{{ $program->name }}</h2>

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
        <button 
            class="btn btn-{{ $color }} me-2 mb-2 subject-btn @if($index === 0) active @endif" 
            data-subject="{{ $subject->id }}">
            {{ $subject->name }}
        </button>
    @endforeach
</div>

<!-- Topics Table -->
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover text-center align-middle shadow-sm rounded">
        <thead class="table-light">
            <tr>
                {{-- <th>Subject</th> --}}
                <th>Topic Name</th>
                <th>Total MCQ</th>
                <th>Time (mins)</th>
                <th>Exam Fee</th>
            </tr>
        </thead>
        <tbody id="topicTableBody">
            @foreach($program->topics as $topic)
                @php
                    shuffle($colors1);
                @endphp
                <tr 
                    data-subject="{{ $topic->program_subject_id }}" 
                    style="display: {{ $loop->first ? 'table-row' : 'none' }};">
                    {{-- <td>{{ $topic->subject->name ?? 'N/A' }}</td> --}}
                    <td  style="background-color: {{ $colors1[0] }}">{{ $topic->topic_name }}</td>
                    <td  style="background-color: {{ $colors1[1] }}">{{ $topic->total_mcq }}</td>
                    <td  style="background-color: {{ $colors1[2] }}">{{ $topic->time }}</td>
                    <td  style="background-color: {{ $colors1[3] }}">{{ $topic->exam_fee }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>




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
<!-- Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".subject-btn");
        const rows = document.querySelectorAll("#topicTableBody tr");

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                const subjectId = this.dataset.subject;

                // remove active class from all buttons
                buttons.forEach(btn => btn.classList.remove("active"));

                // add active class to clicked button
                this.classList.add("active");

                // show only selected subject's rows
                rows.forEach(row => {
                    if (row.dataset.subject === subjectId) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    });
</script>

@endsection
