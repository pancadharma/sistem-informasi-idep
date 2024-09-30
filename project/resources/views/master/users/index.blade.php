@extends('layouts.app')

@section('subtitle', __('cruds.user.list'))
@section('content_header_title', __('cruds.user.list'))
@section('sub_breadcumb', __('cruds.user.title'))

@section('content_body')
    @include('master.users.create')

    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
            <table id="users_list" class="table table-bordered table-striped table-hover row-border display compact responsive nowrap">
                <thead>
                    <tr>
                        <th class="text-center align-middle">#</th>
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
    @include('master.users.show-modal')
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
