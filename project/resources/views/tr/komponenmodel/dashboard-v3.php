<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Komponen Model | AdminLTE 3</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- AdminLTE css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Leaflet.js for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body { margin: 0; }
        .content-wrapper { background-color: #f4f6f9; margin-left: 0 !important; }
        .leaflet-container { height: 400px; width: 100%; border-radius: 0.25rem; }
        #loading-overlay { display: none; }
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .small-box .icon { font-size: 70px; top: 10px; }
        .small-box h3 { font-size: 2.2rem; }
    </style>
</head>
<body class="hold-transition">
<div class="wrapper">
    <div class="content-wrapper">
        <div id="loading-overlay" class="position-fixed w-100 h-100" style="background-color: rgba(0,0,0,0.5); z-index: 1055; top: 0; left: 0; display: flex; justify-content: center; align-items: center;">
            <div class="loader"></div>
        </div>

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 pt-3">
                    <div class="col-sm-12">
                        <h1 class="m-0">Dashboard Komponen Model</h1>
                        <p class="text-muted">Analisis dan Monitoring Implementasi Program di Lapangan</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-filter mr-1"></i>Filter Data</h3></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"><div class="form-group"><label for="program_id">Program</label><select id="program_id" class="form-control"><option value="all">Semua Program</option></select></div></div>
                            <div class="col-md-3"><div class="form-group"><label for="komponenmodel_id">Tipe Komponen</label><select id="komponenmodel_id" class="form-control"><option value="all">Semua Tipe</option></select></div></div>
                            <div class="col-md-3"><div class="form-group"><label for="tahun">Tahun</label><select id="tahun" class="form-control"><option value="all">Semua Tahun</option></select></div></div>
                            <div class="col-md-3 align-self-end"><div class="form-group"><button id="applyFilters" class="btn btn-primary btn-block"><i class="fas fa-check mr-1"></i> Terapkan Filter</button></div></div>
                        </div>
                    </div>
                </div>

                <div id="stats-cards" class="row"></div>

                <div class="row">
                    <div class="col-lg-4 d-flex flex-column">
                        <div class="card flex-grow-1"><div class="card-header"><h3 class="card-title">Distribusi Tipe Komponen</h3></div><div class="card-body"><canvas id="komponenPieChart"></canvas></div></div>
                        <div class="card flex-grow-1"><div class="card-header"><h3 class="card-title">Peta Sebaran Lokasi</h3></div><div class="card-body p-0"><div id="map"></div></div></div>
                    </div>
                    <div class="col-lg-8 d-flex">
                        <div class="card flex-grow-1">
                            <div class="card-header"><h3 class="card-title">Rincian Komponen Program</h3></div>
                            <div class="card-body table-responsive p-0"><table class="table table-hover text-nowrap"><thead><tr><th>Program</th><th>Tipe Komponen</th><th>Total Unit</th><th>Jml. Lokasi</th><th>Status</th></tr></thead><tbody id="komponen-table-body"></tbody></table></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="modal-title-heading" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div><h5 class="modal-title" id="modal-title-heading">Detail Komponen</h5><p id="modal-subtitle" class="text-muted mb-0"></p></div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="callout callout-info"><p id="modal-key-info" class="mb-0"></p></div>
                    <div class="mb-3"><h5 class="mb-2">Target Reinstra yang Didukung</h5><ul id="modal-targets" class="list-unstyled"></ul></div>
                    <div>
                        <h5>Rincian Lokasi Implementasi</h5>
                        <div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Provinsi</th><th>Kabupaten</th><th>Kecamatan</th><th>Desa</th><th>Jumlah</th></tr></thead><tbody id="modal-locations-body"></tbody></table></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    // --- GLOBAL VARIABLES ---
    let komponenPieChart, map;
    let allData = [];
    // Use Laravel's URL helper to dynamically set the base API path
    const API_BASE_URL = '{{ url("/") }}';

    // --- UI HELPER FUNCTIONS ---
    const showLoading = () => $('#loading-overlay').css('display', 'flex');
    const hideLoading = () => $('#loading-overlay').css('display', 'none');

    // --- UTILITY FUNCTION ---
    const groupDataByKomponen = (data) => {
        const grouped = data.reduce((acc, current) => {
            const kompoId = current.komponen_id;
            if (!acc[kompoId]) {
                acc[kompoId] = { ...current, locations: [], targets: new Set() };
            }
            acc[kompoId].locations.push({
                provinsi: current.provinsi, kabupaten: current.kabupaten, kecamatan: current.kecamatan,
                desa: current.desa, jumlah_per_lokasi: current.jumlah_per_lokasi,
                satuan_per_lokasi: current.satuan_per_lokasi, lat: current.lat, long: current.long
            });
            if (current.target_reinstra) {
                current.target_reinstra.split(';').forEach(t => acc[kompoId].targets.add(t.trim()));
            }
            return acc;
        }, {});
        return Object.values(grouped).map(item => ({ ...item, targets: Array.from(item.targets) }));
    };

    // --- RENDERING FUNCTIONS ---
    const renderStatsCards = (data) => {
        const groupedData = groupDataByKomponen(data);
        const totalKomponen = groupedData.length;
        const totalLokasi = data.length;
        const totalUnit = groupedData.reduce((sum, item) => sum + (item.total_unit || 0), 0);
        const activePrograms = new Set(data.filter(d => d.status_program === 'Active').map(d => d.program_id)).size;
        $('#stats-cards').html(`
            <div class="col-lg-3 col-6"><div class="small-box bg-info"><div class="inner"><h3>${totalKomponen}</h3><p>Total Komponen</p></div><div class="icon"><i class="fas fa-cogs"></i></div></div></div>
            <div class="col-lg-3 col-6"><div class="small-box bg-success"><div class="inner"><h3>${totalLokasi}</h3><p>Total Lokasi Tersebar</p></div><div class="icon"><i class="fas fa-map-marker-alt"></i></div></div></div>
            <div class="col-lg-3 col-6"><div class="small-box bg-warning"><div class="inner"><h3>${totalUnit.toLocaleString('id-ID')}</h3><p>Total Unit Implementasi</p></div><div class="icon"><i class="fas fa-tools"></i></div></div></div>
            <div class="col-lg-3 col-6"><div class="small-box bg-danger"><div class="inner"><h3>${activePrograms}</h3><p>Program Aktif</p></div><div class="icon"><i class="fas fa-tasks"></i></div></div></div>
        `);
    };

    const renderPieChart = (data) => {
        const ctx = document.getElementById('komponenPieChart').getContext('2d');
        const distribution = groupDataByKomponen(data).reduce((acc, item) => {
            acc[item.komponen_tipe] = (acc[item.komponen_tipe] || 0) + 1;
            return acc;
        }, {});
        if (komponenPieChart) komponenPieChart.destroy();
        komponenPieChart = new Chart(ctx, {
            type: 'pie',
            data: { labels: Object.keys(distribution), datasets: [{ data: Object.values(distribution), backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d'] }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    };

    const renderMap = (data) => {
        if (!map) {
            map = L.map('map').setView([-8.409518, 115.188919], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
        }
        setTimeout(() => map.invalidateSize(), 200);
        map.eachLayer(layer => { if (layer instanceof L.Marker) map.removeLayer(layer); });
        const validLocations = data.filter(item => item.lat && item.long);
        if (validLocations.length > 0) {
            const markers = validLocations.map(item => L.marker([item.lat, item.long]).bindPopup(`<b>${item.desa}</b><br>${item.komponen_tipe}`));
            const group = new L.featureGroup(markers).addTo(map);
            map.fitBounds(group.getBounds().pad(0.5));
        }
    };

    const renderTable = (data) => {
        const tableBody = $('#komponen-table-body');
        tableBody.empty();
        const groupedData = groupDataByKomponen(data);
        if (groupedData.length === 0) {
            tableBody.html(`<tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data yang cocok dengan filter.</td></tr>`);
            return;
        }
        groupedData.forEach(item => {
            const statusClass = item.status_program === 'Active' ? 'badge-success' : 'badge-warning';
            const row = $(`<tr style="cursor: pointer;" data-komponen-id="${item.komponen_id}"></tr>`).html(`
                <td>${item.nama_program}</td><td><strong>${item.komponen_tipe}</strong></td>
                <td>${(item.total_unit || 0).toLocaleString('id-ID')} ${item.satuan_unit || ''}</td>
                <td>${item.locations.length}</td><td><span class="badge ${statusClass}">${item.status_program}</span></td>
            `);
            row.on('click', () => showDetailModal(item.komponen_id));
            tableBody.append(row);
        });
    };

    const renderDashboard = (data) => {
        renderStatsCards(data);
        renderPieChart(data);
        renderMap(data);
        renderTable(data);
    };

    const showDetailModal = (komponenId) => {
        const data = groupDataByKomponen(allData).find(item => item.komponen_id == komponenId);
        if (!data) return;
        $('#modal-title-heading').text(data.komponen_tipe);
        $('#modal-subtitle').text(`Bagian dari ${data.nama_program} (Tahun ${data.tahun_program})`);
        $('#modal-key-info').text(`Total ${(data.total_unit || 0).toLocaleString('id-ID')} ${data.satuan_unit || ''} diimplementasikan di ${data.locations.length} lokasi.`);
        $('#modal-targets').html(data.targets.map(t => `<li><i class="fas fa-check-circle text-success mr-2"></i>${t}</li>`).join('') || '<li>Tidak ada target yang terhubung.</li>');
        $('#modal-locations-body').html(data.locations.map(loc => `
            <tr><td>${loc.provinsi||'-'}</td><td>${loc.kabupaten||'-'}</td><td>${loc.kecamatan||'-'}</td><td>${loc.desa||'-'}</td><td>${(loc.jumlah_per_lokasi||0).toLocaleString('id-ID')} ${loc.satuan_per_lokasi||''}</td></tr>
        `).join(''));
        $('#detailModal').modal('show');
    };

    // --- DATA FETCHING ---
    const applyFiltersAndFetch = async () => {
        showLoading();
        const params = new URLSearchParams({
            program_id: $('#program_id').val(),
            komponenmodel_id: $('#komponenmodel_id').val(),
            tahun: $('#tahun').val(),
        });
        try {
            const response = await fetch(`/api/dashboard-data?${params.toString()}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            allData = data.dashboard_data;
            renderDashboard(allData);
        } catch (error) {
            console.error("Could not fetch dashboard data:", error);
            $('#komponen-table-body').html(`<tr><td colspan="5" class="text-center py-4 text-danger">Gagal memuat data.</td></tr>`);
        } finally {
            hideLoading();
        }
    };

    const initializeDashboard = async () => {
        showLoading();
        try {
            const response = await fetch(`/api/dashboard-init`);
            if (!response.ok) throw new Error('Failed to initialize from API');
            const initData = await response.json();

            initData.filters.programs.forEach(p => $('#program_id').append(`<option value="${p.id}">${p.nama}</option>`));
            initData.filters.komponen_models.forEach(k => $('#komponenmodel_id').append(`<option value="${k.id}">${k.nama}</option>`));
            initData.filters.years.forEach(y => $('#tahun').append(`<option value="${y}">${y}</option>`));

            allData = initData.dashboard_data;
            renderDashboard(allData);
        } catch(error) {
            console.error("Could not initialize dashboard:", error);
            $('body').html(`<div class="alert alert-danger m-3">Gagal menginisialisasi dashboard. Pastikan server backend berjalan dan API routes dapat diakses.</div>`);
        } finally {
            hideLoading();
        }
    };

    // --- MAIN EXECUTION ---
    $(document).ready(() => {
        initializeDashboard();
        $('#applyFilters').on('click', applyFiltersAndFetch);
    });
</script>
</body>
</html>
