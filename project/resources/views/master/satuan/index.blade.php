@extends('layouts.app')

@section('subtitle', __('cruds.satuan.list'))
@section('content_header_title', __('cruds.satuan.list'))
@section('sub_breadcumb', __('cruds.satuan.title'))

@section('content_body')
    @include('master.satuan.create')
    <div class="card card-outline card-primary">
        <div class="card-body table-responsive">
            <table id="satuan_list" class="table table table-bordered table-striped table-hover row-border display compact responsive nowrap" width="100%">
                {{-- <thead class="bg-dark"> --}}
                 <thead>  
                    <tr>
                        <th class="text-center align-middle"><strong>No.</strong></th>
                        <th class="align-middle">{{ trans('cruds.satuan.fields.nama_satuan') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.status.title') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.satuan.edit')
    @include('master.satuan.show')
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

    @include('master.satuan.js')
    @endpush
