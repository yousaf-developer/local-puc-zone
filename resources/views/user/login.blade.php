<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LOGIN</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/bootstrap.min.css') }}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fonts/fonts.google.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets_user/css/style.css') }}" />
</head>

<script>
    var base_url = "{{url('/')}}";
</script>
<style>
.bottom-links a{
        text-decoration: none;
        color:#666666;
    }
@media (max-width: 500px) {
    
    .tc_links{
        position: fixed;
        bottom: 15%;
        left: 0;
        z-index: 2;
    }
}


</style>
<body>

    <div class="d-flex flex-column justify-content-center px-4 pt-md-5 pt-3">
        <div class="d-flex align-items-center justify-content-md-center">
            <img class="img-fluid" src="{{ asset('assets_user/images/NewLogo.png') }}" />
            <img class="img-fluid px-1" src="{{ asset('assets_user/images/PUCZONE.png') }}" />
        </div>
        <div class="form-heading d-grid align-items-center justify-content-md-center pt-lg-5">
            <h1 class="mt-5">login-</h1>
        </div>
    </div>
    <div class="d-grid align-items-center justify-content-center">
        <section>
            @if(session('error'))
            <div class="alert alert-danger">
                {{session('error')}}
            </div>
            @elseif($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="container px-4 pt-0">
                <form class="row g-3 login-form pt-3" method="POST" action="{{ route('loginSubmit') }}">
                    @csrf
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Username" />
                        <label class="ms-2" for="username">Email/Username</label>
                    </div>


                    <div id="user_password" class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                        <label class="ms-2" for="password">Password</label>
                        <svg class="showPassword" id="showPassword" width="20" height="16" viewBox="0 0 20 16" zfill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.5" d="M0 8C0 9.64 0.425 10.191 1.275 11.296C2.972 13.5 5.818 16 10 16C14.182 16 17.028 13.5 18.725 11.296C19.575 10.192 20 9.639 20 8C20 6.36 19.575 5.809 18.725 4.704C17.028 2.5 14.182 0 10 0C5.818 0 2.972 2.5 1.275 4.704C0.425 5.81 0 6.361 0 8Z" fill="#515C6F" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.25 7.75C6.25 6.75544 6.64509 5.80161 7.34835 5.09835C8.05161 4.39509 9.00544 4 10 4C10.9946 4 11.9484 4.39509 12.6517 5.09835C13.3549 5.80161 13.75 6.75544 13.75 7.75C13.75 8.74456 13.3549 9.69839 12.6517 10.4017C11.9484 11.1049 10.9946 11.5 10 11.5C9.00544 11.5 8.05161 11.1049 7.34835 10.4017C6.64509 9.69839 6.25 8.74456 6.25 7.75ZM7.75 7.75C7.75 7.15326 7.98705 6.58097 8.40901 6.15901C8.83097 5.73705 9.40326 5.5 10 5.5C10.5967 5.5 11.169 5.73705 11.591 6.15901C12.0129 6.58097 12.25 7.15326 12.25 7.75C12.25 8.34674 12.0129 8.91903 11.591 9.34099C11.169 9.76295 10.5967 10 10 10C9.40326 10 8.83097 9.76295 8.40901 9.34099C7.98705 8.91903 7.75 8.34674 7.75 7.75Z" fill="#515C6F" />
                        </svg>
                    </div>


                    <div class="forget text-end mb-3 mb-md-0">
                        <a href="{{route('forget')}}">forgot password?</a>
                    </div>


                    
                    <button type="submit" class="login-btn py-3 d-none d-md-block">
                        Login
                    </button>


                    <button type="submit" class="reg-btn w-75 d-flex align-items-center justify-content-center gap-3 mx-auto mt-2 mt-md-3 py-3 py-lg-2">
                        <a href="{{route('register')}}">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.5 2.4624H3C2.46957 2.4624 1.96086 2.67312 1.58579 3.04819C1.21071 3.42326 1 3.93197 1 4.4624V13.4624C1 13.9928 1.21071 14.5015 1.58579 14.8766C1.96086 15.2517 2.46957 15.4624 3 15.4624H13C13.5304 15.4624 14.0391 15.2517 14.4142 14.8766C14.7893 14.5015 15 13.9928 15 13.4624V8.9624" stroke="#003EDE" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M13 3.46252L13.953 4.46252M15 1.42952C15.2666 1.70517 15.4142 2.07453 15.411 2.458C15.4078 2.84146 15.2541 3.20832 14.983 3.47952L8 10.4625L5 11.4625L6 8.46252L12.987 1.41652C13.2326 1.16899 13.5613 1.02135 13.9095 1.00214C14.2577 0.982931 14.6006 1.09352 14.872 1.31252L15 1.42952Z" stroke="#003EDE" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Register Now
                        </a>
                    </button>

                    <div class="login-btn-hidden d-block d-md-none py-3">
                        <button type="submit" class=" w-100 mx-auto py-3">
                            Login
                        </button>
                    </div>

                    <div class="tc_links">
                        <div class="bottom-links d-flex justify-content-center flex-wrap pt-3 ">
                            <a href="{{@$settings->privacy_policy_link != null ? @$settings->privacy_policy_link : 'javascript:;'}}" target="_blank" class="px-2"><small>Privacy Policy</small> </a>|
                            <a href="{{@$settings->refund_policy_link != null ? @$settings->refund_policy_link : 'javascript:;'}}" target="_blank" class="px-2"><small>Refund Policy</small> </a>|
                            <a href="{{@$settings->terms_of_use_link != null ? @$settings->terms_of_use_link : 'javascript:;'}}" target="_blank" class="px-2"><small>Terms Of Use</small> </a>
                        </div>
                        <div class="bottom-links d-flex justify-content-center flex-wrap">
                            <a href="{{@$settings->contact_us_link != null ? @$settings->contact_us_link : 'javascript:;'}}" target="_blank" class="px-2"><small>Contact Us</small> </a>|
                            <a href="{{@$settings->about_us_link != null ? @$settings->about_us_link : 'javascript:;'}}" target="_blank" class="px-2"><small>About Us</small> </a>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        

        
    </div>

    @push('script')

    <script src="{{ asset('assets/plugins/popper/popper.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/plugins/bootstrap/bootstrap.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets_user/js/main.js') }}"></script>

    @endpush
</body>
<script>
    document.getElementById('showPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const passwordFieldType = passwordField.getAttribute('type');
        if (passwordFieldType === 'password') {
            passwordField.setAttribute('type', 'text');
        } else {
            passwordField.setAttribute('type', 'password');
        }
    });
</script>

</html>