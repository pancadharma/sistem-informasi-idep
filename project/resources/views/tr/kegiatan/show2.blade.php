@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.kegiatan.label'))
@section('content_header_title', __('global.details') . ' ' . __('cruds.kegiatan.label'))

@section('content_body')
<!-- Kegiatan Header Section -->
<div class="card card-outline card-primary mb-4"
     data-kegiatan-id="{{ $kegiatan->id }}"
     data-kegiatan-nama="{{ $kegiatan->activity->nama ?? $kegiatan->nama }}"
     data-kegiatan-kode="{{ $kegiatan->activity->kode ?? $kegiatan->kode }}"
     data-kegiatan-status="{{ $kegiatan->status ?? '-' }}"
     data-kegiatan-start="{{ $kegiatan->tanggalmulai ?? '-' }}"
     data-kegiatan-end="{{ $kegiatan->tanggalselesai ?? '-' }}"
     data-kegiatan-budget="{{ $kegiatan->totalnilai ?? '-' }}"
     data-kegiatan-description="{{ str_replace('"', '&quot;', strip_tags($kegiatan->deskripsi ?? $kegiatan->activity->deskripsi ?? '')) }}">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div>
                    <h3 class="mb-0">{{ $kegiatan->activity->nama ?? $kegiatan->nama }}</h3>
                    <p class="mb-0 text-muted">{{ __('cruds.form.kode') }}: {{ $kegiatan->activity->kode ?? $kegiatan->kode }}</p>
                </div>
            </div>
            <div class="card-tools">
                <span class="badge badge-lg bg-info">{{ strtoupper($kegiatan->status ?? '-') }}</span>
                @can('kegiatan_edit')
                <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-sm btn-outline-primary ml-2">
                    <i class="fas fa-edit"></i> {{ __('global.edit') }}
                </a>
                @endcan
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Quick Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Beneficiaries</span>
                        <span class="info-box-number">{{ $kegiatan->totalBeneficiaries ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Duration</span>
                        <span class="info-box-number">{{ $durationInDays ?? '-' }} Days</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Locations</span>
                        <span class="info-box-number">{{ $kegiatan->lokasi->count() ?? '-' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Budget</span>
                        <span class="info-box-number">{{ number_format($kegiatan->totalnilai ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kegiatan Details Tabs -->
<div class="card">
    <div class="card-header p-0">
        <ul class="nav nav-tabs" id="kegiatanTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab">
                    <i class="fas fa-info-circle"></i> Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="beneficiaries-tab" data-toggle="tab" href="#beneficiaries" role="tab">
                    <i class="fas fa-users"></i> Beneficiaries
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="team-tab" data-toggle="tab" href="#team" role="tab">
                    <i class="fas fa-user-friends"></i> Team
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="partners-tab" data-toggle="tab" href="#partners" role="tab">
                    <i class="fas fa-handshake"></i> Partners
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="locations-tab" data-toggle="tab" href="#locations" role="tab">
                    <i class="fas fa-map-marked-alt"></i> Locations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents" role="tab">
                    <i class="fas fa-folder-open"></i> Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="progress-tab" data-toggle="tab" href="#progress" role="tab">
                    <i class="fas fa-chart-line"></i> Hasil Kegiatan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab">
                    <i class="fas fa-tasks"></i> Outputs & Activities Data
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="kegiatanTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-6">
                        <div class="card card-outline card-danger">
                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ __('cruds.kegiatan.tabs.description') }}</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $kegiatan->deskripsi ?? $kegiatan->activity->deskripsi ?? 'No description available' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ __('cruds.program.output.indicator') . ' '. __('cruds.program.output.label') }}</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $kegiatan->analisamasalah ?? $kegiatan->activity->indikator ?? 'No problem analysis available' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Kegiatan Summary</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $act = $kegiatan->programOutcomeOutputActivity;
                                    $out = optional($act)->program_outcome_output;
                                    $oc  = optional($out)->program_outcome;
                                    $prg = optional($oc)->program;
                                    $docsCount  = is_countable($dokumenPendukung ?? []) ? $dokumenPendukung->count() : 0;
                                    $mediaCount = is_countable($mediaPendukung ?? []) ? $mediaPendukung->count() : 0;
                                @endphp
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <h6 class="mb-1"><i class="fas fa-project-diagram mr-1"></i>{{ $act->nama ?? '-' }} <small class="text-muted">{{ $act->kode ? '— '.$act->kode : '' }}</small></h6>
                                            <div class="text-muted small">Program: {{ ($prg->kode ? $prg->kode.' — ' : '') . ($prg->nama ?? '-') }}</div>
                                        </div>
                                        <div class="col-sm-4 text-right">
                                            <span class="badge badge-info">{{ $kegiatan->status ?? '-' }}</span>
                                            <div class="mt-1 small text-muted">By {{ $kegiatan->user->nama ?? '-' }}</div>
                                        </div>
                                    </div>

                                    <div class="row invoice-info mt-2">
                                        <div class="col-sm-4 invoice-col">
                                            <strong>Program Hierarchy</strong>
                                            <address class="mb-1">
                                                Outcome: {{ $oc->deskripsi ?? ($oc->nama ?? '-') }}<br>
                                                Output: {{ $out->deskripsi ?? ($out->nama ?? '-') }}
                                            </address>
                                        </div>
                                        <div class="col-sm-4 invoice-col">
                                            <strong>Schedule</strong>
                                            <p class="mb-1">
                                                {{ optional($kegiatan->tanggalmulai)->format('Y-m-d') ?? '-' }} → {{ optional($kegiatan->tanggalselesai)->format('Y-m-d') ?? '-' }}
                                                <small class="text-muted d-block">Duration: {{ $durationInDays ?? '-' }} days</small>
                                            </p>
                                            @if($kegiatan->lokasi && $kegiatan->lokasi->count())
                                                @php $first = $kegiatan->lokasi->first(); @endphp
                                                <small class="text-muted d-block">
                                                    {{ $first->desa->nama ?? '-' }}, {{ $first->desa->kecamatan->nama ?? '-' }}
                                                </small>
                                                <a href="#locations" class="small">See all locations ({{ $kegiatan->lokasi->count() }})</a>
                                            @endif
                                        </div>
                                        <div class="col-sm-4 invoice-col">
                                            <strong>Classification</strong>
                                            <p class="mb-1">
                                                Jenis: <span class="badge badge-primary">{{ $kegiatan->jenisKegiatan->nama ?? '-' }}</span>
                                            </p>
                                            <p class="mb-1">
                                                Fase: <span class="badge badge-secondary">{{ $kegiatan->fasepelaporan ?? '-' }}</span>
                                            </p>
                                            <p class="mb-0 small text-muted">Attachments — Docs: {{ $docsCount }}, Media: {{ $mediaCount }}</p>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-sm-6">
                                            <strong>Mitra</strong>
                                            @if($kegiatan->mitra && $kegiatan->mitra->count())
                                                <ul class="list-unstyled small mb-0">
                                                    @foreach($kegiatan->mitra->take(5) as $m)
                                                        <li>{{ $m->nama }}</li>
                                                    @endforeach
                                                    @if($kegiatan->mitra->count() > 5)
                                                        <li class="text-muted">+{{ $kegiatan->mitra->count() - 5 }} more</li>
                                                    @endif
                                                </ul>
                                            @else
                                                <div class="small text-muted">—</div>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                            <strong>Sektor (Reinstra)</strong>
                                            @if($kegiatan->sektor && $kegiatan->sektor->count())
                                                <ul class="list-unstyled small mb-0">
                                                    @foreach($kegiatan->sektor->take(5) as $s)
                                                        <li>{{ $s->nama }}</li>
                                                    @endforeach
                                                    @if($kegiatan->sektor->count() > 5)
                                                        <li class="text-muted">+{{ $kegiatan->sektor->count() - 5 }} more</li>
                                                    @endif
                                                </ul>
                                            @else
                                                <div class="small text-muted">—</div>
                                            @endif
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Beneficiaries Tab -->
            <div class="tab-pane fade" id="beneficiaries" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ __('cruds.kegiatan.peserta.label') }}</h5>
                            </div>
                            <div class="card-body">
                                <!-- Chart Peserta -->
                                <canvas id="pesertaBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ __('cruds.kegiatan.peserta.label') }}</h5>
                            </div>
                            <div class="card-body">
                                <!-- Chart Peserta - Kegiatan -->
                                <canvas id="pesertaDoughnutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('cruds.kegiatan.peserta.label') }}
                            <i class="fas fa-info-circle text-success" data-toggle="tooltip"
                            title="{{ __('cruds.kegiatan.peserta.helper') }}"></i>
                        </h5>
                    </div>
                    {{-- Beneficiaries Breakdown --}}
                    <div class="card-body">
                        <!-- Jumlah Peserta Kegiatan -->
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
                </div>
            </div>
            <!-- Team Tab -->
            <div class="tab-pane fade" id="team" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Kegiatan Team</h5>
                    </div>
                    <div class="card-body">
                        @if($kegiatan->datapenulis && count($kegiatan->datapenulis) > 0)
                            <ul>
                                @foreach($kegiatan->datapenulis as $penulis)
                                    <li>{{ $penulis->nama ?? '' }} ({{ $penulis->kegiatanPeran->nama ?? '' }})</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>No team members assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Partners Tab -->
            <div class="tab-pane fade" id="partners" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Partners</h5>
                    </div>
                    <div class="card-body">
                        @if($kegiatan->mitra && count($kegiatan->mitra) > 0)
                            <ul>
                                @foreach($kegiatan->mitra as $mitra)
                                    <li>{{ $mitra->nama ?? '' }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-handshake fa-3x mb-3"></i>
                                <p>No partners assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Locations Tab -->
            {{-- <div class="tab-pane fade" id="locations" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Locations</h5>
                    </div>
                    <div class="card-body">
                        @if($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
                            <ul>
                                @foreach($kegiatan->lokasi as $lokasi)
                                    <li>
                                        {{ $lokasi->desa->nama ?? '-' }}, {{ $lokasi->desa->kecamatan->nama ?? '-' }}, {{ $lokasi->desa->kecamatan->kabupaten->nama ?? '-' }}, {{ $lokasi->desa->kecamatan->kabupaten->provinsi->nama ?? '-' }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                                <p>No locations assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div> --}}

            <div class="tab-pane fade" id="locations" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Locations</h5>
                    </div>
                    <div class="card-body">
                        @if($kegiatan->lokasi && count($kegiatan->lokasi) > 0)
                            <ul>
                                @foreach($kegiatan->lokasi as $lokasi)
                                    <li>
                                        {{-- Administrative hierarchy --}}
                                        {{ $lokasi->desa->nama ?? '-' }},
                                        {{ $lokasi->desa->kecamatan->nama ?? '-' }},
                                        {{ $lokasi->desa->kecamatan->kabupaten->nama ?? '-' }},
                                        {{ $lokasi->desa->kecamatan->kabupaten->provinsi->nama ?? '-' }}

                                        {{-- Coordinates + Map link --}}
                                        @if ($lokasi->lat && $lokasi->long)
                                            <br>
                                            <a href="https://www.google.com/maps?q={{ $lokasi->lat }},{{ $lokasi->long }}"
                                            target="_blank">
                                                📍 {{ ucwords(strtolower($lokasi->lokasi ?? 'Lihat di Peta')) }}
                                            </a>
                                            <small>
                                                (Lat: {{ $lokasi->lat }}, Long: {{ $lokasi->long }})
                                            </small>
                                        @else
                                            <br>
                                            <small>Coordinates not available</small>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                                <p>No locations assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Documents</h5>
                    </div>
                    <div class="card-body">
                        @if($dokumenPendukung && count($dokumenPendukung) > 0)
                            <ul>
                                @foreach($dokumenPendukung as $doc)
                                    <li><a href="{{ $doc->getUrl() }}" target="_blank">{{ $doc->name }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3"></i>
                                <p>No documents uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Progress Tab -->
            <div class="tab-pane fade" id="progress" role="tabpanel">
                    @if($kegiatanRelation)
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Hasil Kegiatan</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <strong><i class="fas fa-building mr-1"></i> Lembaga Terlibat</strong>
                                <p class="text-muted">{!! $kegiatanRelation->kunjunganlembaga !!}</p>
                                <hr>

                                <strong><i class="fas fa-users mr-1"></i> Peserta Kegiatan</strong>
                                <p class="text-muted">{!! $kegiatanRelation->kunjunganpeserta !!}</p>
                                <hr>

                                <strong><i class="fas fa-tasks mr-1"></i> Kegiatan yang Dilakukan</strong>
                                <p class="text-muted">{!! $kegiatanRelation->kunjunganyangdilakukan !!}</p>
                                <hr>

                                <strong><i class="fas fa-flag-checkered mr-1"></i> Hasil Kegiatan</strong>
                                <p class="text-muted">{!! $kegiatanRelation->kunjunganhasil !!}</p>
                                <hr>

                                <strong><i class="fas fa-coins mr-1"></i> Potensi Pendapatan</strong>
                                <p class="text-muted">{!! $kegiatanRelation->kunjunganpotensipendapatan !!}</p>
                                <hr>

                                <strong><i class="fas fa-calendar-check mr-1"></i> Rencana Tindak Lanjut</strong>
                                <p class="text-muted">{!! $kegiatanRelation->kunjunganrencana !!}</p>
                                <hr>

                                @if($kegiatanRelation->kunjungankendala)
                                    <strong><i class="fas fa-exclamation-triangle mr-1"></i> Kendala</strong>
                                    <p class="text-muted">{!! $kegiatanRelation->kunjungankendala !!}</p>
                                    <hr>
                                @endif

                                <strong><i class="fas fa-lightbulb mr-1"></i> Isu & Rekomendasi</strong>
                                <p class="text-muted">{!! $kegiatanRelation->kunjunganisu !!}</p>
                                <hr>

                                <strong><i class="fas fa-book-reader mr-1"></i> Pembelajaran</strong>
                                <p class="text-muted">{!! $kegiatanRelation->kunjunganpembelajaran !!}</p>

                            </div>
                            <!-- /.card-body -->
                            {{-- <hr> --}}
                        </div>
                        <small class="text-muted">
                            Dibuat: {{ \Carbon\Carbon::parse($kegiatanRelation->created_at)->translatedFormat('d F Y H:i') }}<br>
                            Diperbarui: {{ \Carbon\Carbon::parse($kegiatanRelation->updated_at)->translatedFormat('d F Y H:i') }}
                        </small>
                        @else
                        <div class="text-center text-muted">
                            <i class="fas fa-chart-line fa-3x mb-3"></i>
                            <p>No Data</p>
                        </div>
                    @endif
            </div>


            <!-- Activities Tab -->
            <div class="tab-pane fade" id="activities" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Activities</h5>
                            @if($kegiatan->programOutcomeOutputActivity)
                                @php
                                    $act = $kegiatan->programOutcomeOutputActivity;
                                    $out = optional($act->program_outcome_output);
                                    $oc  = optional($out->program_outcome);
                                    $prg = optional($oc->program);
                                @endphp
                                <span class="text-muted small">
                                    {{ $prg->kode ?? '-' }} › {{ $prg->nama ?? '-' }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if($kegiatan->programOutcomeOutputActivity)
                            @php
                                $act = $kegiatan->programOutcomeOutputActivity;
                                $out = optional($act->program_outcome_output);
                                $oc  = optional($out->program_outcome);
                                $prg = optional($oc->program);
                            @endphp

                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-striped mb-3">
                                        <tbody>
                                            <tr>
                                                <th style="width:35%">Program</th>
                                                <td>{{ ($prg->kode ? $prg->kode.' — ' : '') . ($prg->nama ?? '-') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Outcome</th>
                                                <td>{{ $oc->deskripsi ?? ($oc->nama ?? '-') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Output</th>
                                                <td>{{ $out->deskripsi ?? ($out->nama ?? '-') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-striped mb-3">
                                        <tbody>
                                            <tr>
                                                <th style="width:35%">Activity Code</th>
                                                <td>{{ $act->kode ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Activity Name</th>
                                                <td>{{ $act->nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Indicator</th>
                                                <td>{{ $act->indikator ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Target</th>
                                                <td>{{ $act->target ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            @if(!empty($act->deskripsi))
                                <div class="mt-2">
                                    <h6 class="text-secondary mb-2"><i class="fas fa-align-left mr-1"></i> Description</h6>
                                    <div class="border rounded p-2 bg-light">{!! nl2br(e($act->deskripsi)) !!}</div>
                                </div>
                            @endif

                            {{-- Peer Kegiatan under the same Activity --}}
                            @php
                                $peers = optional($act)->kegiatan ? $act->kegiatan->where('id', '!=', $kegiatan->id) : collect();
                            @endphp
                            @if($peers && $peers->count() > 0)
                                <div class="mt-4">
                                    <h6 class="mb-2"><i class="fas fa-link mr-1"></i> Other Activities Under This Output Activity</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th style="width:10%">ID</th>
                                                    <th>Name</th>
                                                    <th style="width:20%">Status</th>
                                                    <th style="width:15%">Start</th>
                                                    <th style="width:15%">End</th>
                                                    <th style="width:10%" class="text-right">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($peers as $p)
                                                    <tr>
                                                        <td>#{{ $p->id }}</td>
                                                        <td>{{ $p->nama ?? $p->activity->nama ?? '-' }}</td>
                                                        <td><span class="badge badge-secondary">{{ ucfirst($p->status ?? '-') }}</span></td>
                                                        <td>{{ optional($p->tanggalmulai)->format('Y-m-d') ?? '-' }}</td>
                                                        <td>{{ optional($p->tanggalselesai)->format('Y-m-d') ?? '-' }}</td>
                                                        <td class="text-right">
                                                            <a href="{{ route('kegiatan.show', $p->id) }}" class="btn btn-xs btn-outline-primary">View</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Related Files --}}
                            @php
                                $docs = $dokumenPendukung ?? [];
                                $media = $mediaPendukung ?? [];
                            @endphp
                            @if((is_countable($docs) && count($docs) > 0) || (is_countable($media) && count($media) > 0))
                                <div class="mt-4">
                                    <h6 class="mb-2"><i class="fas fa-paperclip mr-1"></i> Attachments</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Documents</h6>
                                            @if(is_countable($docs) && count($docs) > 0)
                                                <ul class="mb-3" style="list-style: disc; padding-left: 1.25rem;">
                                                    @foreach($docs as $doc)
                                                        <li>
                                                            <a href="{{ $doc->getUrl() }}" target="_blank">{{ $doc->getCustomProperty('keterangan') ?: $doc->name }}</a>
                                                            <small class="text-muted"> ({{ number_format($doc->size/1024, 0) }} KB)</small>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">No documents</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Media</h6>
                                            @if(is_countable($media) && count($media) > 0)
                                                <ul class="mb-0" style="list-style: disc; padding-left: 1.25rem;">
                                                    @foreach($media as $m)
                                                        <li>
                                                            <a href="{{ $m->getUrl() }}" target="_blank">{{ $m->getCustomProperty('keterangan') ?: $m->name }}</a>
                                                            <small class="text-muted"> ({{ number_format($m->size/1024, 0) }} KB)</small>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">No media</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-tasks fa-3x mb-3"></i>
                                <p>No activities data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var labels = [
                'Dewasa',
                'Lansia',
                'Remaja',
                'Anak',
                'Disabilitas',
                'Non-Disabilitas',
                'Marjinal'
            ];
            var dataPeserta = [
                {{ $kegiatan->penerimamanfaatdewasatotal ?? 0 }},
                {{ $kegiatan->penerimamanfaatlansiatotal ?? 0 }},
                {{ $kegiatan->penerimamanfaatremajatotal ?? 0 }},
                {{ $kegiatan->penerimamanfaatanaktotal ?? 0 }},
                {{ $kegiatan->penerimamanfaatdisabilitastotal ?? 0 }},
                {{ $kegiatan->penerimamanfaatnondisabilitastotal ?? 0 }},
                {{ $kegiatan->penerimamanfaatmarjinaltotal ?? 0 }}
            ];
            var colors = [
                '#007bff', '#28a745', '#ffc107', '#17a2b8', '#6f42c1', '#fd7e14', '#20c997'
            ];

            // Bar Chart
            new Chart(document.getElementById('pesertaBarChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Peserta',
                        backgroundColor: colors,
                        data: dataPeserta
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'Bar Chart Peserta' }
                    },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Doughnut Chart
            new Chart(document.getElementById('pesertaDoughnutChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Peserta',
                        backgroundColor: colors,
                        data: dataPeserta
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Doughnut Chart Peserta' }
                    }
                }
            });

            // Pie Chart
            new Chart(document.getElementById('pesertaPieChart').getContext('2d'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Peserta',
                        backgroundColor: colors,
                        data: dataPeserta
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'Pie Chart Peserta' }
                    }
                }
            });
        });
    </script>
    <script>
        // Activate tab from hash and keep URL updated for better navigation
        (function() {
            const hash = window.location.hash;
            if (hash) {
                const $tab = $("a[href='" + hash + "']");
                if ($tab.length) { $tab.tab('show'); }
            }
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                history.replaceState(null, null, e.target.getAttribute('href'));
            });
        })();
    </script>
@endpush
