@extends('layouts.app')

@section('subtitle', __('cruds.komponenmodel.dashboard'))
@section('content_header_title', __('cruds.komponenmodel.dashboard'))

@section('content_body')
    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-md-3 order-1">
            <label for="programFilter">{{ __('cruds.komponenmodel.program_name') }}:</label>
            <select id="programFilter" class="form-control select2">
                <option value="">{{ __('global.pleaseSelect') . " ". __('cruds.program.title') }}</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-none">
            <label for="sektorFilter">{{ __('cruds.komponenmodel.sector') }}:</label>
            <select id="sektorFilter" class="form-control select2">
                <option value="">{{ __('global.pleaseSelect') . " ". __('cruds.komponenmodel.sector') }}</option>
                @foreach ($sektors as $sektor)
                    <option value="{{ $sektor['id'] }}">{{ $sektor['nama'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 order-2">
            <label for="modelFilter">{{ __('cruds.komponenmodel.title') }}:</label>
            <select id="modelFilter" class="form-control select2">
                <option value="">{{ __('global.pleaseSelect') . " ". __('cruds.komponenmodel.title') }}</option>
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

    <!-- Komponen Model Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Komponen Model</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="komponenTable">
                            <thead>
                                <tr>
                                    <th>Program</th>
                                    <th>Tipe Komponen</th>
                                    <th>Total Unit</th>
                                    <th>Satuan</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody id="komponenTableBody">
                                <tr>
                                    <td colspan="7" class="text-center">Pilih filter untuk menampilkan data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Program Details (shown after filter selection) -->
    <div class="row" id="programDetailsSection" style="display: none;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Program: <span id="selectedProgramName"></span></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Program</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nama Program:</strong></td>
                                    <td id="programName">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td id="programStatus">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Tahun Mulai:</strong></td>
                                    <td id="programStartYear">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Statistik Program</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Komponen:</strong></td>
                                    <td id="programTotalKomponen">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Lokasi:</strong></td>
                                    <td id="programTotalLokasi">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Unit:</strong></td>
                                    <td id="programTotalUnit">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
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

    <!-- Insights -->
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header"><h3 class="card-title">Jumlah per Program</h3></div>
                <div class="card-body">
                    <canvas id="aggProgramChart" style="min-height:250px;height:250px"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-header"><h3 class="card-title">Jumlah per Provinsi</h3></div>
                <div class="card-body">
                    <canvas id="aggProvinsiChart" style="min-height:250px;height:250px"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header"><h3 class="card-title">Jumlah per Satuan</h3></div>
                <div class="card-body">
                    <canvas id="aggSatuanChart" style="min-height:250px;height:250px"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-outline card-info">
                <div class="card-header"><h3 class="card-title">Top 10 Kabupaten by Kuantitas</h3></div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <thead><tr><th>Kabupaten</th><th class="text-right">Jumlah</th><th class="text-right">Lokasi</th></tr></thead>
                        <tbody id="topKabupatenBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<style type="text/css">
    @media print {
        .no-print {
            display: none !important;
        }
        .col-md-3.order-4.text-right{
            display: none !important;
        }
    }
</style>
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

        // Moved initMap to global scope
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: -2.5489, lng: 118.0149 },
                zoom: 5,
            });
            // Defer the rest of the setup until the document is ready
            $(document).ready(function() {
                updateDashboard();
            });
        }

        function clearMarkers() {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        }

        function updateMap(filters) {
            $.ajax({
                url: '{{ route("komodel.map_markers") }}',
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

        function updateKomponenTable(filters) {
            // Use the existing dashboard data endpoint to get komponen data
            $.ajax({
                url: '/api/dashboard-data',
                data: filters,
                success: function(data) {
                    const tableBody = $('#komponenTableBody');
                    tableBody.empty();

                    if (data.dashboard_data && data.dashboard_data.length > 0) {
                        // Group data by komponen for display
                        const groupedData = data.dashboard_data.reduce((acc, item) => {
                            const key = item.komponen_id;
                            if (!acc[key]) {
                                acc[key] = {
                                    komponen_id: item.komponen_id,
                                    komponen_tipe: item.komponen_tipe,
                                    nama_program: item.nama_program,
                                    status_program: item.status_program,
                                    tahun_program: item.tahun_program,
                                    total_unit: item.total_unit,
                                    satuan_unit: item.satuan_unit,
                                    locations: []
                                };
                            }
                            if (item.provinsi) {
                                acc[key].locations.push({
                                    provinsi: item.provinsi,
                                    kabupaten: item.kabupaten,
                                    kecamatan: item.kecamatan,
                                    desa: item.desa
                                });
                            }
                            return acc;
                        }, {});

                        Object.values(groupedData).forEach(item => {
                            const locationText = item.locations.length > 0
                                ? `${item.locations.length} lokasi`
                                : 'Tidak ada lokasi';

                            const statusBadge = item.status_program === 'Active'
                                ? '<span class="badge badge-success">Aktif</span>'
                                : '<span class="badge badge-warning">Tidak Aktif</span>';

                            const row = `
                                <tr>
                                    <td>${item.nama_program || '-'}</td>
                                    <td>${item.komponen_tipe || '-'}</td>
                                    <td>${(item.total_unit || 0).toLocaleString('id-ID')}</td>
                                    <td>${item.satuan_unit || '-'}</td>
                                    <td>${item.tahun_program || '-'}</td>
                                    <td>${statusBadge}</td>
                                    <td>${locationText}</td>
                                </tr>
                            `;
                            tableBody.append(row);
                        });
                    } else {
                        tableBody.html('<tr><td colspan="7" class="text-center">Tidak ada data yang sesuai dengan filter</td></tr>');
                    }
                },
                error: function() {
                    $('#komponenTableBody').html('<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>');
                }
            });
        }

        function updateProgramDetails(filters) {
            const programId = filters.program_id;
            if (!programId) {
                $('#programDetailsSection').hide();
                return;
            }

            // Get program details
            $.ajax({
                url: '/api/dashboard-data',
                data: filters,
                success: function(data) {
                    if (data.dashboard_data && data.dashboard_data.length > 0) {
                        const firstItem = data.dashboard_data[0];
                        $('#selectedProgramName').text(firstItem.nama_program || '-');
                        $('#programName').text(firstItem.nama_program || '-');
                        $('#programStatus').text(firstItem.status_program || '-');
                        $('#programStartYear').text(firstItem.tahun_program || '-');

                        // Calculate program statistics
                        const groupedData = data.dashboard_data.reduce((acc, item) => {
                            const key = item.komponen_id;
                            if (!acc[key]) {
                                acc[key] = {
                                    komponen_id: item.komponen_id,
                                    total_unit: item.total_unit || 0,
                                    locations: []
                                };
                            }
                            if (item.provinsi) {
                                acc[key].locations.push(item);
                            }
                            return acc;
                        }, {});

                        const totalKomponen = Object.keys(groupedData).length;
                        const totalLokasi = data.dashboard_data.length;
                        const totalUnit = Object.values(groupedData).reduce((sum, item) => sum + item.total_unit, 0);

                        $('#programTotalKomponen').text(totalKomponen);
                        $('#programTotalLokasi').text(totalLokasi);
                        $('#programTotalUnit').text(totalUnit.toLocaleString('id-ID'));

                        $('#programDetailsSection').show();
                    } else {
                        $('#programDetailsSection').hide();
                    }
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
                model_id: $('#modelFilter').val(),
                tahun: $('#tahunFilter').val()
            };

            updateMap(filters);
            updateKomponenTable(filters);
            updateProgramDetails(filters);
            updateSummaryData(filters);
        }

        $(document).ready(function() {
            $('.select2').select2();

            // Event Listeners
            $('#programFilter, #modelFilter, #tahunFilter').on('change', function() {
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
        });
    </script>
@endpush
