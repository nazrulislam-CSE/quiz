<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher - {{ $order->order_no }}</title>
</head>
<body style="margin:0; padding:0; font-family: 'Segoe UI', 'SolaimanLipi', 'Kalpurush', Tahoma, sans-serif; background:#f1f5f9; color:#1e293b; -webkit-print-color-adjust:exact; print-color-adjust:exact;">

@php
    $isGift = (bool) ($order->is_gift ?? false);

    // Buyer Info
    $buyerName  = $order->user->full_name ?? $order->buyer_name  ?? 'N/A';
    $buyerEmail = $order->user->email     ?? $order->buyer_email ?? '';
    $buyerPhone = $order->user->phone     ?? $order->buyer_phone ?? '';
    $buyerImage = $order->user->image     ?? null;

    // Student Info (gift হলে receiver, self হলে buyer নিজেই)
    $studentName  = $order->student->full_name ?? $order->student_name  ?? $buyerName;
    $studentEmail = $order->student->email     ?? $order->student_email ?? $buyerEmail;
    $studentPhone = $order->student->phone     ?? $order->student_phone ?? $buyerPhone;
    $studentImage = $order->student->image     ?? null;

    // Gift হলে "Gift Voucher", না হলে "Purchase Voucher"
    $voucherTitle = $isGift ? 'COURSE GIFT VOUCHER' : 'COURSE PURCHASE VOUCHER';
