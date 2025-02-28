@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.program.title'))
@section('content_header_title', __('global.details') . ' ' . __('cruds.program.title'))

@section('content_body')

<div class="card card-outline card-primary">
    {{-- @can('program_create') --}}
    <div class="card-header">
        <a class="pb-0 col-6" href="{{ route('kegiatan.index') }}">
            {{ __('global.back') }}
        </a>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" onclick="window.location.href=`{{ route('kegiatan.index') }}`"
                title="{{ __('global.back') }}">
                <i class="fas arrow-left"></i>
            </button>
        </div>
    </div>
    {{-- @endcan --}}
    <div class="card-header">
        <h3 class="card-title">
            {{ $kegiatan->activity->kode }} | {{ $kegiatan->activity->nama }}
        </h3>
    </div>
    <div class="card-body">
        <div class="row">
            <p class="muted">Deskripsi Berdasarkan Jenis Kegiatan</p>
        </div>
        <p>Duration: {{ $durationInDays }} days</p>

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

@endpush
