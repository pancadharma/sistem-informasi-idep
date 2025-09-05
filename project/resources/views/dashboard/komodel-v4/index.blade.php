@extends('layouts.app')

@section('subtitle', 'Komponen Model V4 Dashboard')
@section('content_header_title', 'Komponen Model V4 Dashboard')

@section('content_body')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="program_id">Program</label>
                <select name="program_id" id="program_id" class="form-control">
                    <option value="">All Programs</option>
                    @foreach($programDistribution as $program)
                        <option value="{{ $program->program_id }}">{{ $program->program_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="komponenmodel_id">Komponen Model</label>
                <select name="komponenmodel_id" id="komponenmodel_id" class="form-control">
                    <option value="">All Komponen Models</option>
                    @foreach($modelDistribution as $model)
                        <option value="{{ $model->id }}">{{ $model->model_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="provinsi_id">Provinsi</label>
                <select name="provinsi_id" id="provinsi_id" class="form-control">
                    <option value="">All Provinsi</option>
                    @foreach($provinceLevelSummary as $provinsi)
                        <option value="{{ $provinsi->id }}">{{ $provinsi->province_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="tahun">Tahun</label>
                <select name="tahun" id="tahun" class="form-control">
                    <option value="">All Tahun</option>
                    @foreach($timelineProgress as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <!-- Executive Overview Dashboard -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Executive Overview</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalComponentModels }}</h3>
                            <p>Total Component Models</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $programsWithComponents }}</h3>
                            <p>Programs with Components</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $geographicCoverageProvinces }}</h3>
                            <p>Geographic Coverage (Provinces)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-globe-asia"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $totalBeneficiaries }}</h3>
                            <p>Total Beneficiaries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Program Distribution</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="programDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Geographic Heat Map</h3>
                        </div>
                        <div class="card-body">
                            <!-- Placeholder for map -->
                            <div id="map" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Timeline Chart</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="timelineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Progress Completion Rates</h3>
                        </div>
                        <div class="card-body">
                            <!-- Placeholder for progress completion rates -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Geographic Distribution Dashboard -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Geographic Distribution</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Province-Level Summary</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Province</th>
                                <th>Component Locations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($provinceLevelSummary as $summary)
                            <tr>
                                <td>{{ $summary->province_name }}</td>
                                <td>{{ $summary->component_locations }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>District/Regency View</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>District</th>
                                <th>Province</th>
                                <th>Component Locations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($districtLevelSummary as $summary)
                            <tr>
                                <td>{{ $summary->district_name }}</td>
                                <td>{{ $summary->province_name }}</td>
                                <td>{{ $summary->component_locations }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Component Model Analysis Dashboard -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Component Model Analysis</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Model Distribution</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Model Name</th>
                                <th>Total Components</th>
                                <th>Total Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modelDistribution as $dist)
                            <tr>
                                <td>{{ $dist->model_name }}</td>
                                <td>{{ $dist->total_components }}</td>
                                <td>{{ $dist->total_quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>User Assignment</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Assigned Components</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userAssignment as $assignment)
                            <tr>
                                <td>{{ $assignment->user_name }}</td>
                                <td>{{ $assignment->assigned_components }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Component Type Breakdown</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="componentTypeChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quantity Analysis</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="quantityAnalysisChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Utilization Rates</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="utilizationRateChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Beneficiary Impact Dashboard -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Beneficiary Impact</h3>
        </div>
        <div class="card-body">
            <!-- Placeholder for beneficiary data -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Gender Distribution</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="genderDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Age Group Analysis</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="ageGroupAnalysisChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Integration Dashboard -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Program Integration</h3>
        </div>
        <div class="card-body">
            <!-- Placeholder for program integration data -->
            <div id="program-tree"></div>
        </div>
    </div>

    <!-- Operational Management Dashboard -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Operational Management</h3>
        </div>
        <div class="card-body">
            <!-- Placeholder for operational management data -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Assigned Components</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userAssignmentMatrix as $user)
                        <tr>
                            <td>{{ $user->nama }}</td>
                            <td>{{ $user->trmeals_komponen_models_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

    <script>
        $(document).ready(function() {
            // Program Distribution Chart
            var ctx = document.getElementById('programDistributionChart').getContext('2d');
            var programDistributionChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($programDistribution->pluck('program_name')) !!},
                    datasets: [{
                        label: 'Program Distribution',
                        data: {!! json_encode($programDistribution->pluck('total_components')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Timeline Chart
            var timelineCtx = document.getElementById('timelineChart').getContext('2d');
            var timelineChart = new Chart(timelineCtx, {
                type: 'line',
                data: {
                    labels: [], // Add labels here
                    datasets: [{
                        label: 'Component Deployment',
                        data: [], // Add data here
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Map
            var map = L.map('map').setView([-2.5489, 118.0149], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            @foreach($interactiveMapData as $location)
                L.marker([{{ $location->lat }}, {{ $location->long }}]).addTo(map);
            @endforeach

            // Component Type Chart
            var componentTypeCtx = document.getElementById('componentTypeChart').getContext('2d');
            var componentTypeChart = new Chart(componentTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($modelDistribution->pluck('model_name')) !!},
                    datasets: [{
                        label: 'Component Type',
                        data: {!! json_encode($modelDistribution->pluck('total_components')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Quantity Analysis Chart
            var quantityAnalysisCtx = document.getElementById('quantityAnalysisChart').getContext('2d');
            var quantityAnalysisChart = new Chart(quantityAnalysisCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($modelDistribution->pluck('model_name')) !!},
                    datasets: [{
                        label: 'Total Quantity',
                        data: {!! json_encode($modelDistribution->pluck('total_quantity')) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Utilization Rate Chart (Placeholder)
            var utilizationRateCtx = document.getElementById('utilizationRateChart').getContext('2d');
            var utilizationRateChart = new Chart(utilizationRateCtx, {
                type: 'radar',
                data: {
                    labels: [], // Add labels here
                    datasets: [{
                        label: 'Utilization Rate',
                        data: [], // Add data here
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Gender Distribution Chart
            var genderDistributionCtx = document.getElementById('genderDistributionChart').getContext('2d');
            var genderDistributionChart = new Chart(genderDistributionCtx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($genderDistribution->pluck('jenis_kelamin')) !!},
                    datasets: [{
                        label: 'Gender Distribution',
                        data: {!! json_encode($genderDistribution->pluck('total')) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Age Group Analysis Chart
            var ageGroupAnalysisCtx = document.getElementById('ageGroupAnalysisChart').getContext('2d');
            var ageGroupAnalysisChart = new Chart(ageGroupAnalysisCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($ageGroupAnalysis->pluck('age_group')) !!},
                    datasets: [{
                        label: 'Age Group',
                        data: {!! json_encode($ageGroupAnalysis->pluck('total')) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Program Integration View
            var treeData = {!! json_encode($programHierarchy) !!};
            $('#program-tree').jstree({
                'core' : {
                    'data' : treeData
                }
            });

            function updateDashboard(filters) {
                $.ajax({
                    url: '{{ route("dashboard.komodel-v4.index") }}',
                    type: 'GET',
                    data: filters,
                    success: function(data) {
                        // Update KPI cards
                        $('#totalComponentModels').text(data.totalComponentModels);
                        $('#programsWithComponents').text(data.programsWithComponents);
                        $('#geographicCoverageProvinces').text(data.geographicCoverageProvinces);
                        $('#totalBeneficiaries').text(data.totalBeneficiaries);

                        // Update Program Distribution Chart
                        programDistributionChart.data.labels = data.programDistribution.map(item => item.program_name);
                        programDistributionChart.data.datasets[0].data = data.programDistribution.map(item => item.total_components);
                        programDistributionChart.update();

                        // Update Geographic Distribution Tables
                        let provinceTableBody = '';
                        data.provinceLevelSummary.forEach(summary => {
                            provinceTableBody += `<tr><td>${summary.province_name}</td><td>${summary.component_locations}</td></tr>`;
                        });
                        $('table.table-bordered:eq(0) tbody').html(provinceTableBody);

                        let districtTableBody = '';
                        data.districtLevelSummary.forEach(summary => {
                            districtTableBody += `<tr><td>${summary.district_name}</td><td>${summary.province_name}</td><td>${summary.component_locations}</td></tr>`;
                        });
                        $('table.table-bordered:eq(1) tbody').html(districtTableBody);

                        // Update Map
                        map.eachLayer(function(layer) {
                            if (layer instanceof L.Marker) {
                                map.removeLayer(layer);
                            }
                        });
                        data.interactiveMapData.forEach(location => {
                            L.marker([location.lat, location.long]).addTo(map);
                        });

                        // Update Component Model Analysis Tables
                        let modelDistributionTableBody = '';
                        data.modelDistribution.forEach(dist => {
                            modelDistributionTableBody += `<tr><td>${dist.model_name}</td><td>${dist.total_components}</td><td>${dist.total_quantity}</td></tr>`;
                        });
                        $('table.table-bordered:eq(2) tbody').html(modelDistributionTableBody);

                        let userAssignmentTableBody = '';
                        data.userAssignment.forEach(assignment => {
                            userAssignmentTableBody += `<tr><td>${assignment.user_name}</td><td>${assignment.assigned_components}</td></tr>`;
                        });
                        $('table.table-bordered:eq(3) tbody').html(userAssignmentTableBody);

                        // Update Component Type Chart
                        componentTypeChart.data.labels = data.modelDistribution.map(item => item.model_name);
                        componentTypeChart.data.datasets[0].data = data.modelDistribution.map(item => item.total_components);
                        componentTypeChart.update();

                        // Update Quantity Analysis Chart
                        quantityAnalysisChart.data.labels = data.modelDistribution.map(item => item.model_name);
                        quantityAnalysisChart.data.datasets[0].data = data.modelDistribution.map(item => item.total_quantity);
                        quantityAnalysisChart.update();

                        // Update Gender Distribution Chart
                        genderDistributionChart.data.labels = data.genderDistribution.map(item => item.jenis_kelamin);
                        genderDistributionChart.data.datasets[0].data = data.genderDistribution.map(item => item.total);
                        genderDistributionChart.update();

                        // Update Age Group Analysis Chart
                        ageGroupAnalysisChart.data.labels = data.ageGroupAnalysis.map(item => item.age_group);
                        ageGroupAnalysisChart.data.datasets[0].data = data.ageGroupAnalysis.map(item => item.total);
                        ageGroupAnalysisChart.update();

                        // Update Program Integration Tree
                        $('#program-tree').jstree(true).settings.core.data = data.programHierarchy;
                        $('#program-tree').jstree(true).refresh();

                        // Update User Assignment Matrix
                        let userAssignmentMatrixTableBody = '';
                        data.userAssignmentMatrix.forEach(user => {
                            userAssignmentMatrixTableBody += `<tr><td>${user.nama}</td><td>${user.trmeals_komponen_models_count}</td></tr>`;
                        });
                        $('table.table-bordered:eq(4) tbody').html(userAssignmentMatrixTableBody);
                    }
                });
            }

            $('#program_id, #komponenmodel_id, #provinsi_id, #tahun').on('change', function() {
                let filters = {
                    program_id: $('#program_id').val(),
                    komponenmodel_id: $('#komponenmodel_id').val(),
                    provinsi_id: $('#provinsi_id').val(),
                    tahun: $('#tahun').val()
                };
                updateDashboard(filters);
            });
        });
    </script>
@endpush
