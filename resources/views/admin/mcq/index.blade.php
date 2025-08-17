@extends('layouts.admin.app', [$pageTitle => 'Page Title'])

@section('content')
<div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        {{-- <h4 class="content-title mb-2">Hi, welcome back!</h4> --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            </ol>
        </nav>
    </div>
    <div class="d-flex my-auto">
        {{-- <div class=" d-flex right-page">
            <div class="d-flex justify-content-center me-5">
                <div class="">
                    <span class="d-block">
                        <span class="label ">EXPENSES</span>
                    </span>
                    <span class="value">
                        $53,000
                    </span>
                </div>
                <div class="ms-3 mt-2">
                    <span class="sparkline_bar"></span>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="">
                    <span class="d-block">
                        <span class="label">PROFIT</span>
                    </span>
                    <span class="value">
                        $34,000
                    </span>
                </div>
                <div class="ms-3 mt-2">
                    <span class="sparkline_bar31"></span>
                </div>
            </div>
        </div> --}}
    </div>
</div>
    <div class="main-content-body">
        <!-- Row -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}} <span class="badge bg-danger side-badge" style="font-size:17px;">{{ count($mcqs) }}</span> </p>

                            <div class="d-flex">
                                <a href="{{ route('admin.mcq.create')}}" class="btn btn-success me-2">
                                    <i class="fas fa-plus d-inline"></i> Add Now MCQ
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file-datatable" class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">SL</th>
                                            <th class="border-bottom-0">Question</th>
                                            <th class="border-bottom-0">Answer</th>
                                            <th class="border-bottom-0">Dependency</th>
                                            <th class="border-bottom-0">Created At</th>
                                            <th class="border-bottom-0">Status</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mcqs as $key=> $mcq)
                                        <tr>
                                            <td class="col-1">{{ $key+1 }}</td>
                                            <td class="col-3">
                                                <div class="text-wrap">
                                                    Question: {{ $mcq->question }}
                                                    Correct Answer:
                                                    @foreach ($mcq->answers as $answer)
                                                        @if ($answer->is_correct)
                                                            <strong>{{ $answer->answer }}</strong>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="col-2">
                                                @foreach ($mcq->answers as $answer)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="answer_{{ $mcq->id }}" id="answer_{{ $mcq->id }}_{{ $loop->index }}" value="{{ $answer->id }}" {{ $answer->is_correct ? 'checked' : '' }} disabled>
                                                        <label class="form-check-label" for="answer_{{ $mcq->id }}_{{ $loop->index }}">
                                                            {{ $answer->answer }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td class="col-2">
                                                <div class="text-wrap">
                                                    Admission: {{ $mcq->admission->name ?? '' }} 
                                                    <br>
                                                    Department: {{ $mcq->department->name ?? '' }}
                                                    <br>
                                                    Subject: {{ $mcq->subject->name ?? '' }}
                                                    <br>
                                                    Topic: {{ $mcq->topic->name ?? '' }}
                                                </div>
                                            </td>
                                            <td class="col-2">{{ $mcq->created_at->format('d M Y') }}</td>
                                           
                                            <td>
                                                <a href="{{ route('admin.mcq.show',$mcq->id)}}" class="btn btn-success btn-sm mr-2"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.mcq.edit',$mcq->id)}}" class="btn btn-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.mcq.delete',$mcq->id)}}" class="btn btn-danger btn-sm" title="Delete Data" id="delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->
    </div>
@endsection