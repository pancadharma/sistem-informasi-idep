@extends('tr.btor.layouts.print-layout')

@section('title', 'BTOR Report - ' . $kegiatan->id)

@section('content')
<div class="print-container">
    {{-- Header --}}
    <div class="report-header">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="mb-2" style="max-height: 50px;">
            <h2 class="mb-1">BACK TO OFFICE REPORT</h2>
            {{-- <h3 class="mb-0">(BTOR)</h3> --}}
            <hr class="my-3">
        </div>

        @include('tr.btor.partials.header', ['kegiatan' => $kegiatan])
    </div>

    {{-- Basic Information --}}
    <div class="section">
        <h4 class="section-title">I. BASIC INFORMATION</h4>
        <table class="table-print">
            <tr>
                <td width="35%">{{ __('cruds.program.nama') }}</td>
                <td width="5%">:</td>
                <td>{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>{{ __('cruds.activity.label') }}</td>
                <td>:</td>
                <td>{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</td>
                <td>:</td>
                <td>{{ $kegiatan->jenisKegiatan?->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Period</td>
                <td>:</td>
                <td>
                    @if($kegiatan->tanggalmulai && $kegiatan->tanggalselesai)
                        {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d F Y') }} -
                        {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d F Y') }}
                        ({{ $kegiatan->getDurationInDays() }} days)
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>{{ __('cruds.kegiatan.penulis.laporan') }}</td>
                <td>:</td>
                <td>
                    @if($kegiatan->datapenulis?->count() > 0)
                        {{ $kegiatan->datapenulis->pluck('nama')->filter()->implode(', ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>{{ __('cruds.kegiatan.basic.sektor') }}</td>
                <td>:</td>
                <td>
                    @if($kegiatan->sektor?->count() > 0)
                        {{ $kegiatan->sektor->pluck('nama')->implode(', ') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- Activity Specific Details --}}
    <div class="section page-break">
        <h4 class="section-title">II. ACTIVITY DETAILS</h4>
        @include($viewPath, ['kegiatan' => $kegiatan])
    </div>

    {{-- Description --}}
    <div class="section page-break">
        <h4 class="section-title">III. ACTIVITY DESCRIPTION</h4>

        <div class="subsection">
            <h5>A. Background</h5>
            <div class="content-box">
                {!! $kegiatan->deskripsilatarbelakang ?? '<em>No data</em>' !!}
            </div>
        </div>

        <div class="subsection">
            <h5>B. Objectives</h5>
            <div class="content-box">
                {!! $kegiatan->deskripsitujuan ?? '<em>No data</em>' !!}
            </div>
        </div>

        <div class="subsection">
            <h5>C. Outputs</h5>
            <div class="content-box">
                {!! $kegiatan->deskripsikeluaran ?? '<em>No data</em>' !!}
            </div>
        </div>
    </div>

    {{-- Location --}}
    <div class="section">
        <h4 class="section-title">IV. LOCATION INFORMATION</h4>
        @include('tr.btor.partials.location', ['kegiatan' => $kegiatan])
    </div>

    {{-- Beneficiaries --}}
    <div class="section page-break">
        <h4 class="section-title">V. BENEFICIARIES INFORMATION</h4>
        @include('tr.btor.partials.beneficiaries', ['kegiatan' => $kegiatan])
    </div>

    {{-- Footer/Signature --}}
    <div class="section">
        @include('tr.btor.partials.footer', ['kegiatan' => $kegiatan])
    </div>
</div>
@endsection
