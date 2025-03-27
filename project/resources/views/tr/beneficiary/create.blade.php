@extends('layouts.app')

@section('subtitle', __('cruds.beneficiary.add'))
@section('content_header_title') <strong>{{ __('cruds.beneficiary.add') }}</strong>  @endsection
@section('sub_breadcumb')<a href="{{ route('beneficiary.index') }}" title="{{ __('cruds.beneficiary.list') }}"> {{ __('cruds.beneficiary.list') }} </a> @endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('cruds.beneficiary.add') }}">{{ __('cruds.beneficiary.add') }}</span> @endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <form id="createMEALS" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="row">
            @include('tr.beneficiary.tabs')
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

        .select2-container--open .select2-dropdown {
            top: 100% !important; /* Force dropdown to appear below */
            bottom: auto !important;
        }

        .modal {
            overflow: visible !important; /* Ensure modal doesn’t clip content */
        }

        .modal-dialog {
            overflow: visible !important; /* Allow dropdown to extend outside dialog */
        }

        .modal-content {
            overflow: visible !important; /* Prevent content from hiding dropdown */
        }

        .select2-container--open .select2-dropdown {
            z-index: 1056; /* Match or exceed modal z-index (Bootstrap default is 1050) */
        }

                /* Sorting indicators */
        th.asc::after {
            content: ' ↑';
            color: #333;
        }

        th.desc::after {
            content: ' ↓';
            color: #333;
        }

        .responsive-table {
            overflow-x: visible;
            overflow-y: visible;
        }

        .ellipsis-cell {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px; /* Adjust as needed */
            /* display: block; Or display: block */
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
<script>
    // Fix dropdown position when modal is shown
    // $('.modal').on('shown.bs.modal', function() {
    //     $(this).find('.select2-container').each(function() {
    //         const $select = $(this).prev('select'); // Get the associated select element
    //         $select.select2('close'); // Close any open dropdown
    //         $select.select2('open');  // Reopen to recalculate position
    //         $select.select2('close'); // Close again to avoid flicker (optional)
    //     });
    // });

    // Recalculate position on window resize (e.g., Inspect Element)
    $(window).on('resize', function() {
        $('.select2-container--open').each(function() {
            const $select = $(this).prev('select');
            $select.select2('close');
            $select.select2('open');
        });
    });
</script>



@include('tr.beneficiary.js.create')
@include('tr.beneficiary.js.search')

@stack('basic_tab_js')
@include('tr.beneficiary.js.beneficiaries')
@include('tr.beneficiary.js.program')
@include('tr.beneficiary.tabs.program')
@include('api.master.dusun')

@include('api.master.jenis-kelompok-instansi')

@endpush
