@extends('layouts.app')

@section('subtitle', __('cruds.program.dashboard'))
@section('content_header_title', __('cruds.program.dashboard'))

@section('content_body')
<!-- Program Dashboard SPA -->
<div id="programDashboard" class="program-dashboard">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">{{ __('cruds.program.title') }} Dashboard</h1>
                <p class="text-muted mb-0">Manage and monitor all your programs in one place</p>
            </div>
            <div class="col-md-4 text-md-right">
                @can('program_create')
                <button class="btn btn-primary" onclick="showCreateProgram()">
                    <i class="fas fa-plus"></i> {{ __('global.create') }} Program
                </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalPrograms }}</h4>
                            <p class="card-text">Total Programs</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-project-diagram fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $activePrograms }}</h4>
                            <p class="card-text">Active Programs</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-play fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $totalBeneficiaries }}</h4>
                            <p class="card-text">Beneficiaries</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">{{ $completionRate }}%</h4>
                            <p class="card-text">Completion Rate</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-pie fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row">
        <!-- Program List -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('cruds.program.list') }}</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="Search programs..." id="searchPrograms">
                            <div class="input-group-append">
                                <button class="btn btn-default" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Desktop Table View -->
                    <div class="table-responsive d-none d-md-block">
                        <table id="programTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('cruds.program.kode') }}</th>
                                    <th>{{ __('cruds.program.nama') }}</th>
                                    <th>{{ __('cruds.program.tanggalmulai') }}</th>
                                    <th>{{ __('cruds.program.tanggalselesai') }}</th>
                                    <th>{{ __('cruds.status.title') }}</th>
                                    <th>{{ __('global.action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="programTableBody">
                                <!-- Programs will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Mobile Card View -->
                    <div class="d-md-none" id="mobileProgramCards">
                        <!-- Mobile cards will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Quick Actions</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="showProgramStats()">
                            <i class="fas fa-chart-bar"></i> View Statistics
                        </button>
                        <button class="btn btn-outline-success" onclick="exportPrograms()">
                            <i class="fas fa-download"></i> Export Data
                        </button>
                        <button class="btn btn-outline-info" onclick="showProgramReports()">
                            <i class="fas fa-file-alt"></i> Reports
                        </button>
                    </div>
                </div>
            </div>

            <!-- Program Statistics Chart -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Program Status Overview</h3>
                </div>
                <div class="card-body">
                    <canvas id="programStatusChart" height="200"></canvas>
                </div>
            </div>

            <!-- Real-time Collaboration Panel -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Active Users</h3>
                </div>
                <div class="card-body">
                    <div id="activeUsers">
                        <div class="text-center text-muted">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>Checking for active users...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Recent Activity</h3>
                    <span class="badge badge-success live-indicator d-none">
                        <i class="fas fa-circle fa-xs"></i> Live
                    </span>
                </div>
                <div class="card-body">
                    <div id="recentActivity">
                        <!-- Activity will be loaded here -->
                        <div class="text-center text-muted">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <p>Connecting to real-time updates...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Detail Modal -->
    <div class="modal fade" id="programDetailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Program Details</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="programDetailContent">
                    <!-- Program details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Program Modal -->
@include('tr.program.modals.create')

@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<style>
.program-dashboard .card {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
    border: 1px solid rgba(0,0,0,.125);
}
.program-dashboard .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0,0,0,.125);
}
.program-dashboard .bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
}
.program-dashboard .bg-success {
    background: linear-gradient(45deg, #28a745, #1e7e34) !important;
}
.program-dashboard .bg-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800) !important;
}
.program-dashboard .bg-info {
    background: linear-gradient(45deg, #17a2b8, #117a8b) !important;
}
@media (max-width: 768px) {
    .program-dashboard .card-tools {
        margin-top: 1rem;
    }
    .program-dashboard .text-md-right {
        text-align: left !important;
        margin-top: 1rem;
    }
}

/* Real-time collaboration styles */
.live-indicator {
    animation: pulse 2s infinite;
}

.live-indicator i {
    animation: blink 1s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.activity-item {
    transition: all 0.3s ease;
}

.activity-item:hover {
    background-color: rgba(0, 123, 255, 0.05);
    border-radius: 0.375rem;
    margin-left: -0.5rem;
    margin-right: -0.5rem;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.user-avatar {
    transition: transform 0.2s ease;
}

.user-avatar:hover {
    transform: scale(1.1);
}

.activity-icon {
    transition: all 0.3s ease;
}

.activity-item:hover .activity-icon {
    transform: scale(1.1);
}

/* Real-time notification styles */
.real-time-notification {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* User status animations */
.user-status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 0.5rem;
}

.user-status-indicator.online {
    background-color: #28a745;
    animation: pulse 2s infinite;
}

.user-status-indicator.offline {
    background-color: #6c757d;
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
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pusher-js@8.2.0/dist/pusher.min.js"></script>

<script>
class ProgramDashboard {
    constructor() {
        this.programs = [];
        this.filteredPrograms = [];
        this.activeUsers = new Map();
        this.echo = null;
        this.currentUser = {{ auth()->user()->id ?? 'null' }};
        this.currentUserName = "{{ auth()->user()->name ?? 'Guest' }}";
        this.init();
    }

    init() {
        this.loadPrograms();
        this.setupEventListeners();
        this.initChart();
        this.initRealTimeFeatures();
    }

    async loadPrograms() {
        try {
            const response = await fetch('{{ route("data.program") }}');
            const data = await response.json();
            this.programs = data.data;
            this.filteredPrograms = this.programs;
            this.renderProgramTable();
            this.updateChart();
        } catch (error) {
            console.error('Error loading programs:', error);
            Toast.fire({
                icon: 'error',
                title: 'Error loading programs'
            });
        }
    }

    renderProgramTable() {
        // Desktop table view
        const tbody = document.getElementById('programTableBody');
        tbody.innerHTML = '';

        // Mobile card view
        const mobileContainer = document.getElementById('mobileProgramCards');
        mobileContainer.innerHTML = '';

        this.filteredPrograms.forEach(program => {
            // Desktop table row
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${program.kode}</td>
                <td>${program.nama}</td>
                <td>${program.tanggalmulai}</td>
                <td>${program.tanggalselesai}</td>
                <td>${this.getStatusBadge(program.status)}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-info" onclick="programDashboard.viewProgram(${program.id})" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-primary" onclick="programDashboard.editProgram(${program.id})" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-success" onclick="programDashboard.showDetails(${program.id})" title="Details">
                            <i class="fas fa-th-list"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);

            // Mobile card
            const card = document.createElement('div');
            card.innerHTML = `
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">${program.nama}</h6>
                            ${this.getStatusBadge(program.status)}
                        </div>
                        <div class="program-meta">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-code"></i> ${program.kode}
                            </small>
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-calendar"></i> ${program.tanggalmulai} - ${program.tanggalselesai}
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-users"></i> ${program.ekspektasipenerimamanfaat || 0} beneficiaries
                            </small>
                        </div>
                        <div class="program-actions mt-3">
                            <div class="btn-group btn-group-sm w-100">
                                <button class="btn btn-outline-primary" onclick="programDashboard.viewProgram(${program.id})">
                                    <i class="fas fa-eye"></i>
                                    <span class="d-none d-sm-inline">View</span>
                                </button>
                                <button class="btn btn-outline-success" onclick="programDashboard.editProgram(${program.id})">
                                    <i class="fas fa-edit"></i>
                                    <span class="d-none d-sm-inline">Edit</span>
                                </button>
                                <button class="btn btn-outline-info" onclick="programDashboard.showDetails(${program.id})">
                                    <i class="fas fa-list"></i>
                                    <span class="d-none d-sm-inline">Details</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            mobileContainer.appendChild(card.firstElementChild);
        });
    }

    getStatusBadge(status) {
        const badges = {
            'draft': '<span class="badge badge-secondary">Draft</span>',
            'pending': '<span class="badge badge-warning">Pending</span>',
            'running': '<span class="badge bg-teal">Running</span>',
            'submit': '<span class="badge badge-success">Submit</span>'
        };
        return badges[status] || `<span class="badge badge-secondary">${status}</span>`;
    }

    setupEventListeners() {
        // Search functionality
        document.getElementById('searchPrograms').addEventListener('input', (e) => {
            const searchTerm = e.target.value;
            this.filteredPrograms = this.programs.filter(program => 
                this.matchesSearch(program, searchTerm)
            );
            this.renderProgramTable();
        });
    }

    initChart() {
        const ctx = document.getElementById('programStatusChart').getContext('2d');
        this.chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Draft', 'Pending', 'Running', 'Submit'],
                datasets: [{
                    data: [0, 0, 0, 0],
                    backgroundColor: [
                        '#6c757d',
                        '#ffc107',
                        '#28a745',
                        '#17a2b8'
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
                    }
                }
            }
        });
    }

    updateChart() {
        if (!this.chart) return;

        const statusCounts = {
            draft: 0,
            pending: 0,
            running: 0,
            submit: 0
        };

        this.programs.forEach(program => {
            if (statusCounts.hasOwnProperty(program.status)) {
                statusCounts[program.status]++;
            }
        });

        this.chart.data.datasets[0].data = [
            statusCounts.draft,
            statusCounts.pending,
            statusCounts.running,
            statusCounts.submit
        ];

        this.chart.update();
    }

    viewProgram(id) {
        window.location.href = `{{ route('program.show', ':id') }}`.replace(':id', id);
    }

    editProgram(id) {
        window.location.href = `{{ route('program.edit', ':id') }}`.replace(':id', id);
    }

    showDetails(id) {
        window.location.href = `{{ route('program.details', ':id') }}`.replace(':id', id);
    }

    initRealTimeFeatures() {
        // Initialize Laravel Echo for real-time features
        if (typeof Echo !== 'undefined') {
            this.echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ config("broadcasting.connections.pusher.key") }}',
                cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                forceTLS: true
            });

            this.setupPresenceChannel();
            this.setupProgramUpdateChannel();
            this.setupActivityChannel();
        }

        // Simulate real-time updates for demo purposes
        this.simulateRealTimeFeatures();
    }

    setupPresenceChannel() {
        try {
            const presenceChannel = this.echo.join('program-dashboard');
            
            presenceChannel.here((users) => {
                this.updateActiveUsers(users);
            });

            presenceChannel.joining((user) => {
                this.addActiveUser(user);
                this.showNotification(`${user.name} joined the dashboard`, 'info');
            });

            presenceChannel.leaving((user) => {
                this.removeActiveUser(user.id);
                this.showNotification(`${user.name} left the dashboard`, 'info');
            });

            presenceChannel.here((users) => {
                document.querySelector('.live-indicator').classList.remove('d-none');
            });
        } catch (error) {
            console.log('Presence channel not available, using simulation');
        }
    }

    setupProgramUpdateChannel() {
        try {
            this.echo.channel('program-updates')
                .listen('ProgramUpdated', (e) => {
                    this.handleProgramUpdate(e.program);
                })
                .listen('ProgramCreated', (e) => {
                    this.handleProgramCreated(e.program);
                })
                .listen('ProgramDeleted', (e) => {
                    this.handleProgramDeleted(e.programId);
                });
        } catch (error) {
            console.log('Program update channel not available, using simulation');
        }
    }

    setupActivityChannel() {
        try {
            this.echo.channel('activity-feed')
                .listen('NewActivity', (e) => {
                    this.addActivityItem(e.activity);
                });
        } catch (error) {
            console.log('Activity channel not available, using simulation');
        }
    }

    updateActiveUsers(users) {
        const container = document.getElementById('activeUsers');
        this.activeUsers.clear();
        
        users.forEach(user => {
            this.activeUsers.set(user.id, user);
        });

        this.renderActiveUsers();
    }

    addActiveUser(user) {
        this.activeUsers.set(user.id, user);
        this.renderActiveUsers();
    }

    removeActiveUser(userId) {
        this.activeUsers.delete(userId);
        this.renderActiveUsers();
    }

    renderActiveUsers() {
        const container = document.getElementById('activeUsers');
        
        if (this.activeUsers.size === 0) {
            container.innerHTML = `
                <div class="text-center text-muted">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <p>No active users</p>
                </div>
            `;
            return;
        }

        const userHtml = Array.from(this.activeUsers.values()).map(user => `
            <div class="d-flex align-items-center mb-2">
                <div class="user-avatar mr-2">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px;">
                        <span class="text-white font-weight-bold">${user.name.charAt(0).toUpperCase()}</span>
                    </div>
                </div>
                <div class="user-info">
                    <div class="font-weight-bold">${user.name}</div>
                    <small class="text-muted">${this.getUserStatus(user)}</small>
                </div>
            </div>
        `).join('');

        container.innerHTML = userHtml;
    }

    getUserStatus(user) {
        if (user.id === this.currentUser) {
            return 'You';
        }
        return 'Active now';
    }

    handleProgramUpdate(program) {
        const index = this.programs.findIndex(p => p.id === program.id);
        if (index !== -1) {
            this.programs[index] = program;
            this.filteredPrograms = this.programs.filter(p => 
                this.matchesSearch(p, document.getElementById('searchPrograms').value)
            );
            this.renderProgramTable();
            this.updateChart();
            this.showNotification(`Program "${program.nama}" was updated`, 'success');
        }
    }

    handleProgramCreated(program) {
        this.programs.unshift(program);
        this.filteredPrograms = this.programs.filter(p => 
            this.matchesSearch(p, document.getElementById('searchPrograms').value)
        );
        this.renderProgramTable();
        this.updateChart();
        this.showNotification(`New program "${program.nama}" was created`, 'success');
    }

    handleProgramDeleted(programId) {
        this.programs = this.programs.filter(p => p.id !== programId);
        this.filteredPrograms = this.programs.filter(p => 
            this.matchesSearch(p, document.getElementById('searchPrograms').value)
        );
        this.renderProgramTable();
        this.updateChart();
        this.showNotification('A program was deleted', 'warning');
    }

    addActivityItem(activity) {
        const container = document.getElementById('recentActivity');
        const activityElement = document.createElement('div');
        activityElement.className = 'activity-item mb-3 pb-3 border-bottom';
        activityElement.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="activity-icon mr-2">
                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" 
                         style="width: 32px; height: 32px;">
                        <i class="fas fa-${activity.icon} text-white"></i>
                    </div>
                </div>
                <div class="activity-content flex-grow-1">
                    <div class="font-weight-bold">${activity.title}</div>
                    <small class="text-muted">${activity.description}</small>
                    <div class="text-muted" style="font-size: 0.75rem;">
                        ${activity.time} by ${activity.user}
                    </div>
                </div>
            </div>
        `;

        // Add to top of activity list
        const firstActivity = container.querySelector('.activity-item');
        if (firstActivity) {
            container.insertBefore(activityElement, firstActivity);
        } else {
            container.innerHTML = '';
            container.appendChild(activityElement);
        }

        // Keep only last 10 activities
        const activities = container.querySelectorAll('.activity-item');
        if (activities.length > 10) {
            activities[activities.length - 1].remove();
        }
    }

    showNotification(message, type = 'info') {
        Toast.fire({
            icon: type,
            title: message,
            timer: 3000
        });
    }

    matchesSearch(program, searchTerm) {
        if (!searchTerm) return true;
        const term = searchTerm.toLowerCase();
        return program.nama.toLowerCase().includes(term) || 
               program.kode.toLowerCase().includes(term);
    }

    simulateRealTimeFeatures() {
        // Simulate active users
        setTimeout(() => {
            this.updateActiveUsers([
                { id: this.currentUser, name: this.currentUserName },
                { id: 2, name: 'John Doe' },
                { id: 3, name: 'Jane Smith' }
            ]);
        }, 2000);

        // Simulate activity feed
        setTimeout(() => {
            const activities = [
                {
                    icon: 'edit',
                    title: 'Program Updated',
                    description: 'Education Support Program 2024 was modified',
                    time: '2 minutes ago',
                    user: 'John Doe'
                },
                {
                    icon: 'plus',
                    title: 'New Program Created',
                    description: 'Health Initiative Program was added',
                    time: '5 minutes ago',
                    user: 'Jane Smith'
                },
                {
                    icon: 'chart-line',
                    title: 'Progress Report',
                    description: 'Monthly progress report generated',
                    time: '10 minutes ago',
                    user: 'System'
                }
            ];

            const container = document.getElementById('recentActivity');
            container.innerHTML = '';
            
            activities.forEach(activity => {
                this.addActivityItem(activity);
            });

            document.querySelector('.live-indicator').classList.remove('d-none');
        }, 3000);

        // Simulate periodic updates
        setInterval(() => {
            if (Math.random() > 0.7) {
                const randomUser = ['Alice Johnson', 'Bob Wilson', 'Carol Davis'][Math.floor(Math.random() * 3)];
                const actions = ['viewed', 'edited', 'commented on'];
                const action = actions[Math.floor(Math.random() * actions.length)];
                
                this.showNotification(`${randomUser} ${action} a program`, 'info');
            }
        }, 30000);
    }
}

// Initialize dashboard
const programDashboard = new ProgramDashboard();

// Global functions for button clicks
function showCreateProgram() {
    window.location.href = '{{ route('program.create') }}';
}

function showProgramStats() {
    Toast.fire({
        icon: 'info',
        title: 'Statistics feature coming soon'
    });
}

function exportPrograms() {
    Toast.fire({
        icon: 'info',
        title: 'Export feature coming soon'
    });
}

function showProgramReports() {
    Toast.fire({
        icon: 'info',
        title: 'Reports feature coming soon'
    });
}

$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush