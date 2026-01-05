@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.program.title'))
@section('content_header_title', __('global.details') .' '. $program->nama ?? '' . ' ' . __('cruds.program.title'))

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
    <div class="card-header d-flex align-items-center">
        {{-- <div class="d-flex align-items-center">
            <a class="btn btn-outline-secondary mr-3" href="{{ route('program.index') }}">
                <i class="fas fa-arrow-left"></i> {{ __('global.back') }}
            </a>
            <div>
                <h2 class="mb-0">{{ $program->nama ?? '' }}</h2>
            </div>
        </div> --}}
        <div class="card-title">
            <h3 class="text-muted pr-2">
                {{-- {{ __('cruds.program.kode') }}:  --}}
                {{ $program->kode }}</h3>
        </div>
        <div class="{{-- card-tools --}} ml-auto">
            <span class="badge badge-lg {{ $program->status === 'running' ? 'bg-success' : ($program->status === 'pending' ? 'bg-warning' : ($program->status === 'complete' ? 'bg-info' : 'bg-secondary')) }}">
                {{ strtoupper($program->status) }}
            </span>

            <!-- Print Button -->
            <button type="button" class="btn btn-sm btn-default mr-2" onclick="window.print()">
                <i class="fas fa-print"></i> Print Page
            </button>

            <!-- Export Dropdown -->
            <div class="btn-group d-none">
                <button type="button" class="btn btn-sm btn-outline-info dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-download"></i> Export
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" onclick="exportProgram('pdf')">
                        <i class="fas fa-file-pdf"></i> Export as PDF
                    </a>
                    <a class="dropdown-item" href="#" onclick="exportProgram('xlsx')">
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
            <a href="{{ route('program.edit', $program->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <!-- Quick Stats Row -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{ __('cruds.program.expektasi') }}</span>
                        <span class="info-box-number">{{ $totalBeneficiaries }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Duration</span>
                        <span class="info-box-number">{{ $durationInDays }} Days</span>
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
            <div class="col-md-3 col-sm-6 col-xl-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h4>Start Date</h4>
                        <p>{{ $program->tanggalmulai }}</p>
                    </div>
                    {{-- <div class="icon">
                        <i class="fas fa-play"></i>
                    </div> --}}
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xl-6">
                <div class="small-box bg-light">
                    <div class="inner">
                        <h4>End Date</h4>
                        <p>{{ $program->tanggalselesai }}</p>
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
            <li class="nav-item d-none">
                <a class="nav-link" id="progress-tab" data-toggle="tab" href="#progress" role="tab">
                    <i class="fas fa-chart-line"></i> Progress
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="collaboration-tab" data-toggle="tab" href="#collaboration" role="tab">
                    <i class="fas fa-users-cog"></i> Collaboration
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" id="target-groups-tab" data-toggle="tab" href="#target-groups" role="tab">
                    <i class="fas fa-bullseye"></i> Target Groups
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="activities-tab" data-toggle="tab" href="#activities" role="tab">
                    <i class="fas fa-tasks"></i> Activities
                </a>
            </li> --}}
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
                                <h5 class="card-title mb-0">{{ __('cruds.program.expektasi') }}  Breakdown</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="beneficiariesChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">{{ __('cruds.program.expektasi') }}</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <tr>
                                        <th>{{__('cruds.program.ekspektasipenerimamanfaatwoman')}}:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaatwoman ?: 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('cruds.program.ekspektasipenerimamanfaatman')}}:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaatman ?: 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('cruds.program.ekspektasipenerimamanfaatgirl')}}:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaatgirl ?: 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('cruds.program.ekspektasipenerimamanfaatboy')}}:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaatboy ?: 0 }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('cruds.program.ekspektasipenerimamanfaattidaklangsung')}}:</th>
                                        <td>{{ $program->ekspektasipenerimamanfaattidaklangsung ?: 0 }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <th><strong>{{__('Total ') . __('cruds.program.expektasi') }}:</strong></th>
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
                                                    {{-- <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                                                         style="width: 64px; height: 64px;">
                                                        <span class="text-white h4 mb-0">{{ strtoupper(substr($staff->nama, 0, 1)) }}</span>
                                                    </div> --}}
                                                    <h6 class="staff">{{ $staff->nama }}</h6>
                                                    <span class="badge badge-info">{{ $staff->pivot->peran_id ? (\App\Models\Peran::find($staff->pivot->peran_id)->nama ?? 'Team Member') : 'Team Member' }}</span>
                                                    <p class="text-muted">{{ $staff->email }}</p>
                                                </div>
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
                                <h5 class="card-title mb-0">{{ __('cruds.mpendonor.mpendonor') }}</h5>
                            </div>
                            <div class="card-body">
                                @if($program->pendonor->count() > 0)
                                    @foreach($program->pendonor as $pendonor)
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <h6 class="pendonor">{{ $pendonor->nama }}</h6>
                                                {{-- <p class="card-text text-muted">{{ $pendonor->kategori ? $pendonor->kategori->nama : 'Uncategorized' }}</p> --}}
                                                @if($pendonor->pivot->nilaidonasi)
                                                    <span class="mb-0"><strong>{{ __('cruds.program.donor.val') }}:</strong> Rp {{ number_format($pendonor->pivot->nilaidonasi, 0, ',', '.') }}</span>
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
                        <h5 class="card-title mb-0">Program Locations</h5>
                    </div>
                    <div class="card-body">
                        @if($program->lokasi->count() > 0)
                            <div class="row">
                                @foreach($program->lokasi as $lokasi)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="lokasi">{{ __('cruds.provinsi.title') }}: {{ $lokasi->nama }}</h5>
                                                {{-- <span class="text-muted mb-0">
                                                    {{ __('cruds.provinsi.title') }}: {{ $lokasi->nama }}
                                                </span> --}}
                                                {{-- <div class="text-muted">ID: {{ $lokasi->id }}</div> --}}
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
                <div class="card">
                    <div class="card-body border-top">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-info mb-0"><i class="fas fa-folder-open me-2"></i>{{ __('Program Documents & Files') }}</h5>
                            @can('program_edit')
                            <button type="button" class="btn btn-primary btn-sm" onclick="uploadProgramFile()">
                                <i class="fas fa-plus me-1"></i>{{ __('Upload Files') }}
                            </button>
                            @endcan
                        </div>

                        <!-- Files Section -->
                        <div class="files-section">
                            @php
                                $programFiles = $program->getMedia('program_' . $program->id);
                            @endphp
                            @if($programFiles && $programFiles->count() > 0)
                                <div class="row g-3">
                                    @foreach($programFiles as $media)
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="card file-card h-100 shadow-sm hover-shadow transition-all">
                                                <div class="card-body p-3">
                                                    <div class="file-icon text-center mb-3">
                                                        @if(strstr($media->mime_type, "image/"))
                                                            <img src="{{ $media->getUrl('thumb') }}" class="img-fluid rounded" alt="{{ $media->getCustomProperty('keterangan') ?? $media->name }}" style="max-height: 120px; object-fit: cover;">
                                                        @elseif(strstr($media->mime_type, "pdf"))
                                                            <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                                        @elseif(strstr($media->mime_type, "word"))
                                                            <i class="fas fa-file-word fa-4x text-primary"></i>
                                                        @elseif(strstr($media->mime_type, "xlsx") || strstr($media->mime_type, "spreadsheet"))
                                                            <i class="fas fa-file-excel fa-4x text-success"></i>
                                                        @elseif(strstr($media->mime_type, "powerpoint"))
                                                            <i class="fas fa-file-powerpoint fa-4x text-warning"></i>
                                                        @else
                                                            <i class="fas fa-file fa-4x text-secondary"></i>
                                                        @endif
                                                    </div>
                                                    <div class="file-meta">
                                                        <span class="nama-media text-truncate" title="{{ $media->getCustomProperty('keterangan') ?? $media->name }}">
                                                            {{ Str::limit($media->getCustomProperty('keterangan') ?? $media->name, 25) }}
                                                        </span>
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-calendar me-1"></i>{{ $media->created_at->format('d M Y') }}
                                                        </small>
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-weight me-1"></i>{{ $media->human_readable_size }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="card-footer bg-transparent border-top-0 p-2">
                                                    <div class="btn-group w-100" role="group">
                                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="previewProgramFile('{{ $media->getUrl() }}', '{{ $media->mime_type }}')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <a href="{{ $media->getUrl() }}" class="btn btn-outline-success btn-sm" download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        @can('program_edit')
                                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteProgramFile({{ $media->id }})">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                                    <h6 class="text-muted">{{ __('No supporting documents uploaded yet') }}</h6>
                                    <p class="text-muted small">{{ __('Upload documents to support this program') }}</p>
                                    @can('program_edit')
                                    <button class="btn btn-primary" onclick="uploadProgramDocument('file_pendukung_program')">
                                        <i class="fas fa-plus me-2"></i>{{ __('Upload Document') }}
                                    </button>
                                    @endcan
                                </div>
                            @endif
                        </div>

                        <!-- If no files at all -->
                        @if($programFiles->count() == 0)
                            <div class="text-center py-5">
                                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                                <h6 class="text-muted">{{ __('No files uploaded yet') }}</h6>
                                <p class="text-muted small">{{ __('Upload documents and media to support this program') }}</p>
                                @can('program_edit')
                                <button class="btn btn-primary" onclick="uploadProgramFile()">
                                    <i class="fas fa-plus me-2"></i>{{ __('Upload Files') }}
                                </button>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Progress Tab -->
            <div class="tab-pane fade d-none" id="progress" role="tabpanel">
                {{-- <div class="row">
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
                </div> --}}
            </div>

            <!-- Collaboration Tab -->
            <div class="tab-pane fade" id="collaboration" role="tabpanel">
                <div class="row">
                    <!-- Active Collaborators -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header {{-- d-flex justify-content-between align-items-center --}}">
                                <h5 class="card-title mb-0">Collaborators</h5>
                                <div class="card-tools">
                                    <span class="badge badge-success" id="active-users-count">
                                        {{ $program->staff->count() }}
                                    </span>
                                </div>
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

/* File Cards Styles */
.file-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
}

