<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Maintenance Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="vh-100 vw-100 m-auto p-0">
        <div class="row justify-content-center align-items-center h-100 mx-auto">
            <div class="col-10 col-sm-8 col-md-6 col-lg-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('maintenance.index') }}" method="post"> @csrf
                            <div class="row">
                                <div class="input-group">
                                    <input type="password" name="pincode" placeholder="Enter PIN Code" required class="form-control">
                                    <input type="submit" class="btn btn-danger" value="Check">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
