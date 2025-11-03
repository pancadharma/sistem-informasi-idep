@extends('layouts.page')

{{-- Extend and customize the browser title --}}

@section('title') {{ config('adminlte.title') }}@hasSection('subtitle') | @yield('subtitle') @endif @stop

{{-- Extend and customize the page content header --}}
<head>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>

@section('content_header')
@hasSection('content_header_title')
<h1 class="text-muted">
    @yield('content_header_title')
    {{--
                @hasSection('content_header_subtitle')
                <small class="text-dark"><i class="fas fa-xs fa-angle-right text-muted"></i>@yield('content_header_subtitle')</small>
            @endif
            --}}
</h1>
@endif
@stop
{{-- Add Right Breadcumb Max 2 Item With Static Home Breadchumb --}}
@section('breadcumb')
@hasSection ('sub_breadcumb')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="/">{{ __('global.home') }}</a></li>
    <li class="breadcrumb-item active">@yield('sub_breadcumb')</li>
</ol>
@endif
@stop

{{-- Rename section content to content_body --}}

@section('content')
@yield('content_body') {{-- Will showing where the main content is on CRUD --}}
@stop
@extends('layouts.responsive-btn')
{{-- Create a common footer --}}

@section('footer')
<div class="float-right">Version: {{ config('app.version', '1.0.0') }}</div>
<strong>Copyright &copy; 2014 - {{ date('Y') }}
    <a href="{{ config('app.company_url', '/') }}">{{ config('app.company_name', 'IDEP Foundation') }}</a>
</strong>. All rights reserved.
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
<!-- Script to LOG User Activity (Advance Mode) -->
<!-- <script src="https://cdn.lrkt-in.com/LogRocket.min.js" crossorigin="anonymous"></script>
<script>
window.LogRocket && window.LogRocket.init('lcft8s/idep');
LogRocket.identify('{{ Auth::user()->id }}', {
    name: '{{ Auth::user()->nama ?? "-" }}',
    email: '{{ Auth::user()->username ?? "-" }}',
    email: '{{ Auth::user()->email ?? "-" }}',
    // Add your own custom user variables here, ie:
    jabatan: '{{ Auth::user()->jabatan->nama ?? "-" }}'
});
</script> -->
<script>
function ErrorMessagesFormat(errors) {
    let message = '<ul>';
    for (const field in errors) {
        errors[field].forEach(function(error) {
            message += `<li>${error}</li>`;
        });
    }
    message += '</ul>';
    return message;
}
$(document).ready(function() {
    window._token = $('meta[name="csrf-token"]').attr('content')

    // moment.updateLocale('en', {
    //     week: {dow: 1} // Monday is the first day of the week
    // })

    // $('.date').datetimepicker({
    //     format: 'YYYY-MM-DD',
    //     locale: 'en',
    //     icons: {
    //         up: 'fas fa-chevron-up',
    //         down: 'fas fa-chevron-down',
    //         previous: 'fas fa-chevron-left',
    //         next: 'fas fa-chevron-right'
    //     }
    // })

    // $('.datetime').datetimepicker({
    //     format: 'YYYY-MM-DD HH:mm:ss',
    //     locale: 'en',
    //     sideBySide: true,
    //     icons: {
    //         up: 'fas fa-chevron-up',
    //         down: 'fas fa-chevron-down',
    //         previous: 'fas fa-chevron-left',
    //         next: 'fas fa-chevron-right'
    //     }
    // })

    // $('.timepicker').datetimepicker({
    //     format: 'HH:mm:ss',
    //     icons: {
    //         up: 'fas fa-chevron-up',
    //         down: 'fas fa-chevron-down',
    //         previous: 'fas fa-chevron-left',
    //         next: 'fas fa-chevron-right'
    //     }
    // })

    $('.select-all').click(function() {
        let $select2 = $(this).parent().siblings('.select2')
        $select2.find('option').prop('selected', 'selected')
        $select2.trigger('change')
    })
    $('.deselect-all').click(function() {
        let $select2 = $(this).parent().siblings('.select2')
        $select2.find('option').prop('selected', '')
        $select2.trigger('change')
    })
});

// $(document).on('select2:open', function() {
//     setTimeout(function() {
//         document.querySelector('.select2-search__field').focus();
//     }, 100);
// });

$(document).on('focus', '.select2-selection.select2-selection--single', function(e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
});
// steal focus during close - only capture once and stop propogation
$('select.select2').on('select2:closing', function(e) {
    $(e.target).data("select2").$selection.one('focus focusin', function(e) {
        e.stopPropagation();
    });
});

$(function() {
    $('[data-toggle="tooltip"]').tooltip()
});

const Toast = Swal.mixin({
    toast: true,
    showConfirmButton: false,
    // timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});
$.fn.dataTable.ext.errMode = 'throw';
</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
    <style type="text/css">
        .select2 {
            width:100%!important;
        }
        .form-control:focus {
            border-color: #d9edf7;
            -webkit-box-shadow: 0 0 0px 2px rgb(87 204 193 / 72%);
            box-shadow: 0 0 0px 2px rgb(87 204 193 / 72%);
        }
        .modal-dialog-scrollable .modal-body{
            overflow-x: clip!important;
        }
    </style>
@endpush
