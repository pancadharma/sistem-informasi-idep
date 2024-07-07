@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Dashboard') {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', 'Dashboard') {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb','') {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

{{-- Content body: main page content --}}

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">@lang('crud.country.list')</h3>
        </div>
        <div class="card-body">
            <table id="" class="table table-striped table-bordered">

            </table>
        </div>
        <div class="card-footer">
            The footer of the card
        </div>
    </div>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    {{-- call custom plugins js so not all pages load the JS --}}
    @section('plugins.Datatables', true) 
    {{-- @section('plugins.Select2', true) --}}
    {{-- @section('plugins.DateRangePicker', true) --}}
    <script type="text/javascript">
        $(document).ready(function() {
        $('#').DataTable();
    } );
    </script>
@endpush