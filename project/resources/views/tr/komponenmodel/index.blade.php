@extends('layouts.app')

@section('subtitle', __('cruds.komponenmodel.list'))
@section('content_header_title')
    @can('komponenmodel_access')
        <a class="btn-success btn" href="{{ route('komodel.create') }}" title="{{ __('cruds.komponenmodel.add') }}">
            {{ __('global.create') .' '.__('cruds.komponenmodel.label') }}
        </a>
    @endcan
@endsection
@section('sub_breadcumb', __('cruds.komponenmodel.list'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-graph-up"></i>
                {{__('cruds.komponenmodel.list')}}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('komodel.create') }}`"
                    title="{{ __('global.create') . ' ' . __('cruds.komponenmodel.label') }}">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table id="komponenmodelTable" class="table responsive-table table-bordered datatable-komponenmodel" width="100%">
               {{-- Header diambil dari js/index --}}
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

@include('tr.komponenmodel.js.index')
@endpush


