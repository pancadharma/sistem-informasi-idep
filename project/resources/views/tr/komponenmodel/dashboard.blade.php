@extends('layouts.app')

@section('subtitle', 'Komponen Model Dashboard')
@section('content_header_title', 'Komponen Model Dashboard')

@section('content_body')
    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-md-3 order-1">
            <label for="programFilter">Program:</label>
            <select id="programFilter" class="form-control select2">
                <option value="">Semua Program</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-none">
            <label for="sektorFilter">Sektor:</label>
            <select id="sektorFilter" class="form-control select2">
                <option value="">Semua Sektor</option>
                @foreach ($sektors as $sektor)
                    <option value="{{ $sektor['id'] }}">{{ $sektor['nama'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 order-2">
            <label for="modelFilter">Model:</label>
            <select id="modelFilter" class="form-control select2">
                <option value="">Semua Model</option>
                @foreach ($models as $model)
                    <option value="{{ $model->id }}">{{ $model->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 order-3">
            <label for="tahunFilter">Tahun:</label>
            <select id="tahunFilter" class="form-control select2">
                <option value="">Semua Tahun</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 order-4 text-right">
            <label style="display: block;">&nbsp;</label> <!-- Spacer for alignment -->
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-export"></i> Export
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" id="exportPdf">
                        <i class="fas fa-file-pdf text-danger mr-2"></i> PDF
                    </a>
                    <a class="dropdown-item" href="#" id="exportDocx">
                        <i class="fas fa-file-word text-primary mr-2"></i> DOCX
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="totalKomponen">-</h3>
                    <p>Total Komponen Model</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="totalProgram">-</h3>
                    <p>Total Program</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="totalLokasi">-</h3>
                    <p>Total Lokasi</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="totalJumlah">-</h3>
                    <p>Total Kuantitas</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Komponen per Sektor</h3>
                </div>
                <div class="card-body">
                    <canvas id="sektorChart"  style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; box-sizing: border-box; width: 763px;" width="763" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jumlah per Program</h3>
                </div>
                <div class="card-body">
                    <canvas id="programChart"  style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; box-sizing: border-box; width: 763px;" width="763" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Komponen per Model</h3>
                </div>
                <div class="card-body">
                    <canvas id="modelChart"  style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; box-sizing: border-box; width: 763px;" width="763" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Map -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Distribusi Lokasi</h3>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap" async defer></script>

    <script>
        let map;
        let markers = [];
        let sektorChart, programChart, modelChart;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -2.5489, lng: 118.0149 },
                zoom: 5,
            });
            updateDashboard(); // Initial load after map is ready
        }

        function clearMarkers() {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }

        function updateMap(filters) {
            $.ajax({
                url: '{{ route('komodel.map_markers') }}',
                data: filters,
                success: function(data) {
                    clearMarkers();
                    data.forEach(function(item) {
                        if (item.lat && item.long) {
                            const latLng = new google.maps.LatLng(parseFloat(item.lat), parseFloat(item.long));
                            const marker = new google.maps.Marker({
                                position: latLng,
                                map: map,
                            });
                            const infoWindow = new google.maps.InfoWindow({
                                content: `<b>${item.nama_komponen_model}</b><br>${item.nama_provinsi}`
                            });
                            marker.addListener('click', function() {
                                infoWindow.open(map, marker);
                            });
                            markers.push(marker);
                        }
                    });
                }
            });
        }

        function updateSektorChart(filters) {
            $.ajax({
                url: '{{ route("komodel.sektor_chart_data") }}',
                data: filters,
                success: function(data) {
                    let labels = data.map(item => item.sektor_name);
                    let totals = data.map(item => item.total);
                    sektorChart.data.labels = labels;
                    sektorChart.data.datasets[0].data = totals;
                    sektorChart.update();
                }
            });
        }

        function updateProgramChart(filters) {
            $.ajax({
                url: '{{ route("komodel.program_chart_data") }}',
                data: filters,
                success: function(data) {
                    let labels = data.map(item => item.program_name);
                    let totals = data.map(item => item.total);
                    programChart.data.labels = labels;
                    programChart.data.datasets[0].data = totals;
                    programChart.update();
                }
            });
        }

        function updateModelChart(filters) {
            $.ajax({
                url: '{{ route("komodel.model_chart_data") }}',
                data: filters,
                success: function(data) {
                    let labels = data.map(item => item.model_name);
                    let totals = data.map(item => item.total);
                    modelChart.data.labels = labels;
                    modelChart.data.datasets[0].data = totals;
                    modelChart.update();
                }
            });
        }

        function updateSummaryData(filters) {
            $.ajax({
                url: '{{ route("komodel.summary_data") }}',
                data: filters,
                success: function(data) {
                    $('#totalKomponen').text(data.totalKomponen);
                    $('#totalProgram').text(data.totalProgram);
                    $('#totalLokasi').text(data.totalLokasi);
                    $('#totalJumlah').text(data.totalJumlah);
                }
            });
        }

        function updateDashboard() {
            const filters = {
                program_id: $('#programFilter').val(),
                sektor_id: $('#sektorFilter').val(),
                model_id: $('#modelFilter').val(),
                tahun: $('#tahunFilter').val()
            };

            updateMap(filters);
            updateSektorChart(filters);
            updateProgramChart(filters);
            updateModelChart(filters);
            updateSummaryData(filters);
        }

        $(document).ready(function() {
            $('.select2').select2();

            sektorChart = new Chart(document.getElementById('sektorChart').getContext('2d'), {
                type: 'bar',
                data: { labels: [], datasets: [{ label: 'Jumlah Komponen', data: [], backgroundColor: 'rgba(54, 162, 235, 0.2)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 1 }] }
            });

            programChart = new Chart(document.getElementById('programChart').getContext('2d'), {
                type: 'pie',
                data: { labels: [], datasets: [{ label: 'Jumlah', data: [], backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'], borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'], borderWidth: 1 }] }
            });

            modelChart = new Chart(document.getElementById('modelChart').getContext('2d'), {
                type: 'doughnut',
                data: { labels: [], datasets: [{ label: 'Jumlah Komponen', data: [], backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'], borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'], borderWidth: 1 }] },
                options: {
                    onClick: function(evt, elements) {
                        if (elements.length > 0) {
                            const chartElement = elements[0];
                            const modelName = this.data.labels[chartElement.index];
                            updateMap({ model_name: modelName });
                        }
                    }
                }
            });

            $('#programFilter, #sektorFilter, #modelFilter, #tahunFilter').on('change', function() {
                updateDashboard();
            });

            $('#modelFilter').on('change', function() {
                const modelId = $(this).val();
                if (modelId) {
                    $.ajax({
                        url: '{{ route('komodel.programs_by_model') }}',
                        data: { model_id: modelId },
                        success: function(data) {
                            let programFilter = $('#programFilter');
                            programFilter.empty();
                            programFilter.append(new Option('Semua Program', ''));
                            data.forEach(function(program) {
                                programFilter.append(new Option(program.nama, program.id));
                            });
                            programFilter.trigger('change');
                        }
                    });
                } else {
                    // If no model is selected, reset program filter to all programs
                    $('#programFilter').empty().append(new Option('Semua Program', '')).trigger('change');
                }
            });

            // Export functionality
            $('#exportPdf').on('click', function(e) {
                e.preventDefault();
                exportDashboard('pdf');
            });

            $('#exportDocx').on('click', function(e) {
                e.preventDefault();
                exportDashboard('docx');
            });

            function exportDashboard(format) {
                const filters = {
                    program_id: $('#programFilter').val(),
                    sektor_id: $('#sektorFilter').val(),
                    model_id: $('#modelFilter').val(),
                    tahun: $('#tahunFilter').val()
                };

                // Get chart images as base64
                const sektorChartImage = sektorChart.toBase64Image();
                const programChartImage = programChart.toBase64Image();
                const modelChartImage = modelChart.toBase64Image();

                // Create a form and submit
                const form = $('<form>', {
                    action: format === 'pdf' ? '{{ route('komodel.export.pdf') }}' : '{{ route('komodel.export.docx') }}',
                    method: 'POST',
                    target: '_blank', // Open in new tab
                    style: 'display:none;'
                });

                // Add CSRF token
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));

                // Add filters
                for (const key in filters) {
                    form.append($('<input>', {
                        type: 'hidden',
                        name: key,
                        value: filters[key]
                    }));
                }

                // Add chart images
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'sektorChart',
                    value: sektorChartImage
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'programChart',
                    value: programChartImage
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'modelChart',
                    value: modelChartImage
                }));

                $('body').append(form);
                form.submit();
                form.remove(); // Clean up the form
            }
        });
    </script>
@endpush
