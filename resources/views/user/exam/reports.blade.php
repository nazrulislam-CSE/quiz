@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')
<link rel="stylesheet" href="{{ asset('dashboard/auth/css/dashboard.css') }}">
<div class="container">
    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Exam Reports</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sl</th>
                            <th>Admission</th>
                            <th>Department</th>
                            <th>Group</th>
                            <th>Subject</th>
                            <th>Topic</th>
                            <th>Paper Final</th>
                            <th>Model Test</th>
                            <th>Fee</th>
                            <th>Total</th>
                            <th class="text-success">Correct</th>
                            <th class="text-danger">Wrong</th>
                            <th>Score (%)</th>
                            <th>Time Taken</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examResults as $index => $result)
                            @php
                                $examType = App\Models\Admission::find($result->admission->id);
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $result->admission->name ?? '' }}</td>
                                <td>{{ $result->department->name ?? '' }}</td>
                                <td>{{ $result->group->name ?? '' }}</td>
                                <td>{{ $result->subject->name ?? '' }}</td>
                                <td>{{ $result->topic->name ?? '' }}</td>
                                <td>{{ $result->paperFinal->name ?? '' }}</td>
                                <td>{{ $result->modelTest->name ?? '' }}</td>
                                <td>
                                    @if ($examType->name == 'ভার্সিটি এডমিশন')
                                        {{ number_format((float)($result->topic->fee ?? 0), 2) }} টাকা
                                    @elseif($examType->name == 'পেপার ফাইনাল এক্সাম')
                                        {{ number_format((float)($result->paperFinal->fee ?? 0), 2) }} টাকা
                                    @elseif($examType->name == 'ফাইনাল মডেল টেস্ট এক্সাম')
                                        {{ number_format((float)($result->modelTest->fee ?? 0), 2) }} টাকা
                                    @else
                                        {{ number_format((float)($result->topic->fee ?? 0), 2) }} টাকা
                                    @endif
                                </td>
                                <td>{{ $result->total }}</td>
                                <td class="text-success fw-bold">{{ $result->correct }}</td>
                                <td class="text-danger fw-bold">{{ $result->wrong }}</td>
                                <td class="fw-bold">{{ $result->score }}%</td>
                                @php
                                    $totalSeconds = round($result->time_taken * 60);
                                @endphp

                               <td>
                                    @if($totalSeconds < 60)
                                        {{ $totalSeconds }} সেকেন্ড
                                    @else
                                        {{ floor($totalSeconds / 60) }} মিনিট {{ $totalSeconds % 60 }} সেকেন্ড
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('user.exam.view', $result->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No exam reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
