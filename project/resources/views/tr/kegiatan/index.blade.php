@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.list'))
{{--@section('content_header_title', __('cruds.kegiatan.info'))--}}
@section('content_header_title')
    @can('kegiatan_access')
        <a class="btn-success btn" href="{{ route('kegiatan.create') }}" title="{{ __('cruds.kegiatan.add') }}">
            {{ __('global.create') .' '.__('cruds.kegiatan.label') }}
        </a>
    @endcan
@endsection
@section('sub_breadcumb', __('cruds.kegiatan.list'))
@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{__('cruds.kegiatan.list')}}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('kegiatan.create') }}`"
                    title="{{ __('global.create') . ' ' . __('cruds.kegiatan.label') }}">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <table id="kegiatan-list" class="table table-bordered responsive datatable-kegiatan"
                style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 5%;">No.</th>
                        <th>{{ __('cruds.program.nama') }}</th>
                        <th>{{ __('cruds.kegiatan.nama') }}</th>
                        <th>{{ __('cruds.desa.form.nama') }}</th>
                        <th>{{ __('cruds.kegiatan.tanggalmulai') }}</th>
                        <th>{{ __('cruds.kegiatan.tanggalselesai') }}</th>
                        <th>{{ __('cruds.kegiatan.tempat') }}</th>
    {{--                    <th>{{ __('cruds.kegiatan.kategori_lokasi') }}</th>--}}
    {{--                    <th>{{ __('cruds.kegiatan.luaslahan') }}</th>--}}
    {{--                    <th>{{ __('cruds.kegiatan.jenisbantuan') }}</th>--}}
                        <th>{{ __('cruds.kegiatan.status') }}</th>
                        <th>{{ __('global.actions') }}</th>
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