.file-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.file-icon {
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.file-icon img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}

.file-card .card-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.file-meta {
    font-size: 0.8rem;
}

.file-card .btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
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

/* Print Styles */
@media print {
    /* Hide non-essential elements */
    .main-sidebar,
    .main-header,
    .content-header,
    .card-header .card-tools,
    .navbar,
    .main-footer,
    .btn,
    .no-print,
    .swal2-container {
        display: none !important;
    }

    /* Reset container width and margins */
    body,
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        background: white !important;
    }

    .container-fluid {
        padding: 0 !important;
        width: 100% !important;
    }

    .card {
        box-shadow: none !important;
        border: none !important;
        margin-bottom: 20px !important;
    }

    .card-header {
        border-bottom: 2px solid #333 !important;
        padding-left: 0 !important;
    }

    /* Force all tabs to be visible */
    .tab-content > .tab-pane {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        overflow: visible !important;
    }

    /* Hide tab navigation */
    .nav-tabs {
        display: none !important;
    }

    /* Expand all collapse elements if any */
    .collapse {
        display: block !important;
    }

    /* Ensure charts and images are printed */
    canvas, img {
        max-width: 100% !important;
        page-break-inside: avoid;
    }

    /* Ensure background colors are printed */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* Avoid breaking elements inside */
    .row, .card-body, .table {
        page-break-inside: avoid;
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

@php
// Prepare structured data for export
$exportStructure = [
    'program_info' => [
        'id' => $program->id,
        'name' => $program->nama,
        'code' => $program->kode,
        'status' => $program->status,
        'start_date' => $program->tanggalmulai,
        'end_date' => $program->tanggalselesai,
        'total_budget' => $program->totalnilai,
        'description' => strip_tags($program->deskripsiprojek),
        'problem_analysis' => strip_tags($program->analisamasalah),
        'created_at' => $program->created_at->format('Y-m-d H:i:s'),
        'updated_at' => $program->updated_at->format('Y-m-d H:i:s'),
        'duration_days' => $durationInDays ?? 0,
        'beneficiaries_total' => $totalBeneficiaries ?? 0,
    ],
    'beneficiaries_breakdown' => [
        'women' => $program->ekspektasipenerimamanfaatwoman ?? 0,
        'men' => $program->ekspektasipenerimamanfaatman ?? 0,
        'girls' => $program->ekspektasipenerimamanfaatgirl ?? 0,
        'boys' => $program->ekspektasipenerimamanfaatboy ?? 0,
        'indirect' => $program->ekspektasipenerimamanfaattidaklangsung ?? 0,
    ],
    'locations' => $program->lokasi->map(function($l) {
        return [
            'id' => $l->id,
            'name' => $l->nama,
            'province' => $l->province ?? '',
        ];
    })->values(),
    'partners' => $program->partner->map(function($p) {
        return [
            'name' => $p->nama,
            'phone' => $p->telepon,
            'address' => $p->alamat,
        ];
    })->values(),
    'donors' => $program->pendonor->map(function($d) {
        return [
            'name' => $d->nama,
            'donation_amount' => $d->pivot->nilaidonasi,
        ];
    })->values(),
    'team_members' => $program->staff->map(function($s) {
        $roleName = 'Team Member';
        if ($s->pivot->peran_id) {
            $role = \App\Models\Peran::find($s->pivot->peran_id);
            if ($role) $roleName = $role->nama;
        }
        return [
            'name' => $s->name,
            'email' => $s->email,
            'role' => $roleName,
        ];
    })->values(),
    'activities' => $program->allKegiatan()->map(function($k) {
        return [
            'name' => $k->nama,
            'code' => $k->kode,
            'status' => $k->status,
            'start_date' => $k->tanggalmulai,
            'end_date' => $k->tanggalselesai,
            'budget' => $k->totalnilai,
            'progress_percent' => $k->status === 'complete' ? 100 : ($k->status === 'submit' ? 75 : ($k->status === 'running' ? 50 : ($k->status === 'draft' ? 25 : 0)))
        ];
    })->values(),
    'progress_metrics' => $program->targetProgresses->flatMap(function($tp) {
        return $tp->details->map(function($detail) {
             // Cast to float to avoid "Unsupported operand types" error in PHP 8 if values are strings
             $target = (float)($detail->targetable->target ?? 0);
             $actual = (float)($detail->actual ?? 0);
             
             return [
                 'metric' => $detail->targetable->deskripsi ?? 'Unknown',
                 'target' => $target,
                 'actual' => $actual,
                 'percent' => ($target > 0) ? round(($actual / $target) * 100, 2) : 0,
             ];
        });
    })->values(),
    'structure' => [
        'goal' => $program->goal ? [
            'description' => $program->goal->deskripsi,
            'indicator' => $program->goal->indikator,
            'target' => $program->goal->target
        ] : null,
        'objective' => $program->objektif ? [
            'description' => $program->objektif->deskripsi,
            'indicator' => $program->objektif->indikator,
            'target' => $program->objektif->target
        ] : null,
        'outcomes' => $program->outcome->map(function($o) {
            return [
                'description' => $o->deskripsi,
                'indicator' => $o->indikator,
                'target' => $o->target,
                'outputs' => $o->output->map(function($out) {
                    return [
                        'description' => $out->deskripsi,
                        'indicator' => $out->indikator,
                        'target' => $out->target
                    ];
                })
            ];
        })
    ]
];
@endphp
<script>
    const PROGRAM_EXPORT_DATA = {!! json_encode($exportStructure) !!};
</script>

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

    if(format === "pdf"){
        exportProgramPDF(programId, programName);
        return;
    }
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
    // We don't need to reconstruct programData here since we have PROGRAM_EXPORT_DATA
    // But keeping this structure for potential other uses or legacy checks if needed
    // However, for the export, we just delegate to the formatters

    switch(format) {
        case 'json':
            return JSON.stringify(PROGRAM_EXPORT_DATA, null, 2);
        case 'csv':
        case 'xlsx':
            // Flatten the data for CSV/Excel
            return convertToMultiSheetCSV(PROGRAM_EXPORT_DATA);
        case 'pdf':
            // For PDF, we'll use the dedicated exportProgramPDF function
            exportProgramPDF(programId, programName);
            return null;
        default:
            return JSON.stringify(PROGRAM_EXPORT_DATA, null, 2);
    }
}

