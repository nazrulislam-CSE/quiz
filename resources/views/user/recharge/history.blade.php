@extends('layouts.user.app', ['pageTitle' => $pageTitle])

@section('content')

<div class="container">

    <h3 class="mb-4">{{ $pageTitle }}</h3>

    <div class="card shadow-lg border-0">

         <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>
                রিচার্জ হিস্টোরি
            </h5>

            <a href="{{ route('user.recharge.create') }}"
               class="btn btn-light btn-sm">
                <i class="fas fa-mobile-alt me-1"></i>
                রিচার্জ করুন
            </a>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>ক্রমিক</th>
                            <th>মোবাইল নম্বর</th>
                            <th>অপারেটর</th>
                            <th>পরিমাণ</th>
                            <th>রেফারেন্স</th>
                            <th>স্ট্যাটাস</th>
                            <th>তারিখ</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($histories as $index => $recharge)

                            <tr>

                                <td>
                                    {{ $histories->firstItem() + $index }}
                                </td>

                                <td>
                                    {{ $recharge->number }}
                                </td>

                                <td>
                                    @switch($recharge->operator)

                                        @case('GP')
                                            গ্রামীণফোন
                                            @break

                                        @case('BL')
                                            বাংলালিংক
                                            @break

                                        @case('RB')
                                            রবি
                                            @break

                                        @case('AT')
                                            এয়ারটেল
                                            @break

                                        @case('TT')
                                            টেলিটক
                                            @break

                                        @default
                                            {{ $recharge->operator }}

                                    @endswitch
                                </td>

                                <td>
                                    ৳{{ number_format($recharge->amount, 2) }}
                                </td>

                                <td>
                                    {{ $recharge->reference }}
                                </td>

                                <td>

                                    @if($recharge->status === 'success')

                                        <span class="badge bg-success">
                                            সফল
                                        </span>

                                    @elseif($recharge->status === 'failed')

                                        <span class="badge bg-danger">
                                            ব্যর্থ
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            {{ $recharge->status }}
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $recharge->created_at->format('d M Y, h:i A') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    কোনো রিচার্জ হিস্টোরি পাওয়া যায়নি।
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $histories->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>

</div>

@endsection

