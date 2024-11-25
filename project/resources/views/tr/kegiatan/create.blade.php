@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.add'))
{{--@section('content_header_title', __('cruds.kegiatan.add'))--}}
@section('content_header_title')<strong> <i class="fas fa-edit"></i>{{ __('cruds.kegiatan.add') }}</strong> @endsection
@section('sub_breadcumb')<a href="{{ route('kegiatan.index') }}" title="{{ __('cruds.kegiatan.list') }}"> {{ __('cruds.kegiatan.list') }} </a> @endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.kegiatan.add') }}">{{ __('cruds.kegiatan.add') }}</span> @endsection

@section('content_body')
    <form id="createKegiatan" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <div class="row">
            @include('tr.kegiatan.tabs')
        </div>

        {{-- <div class="row"> --}}
            {{-- <div class="col-sm-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <strong><i class="fas fa-edit"></i>
                            {{ __('cruds.kegiatan.add') }}
                        </strong>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mt-2">
                                     @include('tr.kegiatan.tabs')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-success btn-flat">
                                        <i class="bi bi-save"></i>
                                        {{ __('cruds.kegiatan.add') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        {{-- </div> --}}
    </form>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

<script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/buffer.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/plugins/piexif.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('vendor/krajee-fileinput/js/locales/id.js') }}"></script>



@stack('basic_tab_js')

@include('tr.kegiatan.js.create')
{{-- @include('tr.kegiatan.js.detail-create.donor')
@include('tr.kegiatan.js.detail-create.lokasi')
@include('tr.kegiatan.js.detail-create.staff')
@include('tr.kegiatan.js.detail-create.reportschedule')
@include('tr.kegiatan.js.detail-create.outcome')
@include('tr.kegiatan.js.detail-create.partner') --}}
@endpush
