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
            <h4 class="fw-bold mt-4 mb-3">Subjects & Topics</h4>
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
                    <tbody>
                        @forelse($program->topics as $topic)
                            <tr>
                                <td>{{ $topic->subject->name ?? 'N/A' }}</td>
                                <td>{{ $topic->topic_name }}</td>
                                <td>{{ $topic->total_mcq }}</td>
                                <td>{{ $topic->time }}</td>
                                <td>{{ $topic->exam_fee }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No topics available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Back Button -->
            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
                ← Back to Program List
            </a>
        </div>
    </div>
</section>
@endsection