function generateSpecificExportData(dataType) {
    if (!PROGRAM_EXPORT_DATA) return 'No data available';

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
    const b = PROGRAM_EXPORT_DATA.beneficiaries_breakdown;
    const beneficiaries = [
        ['Category', 'Count'],
        ['Women', b.women],
        ['Men', b.men],
        ['Girls', b.girls],
        ['Boys', b.boys],
        ['Indirect', b.indirect],
        ['Total', PROGRAM_EXPORT_DATA.program_info.beneficiaries_total]
    ];
    return convertToCSV(beneficiaries);
}

function generateProgressExport() {
    const headers = [['Metric', 'Target', 'Actual', 'Percentage']];
    const rows = PROGRAM_EXPORT_DATA.progress_metrics.map(pm => [
        pm.metric,
        pm.target,
        pm.actual,
        pm.percent + '%'
    ]);
    return convertToCSV(headers.concat(rows));
}

function generateActivitiesExport() {
    const headers = [['Activity Name', 'Code', 'Status', 'Start Date', 'End Date', 'Budget', 'Progress']];
    const rows = PROGRAM_EXPORT_DATA.activities.map(act => [
        act.name,
        act.code,
        act.status,
        act.start_date,
        act.end_date,
        act.budget,
        act.progress_percent + '%'
    ]);
    return convertToCSV(headers.concat(rows));
}

