@extends('layouts.print')

@section('content')
    <div class="row">
        <div class="col-12">
            <h2 class="page-header">
                <i class="fas fa-globe"></i> Dashboard
                <small class="float-right">Date: {{ date('d/m/Y') }}</small>
            </h2>
        </div>
    </div>

    <div class="row" id="dashboardCards">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $dashboardData['semua'] }}</h3>
                    <p>Total {{ __('cruds.beneficiary.title') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $dashboardData['laki'] }}</h3>
                    <p>{{ __('cruds.beneficiary.penerima.laki') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $dashboardData['perempuan'] }}</h3>
                    <p>{{ __('cruds.beneficiary.penerima.perempuan') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $dashboardData['anak_laki'] }}</h3>
                    <p>{{ __('cruds.beneficiary.penerima.boy') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $dashboardData['anak_perempuan'] }}</h3>
                    <p>{{ __('cruds.beneficiary.penerima.girl') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $dashboardData['disabilitas'] }}</h3>
                    <p>{{ __('cruds.beneficiary.penerima.disability') }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $dashboardData['keluarga'] }}</h3>
                    <p>{{ __('cruds.beneficiary.penerima.keluarga') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="dashboardCharts">
        <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="card card card-success">
                <div class="card-header">
                    <h3 class="card-title">Bar Chart</h3>
                </div>
                <div class="card-body">
                    <canvas id="barChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Pie Chart</h3>
                </div>
                <div class="card-body">
                    <canvas id="pieChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h4>Data Desa Penerima Manfaat</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('cruds.dusun.form.nama') }}</th>
                        <th>{{ __('cruds.dusun.form.des') }}</th>
                        <th>{{ __('cruds.dusun.form.kec') }}</th>
                        <th>{{ __('cruds.dusun.form.kab') }}</th>
                        <th>{{ __('cruds.dusun.form.prov') }}</th>
                        <th>{{ __('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grouped as $item)
                        <tr>
                            <td>{{ $item['nama_dusun'] }}</td>
                            <td>{{ $item['desa'] }}</td>
                            <td>{{ $item['kecamatan'] }}</td>
                            <td>{{ $item['kabupaten'] }}</td>
                            <td>{{ $item['provinsi'] }}</td>
                            <td>{{ $item['total_penerima'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"
    integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        let barChart, pieChart;

        function loadChartData() {
            const filters = {
                provinsi_id: "{{ $request->provinsi_id }}",
                program_id: "{{ $request->program_id }}",
                tahun: "{{ $request->tahun }}"
            };

            fetch('/dashboard/data/get-desa-chart-data?' + new URLSearchParams(filters))
                .then(res => res.json())
                .then(data => {
                    const provinsiLabels = data.map(item => item.provinsi);
                    const desaCounts = data.map(item => item.total_desa);

                    if (barChart) barChart.destroy();
                    if (pieChart) pieChart.destroy();

                    barChart = new Chart(document.getElementById('barChart'), {
                        type: 'bar',
                        data: {
                            labels: provinsiLabels,
                            datasets: [{
                                label: 'Jumlah Desa Penerima Manfaat',
                                data: desaCounts,
                                backgroundColor: generateColors(desaCounts.length),
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (ctx) => `${ctx.raw} Desa`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    pieChart = new Chart(document.getElementById('pieChart'), {
                        type: 'pie',
                        data: {
                            labels: provinsiLabels,
                            datasets: [{
                                data: desaCounts,
                                backgroundColor: generateColors(desaCounts.length),
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'right'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(ctx) {
                                            const value = ctx.raw;
                                            const total = ctx.chart._metasets[ctx.datasetIndex]
                                                .total;
                                            const percentage = ((value / total) * 100).toFixed(
                                                1);
                                            return `${ctx.label}: ${value} desa (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    setTimeout(() => {
                        window.print();
                    }, 1000);
                })
                .catch(error => console.error('Error fetching chart data:', error));
        }

        function generateColors(count) {
            const baseColors = ['#4caf50', '#03a9f4', '#00bcd4', '#e91e63', '#ff9800', '#ff5722', '#9c27b0'];
            const generatedColors = new Set(baseColors);

            while (generatedColors.size < count) {
                const randomColor = generateReadableColor();
                generatedColors.add(randomColor);
            }

            return Array.from(generatedColors).slice(0, count);
        }

        function generateReadableColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            if (isReadableColor(color)) {
                return color;
            } else {
                return generateReadableColor();
            }
        }

        function isReadableColor(color) {
            const r = parseInt(color.slice(1, 3), 16);
            const g = parseInt(color.slice(3, 5), 16);
            const b = parseInt(color.slice(5, 7), 16);
            const brightness = (r * 299 + g * 587 + b * 114) / 1000;
            return brightness > 50 && brightness < 200;
        }

        loadChartData();
    });
</script>
@endpush
