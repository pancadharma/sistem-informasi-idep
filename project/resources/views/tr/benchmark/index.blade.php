@extends('layouts.app')

@section('subtitle', 'Daftar Benchmark')

@section('content_header_title')
<div class="d-flex align-items-center">
    <a class="btn btn-success mr-3 w-25" href="{{ route('benchmark.create') }}" id="btnTambahBenchmark">
        Tambah Benchmark
    </a>
    <select class="form-control w-auto" id="filterProgram">
        <option value="">Filter Program</option>
        {{-- Data program akan diisi via AJAX --}}
    </select>
</div>
@endsection

@section('sub_breadcumb', 'Daftar Benchmark')

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content_body')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="bi bi-graph-up"></i>
                Daftar Benchmark
            </h3>
        </div>

        <div class="card-body table-responsive">
            <table id="benchmarkTable" class="table table-bordered table-striped w-100">
                <thead class="text-center">
                    <tr>
                        <th>Program</th>
                        <th>Tipe Kegiatan</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Implementasi</th>
                        <th>Score</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush

@push('js')
    @section('plugins.Sweetalert2', true)
    @section('plugins.DatatablesNew', true)
    @section('plugins.Select2', true)
    @section('plugins.Toastr', true)
    @section('plugins.Validation', true)

    @include('tr.benchmark.js.index')
@endpush
