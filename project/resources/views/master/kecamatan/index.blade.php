@extends('layouts.app')

@section('subtitle', __('cruds.kecamatan.list'))
@section('content_header_title', __('cruds.kecamatan.title'))
@section('sub_breadcumb', __('cruds.kecamatan.title'))

@section('content_body')
    @include('master.kecamatan.create')

    <div class="container-fluid content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-muted">{{ trans('cruds.kecamatan.list')}}</h1>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="kecamatan_list" class="table table-bordered cell-border ajaxTable datatable-kecamatan" style="width:100%">
                <thead>
                    <tr>
                        <th class="center">{{ trans('cruds.kecamatan.kode') }}</th>
                        <th>{{ trans('cruds.kecamatan.nama') }}</th>
                        <th>{{ trans('cruds.kabupaten.title') }}</th>
                        <th>{{ trans('cruds.status.title') }}</th>
                        <th>{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- @include('master.kecamatan.edit') --}}
    @include('master.kecamatan.show')
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
    
    @include('master.kecamatan.js')
@endpush
