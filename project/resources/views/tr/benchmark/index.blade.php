@extends('layouts.app')

@section('subtitle', __('cruds.benchmark.list'))
@section('content_header_title')
        <a class="btn-success btn" href="{{ route('benchmark.create') }}" title="{{ __('cruds.benchmark.add') }}">
            {{ __('global.create') .' '.__('cruds.benchmark.label') }}
        </a>
@endsection
@section('sub_breadcumb', __('cruds.benchmark.list'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-graph-up"></i>
                {{__('cruds.benchmark.list')}}
            </h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('benchmark.create') }}`"
                title="{{ __('global.create') . ' ' . __('cruds.benchmark.label') }}">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <div class="card-body table-responsive">
            <table id="benchmarkTable" class="table responsive-table table-bordered datatable-benchmark" width="100%">
               {{-- Header diambil dari js/index --}}
            </table>
        </div>
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

    @include('tr.benchmark.js.index')
@endpush
