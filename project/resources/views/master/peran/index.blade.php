@extends('layouts.app')

@section('subtitle', __('cruds.peran.list') . ' ' . __('cruds.peran.title'))
@section('content_header_title', __('cruds.peran.list') . ' ' . __('cruds.peran.title'))

@section('content_body')
    @include('master.peran.create')

    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="peran-list" class="table table-bordered table-striped cell-border ajaxTable datatable-peran" style="width:100%">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>{{ trans('cruds.peran.nama') }}</th>
                        <th style="width: 5%;">{{ trans('cruds.status.title') }}</th>
                        <th style="width: 15%;">{{ trans('global.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.peran.edit')
    @include('master.peran.show')
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
    
    @include('master.peran.js')
@endpush