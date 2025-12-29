@extends('auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_header', __('adminlte.login_message'))

@section('auth_body')
    <form action="{{ $login_url }}" method="post">
        @csrf
        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control
            @error('email') is-invalid @enderror"
            value="{{ old('email') }}" placeholder="@lang('adminlte.email')" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            placeholder="@lang('adminlte.password')">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="@lang('adminlte.remember_me_hint')">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        @lang('adminlte.remember_me')
                    </label>
                </div>
            </div>
            <div class="col-5">
                <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}"><span class="fas fa-sign-in-alt"></span>
                    @lang('adminlte.sign_in')
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($password_reset_url)
        <p class="my-0">
            <a href="{{ $password_reset_url }}">
                @lang('adminlte.i_forgot_my_password')
            </a>
        </p>
    @endif

    {{-- Register link --}}
    <!--
        @if($register_url)
            <p class="my-0">
                <a href="{{ $register_url }}">
                    @lang('adminlte.register_a_new_membership')
                </a>
            </p>
        @endif
    -->
@stop

@push('js')
    {{-- javascript to save all resource css js json png into the localstorage --}}
    @section('plugins.Sweetalert2', true)
    @section('plugins.Toastr', true)

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const resources = [
                "{{ asset('vendor/fontawesome-free/css/all.min.css') }}",
                "{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}",
                "{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}",
                "{{ asset('favicons/favicon.ico') }}",
                "{{ asset('favicons/apple-icon-57x57.png') }}",
                "{{ asset('favicons/apple-icon-60x60.png') }}",
                "{{ asset('favicons/apple-icon-72x72.png') }}",
                "{{ asset('favicons/apple-icon-76x76.png') }}",
                "{{ asset('favicons/apple-icon-114x114.png') }}",
                "{{ asset('favicons/apple-icon-120x120.png') }}",
                "{{ asset('favicons/apple-icon-144x144.png') }}",
                "{{ asset('favicons/apple-icon-152x152.png') }}",
                "{{ asset('favicons/apple-icon-180x180.png') }}",
                "{{ asset('favicons/favicon-16x16.png') }}",
                "{{ asset('favicons/favicon-32x32.png') }}",
                "{{ asset('favicons/favicon-96x96.png') }}",
                "{{ asset('favicons/android-icon-192x192.png') }}",
                "{{ asset('favicons/manifest.json') }}",
                "{{ asset('favicons/ms-icon-144x144.png') }}"
            ];

            resources.forEach(function(resource) {
                fetch(resource)
                    .then(response => response.blob())
                    .then(blob => {
                        const url = URL.createObjectURL(blob);
                        localStorage.setItem(resource, url);
                    });
            });
        });
        // add script to load all resource from localstorage if no connection and show alert of offline state
        window.addEventListener('load', function() {
            if (!navigator.onLine) {
                // alert('You are offline. Loading resources from cache.');
                resources.forEach(function(resource) {
                    const cachedUrl = localStorage.getItem(resource);
                    if (cachedUrl) {

                        // Logic to use cachedUrl to replace or inject resources goes here.
                        // Note: Blob URLs stored in localStorage are typically not persistent across sessions.
                    }
                });
            }
        });window.addEventListener('load', function() {
            if (!navigator.onLine) {
                const resources = [
                    "{{ asset('vendor/fontawesome-free/css/all.min.css') }}",
                    "{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}",
                    "{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}",
                    "{{ asset('favicons/favicon.ico') }}",
                    "{{ asset('favicons/apple-icon-57x57.png') }}",
                    "{{ asset('favicons/apple-icon-60x60.png') }}",
                    "{{ asset('favicons/apple-icon-72x72.png') }}",
                    "{{ asset('favicons/apple-icon-76x76.png') }}",
                    "{{ asset('favicons/apple-icon-114x114.png') }}",
                    "{{ asset('favicons/apple-icon-120x120.png') }}",
                    "{{ asset('favicons/apple-icon-144x144.png') }}",
                    "{{ asset('favicons/apple-icon-152x152.png') }}",
                    "{{ asset('favicons/apple-icon-180x180.png') }}",
                    "{{ asset('favicons/favicon-16x16.png') }}",
                    "{{ asset('favicons/favicon-32x32.png') }}",
                    "{{ asset('favicons/favicon-96x96.png') }}",
                    "{{ asset('favicons/android-icon-192x192.png') }}",
                    "{{ asset('favicons/manifest.json') }}",
                    "{{ asset('favicons/ms-icon-144x144.png') }}"
                ];

                resources.forEach(function(resource) {
                    const url = localStorage.getItem(resource);
                    if (url) {
                        const linkElements = document.querySelectorAll(`link[href='${resource}']`);
                        linkElements.forEach(link => {
                            link.href = url;
                        });
                        const imgElements = document.querySelectorAll(`img[src='${resource}']`);
                        imgElements.forEach(img => {
                            img.src = url;
                        });
                    }
                });
            }
        });
        window.addEventListener('online', function() {
            Swal.fire({
                toast: true,
                icon: 'success',
                title: 'You are back online!',
                position: 'bottom-end',
                showConfirmButton: false,
                customClass: {
                    popup: 'colored-toast',
                },
                progressBar: true,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            console.log('You are online');
        });
        window.addEventListener('offline', function() {

            Swal.fire({
                toast: true,
                icon: 'error',
                title: 'Connection lost!',
                text: 'You are currently offline. Some features may not be available.',
                position: 'bottom-end',
                showConfirmButton: false,
                customClass: {
                    popup: 'colored-toast',
                },
            });
            console.log('You are offline');

        });


    </script>
@endpush
