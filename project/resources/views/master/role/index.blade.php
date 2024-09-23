@extends('layouts.app')

@section('subtitle', __('cruds.role.list'))
@section('content_header_title', __('cruds.role.list'))
@section('sub_breadcumb', __('cruds.role.title'))

@section('content_body')
    @include('master.role.create')
    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
            <table id="role_list" class="table table table-bordered table-striped table-hover row-border display compact responsive nowrap" width="100%">
                <thead>
                    <tr>
                        <th class="text-center align-middle">{{ trans('cruds.role.fields.nama') }}</th>
                        {{-- <th class="text-center align-middle">{{ trans('cruds.role.fields.permissions') }}</th> --}}
                        <th class="text-center align-middle">{{ trans('cruds.status.title') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.role.edit')
    @include('master.role.show')
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

    @include('master.role.js')
    @endpush
