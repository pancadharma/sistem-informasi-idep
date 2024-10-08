@extends('layouts.app')

@section('subtitle', __('cruds.provinsi.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.provinsi.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.provinsi.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="row mb-2">
        <div class="col-lg-12">
            @include('master.provinsi.create')
        </div>
    </div>
    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
            <table id="provinsi" class="table table table-bordered table-striped table-hover row-border display compact responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th width="10">No.</th>
                        <th class="center align-middle">{{ trans('cruds.provinsi.kode') }} {{ trans('cruds.provinsi.title') }}</th>
                        <th class="center align-middle">{{ trans('cruds.provinsi.nama') }}</th>
                        <th class="center align-middle">{{ trans('cruds.status.title') }}</th>
                        <th class="center align-middle">{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.provinsi.edit')
    @include('master.provinsi.show')
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Validation', true)

    {{-- JS --}}
    @include('master.provinsi.js')

@endpush
