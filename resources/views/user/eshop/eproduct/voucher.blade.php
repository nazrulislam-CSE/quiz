<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Voucher - {{ $order->order_no }}</title>
</head>
<body style="margin:0; padding:0; font-family: 'Segoe UI', 'SolaimanLipi', 'Kalpurush', Tahoma, sans-serif; background:#f1f5f9; color:#1e293b; -webkit-print-color-adjust:exact; print-color-adjust:exact;">

@php
    /* ===== Buyer Info ===== */
    $buyerName  = $order->user->full_name ?? $order->buyer_name  ?? 'N/A';
    $buyerEmail = $order->user->email     ?? $order->buyer_email ?? '';
    $buyerPhone = $order->user->phone     ?? $order->buyer_phone ?? '';
    $buyerImage = $order->user->image     ?? null;

    /* ===== Product Info ===== */
    $product      = $order->eproduct;
    $prodName     = $product->product_name ?? 'N/A';
    $prodBrand    = $product->brand_name   ?? '—';
    $prodPhoto    = $product->product_photo ?? null;
    $prodSize     = $product->size         ?? null;
    $prodColor    = $product->color        ?? null;
    $prodWarranty = $product->warranty     ?? null;

    /* ===== Price ===== */
    $originalPrice  = (float) $order->price;
    $productTotal   = (float) ($order->product_total ?? $order->paid_amount);
    $shippingCharge = (float) ($order->shipping_charge ?? 0);
    $paidAmount     = (float) $order->paid_amount;
    $discountPct    = (float) $order->discount;
    $discountAmt    = max(0, $originalPrice - $productTotal);
