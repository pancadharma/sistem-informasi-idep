@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.program.title_singular'))
@section('content_header_title', __('global.details') . ' ' . __('cruds.program.title_singular'))
@section('sub_breadcumb', __('cruds.program.title_singular'))

@section('content_body')
<div class="row">
    <div class="col-md-6">
        <div class="card card-info collapsed-card">
            <div class="card-header">
                <h6>{{ __('global.details') . ' ' . __('cruds.program.title_singular') }}</h6>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <form class="col s12">
                  <div class="row">
                    <div class="input-field col s6">
                      <input id="first_name" type="text" class="validate">
                      <label for="first_name">First Name</label>
                    </div>
                    <div class="input-field col s6">
                      <input id="last_name" type="text" class="validate">
                      <label for="last_name">Last Name</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <input disabled value="I am not editable" id="disabled" type="text" class="validate">
                      <label for="disabled">Disabled</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <input id="password" type="password" class="validate">
                      <label for="password">Password</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="input-field col s12">
                      <input id="email" type="email" class="validate">
                      <label for="email">Email</label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col s12">
                      This is an inline input field:
                      <div class="input-field inline">
                        <input id="email_inline" type="email" class="validate">
                        <label for="email_inline">Email</label>
                        <span class="helper-text" data-error="wrong" data-success="right">Helper text</span>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-danger collapsed-card">
            <div class="card-header">
                <h6>{{ __('global.details') . ' ' . __('cruds.program.title_singular') }}</h6>
            </div>
        </div>
    </div>
</div>



@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

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
