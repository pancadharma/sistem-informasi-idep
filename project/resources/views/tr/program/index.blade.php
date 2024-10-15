@extends('layouts.app')

@section('subtitle', __('cruds.program.list') . ' ' . __('cruds.program.title'))
@section('content_header_title', __('cruds.program.list') . ' ' . __('cruds.program.title'))

@section('content_body')

    <div class="card card-outline card-primary">
        @can('program_create')
            <div class="card-header">
                <a class="pb-0" href="{{ route('program.create') }}" class="col-6">
                    {{ trans('global.create') }} {{ trans('cruds.peran.title') }}
                </a>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" onclick="window.location.href='{{ route('program.create') }}'"
                        title="{{ __('global.create') . ' ' . __('cruds.program.title') }}">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        @endcan
        <div class="card-body">
            <table id="program-list" class="table table-bordered table-striped cell-border ajaxTable datatable-program"
                style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 5%;">No.</th>
                        <th>{{ __('cruds.program.kode') }}</th>
                        <th>{{ __('cruds.program.nama') }}</th>
                        <th>{{ __('cruds.program.tanggalmulai') }}</th>
                        <th>{{ __('cruds.program.tanggalselesai') }}</th>
                        <th>{{ __('cruds.program.totalnilai') }}</th>
                        <th>{{ __('cruds.program.ekspektasipenerimamanfaat') }}</th>
                        <th>{{ __('cruds.program.ekspektasipenerimamanfaatwoman') }}</th>
                        <th>{{ __('cruds.program.ekspektasipenerimamanfaatman') }}</th>
                        <th>{{ __('cruds.program.ekspektasipenerimamanfaatgirl') }}</th>
                        <th>{{ __('cruds.program.ekspektasipenerimamanfaatboy') }}</th>
                        <th>{{ __('cruds.program.ekspektasipenerimamanfaattidaklangsung') }}</th>
                        <th>{{ __('cruds.program.deskripsiprojek') }}</th>
                        <th>{{ __('cruds.program.analisamasalah') }}</th>
                        <th>{{ __('cruds.status.title') }}</th>
                        <th>{{ __('global.action') }}</th>
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
