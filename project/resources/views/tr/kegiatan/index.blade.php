@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.list'))
@section('content_header_title', __('cruds.kegiatan.list'))

@section('content_body')

<div class="card card-outline card-primary">
    @can('kegiatan_create')
    <div class="card-header">
        <a class="pb-0 col-6" href="{{ route('kegiatan.create') }}">
            {{ __('global.create') .' '.__('cruds.kegiatan.label') }}
        </a>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('kegiatan.create') }}`"
                title="{{ __('global.create') . ' ' . __('cruds.kegiatan.label') }}">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    @endcan
    <div class="card-body">
        <table id="kegiatan-list" class="table table-bordered responsive datatable-kegiatan"
            style="width:100%">
            <thead>
                <tr>
                    <th class="text-center align-middle" style="width: 5%;">No.</th>
                    <th>{{ __('cruds.kegiatan.kode') }}</th>
                    <th>{{ __('cruds.kegiatan.nama') }}</th>
                    <th>{{ __('cruds.kegiatan.tanggalmulai') }}</th>
                    <th>{{ __('cruds.kegiatan.tanggalselesai') }}</th>
                    <th>{{ __('cruds.kegiatan.tempat') }}</th>
                    <th>{{ __('cruds.kegiatan.kategori_lokasi') }}</th>
                    <th>{{ __('cruds.kegiatan.luaslahan') }}</th>
                    <th>{{ __('cruds.kegiatan.jenisbantuan') }}</th>
                    <th>{{ __('cruds.kegiatan.status') }}</th>
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

@include('tr.kegiatan.js')
@endpush
