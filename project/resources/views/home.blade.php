@extends('layouts.app')

@section('subtitle', 'Dashboard')
@section('content_header_title', 'Dashboard')
@section('sub_breadcumb', '')

@section('content_body')
    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="programFilter">Program:</label>
            <select id="programFilter" class="form-control">
                <option value="">Semua Program</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->nama ?? 'Tanpa Nama' }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="tahunFilter">Periode (Tahun):</label>
            <select id="tahunFilter" class="form-control">
                <option value="">Semua Tahun</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label for="provinsiFilter">Provinsi:</label>
            <select id="provinsiFilter" class="form-control">
                <option value="">Semua Provinsi</option>
                @foreach ($provinsis as $provinsi)
                    <option value="{{ $provinsi->id }}">{{ $provinsi->nama ?? 'Tanpa Nama' }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- End Filter Section -->

    <div class="row" id="dashboardCards">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3 id="totalSemua">-</h3>
                    <p><span class="info-box-text">Total {{ __('cruds.beneficiary.title') }}</span>
                    </p>
                </div>
                <div class="icon">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><span class="info-box-number" id="totalLaki">-</span></h3>

                    <p>{{ __('cruds.beneficiary.penerima.laki') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-male"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><span class="info-box-number" id="totalPerempuan">-</span></h3>

                    <p>{{ __('cruds.beneficiary.penerima.perempuan') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-female"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><span class="info-box-number" id="totalAnakLaki">-</span></h3>
                    <p>{{ __('cruds.beneficiary.penerima.boy') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-child"></i>
                </div>
                {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><span class="info-box-number" id="totalAnakPerempuan">-</span></h3>
                    <p>{{ __('cruds.beneficiary.penerima.girl') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-person-standing-dress"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><span class="info-box-number" id="totalDisabilitas">-</span></h3>
                    <p>{{ __('cruds.beneficiary.penerima.disability') }}</p>
                </div>
                <div class="icon">
                    <i class="bi bi-person-wheelchair"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><span class="info-box-number" id="totalKeluarga">-</span></h3>
                    <p>{{ __('cruds.beneficiary.penerima.keluarga') }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>

    <!-- Chart Section -->
    <div class="row" id="dashboardCharts">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="barChart" height="200" style="display: block;" width="765"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" height="200" style="display: block;" width="765"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- End Chart Section -->

@endsection


@push('css')
    <style>
        .small-box .icon>i {
            font-size: 90px !important;
        }

        @media (min-width: 1200px) {
            .small-box>.inner>h3 {
                font-size: 60px !important;
            }
        }
    </style>
@endpush

@push('js')
    <script src="/vendor/chart.js/Chart.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"
        integrity="sha512-L0Shl7nXXzIlBSUUPpxrokqq4ojqgZFQczTYlGjzONGTDAcLremjwaWv5A+EDLnxhQzY5xUZPWLOLqYRkY0Cbw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <script>
        function loadDashboardData() {
            $.ajax({
                url: "{{ route('dashboard.data') }}",
                method: 'GET',
                data: {
                    program_id: $('#programFilter').val(),
                    provinsi_id: $('#provinsiFilter').val(),
                    tahun: $('#tahunFilter').val()
                },
                success: function(data) {
                    $('#totalSemua').text(data.semua);
                    $('#totalLaki').text(data.laki);
                    $('#totalPerempuan').text(data.perempuan);
                    $('#totalAnakLaki').text(data.anak_laki);
                    $('#totalAnakPerempuan').text(data.anak_perempuan);
                    $('#totalDisabilitas').text(data.disabilitas);
                    $('#totalKeluarga').text(data.keluarga);
                },
                error: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak ada data berdasarkan parameter yang dipilih',
                        showCloseButton: true,
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        position: 'top-end',
                    });
                    $('#totalSemua').text('0');
                    $('#totalLaki').text('0');
                    $('#totalPerempuan').text('0');
                    $('#totalAnakLaki').text('0');
                    $('#totalAnakPerempuan').text('0');
                    $('#totalDisabilitas').text('0');
                    $('#totalKeluaga').text('0');
                    console.error('Error fetching data');
                }
            });
        }

        $(document).ready(function() {
            $('#programFilter, #provinsiFilter, #tahunFilter').change(loadDashboardData);
            loadDashboardData(); // initial load


            fetch('/dashboard/data/get-desa-chart-data')
                .then(res => res.json())
                .then(data => {
                    const provinsiLabels = data.map(item => item.provinsi);
                    const desaCounts = data.map(item => item.total_desa);

                    var ticksStyle = {
                        fontColor: '#495057',
                        fontStyle: 'bold'
                    }

                    var mode = 'index'
                    var intersect = true

                    const barChart = new Chart(document.getElementById('barChart'), {
                        type: 'bar',
                        data: {
                            labels: provinsiLabels,
                            datasets: [{
                                label: 'Jumlah Desa Penerima Manfaat',
                                data: desaCounts,
                                backgroundColor: '#007bff'
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            tooltips: {
                                mode: mode,
                                intersect: intersect
                            },
                            hover: {
                                mode: mode,
                                intersect: intersect
                            },
                            legend: {
                                display: false
                            },

                            responsive: true,
                            indexAxis: 'x', // bisa jadi 'y' jika ingin horizontal bar
                            plugins: {
                                legend: {
                                    display: true
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (ctx) => `${ctx.raw} desa`
                                    }
                                }
                            },
                            // scales: {
                            //     x: {
                            //         ticks: {
                            //             autoSkip: false,
                            //             maxRotation: 90,
                            //             minRotation: 45
                            //         }
                            //     },
                            //     y: {
                            //         beginAtZero: true
                            //     }
                            // }
                            scales: {
                                yAxes: [{
                                    // display: false,
                                    gridLines: {
                                        display: true,
                                        lineWidth: '2px',
                                        color: 'rgba(0, 0, 0, .2)',
                                        zeroLineColor: 'transparent'
                                    },
                                    ticks: $.extend({
                                        beginAtZero: true,
                                        // callback: function(value) {
                                        //     if (value >= 100) {
                                        //         value /= 100
                                        //     }

                                        // return value
                                        // }
                                    }, ticksStyle)
                                }],
                                xAxes: [{
                                    display: true,
                                    gridLines: {
                                        display: false
                                    },
                                    ticks: ticksStyle
                                }]
                            }
                        }
                    });

                    // Jika pie chart masih diinginkan:
                    const pieChart = new Chart(document.getElementById('pieChart'), {
                        type: 'pie',
                        data: {
                            labels: provinsiLabels,
                            datasets: [{
                                data: desaCounts,
                                backgroundColor: generateColors(desaCounts.length)
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    });
                });

            function generateColors(count) {
                const baseColors = ['#4caf50', '#03a9f4', '#00bcd4', '#e91e63', '#ff9800', '#ff5722', '#9c27b0'];
                while (baseColors.length < count) {
                    baseColors.push(`#${Math.floor(Math.random()*16777215).toString(16)}`);
                }
                return baseColors.slice(0, count);
            }
        });
    </script>
@endpush
