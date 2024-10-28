@extends('layouts.app')

{{-- @section('subtitle', __('global.details') . ' ' . __('cruds.program.title_singular')) --}}
@section('subtitle', __('global.details') . ' ' . __('cruds.program.title_singular'). ' '. $program->nama ?? '')
@section('content_header_title', __('cruds.program.outcome.list_program').' '. $program->nama ?? '')
@section('sub_breadcumb', __('cruds.program.title_singular'))

@section('content_body')
<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('cruds.program.outcome.list_program') }}</h3>
            </div>
            <div class="card-body">
                <form action="" class="col s12">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="goal" type="text" class="validate">
                            <label for="goal">{{ __('cruds.program.goals.label') }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="objective" type="text" class="validate">
                            <label for="objective">{{ __('cruds.program.objective.label') }}</label>
                          </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('cruds.program.outcome.label') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    @forelse  ($outcomes as $index => $outcome)
                        <li class="nav-item">
                            <button type="button" class="nav-link btn btn btn-block text-left" data-outcome-id="{{ $outcome->id }}" data-action="load">
                                {{-- <i class="far fa-circle text-danger"></i> --}}
                                {{ __('cruds.program.outcome.out_program') }} {{ $index + 1 }}
                            </button>
                        </li>
                        @empty
                        <div class="nav flex-column nav-tabs h-100">
                            <button type="button" class="btn btn-block"></i>No Outcome</button>
                        </div>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-danger collapsed-card">
            <div class="card-header">
                <h6>{{ __('global.details') . ' ' . __('cruds.program.title_singular') }}</h6>
            </div>
        </div>
        <div class="card-body">
            @foreach ($program->outcome as $out)
                <div class="row">
                    {{ $out }}
                </div>
            @endforeach
        </div>
    </div>
</div>



@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/materialize.css') }}">

@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

<script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>

@endpush
