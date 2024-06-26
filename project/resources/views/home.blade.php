@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Dashboard') {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', 'Dashboard') {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('content_header_subtitle', 'Welcome ') {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

{{-- Content body: main page content --}}

@section('content_body')
    <p>Welcome to this beautiful admin panel.</p>
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    {{-- <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script> --}}
@endpush