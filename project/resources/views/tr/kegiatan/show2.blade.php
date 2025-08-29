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
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a class="btn btn-outline-secondary mr-3" href="{{ route('kegiatan.index') }}">
                <i class="fas fa-arrow-left"></i> {{ __('global.back') }}
            </a>
            <div>
                <h2 class="mb-0">{{ $kegiatan->activity->nama ?? $kegiatan->nama }}</h2>
                <p class="mb-0 text-muted">Code: {{ $kegiatan->activity->kode ?? $kegiatan->kode }}</p>
            </div>
        </div>
        <div class="card-tools">
            <span class="badge badge-lg bg-info">{{ strtoupper($kegiatan->status ?? '-') }}</span>
            @can('kegiatan_edit')
            <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-sm btn-outline-primary ml-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endcan
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
                    <i class="fas fa-chart-line"></i> Progress
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab">
                    <i class="fas fa-tasks"></i> Activities
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="kegiatanTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Kegiatan Description</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $kegiatan->deskripsi ?? $kegiatan->activity->deskripsi ?? 'No description available' }}</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Problem Analysis</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $kegiatan->analisamasalah ?? 'No problem analysis available' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Kegiatan Details</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Status:</th>
                                        <td><span class="badge badge-info">{{ $kegiatan->status ?? '-' }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $kegiatan->created_at->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated:</th>
                                        <td>{{ $kegiatan->updated_at->format('d M Y') ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Activity:</th>
                                        <td>{{ $kegiatan->updated_at->diffForHumans() ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created By:</th>
                                        <td>{{ $kegiatan->user->name ?? 'Unknown' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Beneficiaries Tab -->
            <div class="tab-pane fade" id="beneficiaries" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Beneficiaries Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr><th>Women:</th><td>{{ $kegiatan->ekspektasipenerimamanfaatwoman ?? 0 }}</td></tr>
                            <tr><th>Men:</th><td>{{ $kegiatan->ekspektasipenerimamanfaatman ?? 0 }}</td></tr>
                            <tr><th>Girls:</th><td>{{ $kegiatan->ekspektasipenerimamanfaatgirl ?? 0 }}</td></tr>
                            <tr><th>Boys:</th><td>{{ $kegiatan->ekspektasipenerimamanfaatboy ?? 0 }}</td></tr>
                            <tr><th>Indirect:</th><td>{{ $kegiatan->ekspektasipenerimamanfaattidaklangsung ?? 0 }}</td></tr>
                            <tr class="table-active"><th><strong>Total:</strong></th><td><strong>{{ $kegiatan->totalBeneficiaries ?? 0 }}</strong></td></tr>
                        </table>
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Progress</h5>
                    </div>
                    <div class="card-body">
                        @if($kegiatanRelation)
                            <pre>{{ json_encode($kegiatanRelation, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-chart-line fa-3x mb-3"></i>
                                <p>No progress data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Activities Tab -->
            <div class="tab-pane fade" id="activities" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Activities</h5>
                    </div>
                    <div class="card-body">
                        @if($kegiatan->programOutcomeOutputActivity)
                            <pre>{{ json_encode($kegiatan->programOutcomeOutputActivity, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
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
