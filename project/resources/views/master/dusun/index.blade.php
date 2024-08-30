@extends('layouts.app')

@section('subtitle', __('cruds.dusun.list'))
@section('content_header_title', __('cruds.dusun.title'))
@section('sub_breadcumb', __('cruds.dusun.title'))

@section('content_body')
    @include('master.dusun.create')
    <div class="container-fluid content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-muted">{{ trans('cruds.dusun.list')}}</h1>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="dusun_list" class="table table-bordered table-striped table-hover ajaxTable datatable-dusun">
                <thead>
                    <tr>
                        <th class="text-center align-middle">{{ trans('cruds.dusun.form.kode') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.dusun.form.nama') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.dusun.form.des') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.dusun.form.kode_pos') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.status.title') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    

    @include('master.dusun.edit')
    @include('master.dusun.show')
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
    
    @include('master.dusun.js')
@endpush
