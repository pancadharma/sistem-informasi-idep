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
        <li class="breadcrumb-item"><a href="#">Home</a></li>
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
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>Copyright &copy; 2014 - {{ date('Y') }}
        <a href="{{ config('app.company_url', '/') }}">
            {{ config('app.company_name', 'IDEP Foundation') }}
        </a>
    </strong>. All rights reserved.
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
<script>

    $(document).ready(function() {
        // Add your common script logic here...
    });

</script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
<style type="text/css">

    {{-- You can add AdminLTE customizations here --}}
    /*
    .card-header {
        border-bottom: none;
    }
    .card-title {
        font-weight: 600;
    }
    */

</style>
@endpush