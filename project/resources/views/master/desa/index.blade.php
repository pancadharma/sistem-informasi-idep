@extends('layouts.app')

@section('subtitle', __('cruds.desa.list'))
@section('content_header_title', __('cruds.desa.title'))
@section('sub_breadcumb', __('cruds.desa.title'))

@section('content_body')
    @include('master.desa.create')
    <div class="container-fluid content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-muted">{{ trans('cruds.desa.list')}}</h1>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="desa_list" class="table table-bordered cell-border ajaxTable datatable-desa" style="width:100%">
                <thead>
                    <tr>
                        <th class="center">{{ trans('cruds.desa.form.kode') }}</th>
                        <th>{{ trans('cruds.desa.form.nama') }}</th>
                        <th>{{ trans('cruds.desa.form.kec') }}</th>
                        <th>{{ trans('cruds.status.title') }}</th>
                        <th>{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.desa.edit')
    @include('master.desa.show')
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
    
    @include('master.desa.js')
@endpush
