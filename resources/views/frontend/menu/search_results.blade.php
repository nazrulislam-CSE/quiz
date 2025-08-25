@extends('layouts.frontend.app', [$pageTitle => 'Page Title'])
@section('content')
<section>
    <div class="container">
        <div class="section-title mb-1-9 mb-md-6 text-center wow fadeInUp" data-wow-delay="200ms">
            <span class="sm-title">Search Result</span>
            <h2 class="mb-0 h1">Your Result</h2>
        </div>
        <div class="row mt-n1-9 gx-xxl-5">
            @forelse($results as $result)
            <div class="col-md-6 col-sm-12 col-xl-4 mt-2-9 wow fadeInUp mx-auto" data-wow-delay="200ms">
                <div class="card card-style07 rounded-0 border-0 shadow-lg">
                    <img src="{{ (!empty($result->image)) ? url('upload/result/'.$result->image):url('upload/no_image.jpg') }}"  alt="image" class="img-fluid">
                    <div class="card-body p-4 text-center position-relative">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td style="font-weight: bolder;">Student Name</td>
                                    <td>{{ $result->students_name ?? 'n/a' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bolder;">Roll Number</td>
                                    <td>{{ $result->roll_number ?? 'n/a' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bolder;">Reg Number</td>
                                    <td>{{ $result->reg_number ?? 'n/a' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bolder;">Course Name</td>
                                    <td>{{ $result->course_name ?? 'n/a' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bolder;">Course Duration</td>
                                    <td>{{ $result->course_duration ?? 'n/a' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bolder;">CGPA</td>
                                    <td>{{ $result->cgpa ?? 'n/a' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @empty
                <tr>
                    <h4 class="text-center text-danger">No results found.</h4>
                </tr>
            @endforelse
        </div>
    </div>
</section>
@push('frontend-js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script>
        // Initialize Select2 on the <select> element
        $( '#country' ).select2( {
            theme: 'bootstrap-5'
        } );
    </script>
    @endpush
@endsection