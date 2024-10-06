@extends('layouts.app')

@section('subtitle', __('cruds.program.list') . ' ' . __('cruds.program.title'))
@section('content_header_title', __('cruds.program.list') . ' ' . __('cruds.program.title'))

@section('content_body')

    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="program-list" class="table table-bordered table-striped cell-border ajaxTable datatable-program" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 5%;">No.</th>
                        <th>{{ trans('cruds.program.kode') }}</th>
                        <th>{{ trans('cruds.program.nama') }}</th>
                        <th>{{ trans('cruds.program.tanggalmulai') }}</th>
                        <th>{{ trans('cruds.program.tanggalselesai') }}</th>
                        <th>{{ trans('cruds.program.totalnilai') }}</th>
                        <th>{{ trans('cruds.program.ekspektasipenerimamanfaat') }}</th>
                        <th>{{ trans('cruds.program.ekspektasipenerimamanfaatwoman') }}</th>
                        <th>{{ trans('cruds.program.ekspektasipenerimamanfaatman') }}</th>
                        <th>{{ trans('cruds.program.ekspektasipenerimamanfaatgirl') }}</th>
                        <th>{{ trans('cruds.program.ekspektasipenerimamanfaatboy') }}</th>
                        <th>{{ trans('cruds.program.ekspektasipenerimamanfaattidaklangsung') }}</th>
                        <th>{{ trans('cruds.program.deskripsiprojek') }}</th>
                        <th>{{ trans('cruds.program.analisamasalah') }}</th>
                        <th style="width: 5%;">{{ trans('cruds.status.title') }}</th>
                        <th style="width: 15%;">{{ trans('global.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

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
    
    @include('tr.program.js')
@endpush