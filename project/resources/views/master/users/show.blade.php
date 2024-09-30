@extends('layouts.app')

@section('subtitle', __('cruds.user.title_singular'))
@section('content_header_title', /* __('cruds.user.title_singular') .' '.  */$user->nama)
@section('sub_breadcumb', __('cruds.user.title_singular'))

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-body">
        <div class="row">
            <table class="table table-bordered table-striped table-hover dtr-inline">
                <tbody>
                    <tr>
                        <th>{{ __('cruds.user.fields.name') }}</th>
                        <td>{{ $user->nama }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.user.fields.username') }}</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.user.fields.email') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                    <tr>
                        <th>{{ __('cruds.user.fields.jabatan') }}</th>
                        <td>{{ $user->jabatans->nama }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.user.fields.roles') }}</th>
                        <td>
                            @foreach($user->roles as $key => $roles)
                                <span class="btn-xs bg-warning">{{ $roles->nama }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.status.title') }}</th>
                        <td>
                            <div class="icheck-primary">
                                <input type="checkbox" name="aktif" id="aktif" {{ $user->aktif == 1 ? 'checked' : '' }} value="{{ $user->aktif}}">
                                <label for="show-aktif">{{ $user->aktif == 1 ? 'Active' : 'Not Active' }}</label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>

@can('user_show')
    <a href="{{ route('users.index') }}" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
        <span class="fas fa-user-plus"></span>{{ __('global.back') }}
    </a>
@endcan


@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush
