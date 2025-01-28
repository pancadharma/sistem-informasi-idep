@extends('layouts.app')

@section('subtitle', __('cruds.meals.list'))
@section('content_header_title')
    @can('meals_access')
        <a class="btn-success btn" href="{{ route('meals.create') }}" title="{{ __('cruds.meals.add') }}">
            {{ __('global.create') .' '.__('cruds.meals.label') }}
        </a>
    @endcan
@endsection
@section('sub_breadcumb', __('cruds.meals.list'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{__('cruds.meals.list')}}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('meals.create') }}`"
                    title="{{ __('global.create') . ' ' . __('cruds.meals.label') }}">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table id="mealsTable" class="table responsive-table table-bordered datatable-meals" width="100%">
                <thead class="text-nowrap">
                    <tr>
                        <th class="text-center align-middle" style="width: 5%;" data-orderable="false">{{ __('global.no') }}</th>
                        <th>{{__('cruds.meals.nama')}}</th>
                        <th>{{__('cruds.meals.kode')}}</th>
                        <th>{{__('cruds.meals.program_code')}}</th>
                        <th>{{__('cruds.meals.program_name')}}</th>
                        <th>{{__('cruds.meals.description')}}</th>
                        <th>{{__('cruds.meals.achievments')}}</th>
                        <th>{{__('cruds.meals.progress')}}</th>
                        <th>{{__('cruds.meals.to_complete')}}</th>
                        <th>{{__('cruds.meals.status')}}</th>
                        <th>{{__('global.action')}}</th>
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

@include('tr.meals.js.index')
@endpush


