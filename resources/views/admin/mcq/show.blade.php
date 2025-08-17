@extends('layouts.admin.app', [$pageTitle => 'Page Title'])

@section('content')
 <!-- Content Header (Page header) -->
 <div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        {{-- <h4 class="content-title mb-2">Hi, welcome back!</h4> --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Page Title' }}</li>
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

 <!-- Main content -->
 <div class="card card-primary card-outline shadow-lg mb-4">
    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
       <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}}</p>
       <div class="d-flex">
           <a href="{{ route('admin.mcq.index')}}" class="btn btn-danger me-2">
               <i class="fas fa-list d-inline"></i> MCQ  List
           </a>
       </div>
   </div>
    <div class="card-body">
       <div class="table-responsive">
          <table class="table table-bordered">
             <tr>
                <td>Admission</td>
                <td>{{ $mcq->admission->name ?? '' }}</td>
             </tr>
                <tr>
                    <td>Department</td>
                    <td>{{ $mcq->department->name ?? '' }}</td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td>{{ $mcq->subject->name ?? '' }}</td>
                </tr>
                <tr>
                    <td>Topic</td>
                    <td>{{ $mcq->topic->name ?? '' }}</td>
                </tr>
                <tr>
                    <td>Question</td>
                    <td>{{ $mcq->question }}</td>
                </tr>
                <tr>
                    <td>Answers</td>
                    <td>
                        @if($mcq->answers->count() > 0)
                            <ul class="list-group">
                                @foreach($mcq->answers as $answer)
                                    <li class="list-group-item">
                                        {{ $answer->answer }} 
                                        @if($answer->is_correct)
                                            <span class="badge bg-success">Correct</span>
                                        @else
                                            <span class="badge bg-secondary">Incorrect</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No answers available for this question.</p>
                        @endif
                    </td>
                </tr>
          </table>
       </div>
    </div>
 </div>
@endsection