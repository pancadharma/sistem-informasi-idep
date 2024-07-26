@extends('layouts.app')

@section('subtitle', __('cruds.kabupaten.list')) {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', __('cruds.kabupaten.list')) {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', __('cruds.kabupaten.title')) {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

@section('content_body')
    <div class="row mb-2">
        <div class="col-lg-6">
            <x-adminlte-button label="{{ trans('global.add') }} {{ trans('cruds.kabupaten.title_singular') }}" data-toggle="modal" data-target="#addKabupaten" class="bg-success add-kabupaten"/>
        </div>
    </div>
    <div class="card card-outline card-primary">
        <div class="card-body">
            <table id="kabupaten" class="table table-bordered cell-border ajaxTable datatable-kabupaten" style="width:100%">
                <thead>
                    <tr>
                        <th class="center">{{ trans('cruds.kabupaten.kode') }}</th>
                        <th>DT II</th>
                        <th>{{ trans('cruds.kabupaten.title') }}, {{ trans('cruds.kabupaten.kota') }}</th>
                        <th>{{ trans('cruds.provinsi.title') }}</th>
                        <th>{{ trans('cruds.status.title') }}</th>
                        <th>{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('master.kabupaten.create')
    @include('master.kabupaten.edit')
    @include('master.kabupaten.show')
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
    
    @include('master.kabupaten.js')
@endpush
