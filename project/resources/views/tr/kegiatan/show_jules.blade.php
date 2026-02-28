@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.label') . ' - Jules View')
@section('content_header_title') <strong>{{ __('cruds.kegiatan.label') }} - Jules View</strong>@endsection
@section('sub_breadcumb')<a href="{{ route('kegiatan.index') }}"> {{ __('cruds.kegiatan.list') }}</a>@endsection
@section('sub_sub_breadcumb') / <span>{{ __('global.view') }}</span>@endsection

@section('content_body')
<div class="container-fluid">
    {{-- Action Buttons --}}
    <div class="row mb-4 no-print">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div class="btn-group">
                <a href="{{ route('kegiatan.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> {{ __('global.back') }}
                </a>
            </div>
            <div class="btn-group">
                <a href="{{ route('btor.print', $kegiatan->id) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-print mr-1"></i> {{ __('btor.print_preview') }}
                </a>
                <a href="{{ route('kegiatan.export_jules_pdf', $kegiatan->id) }}" class="btn btn-danger ml-2">
                    <i class="fas fa-file-pdf mr-1"></i> PDF
                </a>
                <a href="{{ route('btor.export.docx', $kegiatan->id) }}" class="btn btn-primary ml-2">
                    <i class="fas fa-file-word mr-1"></i> DOCX
                </a>
                <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-warning ml-2 text-white">
                    <i class="fas fa-edit mr-1"></i> {{ __('global.edit') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Main Content --}}
        <div class="col-lg-8">
            {{-- Main Report Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white" style="background-color: #1a5c28">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold mb-0">BACK TO OFFICE REPORT</h3>
                        <span class="badge badge-light px-3 py-2 text-success font-weight-bold">
                            {{ strtoupper($kegiatan->status ?? 'DRAFT') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h2 class="text-dark font-weight-bold mb-1">
                                {{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'Activity Name Not Set' }}
                            </h2>
                            <p class="text-muted lead mb-0">
                                <i class="fas fa-tag mr-2 text-secondary"></i> {{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 col-sm-12 order-1 mb-2 col-xl-4">
                            <div class="p-3 bg-light rounded border-left border-success">
                                <h5 class="text-uppercase text-muted font-weight-bold d-block mb-1">Program</h5>
                                <span class="font-weight-bold text-dark">
                                    {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 order-2 mb-2 col-xl-4">
                            <div class="p-3 bg-light rounded border-left border-info h-100 text-left">
                                <h5 class="text-uppercase text-muted font-weight-bold d-block mb-1">Jenis Kegiatan</h5>
                                <span class="badge badge-info text-wrap text-left">
                                    {{ $kegiatan->jenisKegiatan?->nama ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 order-3 mb-2 col-xl-4">
                            <div class="p-3 bg-light rounded border-left border-warning h-100">
                                <h5 class="text-uppercase text-muted font-weight-bold d-block mb-1">Fase</h5>
                                <span class="h4 font-weight-bold text-dark">{{ $kegiatan->fasepelaporan ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- 1. Latar Belakang --}}
                    <div class="section mb-5">
                        <h4 class="font-weight-bold mb-3 text-dark border-bottom pb-2">
                            <i class="fas fa-info-circle mr-2 text-primary"></i> 1. {{ __('cruds.kegiatan.description.latar_belakang') }}
                        </h4>
                        <div class="rich-text-content px-2">
                            {!! $kegiatan->deskripsilatarbelakang ?? '<em class="text-muted">Tidak ada data.</em>' !!}
                        </div>
                    </div>

                    {{-- 2. Tujuan --}}
                    <div class="section mb-5">
                        <h4 class="font-weight-bold mb-3 text-dark border-bottom pb-2">
                            <i class="fas fa-bullseye mr-2 text-danger"></i> 2. {{ __('cruds.kegiatan.description.tujuan') }}
                        </h4>
                        <div class="rich-text-content px-2">
                            {!! $kegiatan->deskripsitujuan ?? '<em class="text-muted">Tidak ada data.</em>' !!}
                        </div>
                    </div>

                    {{-- 3. Detail & Dynamic Results --}}
                    <div class="section mb-5">
                        <h4 class="font-weight-bold mb-3 text-dark border-bottom pb-2">
                            <i class="fas fa-list-alt mr-2 text-warning"></i> 3. {{ __('btor.detail_kegiatan') }} & Hasil
                        </h4>

                        {{-- Basic Table for detail --}}
                        <div class="table-responsive mb-4 px-2">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td width="30%" class="text-muted"><strong>Waktu Pelaksanaan</strong></td>
                                        <td width="2%">:</td>
                                        <td>
                                            @if($kegiatan->tanggalmulai)
                                                <i class="far fa-calendar-alt text-success mr-1"></i>
                                                {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                                @if($kegiatan->tanggalselesai && $kegiatan->tanggalmulai != $kegiatan->tanggalselesai)
                                                    - {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                                @endif
                                                <span class="ml-2 badge badge-secondary font-weight-normal">{{ $durationInDays }} Hari</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Lokasi</strong></td>
                                        <td>:</td>
                                        <td>
                                            @forelse($kegiatan->lokasi as $lok)
                                                <div class="mb-1">
                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                                    {{ $lok->lokasi }}, {{ $lok->desa?->nama }}, {{ $lok->desa?->kecamatan?->nama }}
                                                </div>
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Mitra/Pihak Terlibat</strong></td>
                                        <td>:</td>
                                        <td>
                                            @forelse($kegiatan->mitra as $mitra)
                                                <span class="badge badge-light border mr-1">
                                                    <i class="fas fa-handshake text-muted mr-1"></i> {{ $mitra->nama }}
                                                </span>
                                            @empty
                                                -
                                            @endforelse
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Disaggregated Participant Table --}}
                        <div class="mb-4 px-2">
                            <h5 class="font-weight-bold mb-3 text-muted">
                                <i class="fas fa-users-cog mr-2"></i> {{ __('cruds.kegiatan.peserta.label') }} (Disagregat)
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm text-center">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-left">{{ __('cruds.kegiatan.peserta.peserta') }}</th>
                                            <th>{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                                            <th>{{ __('cruds.kegiatan.peserta.pria') }}</th>
                                            <th>{{ __('cruds.kegiatan.peserta.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-left">{{ __('cruds.kegiatan.peserta.dewasa') }} (25-59)</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                                            <td class="font-weight-bold">{{ number_format($kegiatan->penerimamanfaatdewasatotal ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">{{ __('cruds.kegiatan.peserta.lansia') }} (60+)</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatlansialakilaki ?? 0) }}</td>
                                            <td class="font-weight-bold">{{ number_format($kegiatan->penerimamanfaatlansiatotal ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">{{ __('cruds.kegiatan.peserta.remaja') }} (18-24)</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatremajaperempuan ?? 0) }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatremajalakilaki ?? 0) }}</td>
                                            <td class="font-weight-bold">{{ number_format($kegiatan->penerimamanfaatremajatotal ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">{{ __('cruds.kegiatan.peserta.anak') }} (< 18)</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatanakperempuan ?? 0) }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatanaklakilaki ?? 0) }}</td>
                                            <td class="font-weight-bold">{{ number_format($kegiatan->penerimamanfaatanaktotal ?? 0) }}</td>
                                        </tr>
                                        <tr class="bg-light font-weight-bold">
                                            <td class="text-left">TOTAL USIA</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</td>
                                            <td class="text-primary">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</td>
                                        </tr>
                                    </tbody>
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-left">Kelompok Khusus</th>
                                            <th>{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                                            <th>{{ __('cruds.kegiatan.peserta.pria') }}</th>
                                            <th>{{ __('cruds.kegiatan.peserta.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-left">{{ __('cruds.kegiatan.peserta.disabilitas') }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatdisabilitasperempuan ?? 0) }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0) }}</td>
                                            <td class="font-weight-bold">{{ number_format($kegiatan->penerimamanfaatdisabilitastotal ?? 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">{{ __('cruds.kegiatan.peserta.marjinal_lain') }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                                            <td>{{ number_format($kegiatan->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                                            <td class="font-weight-bold">{{ number_format($kegiatan->penerimamanfaatmarjinaltotal ?? 0) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Dynamic Content Following Create Order --}}
                        @include('tr.kegiatan.partials.dynamic_results_jules')
                    </div>

                    {{-- 4. Hasil Pertemuan --}}
                    <div class="section mb-5">
                        <h4 class="font-weight-bold mb-3 text-dark border-bottom pb-2">
                            <i class="fas fa-file-alt mr-2 text-success"></i> 4. {{ __('cruds.kegiatan.description.deskripsikeluaran') }}
                        </h4>
                        <div class="rich-text-content px-2">
                            {!! $kegiatan->deskripsikeluaran ?? '<em class="text-muted">Tidak ada data.</em>' !!}
                        </div>
                    </div>

                    {{-- Additional fields if they are not already in dynamic results --}}
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="section h-100">
                                <h5 class="font-weight-bold mb-3 text-dark border-bottom pb-2">
                                    <i class="fas fa-user-edit mr-2 text-secondary"></i> {{ __('cruds.kegiatan.hasil.catatan_penulis') }}
                                </h5>
                                <div class="px-2 small">
                                    {!! $kegiatan->catatan_penulis ?? '<em class="text-muted">-</em>' !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="section h-100">
                                <h5 class="font-weight-bold mb-3 text-dark border-bottom pb-2">
                                    <i class="fas fa-sync-alt mr-2 text-info"></i> {{ __('cruds.kegiatan.hasil.indikasi_perubahan') }}
                                </h5>
                                <div class="px-2 small">
                                    {!! $kegiatan->indikasi_perubahan ?? '<em class="text-muted">-</em>' !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Right Column: Side Information --}}
        <div class="col-lg-4 order-1">
            {{-- Metrics Card --}}
            <div class="card shadow-sm border-0 mb-4 bg-primary text-white">
                <div class="card-body">
                    <h5 class="font-weight-bold mb-4 border-bottom border-white-50 pb-2">
                        <i class="fas fa-users mr-2"></i> Ringkasan Partisipan
                    </h5>
                    <div class="row text-center mb-3">
                        <div class="col-6 mb-3">
                            <div class="bg-white-10 rounded p-2">
                                <h2 class="font-weight-bold mb-0">{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</h2>
                                <small class="text-uppercase small">Perempuan</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-white-10 rounded p-2">
                                <h2 class="font-weight-bold mb-0">{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</h2>
                                <small class="text-uppercase small">Laki-Laki</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="bg-white-20 rounded p-3 mt-2">
                                <h1 class="font-weight-bold mb-0">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</h1>
                                <small class="text-uppercase font-weight-bold">Total Partisipan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penulis Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3">
                    <i class="fas fa-pen-nib mr-2 text-muted"></i> Tim Pelaksana & Penulis
                </div>
                <div class="card-body pt-2">
                    @forelse($kegiatan->kegiatan_penulis as $penulis)
                        <div class="media mb-3 align-items-center">
                            <div class="bg-light rounded-circle p-2 mr-3 text-center" style="width:40px;height:40px;">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                            <div class="media-body">
                                <h6 class="mb-0 font-weight-bold text-dark">{{ $penulis->user?->nama ?? '-' }}</h6>
                                <small class="text-muted">{{ $penulis->peran?->nama ?? '-' }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small italic">Tidak ada data penulis.</p>
                    @endforelse
                </div>
            </div>

            {{-- Documents Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3">
                    <i class="fas fa-file-alt mr-2 text-primary"></i> Dokumen Pendukung
                </div>
                <div class="card-body pt-2 px-0">
                    <div class="list-group list-group-flush">
                        @php $docs = $kegiatan->getMedia('dokumen_pendukung'); @endphp
                        @forelse($docs as $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="list-group-item list-group-item-action border-0 py-2">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div class="text-truncate mr-2">
                                        <i class="far fa-file-{{ $media->extension == 'pdf' ? 'pdf text-danger' : 'alt text-primary' }} mr-2"></i>
                                        <span class="small font-weight-medium">{{ $media->name }}</span>
                                    </div>
                                    <i class="fas fa-download text-muted small"></i>
                                </div>
                            </a>
                        @empty
                            <div class="px-3 py-2 text-muted small italic">Tidak ada dokumen.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Media Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3">
                    <i class="fas fa-images mr-2 text-success"></i> Media Pendukung
                </div>
                <div class="card-body pt-2">
                    @php $mediaList = $kegiatan->getMedia('media_pendukung'); @endphp
                    @if($mediaList->count() > 0)
                        <div class="row no-gutters">
                            @foreach($mediaList as $media)
                                @if(str_starts_with($media->mime_type, 'image/'))
                                    <div class="col-4 p-1">
                                        <a href="{{ $media->getUrl() }}" target="_blank" title="{{ $media->name }}">
                                            <img src="{{ $media->getUrl('thumb') }}" class="img-fluid rounded shadow-sm" style="height: 60px; width: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                @else
                                    <div class="col-12 mb-1">
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="small text-truncate d-block">
                                            <i class="fas fa-play-circle mr-1"></i> {{ $media->name }}
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted small italic">Tidak ada media.</div>
                    @endif
                </div>
            </div>

            {{-- Program Goals Card --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white font-weight-bold border-bottom-0 pt-3">
                    <i class="fas fa-bullseye mr-2 text-muted"></i> Program Goals
                </div>
                <div class="card-body pt-2 small">
                    @php
                        $goal = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->goal;
                    @endphp
                    @if($goal)
                        <div class="mb-2">
                            <span class="text-muted font-weight-bold uppercase d-block mb-1">Target</span>
                            <div class="p-2 bg-light rounded">{{ $goal->target }}</div>
                        </div>
                        <div>
                            <span class="text-muted font-weight-bold uppercase d-block mb-1">Indikator</span>
                            <div class="p-2 bg-light rounded">{{ $goal->indikator }}</div>
                        </div>
                    @else
                        <p class="text-muted italic">Tidak ada goal yang didefinisikan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('css')
<style>
    .bg-white-10 { background-color: rgba(255,255,255,0.1); }
    .bg-white-20 { background-color: rgba(255,255,255,0.2); }
    .rich-text-content img { max-width: 100%; height: auto !important; }
    .rich-text-content { line-height: 1.6; color: #444; }
    .border-left-success { border-left: 4px solid #28a745 !important; }
    .border-left-info { border-left: 4px solid #17a2b8 !important; }
    .section h4 { position: relative; }
    /* Fix for summernote content */
    .rich-text-content p { margin-bottom: 1rem; }
    .card { border-radius: 0.5rem; overflow: hidden; }
</style>
@endpush
