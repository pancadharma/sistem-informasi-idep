@extends('layouts.app')

@section('subtitle', __('cruds.partner.list'))
@section('content_header_title', __('cruds.partner.list'))
@section('sub_breadcumb', __('cruds.partner.title'))

@section('content_body')
    @include('master.partner.create')
    <div class="card card-outline card-primary">
        <div class="card-body table-responsive-sm">
            <table id="partner_list" class="table table-bordered table-striped table-hover row-border display compact responsive nowrap" width="100%">
                <thead class="">
                    <tr>
                        <th class="text-center align-middle">No.</th>
                        <th class="text-center align-middle">{{ trans('cruds.partner.fields.nama') }}</th>
                        {{-- <th class="text-center align-middle">{{ trans('cruds.partner.fields.ket') }}</th> --}}
                        <th class="text-center align-middle">{{ trans('cruds.status.title') }}</th>
                        <th class="text-center align-middle">{{ trans('cruds.status.action') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('master.partner.edit')
    @include('master.partner.show')
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

    @include('master.partner.js')
    @endpush
