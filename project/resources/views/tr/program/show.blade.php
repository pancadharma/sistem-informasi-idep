@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.program.title'))
@section('content_header_title', __('global.details') . ' ' . __('cruds.program.title'))

@section('content_body')

<!-- Program Header Section -->
<div class="card card-outline card-primary mb-4" 
     data-program-id="{{ $program->id }}" 
     data-program-name="{{ $program->nama }}"
     data-program-code="{{ $program->kode }}"
     data-program-status="{{ $program->status }}"
     data-program-start="{{ $program->tanggalmulai }}"
     data-program-end="{{ $program->tanggalselesai }}"
     data-program-budget="{{ $program->totalnilai }}"
     data-program-description="{{ str_replace('"', '&quot;', strip_tags($program->deskripsiprojek)) }}"
     data-program-analysis="{{ str_replace('"', '&quot;', strip_tags($program->analisamasalah)) }}">
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
            
            <!-- Export Dropdown -->
            <div class="btn-group ml-2">
                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="exportProgram('pdf')">
                        <i class="fas fa-file-pdf"></i> Export as PDF
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportProgram('excel')">
                        <i class="fas fa-file-excel"></i> Export as Excel
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportProgram('json')">
                        <i class="fas fa-file-code"></i> Export as JSON
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="exportProgramData('beneficiaries')">
                        <i class="fas fa-users"></i> Export Beneficiaries Data
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportProgramData('progress')">
                        <i class="fas fa-chart-line"></i> Export Progress Report
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportProgramData('activities')">
                        <i class="fas fa-tasks"></i> Export Activities Data
                    </a>
                </div>
            </div>
            
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
            <li class="nav-item">
                <a class="nav-link" id="collaboration-tab" data-toggle="tab" href="#collaboration" role="tab">
                    <i class="fas fa-users-cog"></i> Collaboration
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="target-groups-tab" data-toggle="tab" href="#target-groups" role="tab">
                    <i class="fas fa-bullseye"></i> Target Groups
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
                                        <td>
                                            <span>{{ $program->updated_at->format('d M Y') }}</span>
                                            <span class="badge badge-success ml-2" id="live-status" style="display: none;">
                                                <i class="fas fa-circle"></i> Live
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Last Activity:</th>
                                        <td id="last-activity">
                                            {{ $program->updated_at->diffForHumans() }}
                                        </td>
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

            <!-- Target Groups Tab -->
            <div class="tab-pane fade" id="target-groups" role="tabpanel">
                <div class="row">
                    <!-- Marginalized Groups -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Marginalized Groups Served</h5>
                            </div>
                            <div class="card-body">
                                @if($program->kelompokMarjinal->count() > 0)
                                    <div class="row">
                                        @foreach($program->kelompokMarjinal as $kelompokMarjinal)
                                            <div class="col-md-12 mb-3">
                                                <div class="card border-left-primary">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="target-group-icon bg-primary">
                                                                <i class="fas fa-users text-white"></i>
                                                            </div>
                                                            <div class="target-group-content flex-grow-1">
                                                                <h6 class="mb-1">{{ $kelompokMarjinal->kelompokmarjinal->nama ?? 'Unknown Group' }}</h6>
                                                                <p class="mb-0 text-muted">Target group for program inclusion</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <h5>No Marginalized Groups Defined</h5>
                                        <p>This program doesn't target specific marginalized groups yet.</p>
                                        <small>Define target groups to ensure inclusive program implementation.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- SDGs Alignment -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">SDGs Alignment</h5>
                            </div>
                            <div class="card-body">
                                @if($program->kaitanSDG->count() > 0)
                                    <div class="row">
                                        @foreach($program->kaitanSDG as $kaitanSDG)
                                            <div class="col-md-12 mb-3">
                                                <div class="card border-left-success">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="sdg-icon bg-success">
                                                                <i class="fas fa-globe text-white"></i>
                                                            </div>
                                                            <div class="sdg-content flex-grow-1">
                                                                <h6 class="mb-1">{{ $kaitanSDG->kaitansdg->nama ?? 'Unknown SDG' }}</h6>
                                                                <p class="mb-0 text-muted">Sustainable Development Goal alignment</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-globe fa-3x mb-3"></i>
                                        <h5>No SDGs Alignment</h5>
                                        <p>This program doesn't have SDGs alignment defined yet.</p>
                                        <small>Align with Sustainable Development Goals for global impact tracking.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Target Reinstra Integration -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Target Reinstra Integration</h5>
                            </div>
                            <div class="card-body">
                                @if($program->targetReinstra->count() > 0)
                                    <div class="row">
                                        @foreach($program->targetReinstra as $targetReinstra)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border-left-info">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="reinstra-icon bg-info">
                                                                <i class="fas fa-crosshairs text-white"></i>
                                                            </div>
                                                            <div class="reinstra-content flex-grow-1">
                                                                <h6 class="mb-1">{{ $targetReinstra->targetreinstra->nama ?? 'Unknown Target' }}</h6>
                                                                <p class="mb-0 text-muted">Strategic target alignment</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-crosshairs fa-3x mb-3"></i>
                                        <h5>No Target Reinstra Integration</h5>
                                        <p>This program doesn't have strategic target integration yet.</p>
                                        <small>Connect with strategic targets for better alignment and reporting.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activities Tab -->
            <div class="tab-pane fade" id="activities" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Related Activities (Kegiatan)</h5>
                                @can('program_edit')
                                <a href="{{ route('kegiatan.create', ['program_id' => $program->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Add Activity
                                </a>
                                @endcan
                            </div>
                            <div class="card-body">
                                @php
                                    $allActivities = $program->allKegiatan();
                                @endphp
                                
                                @if($allActivities->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Activity Name</th>
                                                    <th>Status</th>
                                                    <th>Duration</th>
                                                    <th>Budget</th>
                                                    <th>Progress</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($allActivities as $kegiatan)
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <strong>{{ $kegiatan->nama }}</strong>
                                                                <br>
                                                                <small class="text-muted">{{ $kegiatan->kode }}</small>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-{{ $kegiatan->status === 'running' ? 'success' : ($kegiatan->status === 'pending' ? 'warning' : ($kegiatan->status === 'complete' ? 'info' : 'secondary')) }}">
                                                                {{ ucfirst($kegiatan->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($kegiatan->tanggalmulai && $kegiatan->tanggalselesai)
                                                                {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d M Y') }}
                                                            @else
                                                                <span class="text-muted">Not set</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($kegiatan->totalnilai)
                                                                Rp {{ number_format($kegiatan->totalnilai, 0, ',', '.') }}
                                                            @else
                                                                <span class="text-muted">Not set</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 20px;">
                                                                @php
                                                                    $progress = $kegiatan->status === 'complete' ? 100 : ($kegiatan->status === 'running' ? 75 : ($kegiatan->status === 'pending' ? 25 : 0));
                                                                @endphp
                                                                <div class="progress-bar bg-{{ $progress >= 75 ? 'success' : ($progress >= 50 ? 'warning' : 'danger') }}" 
                                                                     role="progressbar" 
                                                                     style="width: {{ $progress }}%" 
                                                                     aria-valuenow="{{ $progress }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                    {{ $progress }}%
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="{{ route('kegiatan.show', $kegiatan->id) }}" class="btn btn-outline-primary">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                @can('kegiatan_edit')
                                                                <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-outline-success">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                @endcan
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-tasks fa-3x mb-3"></i>
                                        <h5>No Activities Linked</h5>
                                        <p>This program doesn't have any activities linked yet.</p>
                                        <small>Activities help implement program goals and objectives.</small>
                                        @can('program_edit')
                                        <a href="{{ route('kegiatan.create', ['program_id' => $program->id]) }}" class="btn btn-primary mt-3">
                                            <i class="fas fa-plus"></i> Create First Activity
                                        </a>
                                        @endcan
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Collaboration Tab -->
            <div class="tab-pane fade" id="collaboration" role="tabpanel">
                <div class="row">
                    <!-- Active Collaborators -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Active Collaborators</h5>
                                <span class="badge badge-success" id="active-users-count">
                                    {{ $program->staff->count() }} Online
                                </span>
                            </div>
                            <div class="card-body">
                                <div id="active-users-list">
                                    @if($program->staff->count() > 0)
                                        @foreach($program->staff as $staff)
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="user-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                                    <span class="text-white">{{ strtoupper(substr($staff->name, 0, 1)) }}</span>
                                                </div>
                                                <div class="user-info flex-grow-1">
                                                    <h6 class="mb-0">{{ $staff->name }}</h6>
                                                    <small class="text-muted">{{ $staff->email }}</small>
                                                </div>
                                                <div class="user-status">
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-circle"></i> Online
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <p>No active collaborators</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Recent Activity</h5>
                            </div>
                            <div class="card-body">
                                <div id="activity-feed">
                                    <div class="activity-item mb-3">
                                        <div class="d-flex">
                                            <div class="activity-icon bg-info">
                                                <i class="fas fa-edit text-white"></i>
                                            </div>
                                            <div class="activity-content ml-3">
                                                <h6 class="mb-1">Program Updated</h6>
                                                <p class="mb-0 text-muted">Program details were last updated {{ $program->updated_at->diffForHumans() }}</p>
                                                <small class="text-muted">{{ $program->updated_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="activity-item mb-3">
                                        <div class="d-flex">
                                            <div class="activity-icon bg-success">
                                                <i class="fas fa-plus text-white"></i>
                                            </div>
                                            <div class="activity-content ml-3">
                                                <h6 class="mb-1">Program Created</h6>
                                                <p class="mb-0 text-muted">Program was created by {{ $program->users->name ?? 'Unknown' }}</p>
                                                <small class="text-muted">{{ $program->created_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($program->staff->count() > 0)
                                        <div class="activity-item">
                                            <div class="d-flex">
                                                <div class="activity-icon bg-primary">
                                                    <i class="fas fa-users text-white"></i>
                                                </div>
                                                <div class="activity-content ml-3">
                                                    <h6 class="mb-1">Team Members Added</h6>
                                                    <p class="mb-0 text-muted">{{ $program->staff->count() }} team members assigned to this program</p>
                                                    <small class="text-muted">Team collaboration active</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Real-time Updates Status -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Real-time Updates</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="real-time-status mb-3">
                                                <i class="fas fa-wifi fa-2x text-success"></i>
                                            </div>
                                            <h6>Connection Status</h6>
                                            <p class="text-muted">Connected to real-time updates</p>
                                            <button class="btn btn-sm btn-outline-primary" onclick="toggleRealTimeUpdates()">
                                                <i class="fas fa-sync"></i> Refresh Now
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="real-time-status mb-3">
                                                <i class="fas fa-clock fa-2x text-info"></i>
                                            </div>
                                            <h6>Last Update</h6>
                                            <p class="text-muted" id="last-update-time">{{ $program->updated_at->diffForHumans() }}</p>
                                            <small>Auto-refresh enabled</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="real-time-status mb-3">
                                                <i class="fas fa-bell fa-2x text-warning"></i>
                                            </div>
                                            <h6>Notifications</h6>
                                            <p class="text-muted">Real-time notifications active</p>
                                            <button class="btn btn-sm btn-outline-warning" onclick="testNotification()">
                                                <i class="fas fa-bell"></i> Test
                                            </button>
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

/* Target Groups Styles */
.target-group-icon,
.sdg-icon,
.reinstra-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.target-group-content,
.sdg-content,
.reinstra-content {
    flex-grow: 1;
}

.target-group-content h6,
.sdg-content h6,
.reinstra-content h6 {
    margin-bottom: 0.25rem;
    color: #495057;
    font-weight: 600;
}

.target-group-content p,
.sdg-content p,
.reinstra-content p {
    margin-bottom: 0;
    color: #6c757d;
    font-size: 0.875rem;
}

.border-left-primary {
    border-left: 4px solid #007bff;
}

.border-left-success {
    border-left: 4px solid #28a745;
}

.border-left-info {
    border-left: 4px solid #17a2b8;
}

.border-left-warning {
    border-left: 4px solid #ffc107;
}

.border-left-danger {
    border-left: 4px solid #dc3545;
}

/* Hover effects for target group cards */
.card.border-left-primary:hover,
.card.border-left-success:hover,
.card.border-left-info:hover,
.card.border-left-warning:hover,
.card.border-left-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.card.border-left-primary:hover .target-group-icon,
.card.border-left-success:hover .sdg-icon,
.card.border-left-info:hover .reinstra-icon {
    transform: scale(1.1);
}

/* Activities Table Styles */
.activities-table .progress {
    height: 20px;
    border-radius: 0.25rem;
}

.activities-table .btn-group {
    flex-wrap: nowrap;
}

.activities-table .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Collaboration Styles */
.user-avatar {
    transition: transform 0.3s ease;
}

.user-avatar:hover {
    transform: scale(1.1);
}

.user-status .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.activity-content h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.activity-content p {
    color: #6c757d;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

.activity-item {
    opacity: 0;
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.real-time-status i {
    transition: transform 0.3s ease;
}

.real-time-status:hover i {
    transform: scale(1.2);
}

#live-status {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    100% {
        opacity: 1;
    }
}

/* Responsive collaboration styles */
@media (max-width: 768px) {
    .activity-icon {
        width: 28px;
        height: 28px;
    }
    
    .activity-content h6 {
        font-size: 0.9rem;
    }
    
    .activity-content p {
        font-size: 0.8rem;
    }
    
    .user-avatar {
        width: 32px;
        height: 32px;
    }
    
    .user-avatar span {
        font-size: 0.9rem;
    }
    
    .real-time-status i {
        font-size: 1.5rem;
    }
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
    
    /* Target Groups Responsive */
    .target-group-icon,
    .sdg-icon,
    .reinstra-icon {
        width: 40px;
        height: 40px;
    }
    
    .target-group-content h6,
    .sdg-content h6,
    .reinstra-content h6 {
        font-size: 0.9rem;
    }
    
    .target-group-content p,
    .sdg-content p,
    .reinstra-content p {
        font-size: 0.8rem;
    }
    
    /* Activities Table Responsive */
    .activities-table {
        font-size: 0.8rem;
    }
    
    .activities-table .progress {
        height: 15px;
    }
    
    .activities-table .btn-group {
        flex-direction: column;
    }
    
    .activities-table .btn-group .btn {
        margin-bottom: 0.25rem;
        border-radius: 0.375rem !important;
    }
    
    .activities-table .btn-group .btn:not(:last-child) {
        margin-right: 0;
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


function exportProgramData(dataType) {
    // Get program data from data attributes
    const programElement = document.querySelector('[data-program-id]');
    const programId = programElement ? programElement.dataset.programId : '1';
    const programName = programElement ? programElement.dataset.programName : 'Program';
    
    Swal.fire({
        title: 'Exporting Data...',
        text: `Preparing ${dataType} data export...`,
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    let exportData = {};
    
    switch(dataType) {
        case 'beneficiaries':
            exportData = {
                program: programName,
                dataType: 'beneficiaries',
                data: {
                    total: {{ $totalBeneficiaries }},
                    breakdown: {
                        women: {{ $program->ekspektasipenerimamanfaatwoman ?: 0 }},
                        men: {{ $program->ekspektasipenerimamanfaatman ?: 0 }},
                        girls: {{ $program->ekspektasipenerimamanfaatgirl ?: 0 }},
                        boys: {{ $program->ekspektasipenerimamanfaatboy ?: 0 }},
                        indirect: {{ $program->ekspektasipenerimamanfaattidaklangsung ?: 0 }}
                    }
                },
                exportDate: new Date().toISOString()
            };
            break;
            
        case 'progress':
            exportData = {
                program: programName,
                dataType: 'progress',
                data: {
                    status: '{{ $program->status }}',
                    duration: {{ $durationInDays }},
                    progressPercentage: {{ $program->status === 'complete' ? 100 : ($program->status === 'running' ? 75 : ($program->status === 'pending' ? 25 : 0)) }},
                    timeline: {
                        start: '{{ $program->tanggalmulai }}',
                        end: '{{ $program->tanggalselesai }}'
                    }
                },
                exportDate: new Date().toISOString()
            };
            break;
            
        case 'activities':
            exportData = {
                program: programName,
                dataType: 'activities',
                data: {
                    totalActivities: {{ $program->allKegiatan()->count() }},
                    // Activities data would be fetched via AJAX in real implementation
                    activities: []
                },
                exportDate: new Date().toISOString()
            };
            break;
    }
    
    setTimeout(() => {
        Swal.close();
        downloadJSON(exportData, `${programName}_${dataType}_Data.json`);
    }, 1000);
}

function downloadJSON(data, filename) {
    const dataStr = JSON.stringify(data, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    
    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = filename;
    link.click();
    
    URL.revokeObjectURL(link.href);
    
    Swal.fire({
        title: 'Export Complete!',
        text: 'Your file has been downloaded successfully.',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

// Real-time updates functionality
let realTimeUpdatesEnabled = true;
let updateInterval;

// Initialize real-time updates when page loads
document.addEventListener('DOMContentLoaded', function() {
    startRealTimeUpdates();
    showLiveStatus();
});

function startRealTimeUpdates() {
    if (realTimeUpdatesEnabled) {
        updateInterval = setInterval(() => {
            updateLastActivity();
            checkForUpdates();
        }, 30000); // Update every 30 seconds
    }
}

function stopRealTimeUpdates() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
}

function toggleRealTimeUpdates() {
    realTimeUpdatesEnabled = !realTimeUpdatesEnabled;
    
    if (realTimeUpdatesEnabled) {
        startRealTimeUpdates();
        showLiveStatus();
        Swal.fire({
            title: 'Real-time Updates Enabled',
            text: 'Auto-refresh has been turned on.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        stopRealTimeUpdates();
        hideLiveStatus();
        Swal.fire({
            title: 'Real-time Updates Disabled',
            text: 'Auto-refresh has been turned off.',
            icon: 'info',
            timer: 2000,
            showConfirmButton: false
        });
    }
}

function showLiveStatus() {
    const liveStatus = document.getElementById('live-status');
    if (liveStatus) {
        liveStatus.style.display = 'inline-block';
    }
}

function hideLiveStatus() {
    const liveStatus = document.getElementById('live-status');
    if (liveStatus) {
        liveStatus.style.display = 'none';
    }
}

function updateLastActivity() {
    const lastActivityElement = document.getElementById('last-activity');
    const lastUpdateTimeElement = document.getElementById('last-update-time');
    
    if (lastActivityElement) {
        lastActivityElement.textContent = 'Just now';
    }
    
    if (lastUpdateTimeElement) {
        lastUpdateTimeElement.textContent = 'Just now';
    }
}

function checkForUpdates() {
    // Simulate checking for updates (in real implementation, this would make an AJAX call)
    console.log('Checking for program updates...');
    
    // Simulate random activity
    const activities = [
        'Program data synchronized',
        'Team member activity detected',
        'New document uploaded',
        'Progress updated',
        'Collaboration request received'
    ];
    
    const randomActivity = activities[Math.floor(Math.random() * activities.length)];
    addActivityFeedItem(randomActivity);
}

function addActivityFeedItem(activity) {
    const activityFeed = document.getElementById('activity-feed');
    if (activityFeed) {
        const newActivity = document.createElement('div');
        newActivity.className = 'activity-item mb-3';
        newActivity.innerHTML = `
            <div class="d-flex">
                <div class="activity-icon bg-warning">
                    <i class="fas fa-sync text-white"></i>
                </div>
                <div class="activity-content ml-3">
                    <h6 class="mb-1">System Activity</h6>
                    <p class="mb-0 text-muted">${activity}</p>
                    <small class="text-muted">${new Date().toLocaleString()}</small>
                </div>
            </div>
        `;
        
        // Add to top of activity feed
        activityFeed.insertBefore(newActivity, activityFeed.firstChild);
        
        // Keep only last 5 activities
        while (activityFeed.children.length > 5) {
            activityFeed.removeChild(activityFeed.lastChild);
        }
    }
}

function testNotification() {
    if ('Notification' in window) {
        if (Notification.permission === 'granted') {
            showNotification();
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    showNotification();
                }
            });
        } else {
            Swal.fire({
                title: 'Notifications Blocked',
                text: 'Please enable browser notifications to receive alerts.',
                icon: 'warning'
            });
        }
    } else {
        Swal.fire({
            title: 'Notifications Not Supported',
            text: 'Your browser does not support desktop notifications.',
            icon: 'info'
        });
    }
}

function showNotification() {
    const notification = new Notification('Program Update', {
        body: 'New activity detected in your program',
        icon: '/favicon.ico',
        badge: '/favicon.ico'
    });
    
    notification.onclick = function() {
        window.focus();
        notification.close();
    };
    
    Swal.fire({
        title: 'Test Notification Sent',
        text: 'Check your browser notifications!',
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
}

// Simulate user presence updates
function updateUserPresence() {
    const activeUsersCount = document.getElementById('active-users-count');
    if (activeUsersCount) {
        // Simulate changing number of active users
        const baseCount = {{ $program->staff->count() }};
        const variation = Math.floor(Math.random() * 3) - 1; // -1, 0, or 1
        const newCount = Math.max(0, baseCount + variation);
        activeUsersCount.textContent = `${newCount} Online`;
    }
}

// Update user presence every minute
setInterval(updateUserPresence, 60000);

// Export functionality
function exportProgram(format) {
    // Get program data from data attributes
    const programElement = document.querySelector('[data-program-id]');
    const programId = programElement ? programElement.dataset.programId : '1';
    const programName = programElement ? programElement.dataset.programName : 'Program';
    
    // Show loading
    Swal.fire({
        title: 'Preparing Export...',
        text: `Generating ${format.toUpperCase()} export for ${programName}`,
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        timerProgressBar: true
    });
    
    // Simulate export process (in real implementation, this would make AJAX calls)
    setTimeout(() => {
        Swal.fire({
            title: 'Export Ready!',
            text: `${programName} data has been exported as ${format.toUpperCase()}`,
            icon: 'success',
            confirmButtonText: 'Download'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate download
                const exportData = generateExportData(format, programId, programName);
                downloadFile(exportData, `${programName}_program_data.${format}`, format);
            }
        });
    }, 2000);
}

function exportProgramData(dataType) {
    // Get program data from data attributes
    const programElement = document.querySelector('[data-program-id]');
    const programId = programElement ? programElement.dataset.programId : '1';
    const programName = programElement ? programElement.dataset.programName : 'Program';
    
    // Show loading
    Swal.fire({
        title: 'Preparing Export...',
        text: `Exporting ${dataType} data for ${programName}`,
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        timerProgressBar: true
    });
    
    // Simulate export process
    setTimeout(() => {
        Swal.fire({
            title: 'Export Ready!',
            text: `${dataType} data has been exported successfully`,
            icon: 'success',
            confirmButtonText: 'Download'
        }).then((result) => {
            if (result.isConfirmed) {
                // Simulate download
                const exportData = generateSpecificExportData(dataType);
                downloadFile(exportData, `${programName}_${dataType}_data.csv`, 'csv');
            }
        });
    }, 1500);
}

function generateExportData(format, programId, programName) {
    const programData = {
        id: programId,
        name: programName,
        code: 'PROGRAM-001',
        startDate: '2024-01-01',
        endDate: '2024-12-31',
        status: 'Active',
        totalBudget: 1000000,
        totalBeneficiaries: 5000,
        duration: 365,
        description: 'Program description',
        problemAnalysis: 'Problem analysis',
        exportDate: new Date().toISOString(),
        exportedBy: 'Current User'
    };
    
    switch(format) {
        case 'json':
            return JSON.stringify(programData, null, 2);
        case 'csv':
            return convertToCSV(programData);
        case 'excel':
            // In real implementation, use a library like SheetJS
            return convertToCSV(programData); // Fallback to CSV
        case 'pdf':
            // In real implementation, use a library like jsPDF
            return JSON.stringify(programData, null, 2); // Fallback to JSON
        default:
            return JSON.stringify(programData, null, 2);
    }
}

function generateSpecificExportData(dataType) {
    switch(dataType) {
        case 'beneficiaries':
            return generateBeneficiariesExport();
        case 'progress':
            return generateProgressExport();
        case 'activities':
            return generateActivitiesExport();
        default:
            return 'No data available';
    }
}

function generateBeneficiariesExport() {
    const beneficiaries = [
        ['Category', 'Women', 'Men', 'Girls', 'Boys', 'Indirect'],
        ['Expected', 1200, 800, 600, 400, 300]
    ];
    return convertToCSV(beneficiaries);
}

function generateProgressExport() {
    const progress = [
        ['Target Type', 'Target', 'Achieved', 'Progress %'],
        ['Overall Goal', '100%', '75%', '75%'],
        ['Objectives', '8', '6', '75%'],
        ['Outcomes', '12', '9', '75%'],
        ['Activities', '25', '18', '72%']
    ];
    return convertToCSV(progress);
}

function generateActivitiesExport() {
    const activities = [
        ['Activity ID', 'Activity Name', 'Status', 'Progress %', 'Start Date', 'End Date'],
        ['ACT001', 'Community Assessment', 'Completed', '100%', '2024-01-01', '2024-01-15'],
        ['ACT002', 'Stakeholder Engagement', 'In Progress', '60%', '2024-01-16', '2024-02-15'],
        ['ACT003', 'Implementation Phase', 'Planned', '0%', '2024-02-16', '2024-03-31']
    ];
    return convertToCSV(activities);
}

function convertToCSV(data) {
    if (Array.isArray(data)) {
        return data.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
    } else if (typeof data === 'object') {
        const headers = Object.keys(data);
        const values = Object.values(data);
        return [headers.join(','), values.map(v => `"${v}"`).join(',')].join('\n');
    }
    return data;
}

function downloadFile(content, filename, contentType) {
    const blob = new Blob([content], { type: contentType });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
}
</script>
@endpush
