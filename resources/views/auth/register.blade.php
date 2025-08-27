<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ $pageTitle ?? 'Register Account' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('dashboard/auth/css/register.css') }}">
</head>
<body>
    <div class="bg-bubbles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </div>
    
    <div class="container">
        <div class="register-container">
            <div class="register-header">
                <div class="logo">
                    <i class="fas fa-user-graduate"></i>MCQ Admission
                </div>
                <h2>Create an Account</h2>
                <p class="mb-0">Join our learning community today</p>
            </div>
            
            <div class="register-body">
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-section">
                        <h4 class="form-section-title">Personal Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <input type="text" name="full_name" value="{{ old('full_name') }}"  class="form-control" placeholder="Full Name">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="Username">
                                    <i class="fas fa-at"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4 class="form-section-title">Institute Details</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <input type="text" name="institute" class="form-control" placeholder="Institute name">
                                    <i class="fas fa-university"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <select name="division_id" class="form-control">
                                        <option selected disabled>Select division</option>
                                        @foreach(get_divisions() as $division)
                                            <option value="{{ $division->id }}">{{ $division->name_en }} - {{ $division->name_bn }}</option>
                                        @endforeach
                                    </select>
                                    <i class="fas fa-layer-group"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                   <div class="form-section">
                        <h4 class="form-section-title">Contact Information</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <input type="text" id="refer_by" name="refer_by" 
                                        value="{{ $_GET['refer_id'] ?? '' }}" 
                                        class="form-control" placeholder="Enter Refer By Username">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <small id="referMessage"></small>
                            </div>

                            <div class="col-md-6">
                                <div class="input-icon">
                                    <input type="tel" name="phone" value="{{ old('phone') }}" 
                                        class="form-control" placeholder="Phone">
                                    <i class="fas fa-phone"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="form-section">
                        <h4 class="form-section-title">Security</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <input type="password"  name="password" value="{{ old('password') }}" class="form-control" placeholder="Type Password">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-icon">
                                    <input type="password"  name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control" placeholder="Type Confirm Password">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="terms-check form-check">
                        <input class="form-check-input" type="checkbox" id="termsCheck">
                        <label class="form-check-label" for="termsCheck">
                            I accept the <a href="#" class="text-decoration-none">Terms and Conditions</a>
                        </label>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <div class="row">
                        <div class="col-md-6 mx-auto">
                            <button type="submit" class="btn btn-register">
                                <i class="fas fa-user-plus me-2"></i> REGISTER
                            </button>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p>Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function(){

        // refer_by input এ keyup event
        $('#refer_by').on('keyup', function() {
            let username = $(this).val();
            let messageEl = $('#referMessage');

            if(username.length > 2){ 
                $.ajax({
                    url: "{{ route('check.refer') }}", 
                    type: "GET",
                    data: { username: username },
                    success: function(res){
                        if(res.status){ 
                            messageEl.text(res.message).css('color','green');
                        } else { 
                            messageEl.text(res.message).css('color','red');
                        }
                    },
                    error: function(){
                        messageEl.text('Something went wrong').css('color','red');
                    }
                });
            } else {
                messageEl.text(''); 
            }
        });

    });
    </script>

</body>
</html>