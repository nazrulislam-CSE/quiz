<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>{{ $success ? 'Payment Success' : 'Payment Failed' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; padding: 0;
            font-family: 'Segoe UI', 'SolaimanLipi', Tahoma, sans-serif;
            background: {{ $success ? '#f0fdf4' : '#fef2f2' }};
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .card {
            background: #fff; border-radius: 20px;
            padding: 40px 30px; max-width: 420px; width: 90%;
            text-align: center;
            box-shadow: 0 12px 40px rgba(0,0,0,.1);
        }
        .icon {
            width: 80px; height: 80px; border-radius: 50%;
            margin: 0 auto 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 40px;
            background: {{ $success ? '#dcfce7' : '#fee2e2' }};
            color: {{ $success ? '#16a34a' : '#dc2626' }};
        }
        h1 { font-size: 22px; margin: 0 0 10px; color: #1e293b; }
        p  { font-size: 14px; color: #64748b; margin: 6px 0; line-height: 1.6; }
        .order-info {
            background: #f8fafc; border-radius: 12px;
            padding: 14px; margin: 18px 0;
            font-size: 13px; text-align: left;
        }
        .order-info div { display: flex; justify-content: space-between; padding: 4px 0; }
        .order-info strong { color: #1e293b; }
        .btn {
            display: inline-block; margin-top: 16px;
            padding: 12px 28px; border-radius: 10px;
            background: {{ $success ? '#16a34a' : '#dc2626' }};
            color: #fff; text-decoration: none;
            font-weight: 700; font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            {{ $success ? '✓' : '✕' }}
        </div>
        <h1>{{ $success ? 'Payment Successful!' : 'Payment Failed' }}</h1>
        <p>{{ $message }}</p>

        @if($orderNo)
            <div class="order-info">
                <div><span>Order No:</span> <strong>{{ $orderNo }}</strong></div>
                @if($amount > 0)
                    <div><span>Amount:</span> <strong>৳{{ number_format($amount, 2) }}</strong></div>
                @endif
                <div><span>Status:</span> <strong style="color: {{ $success ? '#16a34a' : '#dc2626' }};">{{ strtoupper($status) }}</strong></div>
            </div>
        @endif

        <p style="font-size:12px; margin-top:20px;">
            {{ $success ? 'আপনি এখন অ্যাপে ফিরে গিয়ে বইটি পড়তে পারবেন।' : 'টাকা কাটা হয়নি। আবার চেষ্টা করুন।' }}
        </p>

        {{-- Deep link for Flutter (optional) --}}
        <a href="chalkboardbd://payment/{{ $status }}?order={{ $orderNo }}"
           class="btn">অ্যাপে ফিরে যান</a>
    </div>

    {{-- Auto-close for WebView after 2.5 sec --}}
    <script>
        setTimeout(function () {
            // Flutter WebView এই JS interface ধরতে পারবে
            if (window.FlutterChannel && window.FlutterChannel.postMessage) {
                window.FlutterChannel.postMessage(JSON.stringify({
                    status: '{{ $status }}',
                    order_no: '{{ $orderNo }}',
                    message: '{{ $message }}'
                }));
            }
            // Browser হলে try deep link
            window.location.href = 'chalkboardbd://payment/{{ $status }}?order={{ $orderNo }}';
        }, 2500);
    </script>
</body>
</html>