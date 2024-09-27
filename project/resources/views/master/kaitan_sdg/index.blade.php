@extends('layouts.app')

@section('subtitle', __('cruds.kaitan_sdg.list') . ' ' . __('cruds.kaitan_sdg.title'))
@section('content_header_title', __('cruds.kaitan_sdg.list') . ' ' . __('cruds.kaitan_sdg.title'))

@section('content_body')
    @include('master.kaitan_sdg.create')

    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="kaitan_sdg_list" class="table table-bordered table-striped cell-border ajaxTable datatable-kaitan_sdg" style="width:100%">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th>{{trans('cruds.kaitan_sdg.nama')}}</th>
                        <th style="width: 5%;">{{ trans('cruds.status.title') }}</th>
                        <th style="width: 15%;">{{ trans('global.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.kaitan_sdg.edit')
    @include('master.kaitan_sdg.show')
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
    
    @include('master.kaitan_sdg.js')
@endpush