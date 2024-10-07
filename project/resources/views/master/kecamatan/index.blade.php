@extends('layouts.app')

@section('subtitle', __('cruds.kecamatan.list'))
@section('content_header_title', __('cruds.kecamatan.title'))
@section('sub_breadcumb', __('cruds.kecamatan.title'))

@section('content_body')
    @include('master.kecamatan.create')

    <div class="container-fluid content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="text-muted">{{ __('cruds.kecamatan.list')}}</h1>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
            <table id="kecamatan_list" class="table table-bordered table-striped table-hover row-border display compact responsive nowrap ajaxTable datatable-kecamatan" style="width:100%">
                <thead>
                    <tr>
                        <th class="center align-middle">No.</th>
                        <th class="center align-middle">{{ __('cruds.kecamatan.kode') }}</th>
                        <th class="center align-middle">{{ __('cruds.kecamatan.nama') }}</th>
                        <th class="center align-middle">{{ __('cruds.kabupaten.title') }}</th>
                        <th class="center align-middle">{{ __('cruds.status.title') }}</th>
                        <th class="center align-middle">{{ __('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.kecamatan.edit')
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
