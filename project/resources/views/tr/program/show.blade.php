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
                                                <span class="badge badge-info">{{ $staff->pivot->peran_id ? \App\Models\Peran::find($staff->pivot->peran_id)->nama : 'Team Member' }}</span>
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

            <!-- Progress Tab -->
            <div class="tab-pane fade" id="progress" role="tabpanel">
                <div class="text-center text-muted">
                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                    <h5>Progress Tracking</h5>
                    <p>Progress tracking features will be implemented here.</p>
                    <small>This will include charts, milestones, and achievement metrics.</small>
                </div>
            </div>
        </div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize beneficiaries chart
    const ctx = document.getElementById('beneficiariesChart');
    if (ctx) {
        new Chart(ctx, {
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

    // Initialize tab tooltips
    const tabLinks = document.querySelectorAll('#programTabs .nav-link');
    tabLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.setAttribute('title', this.textContent.trim());
        });
    });
});
</script>
@endpush
