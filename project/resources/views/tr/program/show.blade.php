@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.program.title'))
@section('content_header_title', __('global.details') . ' ' . __('cruds.program.title'))

@section('content_body')

<!-- Program Header Section -->
<div class="card card-outline card-primary mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <a class="btn btn-outline-secondary mr-3" href="{{ route('program.index') }}">
                <i class="fas fa-arrow-left"></i> {{ __('global.back') }}
            </a>
            <div>
                <h2 class="mb-0">{{ $program->nama }}</h2>
                <p class="mb-0 text-muted">Code: {{ $program->kode }}</p>
            </div>
        </div>
        <div class="card-tools">
            <span class="badge badge-lg {{ $program->status === 'running' ? 'bg-success' : ($program->status === 'pending' ? 'bg-warning' : ($program->status === 'complete' ? 'bg-info' : 'bg-secondary')) }}">
                {{ strtoupper($program->status) }}
            </span>
            @can('program_edit')
            <a href="{{ route('program.edit', $program->id) }}" class="btn btn-sm btn-outline-primary ml-2">
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
                        <span class="info-box-number">{{ $totalBeneficiaries }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Duration</span>
                        <span class="info-box-number">{{ $durationInDays }}</span>
                        <span class="info-box-text">days</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-map-marker-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Locations</span>
                        <span class="info-box-number">{{ $program->lokasi()->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Budget</span>
                        <span class="info-box-number">{{ number_format($program->totalnilai, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Info -->
        <div class="row">
            <div class="col-md-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h4>Start Date</h4>
                        <p>{{ $program->tanggalmulai }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h4>End Date</h4>
                        <p>{{ $program->tanggalselesai }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-stop"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Program Details Tabs -->
<div class="card">
    <div class="card-header p-0">
        <ul class="nav nav-tabs" id="programTabs" role="tablist">
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
                <a class="nav-link" id="structure-tab" data-toggle="tab" href="#structure" role="tab">
                    <i class="fas fa-sitemap"></i> Structure
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
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="programTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Program Description</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $program->deskripsiprojek ?: 'No description available' }}</p>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Problem Analysis</h5>
                            </div>
                            <div class="card-body">
                                <p>{{ $program->analisamasalah ?: 'No problem analysis available' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Program Details</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th>Status:</th>
                                        <td><span class="badge badge-{{ $program->status === 'running' ? 'success' : ($program->status === 'pending' ? 'warning' : 'secondary') }}">{{ $program->status }}</span></td>
                                    </tr>
                                    <tr>
                                        <th>Created:</th>
                                        <td>{{ $program->created_at->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated:</th>
                                        <td>{{ $program->updated_at->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Created By:</th>
                                        <td>{{ $program->users->name ?? 'Unknown' }}</td>
                                    </tr>
                                </table>
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
                                <h5 class="card-title mb-0">Beneficiaries Breakdown</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="beneficiariesChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Beneficiary Details</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Women:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaatwoman ?: 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Men:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaatman ?: 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Girls:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaatgirl ?: 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Boys:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaatboy ?: 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>Indirect:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaattidaklangsung ?: 0 }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <th><strong>Total:</strong></th>
                                        <td><strong>{{ $totalBeneficiaries }}</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Tab -->
            <div class="tab-pane fade" id="team" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Program Team</h5>
                    </div>
                    <div class="card-body">
                        @if($program->staff->count() > 0)
                            <div class="row">
                                @foreach($program->staff as $staff)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <div class="mb-3">
                                                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" 
                                                         style="width: 64px; height: 64px;">
                                                        <span class="text-white h4 mb-0">{{ strtoupper(substr($staff->name, 0, 1)) }}</span>
                                                    </div>
                                                </div>
                                                <h6 class="card-title">{{ $staff->name }}</h6>
                                                <p class="text-muted">{{ $staff->email }}</p>
                                                <span class="badge badge-info">{{ $staff->pivot->peran_id ? (\App\Models\Peran::find($staff->pivot->peran_id)->nama ?? 'Team Member') : 'Team Member' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Partner Organizations</h5>
                            </div>
                            <div class="card-body">
                                @if($program->partner->count() > 0)
                                    @foreach($program->partner as $partner)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $partner->nama }}</h6>
                                                <p class="card-text text-muted">{{ $partner->alamat ?: 'No address available' }}</p>
                                                @if($partner->telepon)
                                                    <p class="mb-0"><i class="fas fa-phone"></i> {{ $partner->telepon }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-handshake fa-3x mb-3"></i>
                                        <p>No partners assigned</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Donors</h5>
                            </div>
                            <div class="card-body">
                                @if($program->pendonor->count() > 0)
                                    @foreach($program->pendonor as $pendonor)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $pendonor->nama }}</h6>
                                                <p class="card-text text-muted">{{ $pendonor->kategori ? $pendonor->kategori->nama : 'Uncategorized' }}</p>
                                                @if($pendonor->pivot->nilaidonasi)
                                                    <p class="mb-0"><strong>Donation:</strong> Rp {{ number_format($pendonor->pivot->nilaidonasi, 0, ',', '.') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-donate fa-3x mb-3"></i>
                                        <p>No donors assigned</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Locations Tab -->
            <div class="tab-pane fade" id="locations" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Implementation Locations</h5>
                    </div>
                    <div class="card-body">
                        @if($program->lokasi->count() > 0)
                            <div class="row">
                                @foreach($program->lokasi as $lokasi)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $lokasi->nama }}</h6>
                                                <p class="text-muted mb-0">Province: {{ $lokasi->nama }}</p>
                                                <small class="text-muted">ID: {{ $lokasi->id }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                                <p>No locations specified</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Structure Tab -->
            <div class="tab-pane fade" id="structure" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Program Structure Hierarchy</h5>
                            </div>
                            <div class="card-body">
                                @if($program->goal || $program->objektif || $program->outcome->count() > 0)
                                    <div class="program-structure">
                                        <!-- Goal Section -->
                                        @if($program->goal)
                                            <div class="structure-item goal-level mb-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="structure-icon bg-primary">
                                                        <i class="fas fa-bullseye text-white"></i>
                                                    </div>
                                                    <div class="structure-content flex-grow-1">
                                                        <h6 class="mb-1">Goal</h6>
                                                        <p class="mb-1">{{ $program->goal->deskripsi ?? 'No description available' }}</p>
                                                        <div class="structure-meta">
                                                            <small class="text-muted">
                                                                <strong>Indicator:</strong> {{ $program->goal->indikator ?? 'Not specified' }}<br>
                                                                <strong>Target:</strong> {{ $program->goal->target ?? 'Not set' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Objective Section -->
                                        @if($program->objektif)
                                            <div class="structure-item objective-level mb-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="structure-icon bg-success">
                                                        <i class="fas fa-crosshairs text-white"></i>
                                                    </div>
                                                    <div class="structure-content flex-grow-1">
                                                        <h6 class="mb-1">Objective</h6>
                                                        <p class="mb-1">{{ $program->objektif->deskripsi ?? 'No description available' }}</p>
                                                        <div class="structure-meta">
                                                            <small class="text-muted">
                                                                <strong>Indicator:</strong> {{ $program->objektif->indikator ?? 'Not specified' }}<br>
                                                                <strong>Target:</strong> {{ $program->objektif->target ?? 'Not set' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Outcomes Section -->
                                        @if($program->outcome->count() > 0)
                                            <div class="outcomes-section">
                                                <h6 class="text-primary mb-3">Outcomes</h6>
                                                @foreach($program->outcome as $outcome)
                                                    <div class="structure-item outcome-level mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="structure-icon bg-info">
                                                                <i class="fas fa-trophy text-white"></i>
                                                            </div>
                                                            <div class="structure-content flex-grow-1">
                                                                <h6 class="mb-1">Outcome</h6>
                                                                <p class="mb-1">{{ $outcome->deskripsi ?? 'No description available' }}</p>
                                                                <div class="structure-meta">
                                                                    <small class="text-muted">
                                                                        <strong>Indicator:</strong> {{ $outcome->indikator ?? 'Not specified' }}<br>
                                                                        <strong>Target:</strong> {{ $outcome->target ?? 'Not set' }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Outputs Section -->
                                                        @if($outcome->output->count() > 0)
                                                            <div class="outputs-section ml-4 mt-3">
                                                                <h6 class="text-success mb-2">Outputs</h6>
                                                                @foreach($outcome->output as $output)
                                                                    <div class="structure-item output-level mb-2">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="structure-icon bg-warning">
                                                                                <i class="fas fa-cube text-white"></i>
                                                                            </div>
                                                                            <div class="structure-content flex-grow-1">
                                                                                <h6 class="mb-1">Output</h6>
                                                                                <p class="mb-1">{{ $output->deskripsi ?? 'No description available' }}</p>
                                                                                <div class="structure-meta">
                                                                                    <small class="text-muted">
                                                                                        <strong>Indicator:</strong> {{ $output->indikator ?? 'Not specified' }}<br>
                                                                                        <strong>Target:</strong> {{ $output->target ?? 'Not set' }}
                                                                                    </small>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Activities Section -->
                                                                        @if($output->activities->count() > 0)
                                                                            <div class="activities-section ml-4 mt-2">
                                                                                <h6 class="text-info mb-2">Activities</h6>
                                                                                @foreach($output->activities as $activity)
                                                                                    <div class="structure-item activity-level mb-2">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="structure-icon bg-secondary">
                                                                                                <i class="fas fa-tasks text-white"></i>
                                                                                            </div>
                                                                                            <div class="structure-content flex-grow-1">
                                                                                                <h6 class="mb-1">Activity</h6>
                                                                                                <p class="mb-1">{{ $activity->deskripsi ?? 'No description available' }}</p>
                                                                                                <div class="structure-meta">
                                                                                                    <small class="text-muted">
                                                                                                        <strong>Indicator:</strong> {{ $activity->indikator ?? 'Not specified' }}<br>
                                                                                                        <strong>Target:</strong> {{ $activity->target ?? 'Not set' }}
                                                                                                    </small>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-sitemap fa-3x mb-3"></i>
                                        <h5>No Program Structure Defined</h5>
                                        <p>This program doesn't have any goals, objectives, outcomes, or activities defined yet.</p>
                                        <small>Program structure helps organize and track program implementation.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Program Documents</h5>
                                @can('program_edit')
                                <button class="btn btn-sm btn-primary" onclick="uploadDocument()">
                                    <i class="fas fa-upload"></i> Upload Document
                                </button>
                                @endcan
                            </div>
                            <div class="card-body">
                                @php
                                    $mediaFiles = $program->getMedia('file_pendukung_program');
                                @endphp
                                
                                @if($mediaFiles->count() > 0)
                                    <div class="row">
                                        @foreach($mediaFiles as $media)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card document-card">
                                                    <div class="card-body">
                                                        <div class="document-icon text-center mb-3">
                                                            @if(str_starts_with($media->mime_type, 'image/'))
                                                                <i class="fas fa-image fa-3x text-primary"></i>
                                                            @elseif($media->mime_type === 'application/pdf')
                                                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                            @elseif(in_array($media->mime_type, ['application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation']))
                                                                <i class="fas fa-file-powerpoint fa-3x text-warning"></i>
                                                            @elseif(in_array($media->mime_type, ['application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                                                                <i class="fas fa-file-word fa-3x text-info"></i>
                                                            @elseif(in_array($media->mime_type, ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']))
                                                                <i class="fas fa-file-excel fa-3x text-success"></i>
                                                            @else
                                                                <i class="fas fa-file fa-3x text-secondary"></i>
                                                            @endif
                                                        </div>
                                                        <h6 class="card-title text-truncate" title="{{ $media->name }}">
                                                            {{ $media->name }}
                                                        </h6>
                                                        <p class="card-text">
                                                            <small class="text-muted">
                                                                {{ $media->getCustomProperty('keterangan') ?: 'No description' }}
                                                            </small>
                                                        </p>
                                                        <div class="document-meta">
                                                            <small class="text-muted d-block">
                                                                <i class="fas fa-calendar"></i> {{ $media->created_at->format('d M Y') }}
                                                            </small>
                                                            <small class="text-muted d-block">
                                                                <i class="fas fa-hdd"></i> {{ $media->human_readable_size }}
                                                            </small>
                                                        </div>
                                                        <div class="document-actions mt-3">
                                                            <div class="btn-group btn-group-sm w-100">
                                                                <a href="{{ $media->getUrl() }}" class="btn btn-outline-primary" target="_blank">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                                <a href="{{ $media->getUrl() }}" class="btn btn-outline-success" download>
                                                                    <i class="fas fa-download"></i> Download
                                                                </a>
                                                                @can('program_edit')
                                                                <button class="btn btn-outline-danger" onclick="deleteDocument({{ $media->id }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                                        <h5>No Documents Available</h5>
                                        <p>This program doesn't have any supporting documents yet.</p>
                                        <small>Upload documents to support program implementation and reporting.</small>
                                        @can('program_edit')
                                        <button class="btn btn-primary mt-3" onclick="uploadDocument()">
                                            <i class="fas fa-upload"></i> Upload First Document
                                        </button>
                                        @endcan
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Tab -->
            <div class="tab-pane fade" id="progress" role="tabpanel">
                <div class="row">
                    <!-- Progress Overview -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Progress Overview</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="progress-circle position-relative d-inline-block">
                                        <canvas id="progressChart" width="200" height="200"></canvas>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <h3 class="mb-0">{{ $program->status === 'complete' ? '100' : ($program->status === 'running' ? '75' : ($program->status === 'pending' ? '25' : '0')) }}%</h3>
                                            <small class="text-muted">Complete</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress-details">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Status:</span>
                                        <span class="badge badge-{{ $program->status === 'running' ? 'success' : ($program->status === 'pending' ? 'warning' : 'secondary') }}">{{ ucfirst($program->status) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Duration:</span>
                                        <span>{{ $durationInDays }} days</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Target Progress:</span>
                                        <span>{{ $program->targetProgresses->count() }} metrics</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Target Progress -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Target Progress Details</h5>
                            </div>
                            <div class="card-body">
                                @if($program->targetProgresses->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Metric</th>
                                                    <th>Target</th>
                                                    <th>Achieved</th>
                                                    <th>Progress</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($program->targetProgresses as $progress)
                                                    @foreach($progress->details as $detail)
                                                        <tr>
                                                            <td>{{ $detail->targetable->deskripsi ?? 'Unknown Target' }}</td>
                                                            <td>{{ $detail->targetable->target ?? 0 }}</td>
                                                            <td>{{ $detail->actual ?? 0 }}</td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    @php
                                                                        $targetValue = (float)($detail->targetable->target ?? 0);
                                                                        $actualValue = (float)($detail->actual ?? 0);
                                                                        $percentage = ($targetValue > 0) ? 
                                                                            min(($actualValue / $targetValue) * 100, 100) : 0;
                                                                    @endphp
                                                                    <div class="progress-bar bg-{{ $percentage >= 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}" 
                                                                         role="progressbar" 
                                                                         style="width: {{ $percentage }}%" 
                                                                         aria-valuenow="{{ $percentage }}" 
                                                                         aria-valuemin="0" 
                                                                         aria-valuemax="100">
                                                                        {{ round($percentage) }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-{{ $percentage >= 100 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}">
                                                                    {{ $percentage >= 100 ? 'On Track' : ($percentage >= 50 ? 'In Progress' : 'Behind') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                        <h5>No Progress Data Available</h5>
                                        <p>This program doesn't have any progress tracking data yet.</p>
                                        <small>Progress tracking helps monitor program implementation and achievements.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Timeline -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Program Timeline</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success">
                                            <i class="fas fa-play"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Program Start</h6>
                                            <p class="text-muted">{{ $program->tanggalmulai }}</p>
                                            <small>Program officially began</small>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-{{ $program->status === 'running' ? 'warning' : 'secondary' }}">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Current Phase</h6>
                                            <p class="text-muted">{{ ucfirst($program->status) }}</p>
                                            <small>Program is currently in this phase</small>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-{{ $program->status === 'complete' ? 'success' : 'secondary' }}">
                                            <i class="fas fa-flag-checkered"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6>Program End</h6>
                                            <p class="text-muted">{{ $program->tanggalselesai }}</p>
                                            <small>Expected completion date</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<style>
/* Program Structure Styles */
.program-structure {
    font-family: inherit;
}

.structure-item {
    border-left: 3px solid #e9ecef;
    padding-left: 1rem;
    transition: all 0.3s ease;
}

.structure-item:hover {
    border-left-color: #007bff;
    background-color: rgba(0, 123, 255, 0.05);
    border-radius: 0.375rem;
}

.structure-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.structure-item:hover .structure-icon {
    transform: scale(1.1);
}

.structure-content h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.structure-content p {
    color: #6c757d;
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.structure-meta {
    background-color: #f8f9fa;
    padding: 0.5rem;
    border-radius: 0.25rem;
    border-left: 3px solid #dee2e6;
}

.goal-level .structure-meta {
    border-left-color: #007bff;
}

.objective-level .structure-meta {
    border-left-color: #28a745;
}

.outcome-level .structure-meta {
    border-left-color: #17a2b8;
}

.output-level .structure-meta {
    border-left-color: #ffc107;
}

.activity-level .structure-meta {
    border-left-color: #6c757d;
}

.outcomes-section,
.outputs-section,
.activities-section {
    position: relative;
}

.outcomes-section::before,
.outputs-section::before,
.activities-section::before {
    content: '';
    position: absolute;
    left: -1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.outcomes-section::before {
    background-color: #17a2b8;
}

.outputs-section::before {
    background-color: #ffc107;
}

.activities-section::before {
    background-color: #6c757d;
}

.structure-level-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.structure-level-title::before {
    content: '';
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 0.5rem;
    display: inline-block;
}

.goal-level .structure-level-title::before {
    background-color: #007bff;
}

.objective-level .structure-level-title::before {
    background-color: #28a745;
}

.outcome-level .structure-level-title::before {
    background-color: #17a2b8;
}

.output-level .structure-level-title::before {
    background-color: #ffc107;
}

.activity-level .structure-level-title::before {
    background-color: #6c757d;
}

/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -1.5rem;
    top: 0;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    z-index: 1;
}

.timeline-content {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    border-left: 3px solid #dee2e6;
}

.timeline-content h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.timeline-content p {
    color: #6c757d;
    margin-bottom: 0.25rem;
}

/* Progress Circle Styles */
.progress-circle {
    position: relative;
    display: inline-block;
}

/* Document Card Styles */
.document-card {
    transition: all 0.3s ease;
    height: 100%;
}

.document-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.document-icon {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.document-card:hover .document-icon {
    opacity: 1;
}

.document-card .card-title {
    font-size: 0.9rem;
    font-weight: 600;
}

.document-meta {
    border-top: 1px solid #e9ecef;
    padding-top: 0.5rem;
    margin-top: 0.5rem;
}

.document-actions .btn {
    font-size: 0.8rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    /* Header Section */
    .card-header .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
    }
    
    .card-header .card-tools {
        margin-top: 1rem;
        width: 100%;
    }
    
    .card-header .btn {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Quick Stats */
    .info-box {
        margin-bottom: 1rem;
    }
    
    .info-box .info-box-number {
        font-size: 1.5rem;
    }
    
    /* Tabs Navigation */
    .nav-tabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }
    
    .nav-tabs .nav-link {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
        min-width: auto;
    }
    
    .nav-tabs .nav-link i {
        display: block;
        margin-bottom: 0.25rem;
        font-size: 1rem;
    }
    
    /* Structure Styles */
    .structure-icon {
        width: 32px;
        height: 32px;
    }
    
    .structure-content {
        margin-left: 0.5rem;
    }
    
    .structure-item {
        padding-left: 0.5rem;
    }
    
    .outputs-section,
    .activities-section {
        margin-left: 1rem !important;
    }
    
    /* Timeline Styles */
    .timeline {
        padding-left: 1.5rem;
    }
    
    .timeline-marker {
        left: -1rem;
        width: 1.5rem;
        height: 1.5rem;
    }
    
    .timeline-content {
        padding: 0.75rem;
    }
    
    /* Progress Section */
    .progress-circle canvas {
        width: 150px !important;
        height: 150px !important;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .table .btn {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Document Cards */
    .document-card {
        margin-bottom: 1rem;
    }
    
    .document-icon i {
        font-size: 2rem;
    }
    
    .document-actions .btn {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .document-actions .btn-group {
        flex-direction: column;
    }
    
    .document-actions .btn-group .btn {
        margin-bottom: 0.25rem;
    }
    
    /* Team Cards */
    .team-card .card-body {
        padding: 1rem;
    }
    
    .team-card .rounded-circle {
        width: 48px;
        height: 48px;
    }
    
    .team-card .rounded-circle span {
        font-size: 1.2rem;
    }
    
    /* Partners and Donors */
    .partner-card, .donor-card {
        margin-bottom: 1rem;
    }
    
    /* Location Cards */
    .location-card {
        margin-bottom: 1rem;
    }
    
    /* Chart Containers */
    #beneficiariesChart {
        height: 250px !important;
    }
    
    /* Mobile-specific adjustments */
    .d-md-none {
        display: block !important;
    }
    
    .d-none.d-md-block {
        display: none !important;
    }
    
    /* Button Groups */
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        margin-bottom: 0.25rem;
        border-radius: 0.375rem !important;
    }
    
    .btn-group .btn:not(:last-child) {
        margin-right: 0;
    }
}

/* Extra Small Mobile Devices */
@media (max-width: 576px) {
    .card-header h2 {
        font-size: 1.5rem;
    }
    
    .card-header p {
        font-size: 0.9rem;
    }
    
    .info-box .info-box-text {
        font-size: 0.8rem;
    }
    
    .info-box .info-box-number {
        font-size: 1.25rem;
    }
    
    .nav-tabs .nav-link {
        font-size: 0.7rem;
        padding: 0.4rem 0.6rem;
    }
    
    .nav-tabs .nav-link i {
        font-size: 0.9rem;
    }
    
    .table-responsive {
        font-size: 0.75rem;
    }
    
    .progress {
        height: 15px;
    }
    
    .timeline-content h6 {
        font-size: 0.9rem;
    }
    
    .timeline-content p {
        font-size: 0.8rem;
    }
}
</style>
@endpush

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize beneficiaries chart
    const beneficiariesCtx = document.getElementById('beneficiariesChart');
    if (beneficiariesCtx) {
        new Chart(beneficiariesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Women', 'Men', 'Girls', 'Boys', 'Indirect'],
                datasets: [{
                    data: [
                        {{ $program->ekspektasipenerimamanfaatwoman ?: 0 }},
                        {{ $program->ekspektasipenerimamanfaatman ?: 0 }},
                        {{ $program->ekspektasipenerimamanfaatgirl ?: 0 }},
                        {{ $program->ekspektasipenerimamanfaatboy ?: 0 }},
                        {{ $program->ekspektasipenerimamanfaattidaklangsung ?: 0 }}
                    ],
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Initialize progress chart
    const progressCtx = document.getElementById('progressChart');
    if (progressCtx) {
        const progressPercentage = {{ $program->status === 'complete' ? 100 : ($program->status === 'running' ? 75 : ($program->status === 'pending' ? 25 : 0)) }};
        
        new Chart(progressCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [progressPercentage, 100 - progressPercentage],
                    backgroundColor: [
                        progressPercentage >= 75 ? '#28a745' : (progressPercentage >= 50 ? '#ffc107' : '#dc3545'),
                        '#e9ecef'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });
    }

    // Initialize tab tooltips
    const tabLinks = document.querySelectorAll('#programTabs .nav-link');
    tabLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.setAttribute('title', this.textContent.trim());
        });
    });
});

// Document management functions
function uploadDocument() {
    // For now, redirect to edit page where document upload is available
    window.location.href = '{{ route("program.edit", $program->id) }}#documents';
}

function deleteDocument(mediaId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete document
            fetch(`/program/media/${mediaId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Deleted!',
                        'Document has been deleted.',
                        'success'
                    ).then(() => {
                        // Refresh the page to update documents list
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error!',
                        'Failed to delete document.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error!',
                    'Failed to delete document.',
                    'error'
                );
            });
        }
    });
}
</script>
@endpush