function convertToCSV(data) {
    if (Array.isArray(data)) {
        return data.map(row => row.map(cell => {
            const val = (cell === null || cell === undefined) ? '' : cell.toString();
            return `"${val.replace(/"/g, '""')}"`;
        }).join(',')).join('\n');
    }
    return '';
}

function convertToMultiSheetCSV(data) {
    // Strategy: Create sections for different data types
    let csvContent = [];
    
    // 1. Program Info
    csvContent.push(['PROGRAM INFORMATION']);
    Object.keys(data.program_info).forEach(key => {
        csvContent.push([key.replace(/_/g, ' ').toUpperCase(), data.program_info[key]]);
    });
    csvContent.push([]); // Empty line
    
    // 2. Beneficiaries
    csvContent.push(['BENEFICIARIES']);
    const benKeys = Object.keys(data.beneficiaries_breakdown);
    csvContent.push(['Category', 'Count']);
    benKeys.forEach(k => {
        csvContent.push([k, data.beneficiaries_breakdown[k]]);
    });
    csvContent.push([]);
    
    // 3. Financials (Donors)
    csvContent.push(['DONORS & FUNDING']);
    if (data.donors.length > 0) {
        csvContent.push(['Donor Name', 'Amount']);
        data.donors.forEach(d => csvContent.push([d.name, d.donation_amount]));
    } else {
        csvContent.push(['No donors recorded']);
    }
    csvContent.push([]);
    
    // 4. Team
    csvContent.push(['TEAM MEMBERS']);
    if (data.team_members.length > 0) {
        csvContent.push(['Name', 'Email', 'Role']);
        data.team_members.forEach(t => csvContent.push([t.name, t.email, t.role]));
    } else {
        csvContent.push(['No team members recorded']);
    }
    csvContent.push([]);

    // 5. Activities
    csvContent.push(['ACTIVITIES']);
    if (data.activities.length > 0) {
        csvContent.push(['Name', 'Code', 'Status', 'Start', 'End', 'Budget', 'Progress']);
        data.activities.forEach(a => csvContent.push([a.name, a.code, a.status, a.start_date, a.end_date, a.budget, a.progress_percent + '%']));
    } else {
        csvContent.push(['No activities recorded']);
    }

    return convertToCSV(csvContent);
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

// Program Document Functions
function uploadProgramFile() {
    Swal.fire({
        title: '{{ __('Upload Files') }}',
        html: `
            <input type="file" id="programFile" class="form-control mb-3" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov,.mp3,.wav" multiple>
            <input type="text" id="fileName" class="form-control" placeholder="{{ __('File Name') }}">
        `,
        showCancelButton: true,
        confirmButtonText: '{{ __('Upload') }}',
        cancelButtonText: '{{ __('Cancel') }}',
        preConfirm: () => {
            const files = document.getElementById('programFile').files;
            const name = document.getElementById('fileName').value;

            if (files.length === 0) {
                Swal.showValidationMessage('{{ __('Please select a file') }}');
                return false;
            }

            if (!name) {
                Swal.showValidationMessage('{{ __('Please enter file name') }}');
                return false;
            }

            return { files: Array.from(files), name };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            result.value.files.forEach((file, index) => {
                formData.append('files[]', file);
                formData.append('captions[]', result.value.name + (index > 0 ? ` ${index + 1}` : ''));
            });
            formData.append('program_id', {{ $program->id }});

            fetch('{{ route('program.docs') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('{{ __('Success!') }}', '{{ __('Files uploaded successfully') }}', 'success');
                    // Reload the page to refresh the file list
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('{{ __('Error!') }}', data.message || '{{ __('Upload failed') }}', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('{{ __('Error!') }}', '{{ __('Upload failed') }}', 'error');
            });
        }
    });
}

function previewProgramFile(url, mimeType) {
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
            html: `<iframe src="${url}" style="width: 100%; height: 600px; border: none;"></iframe>`,
            width: '90%',
            height: '600px',
            showCloseButton: true,
            showConfirmButton: false
        });
    } else {
        // For other file types, open in new tab
        window.open(url, '_blank');
    }
}

