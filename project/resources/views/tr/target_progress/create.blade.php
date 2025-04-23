@extends('layouts.app')

@section('subtitle', __('cruds.target_progress.add'))
@section('content_header_title') <strong>{{ __('cruds.target_progress.add') }}</strong>  @endsection
@section('sub_breadcumb')<a href="{{ route('target_progress.index') }}" title="{{ __('cruds.target_progress.list') }}"> {{ __('cruds.target_progress.list') }} </a> @endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.target_progress.add') }}">{{ __('cruds.target_progress.add') }}</span> @endsection

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
    @include('tr.target_progress.modals.program')
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
    <style>
        .card-header.border-bottom-0.card-header.p-0.pt-1.navigasi {
            position: sticky;
            z-index: 100;
            top: 0;
        }
        .mw-100-px{
            min-width: 100px!important;
        }
        .mw-200-px{
            min-width: 200px!important;
        }
        .mw-300-px{
            min-width: 300px!important;
        }
        .input-group{
            flex-wrap: nowrap;
        }
        .bg-25-warning{
            background-color: #fff1d0 !important;
        }
        .bg-25-success{
            background-color: #d8ead2 !important;
        }

        .target-progress-status, .target-progress-risk{
            background-color: #e9eaed;
            width: 100%;
            position: relative;
            display: block;
            white-space: nowrap;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .target-progress-status.selected,
        .target-progress-risk.selected{
            padding-top: 0;
            padding-bottom: 0;
            font-size: inherit;
            line-height: inherit;
            z-index: 0;
        }
        .select2-selection__clear{
            z-index: 1;
        }

        .target-progress-status.opt-to_be_conducted{
            background-color: #743802;
            color: #FFF;
        }
        .target-progress-status.opt-completed{
            background-color: #0f744b;
            color: #FFF;
        }
        .target-progress-status.opt-ongoing{
            background-color: #ffe59f;
            color: inherit;
        }

        .target-progress-risk.opt-none{
            background-color: #c2e1f2;
            color: #537ab8;
        }
        .target-progress-risk.opt-medium{
            background-color: #ffe59f;
            color: inherit;
        }
        .target-progress-risk.opt-high{
            background-color: #b00202;
            color: #FFF;
        }
    </style>
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)

    {{-- File Inputs Plugins - to enhance the standard HTML5 input elements --}}
    <script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
    <script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
    <script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>


    @stack('basic_tab_js')
    @include('tr.target_progress.js.target_progress_form_table')
    @include('tr.target_progress.js.program')
@endpush
