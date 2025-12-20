{{-- resources/views/master/mpendonor/dashboard.blade.php --}}

@extends('layouts.app')

@section('subtitle', 'Dashboard Donasi Pendonor')
@section('content_header_title', 'Dashboard Donasi')
@section('sub_breadcumb', 'Dashboard Donasi')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
@endpush

@section('content_body')
    {{-- Back Button --}}
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('pendonor.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('cruds.mpendonor.list')}}
            </a>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="row">
        <div class="col-12">    
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-filter"></i> {{ __('cruds.mpendonor.filter') }}</h3>
                </div>
                <div class="card-body">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="filter_year" class="">{{ __('cruds.mpendonor.year') }}</label>
                                    <select id="filter_year" name="year" class="form-control select2">
                                        <option value="">{{ __('cruds.mpendonor.year') }}</option>
                                        @for($year = date('Y'); $year >= 2020; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_pendonor" class="">{{ __('cruds.mpendonor.pendonor') }}</label>
                                    <select id="filter_pendonor" name="pendonor_id" class="form-control select2">
                                        <option value="">{{ __('cruds.mpendonor.all_donor') }}</option>
                                        @foreach($pendonors as $pendonor)
                                            <option value="{{ $pendonor->id }}" 
                                                {{ $selectedPendonor && $selectedPendonor->id == $pendonor->id ? 'selected' : '' }}>
                                                {{ $pendonor->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="filter_program" class="">{{ __('cruds.program.title_singular') }}</label>
                                    <select id="filter_program" name="program_id" class="form-control select2">
                                        <option value="">{{ __('cruds.program.all') }}</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}">{{ $program->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label class="text-white d-block">&nbsp;</label>
                                    <button type="submit" class="btn btn-info btn-red">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row" id="statisticsSection">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info stat-card">
                <div class="inner">
                    <h3 id="total_donations">0</h3>
                    <p> {{ __('cruds.mpendonor.jumlah_donasi') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-usd stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success stat-card">
                <div class="inner text-right text-wrap">
                    <h3 id="total_value"><span>Rp </span>0</h3>
                    <p>{{ __('cruds.mpendonor.total_nilai') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning stat-card">
                <div class="inner">
                    <h3 id="unique_donors">0</h3>
                    <p>{{ __('cruds.mpendonor.unik_donor') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger stat-card">
                <div class="inner">
                    <h3 id="unique_programs">0</h3>
                    <p>{{ __('cruds.mpendonor.diff_program') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-project-diagram stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="row">
        {{-- Donasi per Pendonor --}}
        <div class="col-lg-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        {{ __('cruds.mpendonor.donasi_pendonor') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="donorChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Donasi per Program --}}
        <div class="col-lg-6">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        {{ __('cruds.mpendonor.donasi_program') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="programChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Timeline Chart --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        {{ __('cruds.mpendonor.timeline_donasi') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="timelineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed Table --}}
    <div class="row">
        <div class="col-12">
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table mr-1"></i>
                        {{ __('cruds.mpendonor.detail_donasi') }}
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" id="exportExcel">
                            <i class="fas fa-file-excel"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="donationTable">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>{{ __('cruds.mpendonor.nama') }}</th>
                                    <th>{{ __('cruds.program.title_singular') }}</th>
                                    <th width="8%" class="text-center">{{ __('cruds.mpendonor.year') }}</th>
                                    <th width="15%" class="text-right">{{ __('cruds.mpendonor.total_nilai') }}</th>
                                    <th width="12%" class="text-center">{{ __('cruds.mpendonor.tanggal') }}</th>
                                </tr>
                            </thead>
                            <tbody id="donationTableBody">
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Memuat data...
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="4" class="text-right">Total:</th>
                                    <th class="text-right" id="tableTotal">Rp 0</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loading Overlay --}}
    <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center; color:white;">
            <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
            <h4>Memuat data...</h4>
        </div>
    </div>
@stop

@push('js')
@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    // Get the selected pendonor ID from the route (if any)
    const selectedPendonorId = '{{ $selectedPendonor->id ?? "" }}';

    // Initialize locale settings
    const currentLocale = '{{ app()->getLocale() }}';
    const jsLocale = currentLocale === 'id' ? 'id-ID' : 'en-US';
    console.log('Dashboard Locale:', currentLocale, 'JS Locale:', jsLocale);

    // Initialize Select2
    $('.select2').select2({
        placeholder: '{{ __("global.pleaseSelect") }}...',
        allowClear: true
    });

    // If there's a selected pendonor, set the Select2 value
    if (typeof selectedPendonorId !== 'undefined' && selectedPendonorId) {
        $('#filter_pendonor').val(selectedPendonorId).trigger('change');
    }

    // Chart instances
    let donorChart, programChart, timelineChart;
    let donationDataTable;

    // Initialize DataTable
    donationDataTable = $('#donationTable').DataTable({
        data: [],
        columns: [
            { data: null, render: function(data, type, row, meta) {
                return meta.row + 1;
            }},
            { data: 'pendonor' },
            { data: 'program' },
            { data: 'program_year', className: 'text-center', render: function(data) {
                return '<span class="badge badge-secondary">' + data + '</span>';
            }},
            { data: 'nilaidonasi', className: 'text-right', render: function(data) {
                return 'Rp ' + parseFloat(data).toLocaleString(jsLocale);
            }},
            { data: 'tanggal', className: 'text-center', render: function(data) {
                return '<span class="badge badge-info donation-badge"><i class="far fa-calendar"></i> ' + data + '</span>';
            }}
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "{{ __('global.all') }}"]],
        footerCallback: function(row, data, start, end, display) {
            const api = this.api();
            const total = api.column(4).data().reduce(function(a, b) {
                return parseFloat(a) + parseFloat(b);
            }, 0);
            $('#tableTotal').text('Rp ' + total.toLocaleString(jsLocale));
        }
    });

    // Load initial data
    loadDonationData();

    // Filter form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadDonationData();
    });

    // Function to load donation data
    function loadDonationData() {
        $('#loadingOverlay').show();

        const filters = {
            year: $('#filter_year').val(),
            program_id: $('#filter_program').val(),
            pendonor_id: $('#filter_pendonor').val()
        };

        $.ajax({
            url: '{{ route("pendonor.donation.data") }}',
            method: 'GET',
            data: filters,
            dataType: 'json',
            success: function(response) {
                updateStatistics(response.statistics);
                updateCharts(response.charts);
                updateTable(response.details);
                $('#loadingOverlay').hide();
            },
            error: function(xhr, status, error) {
                $('#loadingOverlay').hide();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat data. Silakan coba lagi.',
                });
                console.error('Error:', error);
            }
        });
    }

    // Function to format numbers compactly (Rb/K, Jt/M, M/B, T/T)
    function formatCompactNumber(value) {
        const isID = currentLocale === 'id';
        const suffixes = isID ? 
            { k: 'Rb', m: 'Jt', b: 'M', t: 'T' } : 
            { k: 'K', m: 'M', b: 'B', t: 'T' };

        if (value >= 1000000000000) {
            return (value / 1000000000000).toLocaleString(jsLocale, {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' ' + suffixes.t;
        } else if (value >= 1000000000) {
            return (value / 1000000000).toLocaleString(jsLocale, {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' ' + suffixes.b;
        } else if (value >= 1000000) {
            return (value / 1000000).toLocaleString(jsLocale, {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' ' + suffixes.m;
        } else if (value >= 1000) {
            return (value / 1000).toLocaleString(jsLocale, {maximumFractionDigits: 0}) + ' ' + suffixes.k;
        }
        return value.toLocaleString(jsLocale);
    }

    // Update statistics cards
    function updateStatistics(stats) {
        $('#total_donations').text(stats.total_donations.toLocaleString(jsLocale));
        
        // Compact display for main box, full value in tooltip
        const fullValue = 'Rp ' + stats.total_value.toLocaleString(jsLocale);
        const compactValue = 'Rp ' + formatCompactNumber(stats.total_value);
        
        $('#total_value').text(compactValue);
        $('#total_value').attr('title', fullValue); // Browser tooltip
        $('#total_value').css('cursor', 'help');
        
        // Add dynamic font size adjustment if value is still long
        if (compactValue.length > 10) {
            $('#total_value').css('font-size', '1.8rem');
        } else {
            $('#total_value').css('font-size', '');
        }

        $('#unique_donors').text(stats.unique_donors);
        $('#unique_programs').text(stats.unique_programs);
    }

    // Update charts
    function updateCharts(charts) {
        // Donor Chart (Pie) - Now showing donations by program
        const donorCtx = document.getElementById('donorChart').getContext('2d');
        if (donorChart) donorChart.destroy();
        
        donorChart = new Chart(donorCtx, {
            type: 'doughnut',
            data: {
                labels: charts.by_program.map(p => p.kode),
                datasets: [{
                    label: '{{ __("cruds.mpendonor.chart_donation_count") }}',
                    data: charts.by_program.map(p => p.count),
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: '{{ __("cruds.mpendonor.chart_distribution") }}'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const program = charts.by_program[context.dataIndex];
                                const labelDonasi = '{{ __("cruds.mpendonor.chart_donation") }}';
                                return [
                                    program.name + ' (' + program.kode + '): ' + program.count + ' ' + labelDonasi,
                                    'Total: Rp ' + program.total.toLocaleString(jsLocale)
                                ];
                            }
                        }
                    }
                }
            }
        });

        // Program Chart (Bar)
        const programCtx = document.getElementById('programChart').getContext('2d');
        if (programChart) programChart.destroy();
        
        programChart = new Chart(programCtx, {
            type: 'bar',
            data: {
                labels: charts.by_program.map(p => p.kode),
                datasets: [{
                    label: '{{ __("cruds.mpendonor.chart_donation_value") }} (Rp)',
                    data: charts.by_program.map(p => p.total),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: '{{ __("cruds.mpendonor.chart_donation_value") }}'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString(jsLocale);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString(jsLocale);
                            }
                        }
                    }
                }
            }
        });

        // Timeline Chart (Line) - Stock/Equity style
        const timelineCtx = document.getElementById('timelineChart').getContext('2d');
        if (timelineChart) timelineChart.destroy();
        
        // Create gradient fill for stock chart effect
        const gradient = timelineCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(34, 197, 94, 0.4)');
        gradient.addColorStop(0.5, 'rgba(34, 197, 94, 0.1)');
        gradient.addColorStop(1, 'rgba(34, 197, 94, 0)');

        timelineChart = new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: charts.timeline.map(t => t.month),
                datasets: [{
                    label: '{{ __("cruds.mpendonor.fields.total_donation") ?? "Total Donasi" }} (Rp)',
                    data: charts.timeline.map(t => t.total),
                    borderColor: 'rgba(34, 197, 94, 1)',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: '{{ __("cruds.mpendonor.donation_trend") ?? "" }}',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return 'Total: Rp ' + context.parsed.y.toLocaleString(jsLocale);
                            },
                            afterLabel: function(context) {
                                const dataIndex = context.dataIndex;
                                const count = charts.timeline[dataIndex]?.count || 0;
                                const labelDonationCount = '{{ __("cruds.mpendonor.chart_donation_count") }}';
                                return labelDonationCount + ': ' + count;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Bulan - Tahun',
                            font: { weight: 'bold' }
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        title: {
                            display: true,
                            text: '{{ __("cruds.mpendonor.chart_total_donation") }} (Rp)',
                            font: { weight: 'bold' }
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + formatCompactNumber(value);
                            }
                        }
                    }
                }
            }
        });
    }

    // Update detail table
    function updateTable(details) {
        donationDataTable.clear();
        
        if (details.length === 0) {
            donationDataTable.draw();
            $('#tableTotal').text('Rp 0');
            return;
        }

        donationDataTable.rows.add(details).draw();
    }

    // Export to Excel
    $('#exportExcel').on('click', function() {
        const filters = {
            year: $('#filter_year').val(),
            program_id: $('#filter_program').val(),
            pendonor_id: $('#filter_pendonor').val()
        };

        const queryString = $.param(filters);
        window.location.href = '{{ route("pendonor.donation.export") }}?' + queryString;
    });

    // Auto-refresh every 5 minutes
    setInterval(function() {
        loadDonationData();
    }, 300000);
});
</script>
@endpush