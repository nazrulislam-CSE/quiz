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
                            <p class="card-title my-0">{{ $pageTitle ?? 'Page Title'}} <span class="badge bg-danger side-badge" style="font-size:17px;">{{ count($topics) }}</span> </p>

                            <div class="d-flex">
                                <a href="{{ route('admin.model.create')}}" class="btn btn-success me-2">
                                    <i class="fas fa-plus d-inline"></i> Add Now Final Model Test
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file-datatable" class="border-top-0  table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">SL</th>
                                            <th class="border-bottom-0">ICON</th>
                                            <th class="border-bottom-0">Name</th>
                                            <th class="border-bottom-0">Admission</th>
                                            <th class="border-bottom-0">Department</th>
                                            <th class="border-bottom-0">Subject</th>
                                            <th class="border-bottom-0">Exam Duration/Time</th>
                                            <th class="border-bottom-0">Exam Mark</th>
                                            <th class="border-bottom-0">Fee</th>
                                            <th class="border-bottom-0">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($models as $key=> $model)
                                        <tr>
                                            <td class="col-1">{{ $key+1 }}</td>
                                            <td>
                                                <img src="{{ (!empty($model->image)) ? url('upload/model/'.$model->image):url('upload/mcq.png') }}"  width="30" height="20" alt="No ICON" class="img-fluid">
                                            </td>
                                            <td>
                                                {{ $model->name ?? '' }}
                                            </td>
                                            <td>{{ $model->subject->admission->name ?? '' }}</td>
                                            <td>{{ $model->subject->department->name ?? '' }}</td>
                                            <td>{{ $model->subject->name ?? '' }}</td>
                                            <td>
                                                {{ $model->exam_duration ?? '' }}
                                            </td>
                                            <td>
                                                {{ $model->exam_mark ?? '' }}
                                            </td>
                                            <td>
                                                {{ $model->fee ?? '' }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.model.show',$model->id)}}" class="btn btn-success btn-sm mr-2"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.model.edit',$model->id)}}" class="btn btn-primary btn-sm mr-2"><i class="fas fa-edit"></i></a>
                                                <a href="{{ route('admin.model.delete',$model->id)}}" class="btn btn-danger btn-sm" title="Delete Data" id="delete"><i class="fa fa-trash"></i></a>
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