@endphp

    {{-- ============ ACTION BAR (not printed) ============ --}}
    <div class="no-print" style="max-width:850px; margin:20px auto 14px; display:flex; justify-content:flex-end; gap:8px; padding:0 15px;">
        <a href="{{ route('user.eproduct.orders') }}"
           style="padding:8px 18px; background:#ffffff; color:#334155; border:1px solid #e2e8f0; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600; display:inline-block;">
            ← Back to Orders
        </a>
        <button type="button" onclick="window.print()"
                style="padding:8px 18px; background:#ea580c; color:#ffffff; border:none; border-radius:8px; cursor:pointer; font-size:13px; font-weight:600;">
            🖨 Print Voucher
        </button>
    </div>

    {{-- ============ VOUCHER WRAPPER ============ --}}
    <div style="max-width:850px; margin:0 auto 30px; background:#ffffff; border-radius:12px; box-shadow:0 6px 30px rgba(0,0,0,.08); overflow:hidden;">

        {{-- ============ HEADER ============ --}}
        <div style="background: linear-gradient(135deg, #f59e0b 0%, #ea580c 50%, #dc2626 100%); background-color:#ea580c; color:#ffffff; padding:22px 28px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">

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
                    📍 Corporate address: Malopara, Rajshahi <br>
                    📍 Head office: Dhanmondi 27, Dhaka
                </div>
            </div>

            <div style="text-align:right;">
                <div style="display:inline-block; background:rgba(255,255,255,.25); padding:6px 16px; border-radius:20px; font-size:13px; font-weight:600; color:#ffffff;">
                    PRODUCT PURCHASE VOUCHER
                </div>
                <div style="font-size:12px; color:#ffffff; opacity:.9; margin-top:6px;">
                    Order No: <strong>{{ $order->order_no }}</strong>
                </div>
            </div>

        </div>

        {{-- ============ BODY ============ --}}
        <div style="padding:26px 28px;">

            {{-- ============ SUCCESS NOTICE ============ --}}
            @if ($order->payment_status === 'paid')
                <div style="background:#f0fdf4; border:2px solid #10b981; border-radius:10px; padding:14px 18px; margin-bottom:22px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="font-size:26px; line-height:1;">✅</div>
                        <div>
                            <div style="font-weight:800; color:#059669; font-size:14px; margin-bottom:2px;">
                                Payment Successful
                            </div>
                            <div style="font-size:12.5px; color:#047857;">
                                আপনার অর্ডার সফলভাবে সম্পন্ন হয়েছে। ধন্যবাদ <strong>{{ $buyerName }}</strong>!
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ============ BUYER + ORDER INFO ============ --}}
            <table style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                <tr>
                    {{-- Buyer --}}
                    <td style="width:50%; vertical-align:top; padding-right:12px;">
                        <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#ea580c; border-bottom:2px solid #fed7aa; padding-bottom:6px; margin-bottom:12px;">
                            BUYER INFORMATION
                        </div>

                        <table style="width:100%; border-collapse:collapse; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">
                            <tr>
                                <td style="width:70px; vertical-align:middle; padding:12px;">
                                    <img src="{{ !empty($buyerImage)
                                                ? url('upload/user/' . $buyerImage)
                                                : url('upload/avater.png') }}"
                                         alt="Buyer"
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
                        <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#ea580c; border-bottom:2px solid #fed7aa; padding-bottom:6px; margin-bottom:12px;">
                            ORDER INFORMATION
                        </div>

                        <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                            <tr>
                                <td style="padding:6px 0; color:#64748b; width:110px;">Order No:</td>
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
                                    @elseif ($order->payment_status === 'pending')
                                        <span style="color:#d97706;">⏳ PENDING</span>
                                    @else
                                        <span style="color:#dc2626;">{{ strtoupper($order->payment_status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- ============ PRODUCT DETAILS ============ --}}
            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#ea580c; border-bottom:2px solid #fed7aa; padding-bottom:6px; margin-bottom:12px;">
                PRODUCT DETAILS
            </div>

            <table style="width:100%; border-collapse:collapse; margin-bottom:18px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden;">
                <tr>
                    {{-- Product Image --}}
                    <td style="width:120px; vertical-align:top; padding:14px;">
                        @if ($prodPhoto)
                            <img src="{{ asset('storage/' . $prodPhoto) }}"
                                 alt="{{ $prodName }}"
                                 style="width:90px; height:90px; object-fit:cover; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,.15); display:block;">
                        @else
                            <div style="width:90px; height:90px; background:linear-gradient(135deg,#fff7ed,#fef3c7); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#ea580c; font-size:32px;">
                                📦
                            </div>
                        @endif
                    </td>

                    {{-- Product Info --}}
                    <td style="vertical-align:top; padding:14px 14px 14px 0;">
                        <div style="font-weight:800; font-size:16px; color:#1e293b; margin-bottom:6px; line-height:1.3;">
                            {{ $prodName }}
                        </div>
                        <div style="font-size:13px; color:#ea580c; font-weight:600; margin-bottom:8px;">
                            🏷 {{ $prodBrand }}
                        </div>

                        <table style="border-collapse:collapse; font-size:12.5px;">
                            @if ($prodSize)
                                <tr>
                                    <td style="padding:3px 12px 3px 0; color:#64748b;">Size:</td>
                                    <td style="padding:3px 0; font-weight:600; color:#1e293b;">{{ $prodSize }}</td>
                                </tr>
                            @endif
                            @if ($prodColor)
                                <tr>
                                    <td style="padding:3px 12px 3px 0; color:#64748b;">Color:</td>
                                    <td style="padding:3px 0; font-weight:600; color:#1e293b;">{{ $prodColor }}</td>
                                </tr>
                            @endif
                            @if ($prodWarranty)
                                <tr>
                                    <td style="padding:3px 12px 3px 0; color:#64748b;">Warranty:</td>
                                    <td style="padding:3px 0; font-weight:600; color:#1e293b;">{{ $prodWarranty }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td style="padding:3px 12px 3px 0; color:#64748b;">Quantity:</td>
                                <td style="padding:3px 0; font-weight:600; color:#1e293b;">1</td>
                            </tr>
                            @if ($discountPct > 0)
                                <tr>
                                    <td style="padding:3px 12px 3px 0; color:#64748b;">Discount:</td>
                                    <td style="padding:3px 0; font-weight:700; color:#ef4444;">
                                        {{ (int) $discountPct }}% OFF
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </td>

                    {{-- Price --}}
                    <td style="width:140px; vertical-align:middle; padding:14px; text-align:right; border-left:1px dashed #cbd5e1;">
                        @if ($discountPct > 0)
                            <div style="font-size:12px; color:#94a3b8; text-decoration:line-through;">
                                ৳{{ number_format($originalPrice, 0) }}
                            </div>
                        @endif
                        <div style="font-size:20px; font-weight:800; color:#ea580c; margin-top:2px;">
                            ৳{{ number_format($productTotal, 0) }}
                        </div>
                    </td>
                </tr>
            </table>

            {{-- ============ SHIPPING INFORMATION ============ --}}
            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#ea580c; border-bottom:2px solid #fed7aa; padding-bottom:6px; margin-bottom:12px; margin-top:4px;">
                SHIPPING INFORMATION
            </div>

            <table style="width:100%; border-collapse:collapse; background:#fff7ed; border:1px solid #fed7aa; border-radius:10px; margin-bottom:22px;">
                <tr>
                    <td style="width:60px; vertical-align:middle; padding:14px; text-align:center;">
                        <div style="width:42px; height:42px; background:#ea580c; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">
                            🚚
                        </div>
                    </td>
                    <td style="vertical-align:middle; padding:14px 14px 14px 0;">
                        <table style="width:100%; border-collapse:collapse; font-size:13px;">
                            <tr>
                                <td style="padding:4px 0; color:#9a3412; width:110px;">প্রাপকের নাম:</td>
                                <td style="padding:4px 0; font-weight:700; color:#1e293b;">
                                    {{ $order->shipping_name ?? $buyerName }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0; color:#9a3412;">মোবাইল নম্বর:</td>
                                <td style="padding:4px 0; font-weight:700; color:#1e293b;">
                                    {{ $order->shipping_phone ?? $buyerPhone ?? '—' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0; color:#9a3412; vertical-align:top;">সম্পূর্ণ ঠিকানা:</td>
                                <td style="padding:4px 0; font-weight:600; color:#1e293b; line-height:1.5;">
                                    {{ $order->shipping_address ?? '—' }}
                                    @if ($order->shipping_city)
                                        <br><span style="color:#64748b; font-size:12px;">
                                            📍 {{ $order->shipping_city }}
                                            @if ($order->shipping_postcode)
                                                — {{ $order->shipping_postcode }}
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:4px 0; color:#9a3412;">ডেলিভারি ধরন:</td>
                                <td style="padding:4px 0;">
                                    <span style="background:#ea580c; color:#fff; font-size:11px; padding:3px 10px; border-radius:20px; font-weight:700;">
                                        {{ $order->delivery_label ?? '—' }}
                                    </span>
                                    @if ($shippingCharge > 0)
                                        <span style="color:#ea580c; font-weight:700; margin-left:6px;">
                                            (৳{{ number_format($shippingCharge, 0) }})
                                        </span>
                                    @else
                                        <span style="color:#059669; font-weight:700; margin-left:6px;">
                                            (ফ্রি)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- ============ SUMMARY ============ --}}
            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:50%;"></td>
                    <td style="width:50%;">
                        <table style="width:100%; border-collapse:collapse; font-size:13px;">
                            <tr>
                                <td style="padding:6px 0; color:#64748b; border-bottom:1px dashed #e2e8f0;">
                                    Sub Total
                                </td>
                                <td style="padding:6px 0; text-align:right; font-weight:600; color:#1e293b; border-bottom:1px dashed #e2e8f0;">
                                    ৳{{ number_format($originalPrice, 2) }}
                                </td>
                            </tr>

                            @if ($discountPct > 0 && $discountAmt > 0)
                                <tr>
                                    <td style="padding:6px 0; color:#64748b; border-bottom:1px dashed #e2e8f0;">
                                        Discount ({{ (int) $discountPct }}%)
                                    </td>
                                    <td style="padding:6px 0; text-align:right; font-weight:600; color:#059669; border-bottom:1px dashed #e2e8f0;">
                                        − ৳{{ number_format($discountAmt, 2) }}
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td style="padding:6px 0; color:#64748b; border-bottom:1px dashed #e2e8f0;">
                                    Shipping ({{ $order->delivery_label ?? '—' }})
                                </td>
                                <td style="padding:6px 0; text-align:right; font-weight:600; color:#1e293b; border-bottom:1px dashed #e2e8f0;">
                                    @if ($shippingCharge > 0)
                                        + ৳{{ number_format($shippingCharge, 2) }}
                                    @else
                                        ফ্রি
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:10px 0 0 0; font-weight:800; font-size:15px; color:#ea580c; border-top:2px solid #ea580c;">
                                    Total Paid
                                </td>
                                <td style="padding:10px 0 0 0; text-align:right; font-weight:800; font-size:15px; color:#ea580c; border-top:2px solid #ea580c;">
                                    ৳{{ number_format($paidAmount, 2) }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            {{-- ============ NOTE ============ --}}
            <div style="margin-top:24px; padding:12px 16px; background:#fff7ed; border-left:4px solid #ea580c; border-radius:6px; font-size:12px; color:#9a3412; line-height:1.7;">
                <strong>📌 নোট:</strong>
                আপনার অর্ডারটি সফলভাবে সম্পন্ন হয়েছে।
                @if (($order->delivery_type ?? '') === 'self_receive')
                    অনুগ্রহ করে আমাদের অফিস থেকে প্রোডাক্ট সংগ্রহ করুন — <strong>Dhanmondi 27, Dhaka</strong>।
                @else
                    শীঘ্রই আমাদের কুরিয়ার প্রতিনিধি আপনার সাথে যোগাযোগ করবেন ডেলিভারির জন্য।
                @endif
                যোগাযোগ: <strong>01521420274</strong>
            </div>

            {{-- ============ SIGNATURE ============ --}}
            <table style="width:100%; border-collapse:collapse; margin-top:40px;">
                <tr>
                    <td style="width:50%; text-align:center;">
                        <div style="border-top:1px solid #94a3b8; width:180px; margin:0 auto 4px;"></div>
                        <div style="font-size:11px; color:#64748b;">Buyer Signature</div>
                    </td>
                    <td style="width:50%; text-align:center;">
                        <div style="border-top:1px solid #94a3b8; width:180px; margin:0 auto 4px;"></div>
                        <div style="font-size:11px; color:#64748b;">Authorized Signature</div>
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