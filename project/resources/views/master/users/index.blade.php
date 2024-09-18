@extends('layouts.app')

@section('subtitle', __('cruds.user.list'))
@section('content_header_title', __('cruds.user.title'))
@section('sub_breadcumb', __('cruds.user.title'))

@section('content_body')
    @include('master.users.create')
    <div class="container-fluid content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-muted">{{ trans('cruds.user.list')}}</h1>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="users_list" class="table table-bordered table-striped table-hover dataTable dtr-inline">
                <thead>
                    <tr>
                        <th class="text-center align-middle">{{ trans('cruds.user.fields.name') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.user.fields.username') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.user.fields.email') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.user.fields.roles') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.status.title') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    

    @include('master.users.edit')
    @include('master.users.show')
    @stop
    
    @push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @endpush
    
    @push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)
    
    @include('master.users.js')
@endpush