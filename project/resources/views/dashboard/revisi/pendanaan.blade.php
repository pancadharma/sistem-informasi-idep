@extends('layouts.app')

@section('subtitle', __('cruds.mpendonor.dashboard'))
@section('content_header_title', __('cruds.mpendonor.dashboard'))
@section('content_header_right')
    
@endsection

@section('content_body')
<!-- Filter Section -->
<div class="row mb-3">

    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter"></i> {{ __('cruds.mpendonor.filter') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="programFilter">{{ __('cruds.program.title') }}</label>
                        <select id="programFilter" class="form-control select2">
                            <option value="">{{ __('cruds.program.all') }}</option>
                            @foreach($programs as $p)
                                <option value="{{ $p->id }}">{{ $p->kode }} - {{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tahunFilter">{{ __('cruds.program.periode') }}</label>
                        <select id="tahunFilter" class="form-control select2">
                            <option value="">{{ __('cruds.program.all_years') }}</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="donorFilter">{{ __('cruds.mpendonor.pendonor') }}</label>
                        <select id="donorFilter" class="form-control select2">
                            <option value="">{{ __('cruds.mpendonor.all_donor') }}</option>
                            @foreach($donors as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Grid (Small Boxes) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="totalFunding">Rp 0</h3>
                <p>{{ __('cruds.mpendonor.total_pendanaan') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="totalDonors">0</h3>
                <p>{{ __('cruds.mpendonor.total_pendonor') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="totalPrograms">0</h3>
                <p>{{ __('cruds.mpendonor.total_program') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-project-diagram"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="avgDonation">Rp 0</h3>
                <p>{{ __('cruds.mpendonor.rata_rata_donasi') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>

<!-- Info Callout -->
<div class="callout callout-info">
    <h5><i class="fas fa-info"></i> {{ __('cruds.mpendonor.information') }}</h5>
    {{ __('cruds.mpendonor.ket_dashboard') }}
</div>

<div class="row">
    <!-- SDG Contribution Chart -->
    <div class="col-lg-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-globe mr-1"></i> {{ __('cruds.mpendonor.sdg_contribution') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 400px;">
                    <canvas id="sdgChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sector Contribution Chart -->
    <div class="col-lg-6">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> {{ __('cruds.mpendonor.sector_contribution') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 400px;">
                    <canvas id="sektorChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Timeline Pendanaan --}}

<div class="row">
        <div class="col-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        {{ __('cruds.mpendonor.timeline_pendanaan') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 400px;">
                        <canvas id="timelinePendanaanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="row">
    <!-- Donor List Table -->
    <div class="col-lg-12">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-1"></i>
                {{ __('cruds.mpendonor.donor_list') }}
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="donorTable" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('cruds.mpendonor.nama') }}</th>
                                <th class="text-center align-middle">{{ __('cruds.mpendonor.total_program') }}</th>
                                <th class="text-right align-middle">{{ __('cruds.mpendonor.total_pendanaan') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populated by DataTables -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right">Total:</th>
                                <th class="text-right" id="footerTotalPrograms"></th>
                                <th class="text-right" id="footerTotalDonations"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .small-box .icon > i { opacity: 0.4; }
    .chart-container { position: relative; height: 400px; }
</style>
@endpush

@push('js')
@section('plugins.Select2', true)
@section('plugins.DatatablesNew', true)

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Global variables
    let sdgChart, sektorChart, timelineChart;
    let donorDataTable;
    
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%'
        });
        
        // Initialize Charts
        initCharts();
        
        // Initialize DataTable
        donorDataTable = $('#donorTable').DataTable({
            responsive: true,
            processing: true,
            deferRender: true,
            stateSave: true,
            data: [],
            columns: [
                { data: 'nama', width: '60%' },
                { data: 'program_count', width: '15%' },
                { 
                    data: 'total_donated',
                    className: 'text-right align-middle',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                }
            ],
            order: [[2, 'desc']], // Sort by total donated
            // language: {
            //     url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            // }
        });
        
        // Load initial data
        loadDashboardData();
        
        // Filter Changes
        $('#programFilter, #tahunFilter, #donorFilter').change(function() {
            loadDashboardData();
        });
    });
    
    function initCharts() {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { position: 'bottom', labels: { usePointStyle: true } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + formatRupiah(context.raw);
                        }
                    }
                }
            }
        };

        // SDG Chart (Horizontal Bar)
        const ctxSdg = document.getElementById('sdgChart').getContext('2d');
        sdgChart = new Chart(ctxSdg, {
            type: 'bar',
            data: { labels: [], datasets: [] },
            options: { 
                ...commonOptions, 
                indexAxis: 'y',
                scales: { 
                    x: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatRupiah(value);
                            }
                        }
                    } 
                } 
            }
        });
        
        // Sector Chart (Doughnut)
        const ctxSektor = document.getElementById('sektorChart').getContext('2d');
        sektorChart = new Chart(ctxSektor, {
            type: 'doughnut',
            data: { labels: [], datasets: [] },
            options: commonOptions
        });
        
        // Timeline Chart (Line)
        const ctxTimeline = document.getElementById('timelinePendanaanChart').getContext('2d');
        timelineChart = new Chart(ctxTimeline, {
            type: 'line',
            data: { labels: [], datasets: [] },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Pendanaan: ' + formatRupiah(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatRupiah(value);
                            }
                        }
                    }
                }
            }
        });
    }
    
    function loadDashboardData() {
        const filters = {
            program_id: $('#programFilter').val(),
            tahun: $('#tahunFilter').val(),
            donor_id: $('#donorFilter').val()
        };
        
        $.ajax({
            url: "{{ route('dashboard.pendanaan.data') }}",
            type: "GET",
            data: filters,
            success: function(response) {
                updateStatistics(response.stats);
                updateSDGChart(response.sdgContribution);
                updateSektorChart(response.sektorContribution);
                updateTimelineChart(response.timelineData);
                updateDonorTable(response.donorList);
            },
            error: function(err) {
                console.error("Error loading data", err);
            }
        });
    }
    
    function updateStatistics(stats) {
        $('#totalFunding').text(formatRupiah(stats.totalFunding));
        $('#totalDonors').text(stats.totalDonors);
        $('#totalPrograms').text(stats.totalPrograms);
        $('#avgDonation').text(formatRupiah(stats.avgDonation));
    }
    
    function updateSDGChart(data) {
        const labels = data.map(item => item.sdg_name);
        const values = data.map(item => item.total);
        
        sdgChart.data.labels = labels;
        sdgChart.data.datasets = [{
            label: 'Total Pendanaan',
            data: values,
            backgroundColor: generateColors(values.length),
            borderRadius: 4
        }];
        sdgChart.update();
    }
    
    function updateSektorChart(data) {
        const labels = data.map(item => item.sektor_name);
        const values = data.map(item => item.total);
        
        sektorChart.data.labels = labels;
        sektorChart.data.datasets = [{
            data: values,
            backgroundColor: generateColors(values.length)
        }];
        sektorChart.update();
    }
    
    function updateTimelineChart(data) {
        const labels = data.map(item => item.label);
        const values = data.map(item => item.total);
        
        timelineChart.data.labels = labels;
        timelineChart.data.datasets = [{
            label: 'Total Pendanaan',
            data: values,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.3,
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#667eea',
            fill: true
        }];
        timelineChart.update();
    }
    
    function updateDonorTable(data) {
        donorDataTable.clear();
        donorDataTable.rows.add(data);
        donorDataTable.draw();
        
        // Update footer totals
        if (data && data.length > 0) {
            const totalPrograms = data.reduce((sum, d) => sum + (parseInt(d.program_count) || 0), 0);
            const totalDonations = data.reduce((sum, d) => sum + (parseFloat(d.total_donated) || 0), 0);
            
            $('#footerTotalPrograms').text(totalPrograms);
            $('#footerTotalDonations').text(formatRupiah(totalDonations));
        } else {
            $('#footerTotalPrograms').text('0');
            $('#footerTotalDonations').text('Rp 0');
        }
    }
    
    function formatRupiah(value) {
        if (!value || value === 0) return 'Rp 0';
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    }
    
    function generateColors(count) {
        const baseColors = ['#4caf50', '#03a9f4', '#00bcd4', '#e91e63', '#ff9800', '#ff5722', '#9c27b0', '#667eea', '#f5576c'];
        const colors = [];
        for (let i = 0; i < count; i++) {
            colors.push(baseColors[i % baseColors.length]);
        }
        return colors;
    }
</script>
@endpush