function deleteProgramFile(mediaId) {
    Swal.fire({
        title: '{{ __('Are you sure?') }}',
        text: "{{ __('You won\'t be able to revert this!') }}",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        confirmButtonColor: '#d33',
        confirmButtonText: '{{ __('Yes, delete it!') }}',
        cancelButtonText: '{{ __('Cancel') }}'
    }).then((result) => {
        if (result.isConfirmed) {
            // Make AJAX request to delete file
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
                    Swal.fire('{{ __('Deleted!') }}', '{{ __('File has been deleted.') }}', 'success');
                    // Reload the page to refresh the file list
                    setTimeout(() => location.reload(), 1500);
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

// PDF Export Function
function exportProgramPDF(programId, programName) {
    Swal.fire({
        title: 'Generating PDF...',
        text: 'Converting program data to PDF format. This may take a moment.',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Create a hidden container for PDF content
    const pdfContainer = document.createElement('div');
    pdfContainer.style.position = 'absolute';
    pdfContainer.style.left = '-9999px';
    pdfContainer.style.width = '210mm'; // A4 width
    pdfContainer.style.padding = '20px';
    pdfContainer.style.fontFamily = 'Arial, sans-serif';
    pdfContainer.style.fontSize = '12px';
    pdfContainer.style.lineHeight = '1.4';
    pdfContainer.style.backgroundColor = 'white';
    pdfContainer.style.color = 'black';

    // Generate PDF content with vertical tabs
    pdfContainer.innerHTML = generatePDFContent(programId, programName);
    document.body.appendChild(pdfContainer);

    // Use html2canvas to capture the content
    html2canvas(pdfContainer, {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff'
    }).then(canvas => {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');

        const imgData = canvas.toDataURL('image/png');
        const imgWidth = 210; // A4 width in mm
        const pageHeight = 295; // A4 height in mm
        const imgHeight = (canvas.height * imgWidth) / canvas.width;
        let heightLeft = imgHeight;
        let position = 0;

        // Add first page
        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        // Add additional pages if needed
        while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        // Save the PDF
        pdf.save(`${programName}_Program_Report.pdf`);

        // Clean up
        document.body.removeChild(pdfContainer);

        Swal.fire({
            title: 'PDF Generated!',
            text: 'Your program report has been downloaded successfully.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }).catch(error => {
        console.error('PDF generation error:', error);
        document.body.removeChild(pdfContainer);

        Swal.fire({
            title: 'PDF Generation Failed',
            text: 'There was an error generating the PDF. Please try again.',
            icon: 'error'
        });
    });
}

function generatePDFContent(programId, programName) {
    if (typeof PROGRAM_EXPORT_DATA === 'undefined' || !PROGRAM_EXPORT_DATA) return '<h1 style="text-align:center">Error Loading Data</h1>';


    const info = PROGRAM_EXPORT_DATA.program_info;
    const currentDate = new Date().toLocaleDateString();

    let html = `
        <div style="margin-bottom: 30px;">
            <h1 style="text-align: center; margin-bottom: 10px; color: #333; font-size: 24px;">
                ${info.name}
            </h1>
            <p style="text-align: center; margin-bottom: 30px; color: #666; font-size: 14px;">
                Program Report - Generated on ${currentDate}
            </p>

            <!-- Initial Info Table -->
            <div style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                <h2 style="margin-bottom: 15px; color: #333; font-size: 18px;">Program Information</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 30%; padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Program Code:</td>
                        <td style="width: 70%; padding: 8px; border-bottom: 1px solid #eee;">${info.code}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Status:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;">${info.status}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Start Date:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;">${info.start_date}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">End Date:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;">${info.end_date}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">Total Budget:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;">${info.total_budget}</td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                <h2 style="margin-bottom: 15px; color: #333; font-size: 18px;">Description & Analysis</h2>
                 <p style="margin-bottom: 15px; line-height: 1.6;"><strong>Description:</strong> ${info.description}</p>
                 <p style="margin-bottom: 15px; line-height: 1.6;"><strong>Problem Analysis:</strong> ${info.problem_analysis}</p>
            </div>
            
            <h2 style="margin-bottom: 20px; color: #333; font-size: 18px;">Detailed Report</h2>
            
            <!-- Beneficiaries -->
             <div style="margin-bottom: 25px; padding: 15px; border-left: 4px solid #dc3545; background-color: #f8f9fa;">
                <h3 style="margin-bottom: 10px; color: #dc3545; font-size: 16px;">Beneficiaries</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="background-color: #eee;">
                        <th style="padding: 8px; border: 1px solid #ddd;">Women</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Men</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Girls</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Boys</th>
                        <th style="padding: 8px; border: 1px solid #ddd;">Total</th>
                    </tr>
                    <tr>
                         <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${PROGRAM_EXPORT_DATA.beneficiaries_breakdown.women}</td>
                         <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${PROGRAM_EXPORT_DATA.beneficiaries_breakdown.men}</td>
                         <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${PROGRAM_EXPORT_DATA.beneficiaries_breakdown.girls}</td>
                         <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">${PROGRAM_EXPORT_DATA.beneficiaries_breakdown.boys}</td>
                         <td style="padding: 8px; border: 1px solid #ddd; text-align: center; font-weight: bold;">${info.beneficiaries_total}</td>
                    </tr>
                </table>
             </div>
             
             <!-- Team -->
             <div style="margin-bottom: 25px; padding: 15px; border-left: 4px solid #28a745; background-color: #f8f9fa;">
                <h3 style="margin-bottom: 10px; color: #28a745; font-size: 16px;">Team Members</h3>
                <ul style="list-style: none; padding-left: 0;">
                    ${PROGRAM_EXPORT_DATA.team_members.length > 0 ? 
                        PROGRAM_EXPORT_DATA.team_members.map(t => 
                            `<li style="margin-bottom: 5px; border-bottom: 1px solid #eee; padding-bottom: 5px;">
                                <strong>${t.name}</strong> - ${t.role} <br> <small>${t.email}</small>
                             </li>`
                        ).join('') 
                    : '<li>No team members recorded.</li>'}
                </ul>
             </div>

            <!-- Activities -->
            <div style="margin-bottom: 25px; padding: 15px; border-left: 4px solid #6610f2; background-color: #f8f9fa;">
                <h3 style="margin-bottom: 10px; color: #6610f2; font-size: 16px;">Activities</h3>
                 <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                    <tr style="background-color: #eee;">
                        <th style="padding: 5px; border: 1px solid #ddd;">Name</th>
                        <th style="padding: 5px; border: 1px solid #ddd;">Status</th>
                        <th style="padding: 5px; border: 1px solid #ddd;">Progress</th>
                        <th style="padding: 5px; border: 1px solid #ddd;">Budget</th>
                    </tr>
                    ${PROGRAM_EXPORT_DATA.activities.length > 0 ? 
                        PROGRAM_EXPORT_DATA.activities.map(a => 
                             `<tr>
                                <td style="padding: 5px; border: 1px solid #ddd;">${a.name}</td>
                                <td style="padding: 5px; border: 1px solid #ddd;">${a.status}</td>
                                <td style="padding: 5px; border: 1px solid #ddd;">${a.progress_percent}%</td>
                                <td style="padding: 5px; border: 1px solid #ddd;">${a.budget}</td>
                              </tr>`
                        ).join('')
                    : '<tr><td colspan="4" style="padding: 5px; text-align: center;">No activities recorded.</td></tr>'}
                 </table>
            </div>

            <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; color: #666; font-size: 12px;">
                <p>Generated by App - ${currentDate}</p>
            </div>
        </div>
    `;
    return html;
}
</script>
@endpush
