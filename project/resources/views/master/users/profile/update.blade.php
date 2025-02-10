@extends('layouts.app')

@section('subtitle', __('cruds.user.profile.profile'))
@section('content_header_title', __('cruds.user.profile.profile'))
@section('sub_breadcumb', __('cruds.user.profile.label'))

@section('content_body')
<div class="row">
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <a class="img-url" href="{{ $user->full_profile() }}?t={{ time() }}" data-lightbox="profile" target="_blank"
                        data-title="{{ $user->nama ?? '' }} Profile Picture">
                        <img id="profilePic" class="profile-pic img-fluid img-circle" src="{{ $user->adminlte_image() }}?t={{ time() }}" alt="{{ $user->nama ?? '' }} profile picture">
                    </a>
                </div>

                <h3 class="profile-username text-center">{{ $user->nama }}</h3>
                <p class="text-muted text-center">{{ $user->jabatans->nama ??  '-' }}</p>

            </div>

        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-primary card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#update"
                            data-toggle="tab">{{ __('cruds.user.profile.update') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#password"
                            data-toggle="tab">{{ __('cruds.user.profile.change_password') }}</a></li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="update">
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" id="updateProfile">
                            <div class="form-group row">
                                <label for="nama"
                                    class="col-sm-2 col-form-label">{{ __('cruds.user.fields.nama') }}</label>
                                <div class="col-sm-10">
                                    <input required type="text" name="nama"
                                        class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" id="nama"
                                        value="{{ old('nama', $user->nama) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="username"
                                    class="col-sm-2 col-form-label">{{ __('cruds.user.fields.username') }}</label>
                                <div class="col-sm-10">
                                    <input type="username" readonly
                                        title="{{ __('cruds.user.validation.fields.username_admin') }}"
                                        class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                        id="username" value="{{ old('username', $user->username) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email"
                                    class="col-sm-2 col-form-label">{{ __('cruds.user.fields.email') }}</label>
                                <div class="col-sm-10">
                                    <input required type="email" name="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email"
                                        value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descritpion"
                                    class="col-sm-2 col-form-label">{{ __('cruds.user.profile.bio') }}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="description"
                                        name="description">{{ old('description', $user->description) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descritpion"
                                    class="col-sm-2 col-form-label">{{ __('cruds.user.profile.images') }}</label>
                                <div class="col-sm-10">
                                    <div class="form-group file-loading">
                                        <input id="profile_picture" name="profile_picture" type="file"
                                            class="form-control" data-show-upload="false" data-show-caption="true">
                                    </div>
                                    <div id="captions-container"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" id="updateProfileBtn" class="btn btn-primary btn-block"><i
                                            class="bi bi-person-check-fill"></i> {{ __('global.save') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane" id="password">
                        <form method="POST" class="form-horizontal" id="updatePassword">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                    for="password">{{ __('cruds.user.profile.password') }}</label>
                                <div class="col-sm-10">
                                    <input required class="form-control" type="password" name="password" id="password"
                                        required="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                    for="password_confirmation">{{ __('cruds.user.profile.repeat_password') }}</label>
                                <div class="col-sm-10">
                                    <input required class="form-control" type="password" name="password_confirmation"
                                        id="password_confirmation" required="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button id="updatePasswordBtn" class="btn btn-primary btn-block" type="submit"><i
                                            class="bi bi-lock"></i>
                                        {{ __('cruds.user.profile.change_password') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- Lightbox2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">


<style>
.profile-pic {
    background: linear-gradient(130deg, rgb(255, 196, 0, 1), rgb(214, 0, 189));
    /* border: 2px solid #ffffff; */
    margin: 0 auto;
    padding: 4px;
    width: 50%;
}

.profile-pic:hover {
    background: linear-gradient(270deg, rgb(255, 196, 0, 1), rgb(214, 0, 189));
    cursor: pointer;
}
</style>
@endpush

@push('js')
<!-- Lightbox2 JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
@include('master.users.profile.js')
@endpush