@endphp

    {{-- ============ ACTION BAR (not printed) ============ --}}
    <div class="no-print" style="max-width:850px; margin:20px auto 14px; display:flex; justify-content:flex-end; gap:8px; padding:0 15px;">
        <a href="{{ route('user.study.center.my.courses') }}"
           style="padding:8px 18px; background:#ffffff; color:#334155; border:1px solid #e2e8f0; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600; display:inline-block;">
            ← Back
        </a>
        <button type="button" onclick="window.print()"
                style="padding:8px 18px; background:#4f46e5; color:#ffffff; border:none; border-radius:8px; cursor:pointer; font-size:13px; font-weight:600;">
            🖨 Print Voucher
        </button>
    </div>

    {{-- ============ VOUCHER WRAPPER ============ --}}
    <div style="max-width:850px; margin:0 auto 30px; background:#ffffff; border-radius:12px; box-shadow:0 6px 30px rgba(0,0,0,.08); overflow:hidden;">

        {{-- ============ HEADER ============ --}}
        <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%); background-color:#4f46e5; color:#ffffff; padding:22px 28px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">

            <div>
                <h1 style="margin:0 0 6px 0; font-size:22px; font-weight:700; color:#ffffff; letter-spacing:.5px;">
                    {{ get_setting('site_name')->value ?? 'Chalkboard Bd' }}
                </h1>
                <div style="font-size:12px; color:#ffffff; opacity:.95; margin-bottom:3px;">
                    📞 01521420274 &nbsp;|&nbsp;
                    📞 01805007535 &nbsp;|&nbsp;
                    ✉ {{ get_setting('email')->value ?? '' }}
                </div>
                <div style="font-size:12px; color:#ffffff; opacity:.9;">
                    📍 Corporate address: Malopara,Rajshahi <br>
                    📍 Head office: Dhanmondi 27,Dhaka
                </div>
            </div>

            <div style="text-align:right;">
                <div style="display:inline-block; background:rgba(255,255,255,.2); padding:6px 16px; border-radius:20px; font-size:13px; font-weight:600; color:#ffffff;">
                    {{ $voucherTitle }}
                </div>
                <div style="font-size:12px; color:#ffffff; opacity:.9; margin-top:6px;">
                    Order No: <strong>{{ $order->order_no }}</strong>
                </div>
            </div>

        </div>

        {{-- ============ BODY ============ --}}
        <div style="padding:26px 28px;">

            {{-- ============ GIFT NOTICE ============ --}}
            @if ($isGift)
                <div style="background:#f0fdf4; border:2px solid #10b981; border-radius:10px; padding:14px 18px; margin-bottom:22px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="font-size:26px; line-height:1;">🎁</div>
                        <div>
                            <div style="font-weight:800; color:#059669; font-size:14px; margin-bottom:2px;">
                                This is a GIFT Voucher
                            </div>
                            <div style="font-size:12.5px; color:#047857;">
                                Gifted to: <strong>{{ $studentName }}</strong>
                                &nbsp;|&nbsp;
                                Gifted by: <strong>{{ $buyerName }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============ BUYER + STUDENT INFO ============ --}}
            @if ($isGift)
                {{-- ========== GIFT: Buyer + Student (2 columns) ========== --}}
                <table style="width:100%; border-collapse:collapse; margin-bottom:20px;">
                    <tr>
                        {{-- Buyer --}}
                        <td style="width:50%; vertical-align:top; padding-right:8px;">
                            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#7c3aed; border-bottom:2px solid #eef2ff; padding-bottom:6px; margin-bottom:12px;">
                                BUYER (WHO PAID)
                            </div>

                            <table style="width:100%; border-collapse:collapse; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">
                                <tr>
                                    <td style="width:60px; vertical-align:middle; padding:10px;">
                                        <img src="{{ !empty($buyerImage)
                                                    ? url('upload/user/' . $buyerImage)
                                                    : url('upload/avater.png') }}"
                                             alt="Buyer"
                                             style="width:48px; height:48px; object-fit:cover; border-radius:50%; border:2px solid #e5e7eb; display:block;">
                                    </td>
                                    <td style="vertical-align:middle; padding:10px 10px 10px 0;">
                                        <div style="font-weight:700; font-size:14px; color:#1e293b; margin-bottom:3px;">
                                            {{ $buyerName }}
                                        </div>
                                        <div style="font-size:11.5px; color:#64748b; margin-bottom:2px;">
                                            User ID: #{{ $order->user_id }}
                                        </div>
                                        @if ($buyerEmail)
                                            <div style="font-size:11.5px; color:#64748b; margin-bottom:2px;">
                                                {{ $buyerEmail }}
                                            </div>
                                        @endif
                                        @if ($buyerPhone)
                                            <div style="font-size:11.5px; color:#64748b;">
                                                📞 {{ $buyerPhone }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>

                        {{-- Student --}}
                        <td style="width:50%; vertical-align:top; padding-left:8px;">
                            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#059669; border-bottom:2px solid #d1fae5; padding-bottom:6px; margin-bottom:12px;">
                                STUDENT (WHO RECEIVED) 🎓
                            </div>

                            <table style="width:100%; border-collapse:collapse; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px;">
                                <tr>
                                    <td style="width:60px; vertical-align:middle; padding:10px;">
                                        <img src="{{ !empty($studentImage)
                                                    ? url('upload/user/' . $studentImage)
                                                    : url('upload/avater.png') }}"
                                             alt="Student"
                                             style="width:48px; height:48px; object-fit:cover; border-radius:50%; border:2px solid #86efac; display:block;">
                                    </td>
                                    <td style="vertical-align:middle; padding:10px 10px 10px 0;">
                                        <div style="font-weight:700; font-size:14px; color:#065f46; margin-bottom:3px;">
                                            {{ $studentName }}
                                        </div>
                                        <div style="font-size:11.5px; color:#047857; margin-bottom:2px;">
                                            User ID: #{{ $order->student_id ?? '—' }}
                                        </div>
                                        @if ($studentEmail)
                                            <div style="font-size:11.5px; color:#047857; margin-bottom:2px;">
                                                {{ $studentEmail }}
                                            </div>
                                        @endif
                                        @if ($studentPhone)
                                            <div style="font-size:11.5px; color:#047857;">
                                                📞 {{ $studentPhone }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                {{-- Order Info (full width below) --}}
                <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#7c3aed; border-bottom:2px solid #eef2ff; padding-bottom:6px; margin-bottom:12px; margin-top:4px;">
                    ORDER INFORMATION
                </div>

                <table style="width:100%; border-collapse:collapse; font-size:13.5px; margin-bottom:22px;">
                    <tr>
                        <td style="padding:6px 0; color:#64748b; width:130px;">Order No:</td>
                        <td style="padding:6px 0; font-weight:600; color:#1e293b;">{{ $order->order_no }}</td>
                        <td style="padding:6px 0; color:#64748b; width:110px;">Order Date:</td>
                        <td style="padding:6px 0; font-weight:600; color:#1e293b;">
                            {{ $order->paid_at?->format('d M Y, h:i A') ?? $order->created_at?->format('d M Y, h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; color:#64748b;">Payment:</td>
                        <td style="padding:6px 0; font-weight:600; color:#1e293b;">
                            {{ ucfirst($order->payment_method ?? 'EPS') }}
                        </td>
                        <td style="padding:6px 0; color:#64748b;">Status:</td>
                        <td style="padding:6px 0; font-weight:700;">
                            @if ($order->payment_status === 'paid')
                                <span style="color:#059669;">✔ PAID</span>
                            @else
                                <span style="color:#dc2626;">{{ strtoupper($order->payment_status) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0; color:#64748b;">Txn ID:</td>
                        <td colspan="3" style="padding:6px 0; font-weight:600; color:#1e293b; font-size:12px; word-break:break-all;">
                            {{ $order->merchant_transaction_id ?? '—' }}
                        </td>
                    </tr>
                </table>

            @else
                {{-- ========== SELF: Buyer = Student, existing layout ========== --}}
                <table style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                    <tr>
                        {{-- Buyer / Student (same person) --}}
                        <td style="width:50%; vertical-align:top; padding-right:12px;">
                            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#7c3aed; border-bottom:2px solid #eef2ff; padding-bottom:6px; margin-bottom:12px;">
                                STUDENT / BUYER INFORMATION
                            </div>

                            <table style="width:100%; border-collapse:collapse; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">
                                <tr>
                                    <td style="width:70px; vertical-align:middle; padding:12px;">
                                        <img src="{{ !empty($buyerImage)
                                                    ? url('upload/user/' . $buyerImage)
                                                    : url('upload/avater.png') }}"
                                             alt="User"
                                             style="width:56px; height:56px; object-fit:cover; border-radius:50%; border:2px solid #e5e7eb; display:block;">
                                    </td>
                                    <td style="vertical-align:middle; padding:12px 12px 12px 0;">
                                        <div style="font-weight:700; font-size:15px; color:#1e293b; margin-bottom:3px;">
                                            {{ $buyerName }}
                                        </div>
                                        <div style="font-size:12px; color:#64748b; margin-bottom:2px;">
                                            User ID: #{{ $order->user_id }}
                                        </div>
                                        @if ($buyerEmail)
                                            <div style="font-size:12px; color:#64748b; margin-bottom:2px;">
                                                {{ $buyerEmail }}
                                            </div>
                                        @endif
                                        @if ($buyerPhone)
                                            <div style="font-size:12px; color:#64748b;">
                                                📞 {{ $buyerPhone }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>

                        {{-- Order Info --}}
                        <td style="width:50%; vertical-align:top; padding-left:12px;">
                            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#7c3aed; border-bottom:2px solid #eef2ff; padding-bottom:6px; margin-bottom:12px;">
                                ORDER INFORMATION
                            </div>

                            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                                <tr>
                                    <td style="padding:6px 0; color:#64748b; width:130px;">Order No:</td>
                                    <td style="padding:6px 0; font-weight:600; color:#1e293b;">{{ $order->order_no }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0; color:#64748b;">Order Date:</td>
                                    <td style="padding:6px 0; font-weight:600; color:#1e293b;">
                                        {{ $order->paid_at?->format('d M Y, h:i A') ?? $order->created_at?->format('d M Y, h:i A') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0; color:#64748b;">Payment:</td>
                                    <td style="padding:6px 0; font-weight:600; color:#1e293b;">
                                        {{ ucfirst($order->payment_method ?? 'EPS') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0; color:#64748b;">Txn ID:</td>
                                    <td style="padding:6px 0; font-weight:600; color:#1e293b; font-size:12px; word-break:break-all;">
                                        {{ $order->merchant_transaction_id ?? '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0; color:#64748b;">Status:</td>
                                    <td style="padding:6px 0; font-weight:700;">
                                        @if ($order->payment_status === 'paid')
                                            <span style="color:#059669;">✔ PAID</span>
                                        @else
                                            <span style="color:#dc2626;">{{ strtoupper($order->payment_status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            @endif

            {{-- ============ Course Table ============ --}}
            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#7c3aed; border-bottom:2px solid #eef2ff; padding-bottom:6px; margin-bottom:12px;">
                COURSE DETAILS
                @if ($isGift)
                    <span style="color:#059669; text-transform:none; font-weight:600; font-size:11.5px;">
                        (Gifted to {{ $studentName }})
                    </span>
                @endif
            </div>

            <table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
                <thead>
                    <tr>
                        <th style="background:#4f46e5; background-color:#4f46e5; color:#ffffff; padding:10px 12px; font-size:12.5px; font-weight:600; text-align:left; width:40px;">#</th>
                        <th style="background:#4f46e5; background-color:#4f46e5; color:#ffffff; padding:10px 12px; font-size:12.5px; font-weight:600; text-align:left;">Course Title</th>
                        <th style="background:#4f46e5; background-color:#4f46e5; color:#ffffff; padding:10px 12px; font-size:12.5px; font-weight:600; text-align:left;">Type / Class</th>
                        <th style="background:#4f46e5; background-color:#4f46e5; color:#ffffff; padding:10px 12px; font-size:12.5px; font-weight:600; text-align:right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:10px 12px; font-size:13px; border-bottom:1px solid #f1f5f9;">1</td>
                        <td style="padding:10px 12px; font-size:13px; border-bottom:1px solid #f1f5f9;">
                            <strong style="color:#1e293b;">{{ $order->course->title ?? 'N/A' }}</strong>
                            <br>
                            <small style="color:#64748b; font-size:11.5px;">
                                {{ $order->course->courseCategory->name ?? '' }}
                            </small>
                        </td>
                        <td style="padding:10px 12px; font-size:13px; border-bottom:1px solid #f1f5f9;">
                            <div style="font-size:12px; color:#4f46e5;">{{ $order->course->courseType->name ?? '—' }}</div>
                            <div style="font-size:11.5px; color:#64748b;">{{ $order->course->courseClass->name ?? '—' }}</div>
                        </td>
                        <td style="padding:10px 12px; font-size:13px; border-bottom:1px solid #f1f5f9; text-align:right;">
                            ৳{{ number_format($order->price, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- ============ Summary ============ --}}
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:50%;"></td>
                    <td style="width:50%;">
                        <table style="width:100%; border-collapse:collapse; font-size:13px;">
                            <tr>
                                <td style="padding:6px 0; color:#64748b; border-bottom:1px dashed #e2e8f0;">Sub Total</td>
                                <td style="padding:6px 0; text-align:right; font-weight:600; color:#1e293b; border-bottom:1px dashed #e2e8f0;">
                                    ৳{{ number_format($order->price, 2) }}
                                </td>
                            </tr>

                            @if ($order->discount_price)
                                <tr>
                                    <td style="padding:6px 0; color:#64748b; border-bottom:1px dashed #e2e8f0;">Discount Price</td>
                                    <td style="padding:6px 0; text-align:right; font-weight:600; color:#1e293b; border-bottom:1px dashed #e2e8f0;">
                                        ৳{{ number_format($order->discount_price, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0; color:#64748b; border-bottom:1px dashed #e2e8f0;">You Saved</td>
                                    <td style="padding:6px 0; text-align:right; font-weight:600; color:#059669; border-bottom:1px dashed #e2e8f0;">
                                        ৳{{ number_format($order->price - $order->discount_price, 2) }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td style="padding:10px 0 0 0; font-weight:800; font-size:15px; color:#4f46e5; border-top:2px solid #4f46e5;">
                                    Total Paid
                                </td>
                                <td style="padding:10px 0 0 0; text-align:right; font-weight:800; font-size:15px; color:#4f46e5; border-top:2px solid #4f46e5;">
                                    ৳{{ number_format($order->paid_amount, 2) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- ============ Signature ============ --}}
            <table style="width:100%; border-collapse:collapse; margin-top:40px;">
                <tr>
                    <td style="width:50%; text-align:center;">
                        <div style="border-top:1px solid #94a3b8; width:180px; margin:0 auto 4px;"></div>
                        <div style="font-size:11px; color:#64748b;">
                            {{ $isGift ? 'Buyer Signature' : 'Buyer Signature' }}
                        </div>
                    </td>
                    <td style="width:50%; text-align:center;">
                        <div style="border-top:1px solid #94a3b8; width:180px; margin:0 auto 4px;"></div>
                        <div style="font-size:11px; color:#64748b;">
                            {{ $isGift ? 'Student Signature' : 'Authorized Signature' }}
                        </div>
                    </td>
                </tr>
            </table>

        </div>

    </div>

    {{-- ============ PRINT STYLE ============ --}}
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; padding: 0 !important; margin: 0 !important; }
            @page { size: A4; margin: 10mm; }
        }
    </style>

</body>
</html>