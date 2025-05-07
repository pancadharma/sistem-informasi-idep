@php
    $action = Route::currentRouteAction();
    $method = $action ? explode('@', $action)[1] ?? '' : '';
@endphp

@extends('layouts.app')

@section('subtitle', __('cruds.target_progress.' . $method))
@section('content_header_title')
	<strong>{{ __('cruds.target_progress.' . $method) }}</strong>
@endsection
@section('sub_breadcumb')
	<a
		href="{{ route('target_progress.index') }}"
		title="{{ __('cruds.target_progress.list') }}"
	>
		{{ __('cruds.target_progress.list') }}
	</a>
@endsection

@section('sub_sub_breadcumb')
	<span>/</span>
	<span title="Current Page {{ __('cruds.target_progress.' . $method) }}">
		{{ __('cruds.target_progress.' . $method) }}
	</span>
@endsection

@section('preloader')
	<i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
	<h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
	<div class="row">
		<div class="col-12 col-sm-12">
			<div class="card card-primary card-tabs">
				<div class="card-header border-bottom-0 card-header p-0 pt-1 navigasi z-index-1">
					<ul class="nav nav-tabs" id="details-target-progress-tab" role="tablist">
						<button type="button" class="btn btn-tool btn-small" data-card-widget="collapse" title="Minimize">
							<i class="bi bi-arrows-collapse"></i>
						</button>
						
						<li class="nav-item">
							<a class="nav-link active" id="target-progress-tab" data-toggle="pill" href="#tab-target-progress" role="tab" aria-controls="tab-target-progress" aria-selected="true">
								{{ __('cruds.target_progress.label') }}
							</a>
						</li>
					</ul>
				</div>
				
				<div class="card-body">
					<div class="tab-content" id="details-target-progress-tabContent">
						<div class="tab-pane fade show active" id="tab-target-progress" role="tabpanel" aria-labelledby="target-progress-tab">
							@include('tr.target_progress._form')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	{{-- Modals --}}
	@include('tr.target_progress.modals._program')
	@include('tr.target_progress.modals._history')
	@include('tr.target_progress.modals._target_progress_history')
@stop

@push('css')
	<link rel="stylesheet" href="{{ asset('vendor/daterangepicker/daterangepicker.css') }}">
	
	@include('tr.target_progress.css.common')
@endpush

@push('js')
	@section('plugins.Sweetalert2', true)
	@section('plugins.DatatablesNew', true)
	@section('plugins.Select2', true)
	@section('plugins.Toastr', true)
	@section('plugins.Validation', true)

	{{-- File Inputs Plugins - to enhance the standard HTML5 input elements --}}
	<script src="{{ asset('vendor/moment/moment.min.js') }}"></script>
	<script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>

	@stack('basic_tab_js')
	<script>const $doc = $(document);</script>
	@include('tr.target_progress.js.target_progress_form_table')
	@include('tr.target_progress.js.history_list_table')
	@include('tr.target_progress.js.program')
	@include('tr.target_progress.js.target_progress_history_table')

	@include('tr.target_progress.js.messages')
@endpush
