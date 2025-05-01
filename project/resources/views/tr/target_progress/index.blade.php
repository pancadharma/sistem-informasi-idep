@extends('layouts.app')

@section('subtitle', __('cruds.target_progress.list'))
@section('content_header_title')
    <a class="btn-success btn" href="{{ route('target_progress.create') }}" title="{{ __('cruds.target_progress.add') }}">
        {{ __('global.create') .' '.__('cruds.target_progress.label') }}
    </a>
@endsection
@section('sub_breadcumb', __('cruds.target_progress.list'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-graph-up"></i>
                {{__('cruds.target_progress.list')}}
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('target_progress.create') }}`"
                    title="{{ __('global.create') . ' ' . __('cruds.target_progress.label') }}">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        {{-- INDEX - Target & Progress Table --}}
        <div class="card-body">
            <table id="target_progress_table" class="table responsive-table table-bordered datatable-target_progress" width="100%">
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

@include('tr.target_progress.js.index')
@endpush


