@extends('layouts.page')

{{-- Extend and customize the browser title --}}

@section('title') {{ config('adminlte.title') }}@hasSection('subtitle') | @yield('subtitle') @endif @stop

{{-- Extend and customize the page content header --}}

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
        <li class="breadcrumb-item"><a href="#">{{ trans('global.home') }}</a></li>
        <li class="breadcrumb-item active">@yield('sub_breadcumb')</li>
    </ol>
    @endif
@stop

{{-- Rename section content to content_body --}}

@section('content')
    @yield('content_body') {{-- Will showing where the main content is on CRUD --}}
@stop

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
<script>
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

        $('.select-all').click(function () {
            let $select2 = $(this).parent().siblings('.select2')
            $select2.find('option').prop('selected', 'selected')
            $select2.trigger('change')
        })
        $('.deselect-all').click(function () {
            let $select2 = $(this).parent().siblings('.select2')
            $select2.find('option').prop('selected', '')
            $select2.trigger('change')
        })
    });

    $(document).on('select2:open', function() {
        setTimeout(function() {
            document.querySelector('.select2-search__field').focus();
        }, 100);
    });

    $(function () {
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
</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
{{-- <style type="text/css">

</style> --}}
@endpush
