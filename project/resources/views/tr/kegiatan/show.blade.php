@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.kegiatan.label'))
@section('content_header_title')
    {{-- <button type="button" class="btn btn-secondary btn-sm print-btn"
        title="{{ __('global.print') . ' ' . __('cruds.kegiatan.label') }}">
        <i class="fa fa-print"></i>
    </button> --}}
    <a href="{{ route('btor.print', $kegiatan->id) }}" class="btn btn-info" target="_blank">
        <i class="fas fa-print"></i> {{ __('btor.print_preview') }}
    </a>
@endsection
@section('sub_breadcumb', __('cruds.kegiatan.list'))

@section('content_body')

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h1 class="card-title">
                {{ __('BACK TO OFFICE REPORT') }}
                <span class="text-primary">
                    {{ $kegiatan->programOutcomeOutputActivity->kode ?? ''}}
                </span>
                {{ $kegiatan->programOutcomeOutputActivity->nama ?? '' }}
            </h1>
            <div class="card-tools">
                <button type="button" class="btn" onclick="window.location.href=`{{ route('kegiatan.index') }}`"
                    title="{{ __('global.back') }}">
                    <i class="fa fa-arrow-left"></i>
                </button>
            </div>
        </div>
        <!-- Activity Metrics Section -->
        <div class="card-body border-top">
            <div class="col-md-12">
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h3 class="mb-0 text-primary">{{ $kegiatan->lokasi?->count() ?? 0 }}</h3>
                            <small>{{ __('Locations') }}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h3 class="mb-0 text-success">{{ $kegiatan->mitra?->count() ?? 0 }}</h3>
                            <small>{{ __('Partners') }}</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2">
                            <h3 class="mb-0 text-warning">{{ $kegiatan->penerimamanfaattotal ?? 0 }}</h3>
                            <small>{{ __('Beneficiaries') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End Activity Metrics Section -->
        <div class="card-body bg-light m-0 p-3">
            <div class="details container-fluid">
                <!-- Basic Information Card -->
                <div class="card shadow-sm mb-4 border-top">
                    <div class="card-header {{-- bg-white border-bottom-0 pt-3 pb-2 --}}">
                        <h5 class="text-primary mb-0"><i class="fas fa-info-circle me-2"></i>{{ __('Basic Information') }}</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
                                <div class="fw-medium text-dark">{{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->kode ?? '-' }}</div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.program_nama') }}</label>
                                <div>
                                    <a href="{{ route('program.show', $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->id) }}" target="_blank" class="text-decoration-none fw-medium">
                                        {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program?->nama ?? '-' }} <i class="fas fa-external-link-alt small ms-1"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.kode') }}</label>
                                <div class="fw-medium text-dark">{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '-' }}</div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.nama') }}</label>
                                <div class="fw-medium text-dark">{{ $kegiatan->programOutcomeOutputActivity?->nama ?? '-' }}</div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.jenis_kegiatan') }}</label>
                                <div><span class="badge bg-info text-dark bg-opacity-10 border border-info px-3 py-2">{{ $kegiatan->jenisKegiatan?->nama ?? '-' }}</span></div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.sektor_kegiatan') }}</label>
                                <div>
                                    @if($kegiatan->sektor && $kegiatan->sektor->count() > 0)
                                        @foreach ($kegiatan->sektor as $value)
                                            <span class="badge bg-warning text-dark me-1">{{ $value->nama }}</span>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.fase_pelaporan') }}</label>
                                <div class="fw-medium">{{ $kegiatan->fasepelaporan ?? '-' }}</div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.status') }}</label>
                                <div>
                                    <span class="badge badge-{{ $kegiatan->status === 'completed' ? 'success' : ($kegiatan->status === 'ongoing' ? 'warning' : 'secondary') }} px-3 py-2">
                                        {{ ucfirst($kegiatan->status ?? 'draft') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.durasi') }}</label>
                                <div class="fw-medium">{{ $durationInDays ?? 0 }} {{ __('cruds.kegiatan.days') }}</div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.tanggalmulai') }}</label>
                                <div class="fw-medium">
                                    @if($kegiatan->tanggalmulai)
                                        <i class="far fa-calendar-alt me-1 text-muted"></i> {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.tanggalselesai') }}</label>
                                <div class="fw-medium">
                                    @if($kegiatan->tanggalselesai)
                                        <i class="far fa-calendar-check me-1 text-muted"></i> {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <label class="text-secondary small text-uppercase fw-bold">{{ __('cruds.kegiatan.basic.nama_mitra') }}</label>
                                <div>
                                    @if($kegiatan->mitra && $kegiatan->mitra->count() > 0)
                                        @foreach($kegiatan->mitra as $mitra)
                                            <span class="badge bg-secondary me-1"><i class="fas fa-handshake me-1"></i> {{ $mitra->nama }}</span>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Location Information -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4 h-100">
                            <div class="card-header {{-- bg-white border-bottom-0 pt-3 pb-2 --}}">
                                <h5 class="text-success mb-0"><i class="fas fa-map-marker-alt me-2"></i>{{ __('Location Information') }}</h5>
                            </div>
                            <div class="card-body pt-1">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm border-0 mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Kecamatan/Desa') }}</th>
                                                <th>{{ __('Lokasi') }}</th>
                                                <th class="text-end">{{ __('Coordinate') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($kegiatan->lokasi as $lokasi)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $lokasi->desa?->kecamatan?->nama ?? '-' }}</div>
                                                        <small class="text-muted">{{ $lokasi->desa?->nama ?? '-' }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($lokasi->lat && $lokasi->long)
                                                            <a href="https://www.google.com/maps?q={{ $lokasi->lat }},{{ $lokasi->long }}" target="_blank" class="text-decoration-none">
                                                                <i class="fas fa-map-pin text-danger"></i> {{ ucwords(strtolower($lokasi->lokasi ?? 'Lihat Peta')) }}
                                                            </a>
                                                        @else
                                                            {{ $lokasi->lokasi ?? '—' }}
                                                        @endif
                                                    </td>
                                                    <td class="text-end small">
                                                        <div>{{ $lokasi->lat ?? '-' }}</div>
                                                        <div>{{ $lokasi->long ?? '-' }}</div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted small fst-italic">{{ __('Tidak ada data lokasi tersedia.') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Penulis Information -->
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4 h-100">
                            <div class="card-header {{-- bg-white border-bottom-0 pt-3 pb-2 --}}">
                                <h5 class="text-info mb-0"><i class="fas fa-users me-2"></i>{{ __('Penulis Laporan') }}</h5>
                            </div>
                            <div class="card-body pt-1">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm border-0 mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('Penulis') }}</th>
                                                <th>{{ __('Jabatan') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($kegiatan->datapenulis ?? [] as $penulis)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-light rounded-circle p-2 me-2 text-center" style="width:32px;height:32px;line-height:1;">
                                                                <i class="fas fa-user-circle text-secondary"></i>
                                                            </div>
                                                            <div>{{ $penulis->nama ?? '-' }}</div>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle"><span class="badge bg-light text-dark border">{{ $penulis->kegiatanPeran?->nama ?? '-' }}</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center text-muted small fst-italic">{{ __('Tidak ada data penulis tersedia.') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Program Hierarchy & Progress -->
                <div class="card shadow-sm mb-4 mt-4">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
                        <h5 class="text-warning text-dark mb-0"><i class="fas fa-sitemap me-2"></i>{{ __('Program Hierarchy & Progress') }}</h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded text-center h-100 border">
                                    <h6 class="text-uppercase text-muted fw-bold d-block mb-1">{{ __('Program Outcome Target') }}</h6>
                                    <p class="mb-0 text-primary">
                                        {{-- {{ $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->target ?? '-' }} --}}
                                        {!! nl2br(e($kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->target ?? '-')) !!}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded text-center h-100 border">
                                    <h6 class="text-uppercase text-muted fw-bold d-block mb-1">
                                        {{ __('Outcome Output Target') }}
                                    </h6>
                                    <p class="mb-0 text-info">
                                        {!! nl2br(e($kegiatan->programOutcomeOutputActivity?->program_outcome_output?->target ?? '-')) !!}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded text-center h-100 border">
                                    <h6 class="text-uppercase text-muted fw-bold d-block mb-1">
                                        {{ __('Output Activit Target') }}
                                    </h6>
                                    <p class="mb-0 text-success">
                                        {!! nl2br(e($kegiatan->programOutcomeOutputActivity?->target ?? '-')) !!}
                                    </p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mt-2">
                                    <strong class="text-secondary small text-uppercase"><i class="fas fa-bullseye me-1"></i> {{ __('Program Goals') }}:</strong> 
                                    @php
                                        $program = $kegiatan->programOutcomeOutputActivity?->program_outcome_output?->program_outcome?->program;
                                        $goal = $program?->goal ?? null;
                                    @endphp
                                    @if($goal)
                                        <div class="mt-2 d-flex flex-wrap gap-2">
                                            @if($goal->deskripsi)<div class="input-group mt-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $goal->deskripsi }}</span></div>
                                            @endif
                                            @if($goal->indikator)<div class="input-group mt-2">
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success">{{ $goal->indikator }}</span></div>
                                            @endif
                                            @if($goal->target)
                                            <div class="input-group mt-2"><span class="badge bg-warning bg-opacity-10 text-dark border border-warning">{{ $goal->target }}</span></div>@endif
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic ms-2">{{ __('No goals defined') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-body border-top">
            <!-- latar belakang kegiatan -->
            <div class="form-group row">
                <div class="col-sm col-md col-lg self-center">
                    <label for="deskripsilatarbelakang" class="input-group">
                       <h5>
                           {{ __('cruds.kegiatan.description.latar_belakang') }}
                        </h5>
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.description.latar_belakang_helper') }}"></i>
                    </label>
                    {!! $kegiatan->deskripsilatarbelakang ?? '' !!}
                </div>
            </div>
            <!-- tujuan kegiatan -->
            <div class="form-group row">
                <div class="col-sm col-md col-lg self-center">
                    <label for="deskripsitujuan" class="mb-0 input-group">
                        <h5>
                            {{ __('cruds.kegiatan.description.tujuan') }}
                        </h5>
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.description.tujuan_helper') }}"></i>
                    </label>
                    {!! $kegiatan->deskripsitujuan ?? '' !!}
                </div>
            </div>
            <!-- deskripsi keluaran / Hasil Pertemuan kegiatan -->
            <div class="form-group row">
                <div class="col-sm col-md col-lg self-center">
                    <label for="deskripsikeluaran" class="mb-0 input-group">
                        <h5>
                            {{ __('cruds.kegiatan.description.deskripsikeluaran') }}

                        </h5>
                            
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.description.keluaran_helper') }}"></i>
                    </label>
                    {!! $kegiatan->deskripsikeluaran ?? '' !!}
                </div>
            </div>

            <!-- Details Kegiatan Section -->
            <div class="card-body border-top">

                @php
                    $jenisId = $kegiatan->jeniskegiatan_id;
                    $viewPath = App\Models\Export\BTOR::getViewPath($kegiatan->jeniskegiatan_id);
                @endphp
                <h5 class="text-primary mb-4"><i class="fas fa-clipboard-list me-2"></i>
                    {{ __('Hasil Kegiatan') }}
                </h5>  
                @if(isset($viewPath))
                    <div class="print-jenis-kegiatan">
                        <h5>
                            {{ $kegiatan->jenisKegiatan?->nama ?? 'Jenis Kegiatan' }}
                        </h5>
                        @include($viewPath, ['kegiatan' => $kegiatan])
                    </div>
                @endif
            </div>


            <!-- Peserta yang terlibat -->
            <div class="form-group row mb-0">
                <div class="col-sm col-md col-lg self-center">
                    <label class="self-center input-group">
                        {{ __('cruds.kegiatan.peserta.label') }}
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.peserta.helper') }}"></i>
                    </label>
                </div>
            </div>

            
            {{-- Tabel Jumlah Peserta Kegiatan --}}
            <div class="form-group row">
                <div class="col-sm col-md col-lg self-center">
                    <div class="card-body table-responsive p-0">
                        <table id="peserta_kegiatan_summary" class="table table-sm table-borderless mb-0 table-custom"
                            width="100%">
                            <!-- jumlah peserta kegiatan -->
                            <thead style="background-color: #11bd7e !important">
                                <tr class="align-middle text-center text-nowrap">
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.peserta') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.wanita') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.pria') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!--dewasa row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.dewasa') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatdewasaperempuan"
                                            name="penerimamanfaatdewasaperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdewasaperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatdewasalakilaki"
                                            name="penerimamanfaatdewasalakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdewasalakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatdewasatotal"
                                            name="penerimamanfaatdewasatotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdewasatotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--lansia row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatlansiaperempuan"
                                            name="penerimamanfaatlansiaperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlansiaperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatlansialakilaki"
                                            name="penerimamanfaatlansialakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlansialakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatlansiatotal"
                                            name="penerimamanfaatlansiatotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlansiatotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--remaja row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.remaja') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatremajaperempuan"
                                            name="penerimamanfaatremajaperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatremajaperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatremajalakilaki"
                                            name="penerimamanfaatremajalakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatremajalakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatperempuantotal"
                                            name="penerimamanfaatperempuantotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatremajatotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--anak-anak row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.anak') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatanakperempuan"
                                            name="penerimamanfaatanakperempuan"
                                            class="calculate form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatanakperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatanaklakilaki"
                                            name="penerimamanfaatanaklakilaki"
                                            class="calculate form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatanaklakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatanaktotal"
                                            name="penerimamanfaatanaktotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatanaktotal ?? 0 }}">
                                    </td>
                                </tr>
                                <tr class="align-middle text-center text-nowrap">
                                    <th class="pl-1 text-left text-sm">{{ __('cruds.kegiatan.peserta.total') }}</th>
                                    <th class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatperempuantotal"
                                            name="penerimamanfaatperempuantotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}">
                                    </th>
                                    <th class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatlakilakitotal"
                                            name="penerimamanfaatlakilakitotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}">
                                    </th>
                                    <th class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaattotal"
                                            name="penerimamanfaattotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaattotal ?? 0 }}">
                                    </th>
                                </tr>
                            </tbody>
                            <!--jumlah peserta disabilitas -->
                            <tfoot>
                                <!--<th style="background-color: #6111bd !important table-dark">-->
                                <tr class="align-middle text-center text-nowrap"
                                    style="background-color: #6111bd !important">
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.peserta') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.wanita') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.pria') }}</th>
                                    <th
                                        class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                                        {{ __('cruds.kegiatan.peserta.total') }}</th>
                                </tr>
                                <!--</th>-->
                                <!--disabilitas row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.disabilitas') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatdisabilitasperempuan"
                                            name="penerimamanfaatdisabilitasperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdisabilitasperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatdisabilitaslakilaki"
                                            name="penerimamanfaatdisabilitaslakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatdisabilitastotal"
                                            name="penerimamanfaatdisabilitastotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatdisabilitastotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--non disabilitias  row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.non_disabilitas') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatnondisabilitasperempuan"
                                            name="penerimamanfaatnondisabilitasperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatnondisabilitaslakilaki"
                                            name="penerimamanfaatnondisabilitaslakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatnondisabilitastotal"
                                            name="penerimamanfaatnondisabilitastotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatnondisabilitastotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--marjinal row-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.marjinal_lain') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatmarjinalperempuan"
                                            name="penerimamanfaatmarjinalperempuan"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatmarjinalperempuan ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="penerimamanfaatmarjinallakilaki"
                                            name="penerimamanfaatmarjinallakilaki"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatmarjinallakilaki ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="penerimamanfaatmarjinaltotal"
                                            name="penerimamanfaatmarjinaltotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatmarjinaltotal ?? 0 }}">
                                    </td>
                                </tr>
                                <!--total beneficiaries difabel-->
                                <tr>
                                    <td class="pl-1">
                                        <label class="text-sm">{{ __('cruds.kegiatan.peserta.total') }}</label>
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="total_beneficiaries_perempuan"
                                            name="penerimamanfaatperempuantotal"
                                            class="calculate form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}">
                                    </td>
                                    <td class="pl-1">
                                        <input type="number" readonly id="total_beneficiaries_lakilaki"
                                            name="penerimamanfaatlakilakitotal"
                                            class="calculate form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}">
                                    </td>
                                    <td class="pl-1 pr-1">
                                        <input type="number" readonly id="beneficiaries_difable_total"
                                            name="penerimamanfaattotal"
                                            class="form-control-border border-width-2 form-control form-control-sm text-center"
                                            value="{{ $kegiatan->penerimamanfaattotal ?? 0 }}">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>

        </div>


        <!-- Tantangan & Solusi Section -->
        <div class="card-body border-top">
            <h5 class="text-warning mb-4"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('Tantangan & Solusi') }}</h5>
            
            @php
                $kendala = $kegiatan->assessment?->assessmentkendala 
                        ?? $kegiatan->sosialisasi?->sosialisasikendala
                        ?? $kegiatan->pelatihan?->pelatihankendala
                        ?? $kegiatan->pembelanjaan?->pembelanjaankendala
                        ?? $kegiatan->pengembangan?->pengembangankendala
                        ?? $kegiatan->kampanye?->kampanyekendala
                        ?? $kegiatan->pemetaan?->pemetaankendala
                        ?? $kegiatan->monitoring?->monitoringkendala
                        ?? $kegiatan->kunjungan?->kunjungankendala
                        ?? $kegiatan->konsultasi?->konsultasikendala
                        ?? $kegiatan->lainnya?->lainnyakendala
                        ?? null;
                $solusi = $kegiatan->assessment?->assessmentpembelajaran 
                       ?? $kegiatan->sosialisasi?->sosialisasipembelajaran
                       ?? $kegiatan->pelatihan?->pelatihanpembelajaran
                       ?? $kegiatan->pembelanjaan?->pembelanjaanpembelajaran
                       ?? $kegiatan->pengembangan?->pengembanganpembelajaran
                       ?? $kegiatan->kampanye?->kampanyepembelajaran
                       ?? $kegiatan->pemetaan?->pemetaanpembelajaran
                       ?? $kegiatan->monitoring?->monitoringpembelajaran
                       ?? $kegiatan->kunjungan?->kunjunganpembelajaran
                       ?? $kegiatan->konsultasi?->konsultasipembelajaran
                       ?? $kegiatan->lainnya?->lainnyapembelajaran
                       ?? null;
            @endphp

            @if($kendala || $solusi)
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50%;">{{ __('Tantangan') }}</th>
                                <th style="width: 50%;">{{ __('Solusi yang Diambil') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{!! $kendala ?? '<em class="text-muted">-</em>' !!}</td>
                                <td>{!! $solusi ?? '<em class="text-muted">-</em>' !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-3 text-muted bg-light rounded">
                    <i class="fas fa-check-circle me-1"></i> {{ __('Tidak ada tantangan yang dicatat') }}
                </div>
            @endif
        </div>

        <!-- Isu yang Perlu Diperhatikan Section -->
        <div class="card-body border-top">
            <h5 class="text-danger mb-4"><i class="fas fa-flag me-2"></i>{{ __('Isu yang Perlu Diperhatikan') }}</h5>
            
            @php
                $isu = $kegiatan->assessment?->assessmentisu 
                    ?? $kegiatan->sosialisasi?->sosialisasiisu
                    ?? $kegiatan->pelatihan?->pelatihanisu
                    ?? $kegiatan->pembelanjaan?->pembelanjaanisu
                    ?? $kegiatan->pengembangan?->pengembanganisu
                    ?? $kegiatan->kampanye?->kampanyeisu
                    ?? $kegiatan->pemetaan?->pemetaanisu
                    ?? $kegiatan->monitoring?->monitoringisu
                    ?? $kegiatan->kunjungan?->kunjunganisu
                    ?? $kegiatan->konsultasi?->konsultasiisu
                    ?? $kegiatan->lainnya?->lainnyaisu
                    ?? null;
            @endphp

            @if($isu)
                <div class="bg-light p-3 rounded">
                    {!! $isu !!}
                </div>
            @else
                <div class="text-center py-3 text-muted bg-light rounded">
                    <i class="fas fa-check-circle me-1"></i> {{ __('Tidak ada isu yang perlu diperhatikan') }}
                </div>
            @endif
        </div>

        <!-- Pembelajaran Section -->
        <div class="card-body border-top">
            <h5 class="text-success mb-4"><i class="fas fa-lightbulb me-2"></i>{{ __('Pembelajaran') }}</h5>
            
            @php
                $pembelajaran = $kegiatan->assessment?->assessmentpembelajaran
                             ?? $kegiatan->pelatihan?->pelatihanpembelajaran
                             ?? $kegiatan->monitoring?->monitoringpembelajaran
                             ?? $kegiatan->sosialisasi?->sosialisasipembelajaran
                             ?? $kegiatan->kampanye?->kampanyepembelajaran
                             ?? $kegiatan->konsultasi?->konsultasipembelajaran
                             ?? $kegiatan->kunjungan?->kunjunganpembelajaran
                             ?? $kegiatan->pembelanjaan?->pembelanjaanpembelajaran
                             ?? $kegiatan->pengembangan?->pengembanganpembelajaran
                             ?? $kegiatan->pemetaan?->pemetaanpembelajaran
                             ?? $kegiatan->lainnya?->lainnyapembelajaran
                             ?? null;
            @endphp

            @if($pembelajaran)
                <div class="bg-light p-3 rounded">
                    {!! $pembelajaran !!}
                </div>
            @else
                <div class="text-center py-3 text-muted bg-light rounded">
                    <i class="fas fa-info-circle me-1"></i> {{ __('Tidak ada pembelajaran yang tersedia') }}
                </div>
            @endif
        </div>

        <!-- Related Documents & Files Section -->
        <div class="card-body border-top">
            <h5 class="text-info mb-4"><i class="fas fa-folder-open me-2"></i>{{ __('Related Documents & Files') }}</h5>

            <!-- Documents Section -->
            <div class="mb-4">
                <h6 class="text-primary mb-3"><i class="fas fa-file-alt me-2"></i>{{ __('Documents') }}</h6>
                @if($kegiatan->getMedia('dokumen_pendukung') && $kegiatan->getMedia('dokumen_pendukung')->count() > 0)
                    <div class="row g-3">
                        @foreach($kegiatan->getMedia('dokumen_pendukung') as $media)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card file-card h-100 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="file-icon text-center mb-3">
                                            @if(strstr($media->mime_type, "image/"))
                                                <img src="{{ $media->getUrl('thumb') }}" class="img-fluid rounded" alt="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" style="max-height: 100px; object-fit: cover;">
                                            @elseif(strstr($media->mime_type, "pdf"))
                                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                            @elseif(strstr($media->mime_type, "word"))
                                                <i class="fas fa-file-word fa-3x text-primary"></i>
                                            @elseif(strstr($media->mime_type, "excel") || strstr($media->mime_type, "spreadsheet"))
                                                <i class="fas fa-file-excel fa-3x text-success"></i>
                                            @elseif(strstr($media->mime_type, "powerpoint"))
                                                <i class="fas fa-file-powerpoint fa-3x text-warning"></i>
                                            @else
                                                <i class="fas fa-file fa-3x text-secondary"></i>
                                            @endif
                                        </div>
                                        <h6 class="card-title text-truncate small" title="{{ $media->getCustomProperty('keterangan') ?? $media->name }}">
                                            {{ Str::limit($media->getCustomProperty('keterangan') ?? $media->name, 30) }}
                                        </h6>
                                        <div class="file-meta">
                                            <small class="text-muted d-block"><i class="fas fa-calendar me-1"></i>{{ $media->created_at->format('d M Y') }}</small>
                                            <small class="text-muted d-block"><i class="fas fa-weight me-1"></i>{{ $media->human_readable_size }}</small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 p-2">
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="previewFile('{{ $media->getUrl() }}', '{{ $media->mime_type }}')" title="{{ __('Preview') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ $media->getUrl() }}" class="btn btn-outline-success btn-sm" download="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" title="{{ __('Download') }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 bg-light rounded">
                        <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                        <p class="text-muted small mb-0">{{ __('No supporting documents available') }}</p>
                    </div>
                @endif
            </div>

            <hr class="my-4">

            <!-- Media Section -->
            <div>
                <h6 class="text-success mb-3"><i class="fas fa-images me-2"></i>{{ __('Media') }}</h6>
                @if($kegiatan->getMedia('media_pendukung') && $kegiatan->getMedia('media_pendukung')->count() > 0)
                    <div class="row g-3">
                        @foreach($kegiatan->getMedia('media_pendukung') as $media)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card file-card h-100 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="file-icon text-center mb-3">
                                            @if(strstr($media->mime_type, "image/"))
                                                <img src="{{ $media->getUrl('thumb') }}" class="img-fluid rounded" alt="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" style="max-height: 100px; object-fit: cover;">
                                            @elseif(strstr($media->mime_type, "video/"))
                                                <i class="fas fa-file-video fa-3x text-warning"></i>
                                            @elseif(strstr($media->mime_type, "audio/"))
                                                <i class="fas fa-file-audio fa-3x text-info"></i>
                                            @else
                                                <i class="fas fa-file fa-3x text-secondary"></i>
                                            @endif
                                        </div>
                                        <h6 class="card-title text-truncate small" title="{{ $media->getCustomProperty('keterangan') ?? $media->name }}">
                                            {{ Str::limit($media->getCustomProperty('keterangan') ?? $media->name, 30) }}
                                        </h6>
                                        <div class="file-meta">
                                            <small class="text-muted d-block"><i class="fas fa-calendar me-1"></i>{{ $media->created_at->format('d M Y') }}</small>
                                            <small class="text-muted d-block"><i class="fas fa-weight me-1"></i>{{ $media->human_readable_size }}</small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 p-2">
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="previewFile('{{ $media->getUrl() }}', '{{ $media->mime_type }}')" title="{{ __('Preview') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ $media->getUrl() }}" class="btn btn-outline-success btn-sm" download="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" title="{{ __('Download') }}">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 bg-light rounded">
                        <i class="fas fa-images fa-2x text-muted mb-2"></i>
                        <p class="text-muted small mb-0">{{ __('No supporting media available') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@stop

@push('css')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @include('tr.kegiatan.custom.css')
@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

    <script>
        function uploadDocument(collection) {
            const isDocument = collection === 'dokumen_pendukung';
            const title = isDocument ? '{{ __('Upload Document') }}' : '{{ __('Upload Media') }}';
            const accept = isDocument ? '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt' : '.jpg,.jpeg,.png,.gif,.mp4,.mov,.avi,.mp3,.wav';
            const placeholder = isDocument ? '{{ __('Document Name') }}' : '{{ __('Media Name') }}';

            Swal.fire({
                title: title,
                html: `
                    <input type="file" id="documentFile" class="form-control mb-3" accept="${accept}">
                    <input type="text" id="documentName" class="form-control" placeholder="${placeholder}">
                `,
                showCancelButton: true,
                confirmButtonText: '{{ __('Upload') }}',
                cancelButtonText: '{{ __('Cancel') }}',
                preConfirm: () => {
                    const file = document.getElementById('documentFile').files[0];
                    const name = document.getElementById('documentName').value;

                    if (!file) {
                        Swal.showValidationMessage('{{ __('Please select a file') }}');
                        return false;
                    }

                    if (!name) {
                        Swal.showValidationMessage(isDocument ? '{{ __('Please enter document name') }}' : '{{ __('Please enter media name') }}');
                        return false;
                    }

                    return { file, name };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('file', result.value.file);
                    formData.append('name', result.value.name);
                    formData.append('collection', collection);
                    formData.append('kegiatan_id', {{ $kegiatan->id }});

                    fetch('{{ route('kegiatan.upload-document') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const successMessage = isDocument ? '{{ __('Document uploaded successfully') }}' : '{{ __('Media uploaded successfully') }}';
                            Swal.fire('{{ __('Success') }}', successMessage, 'success')
                                .then(() => {
                                    location.reload();
                                });
                        } else {
                            Swal.fire('{{ __('Error') }}', data.message || '{{ __('Upload failed') }}', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('{{ __('Error') }}', '{{ __('Upload failed') }}', 'error');
                    });
                }
            });
        }

        // Tab switching functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.getElementById('documents-content').style.display = 'none';
            document.getElementById('media-content').style.display = 'none';

            // Remove active class from all tabs
            document.getElementById('documents-tab').classList.remove('active');
            document.getElementById('media-tab').classList.remove('active');

            // Show selected tab content
            document.getElementById(tabName + '-content').style.display = 'block';
            document.getElementById(tabName + '-tab').classList.add('active');
        }

        // File preview functionality
        function previewFile(url, mimeType) {
            if (mimeType.startsWith('image/')) {
                Swal.fire({
                    title: '{{ __('Image Preview') }}',
                    html: `<img src="${url}" class="img-fluid" style="max-width: 100%; height: auto;">`,
                    width: '80%',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } else if (mimeType === 'application/pdf') {
                Swal.fire({
                    title: '{{ __('PDF Preview') }}',
                    html: `<iframe src="${url}" style="width: 100%; height: 500px; border: none;"></iframe>`,
                    width: '80%',
                    height: '600px',
                    showCloseButton: true,
                    showConfirmButton: false
                });
            } else {
                // For other file types, open in new tab
                window.open(url, '_blank');
            }
        }

        // File delete functionality
        function deleteFile(mediaId) {
            Swal.fire({
                title: '{{ __('Are you sure?') }}',
                text: "{{ __('You won\'t be able to revert this!') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __('Yes, delete it!') }}',
                cancelButtonText: '{{ __('Cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make AJAX request to delete file
                    fetch(`/kegiatan/media/${mediaId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('{{ __('Deleted!') }}', '{{ __('File has been deleted.') }}', 'success');
                            // Reload the page to refresh the file list
                            location.reload();
                        } else {
                            Swal.fire('{{ __('Error!') }}', data.message || '{{ __('Failed to delete file.') }}', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('{{ __('Error!') }}', '{{ __('Failed to delete file.') }}', 'error');
                    });
                }
            });
        }

        // Add hover effects for file cards
        document.addEventListener('DOMContentLoaded', function() {
            const fileCards = document.querySelectorAll('.file-card');
            fileCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 0.125rem 0.25rem rgba(0,0,0,0.075)';
                });
            });

            // Print functionality
            const printBtn = document.querySelector('.print-btn');
            if (printBtn) {
                printBtn.addEventListener('click', function() {
                    window.print();
                });
            }
        });
    </script>
@endpush
