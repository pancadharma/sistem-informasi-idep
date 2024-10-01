@extends('layouts.app')

@section('subtitle', __('cruds.program.list'))
@section('content_header_title', __('cruds.program.title'))
@section('sub_breadcumb', __('cruds.program.title'))

@section('content_body')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary collapsed-card">
            <div class="card-header">
                <div class="card-tools">
                    @can('program_create')
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                    @endcan
                </div>
                <h6>{{ trans('global.create')}} {{trans('cruds.program.title_singular')}}</h6>
            </div>
        </div>
    </div>
</div>
{{-- <form action="">
    <div class="row">
        <div class="card-body">
            <div class="card card-outline card-primary">
                <div class="card-header">

                </div>
                <div class="card-body table-responsive">

                </div>
            </div>
        </div>
    </div>
</form> --}}
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                {{ __('cruds.program.info_dasar') }}
            </div>
            <div class="card-body table-responsive">
                
            </div>
        </div>
    </div>
</div>

    @stop

    @push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @endpush

    @push('js')
    @section('plugins.Sweetalert2', true)
    {{-- @section('plugins.DatatablesNew', true) --}}
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)
    @include('tr.program.js')

<script>
    //SCRIPT FOR CREATE PROGRAM FORM
    $(document).ready(function() {




    });



</script>
@endpush
