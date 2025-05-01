@extends('layouts.app')

@section('subtitle', __('cruds.komponenmodel.add'))
@section('content_header_title') <strong>{{ __('cruds.komponenmodel.add') }}</strong>  @endsection
@section('sub_breadcumb')<a href="{{ route('komodel.index') }}" title="{{ __('cruds.komponenmodel.list') }}"> {{ __('cruds.komponenmodel.list') }} </a> @endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.komponenmodel.add') }}">{{ __('cruds.komponenmodel.add') }}</span> @endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <form id="createKOMODEL" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="row">
            @include('tr.komponenmodel.tabs') 
        </div>
    </form>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/krajee-fileinput/css/fileinput.min.css') }}">
    <style>
        .card-header.border-bottom-0.card-header.p-0.pt-1.navigasi {
            position: sticky;
            z-index: 1045;
            top: 0;
        }
        .wah {
            display: grid;
            align-content: space-around;
            justify-content: center;
            align-items: center;
            justify-items: stretch;
        }
    </style>
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




{{-- @include('tr.komponenmodel.js.create')  --}}

@stack('basic_tab_js')
@include('tr.komponenmodel.js.komodel')
@include('tr.komponenmodel.js.program')
@include('tr.komponenmodel.tabs.program')
@include('tr.komponenmodel.tabs.tambahkomponen-modal')
@include('api.master.dusun')



@endpush
