{{-- @extends('tr.btor.layouts.master')

@section('title', 'BTOR Report Detail') --}}

@extends('layouts.app')

@section('subtitle', __('BTOR Report Detail'))
@section('content_header_title') <strong>{{ __('BTOR Report Detail') }}</strong>@endsection
@section('sub_breadcumb')<a href="{{ route('btor.index') }}" title="{{ __('BTOR Report List') }}"> {{ __('BTOR Report List') }}</a>@endsection
@section('sub_sub_breadcumb') / <span title="Current Page {{ __('BTOR Report Detail') }}">{{ __('BTOR Report Detail') }}</span>@endsection

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

{{-- @section('content') --}}
@section('content_body')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            {{-- In show.blade.php actions --}}
            <div class="btn-group">
                <a href="{{ route('btor.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>

                <a href="{{ route('btor.print', $kegiatan->id) }}" class="btn btn-sm btn-info ml-1" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>

                <a href="{{ route('btor.export.pdf', $kegiatan->id) }}" class="btn btn-sm btn-danger ml-1">
                    <i class="fas fa-file-pdf"></i>PDF (Landscape)
                </a>

                <a href="{{ route('btor.export.pdf', ['id' => $kegiatan->id, 'orientation' => 'portrait']) }}" class="btn btn-sm btn-danger ml-1">
                    <i class="fas fa-file-pdf"></i> PDF (Portrait)
                </a>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">BTOR - {{ __('btor.btor') }}</h3>
            <small>Report ID: {{ $kegiatan->id ?? '-' }}</small>
        </div>

        <div class="card-body">
            {{-- Header Information --}}
            @include('tr.btor.partials.header', ['kegiatan' => $kegiatan])

            {{-- Basic Information --}}
            <h4 class="mt-4 mb-3 border-bottom pb-2">Basic Information</h4>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td width="30%"><strong>Program Code</strong></td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->kode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Program Name</strong></td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Activity Code</strong></td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Activity Name</strong></td>
                        <td>{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Activity Type</strong></td>
                        <td>
                            <span class="badge badge-info badge-lg">
                                {{ $kegiatan->jenisKegiatan?->nama ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Report Phase</strong></td>
                        <td>{{ $kegiatan->fasepelaporan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Start Date</strong></td>
                        <td>
                            @if($kegiatan->tanggalmulai)
                                {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d F Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>End Date</strong></td>
                        <td>
                            @if($kegiatan->tanggalselesai)
                                {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d F Y') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Duration</strong></td>
                        <td>{{ $kegiatan->getDurationInDays() }} days</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            <span class="badge badge-{{ $kegiatan->status === 'completed' ? 'success' : ($kegiatan->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($kegiatan->status ?? 'draft') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Report Writer(s)</strong></td>
                        <td>
                            @if($kegiatan->datapenulis?->count() > 0)
                                {{ $kegiatan->datapenulis->pluck('nama')->filter()->implode(', ') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Position(s)</strong></td>
                        <td>
                            @if($kegiatan->kegiatan_penulis?->count() > 0)
                               {{ $kegiatan->kegiatan_penulis?->pluck('peran.nama')->filter()->implode(', ') ?: '-' }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Sector(s)</strong></td>
                        <td>
                            @if($kegiatan->sektor?->count() > 0)
                                {{ $kegiatan->sektor->pluck('nama')->implode(', ') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Partner(s)</strong></td>
                        <td>
                            @if($kegiatan->mitra?->count() > 0)
                                {{ $kegiatan->mitra->pluck('nama')->implode(', ') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            {{-- Activity Type Specific Content --}}
            <h4 class="mt-4 mb-3 border-bottom pb-2">{{ __('btor.activity_details') }}</h4>
            @include($viewPath, ['kegiatan' => $kegiatan])

            {{-- Activity Description --}}
            <h4 class="mt-4 mb-3 border-bottom pb-2">{{ __('btor.activity_description') }}</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5>{{ __('btor.deskripsilatarbelakang') }}</h5>
                    <div class="p-3 bg-light rounded">
                        {!! $kegiatan->deskripsilatarbelakang ?? '<em class="text-muted">No data</em>' !!}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <h5>{{ __('btor.deskripsitujuan') }}</h5>
                    <div class="p-3 bg-light rounded">
                        {!! $kegiatan->deskripsitujuan ?? '<em class="text-muted">No data</em>' !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5>{{ __('btor.deskripsikeluaran') }}</h5>
                    <div class="p-3 bg-light rounded">
                        {!! $kegiatan->deskripsikeluaran ?? '<em class="text-muted">No data</em>' !!}
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <h5>{{ __('btor.deskripsiyangdikaji') }}</h5>
                    <div class="p-3 bg-light rounded">
                        {!! $kegiatan->deskripsiyangdikaji ?? '<em class="text-muted">No data</em>' !!}
                    </div>
                </div>
            </div>

            {{-- Location Information --}}
            <h4 class="mt-4 mb-3 border-bottom pb-2">{{ __('btor.informasi_lokasi') }}</h4>
            @include('tr.btor.partials.location', ['kegiatan' => $kegiatan])

            {{-- Beneficiaries Information --}}
            <h4 class="mt-4 mb-3 border-bottom pb-2">{{ __('btor.penerima_manfaat') }}</h4>
            @include('tr.btor.partials.beneficiaries', ['kegiatan' => $kegiatan])

            {{-- Footer --}}
            @include('tr.btor.partials.footer', ['kegiatan' => $kegiatan])
        </div>
    </div>
</div>
@stop
