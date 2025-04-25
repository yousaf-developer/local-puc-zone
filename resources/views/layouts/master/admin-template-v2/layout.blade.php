<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- bootstrap 5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <!-- bootstrap 5-->

    <!-- aos animation -->
    <script src="{{asset('assets-v2')}}/css/aos.js"></script>
    <link href="{{asset('assets-v2')}}/css/aos.css" rel="stylesheet">
    <!-- aos animation -->

    <!-- css -->
    <link href="{{asset('assets-v2')}}/css/hover.css" rel="stylesheet">
    <link href="{{asset('assets-v2')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('assets-v2')}}/css/responsive.css" rel="stylesheet">
    <!-- css -->

    <link href="{{asset('assets-v2/images/fav.png')}}" rel="icon" type="image/x-icon">
    <title>Page Title</title>



    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/plugins/fonts/fonts.google.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets_admin/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/select2.min.css') }}" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    var base_url = "{{url('/')}}";
</script>
</head>

<body>

<!-- mobile-navigation -->
@include('layouts.master.admin-template-v2.mobile-sidebar')
<!-- mobile-navigation -->

<div class="puc-layout">
    @include('layouts.master.admin-template-v2.sidebar')
    <section class="puc-body">
        @yield('content')
    </section>
</div>



<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<script src="{{asset('assets-v2/css/jquery.min.js')}}"></script>
<script src="{{asset('assets-v2/css/script.js')}}"></script>

<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/popper/popper.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('assets/plugins/bootstrap/bootstrap.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets_admin/js/main.js') }}"></script>
<script src="{{ asset('assets_admin/customjs/common.js') }}"></script>


<script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>

<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.min.js') }}"></script>

<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip-utils.min.js') }}"></script>

@stack('script')




</body>

</html>
