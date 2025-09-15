@extends('layouts.app')

@section('subtitle', __('cruds.komponenmodel.dashboard'))
@section('content_header_title', __('cruds.komponenmodel.dashboard'))

@section('content_body')
    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-md-2">
            <label for="program_id">Program:</label>
            <select id="program_id" class="form-control select2">
                <option value="all">Semua Program</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="komponenmodel_id">Tipe Komponen:</label>
            <select id="komponenmodel_id" class="form-control select2">
                <option value="all">Semua Tipe</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="provinsi_id">Provinsi:</label>
            <select id="provinsi_id" class="form-control select2">
                <option value="all">Semua Provinsi</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="tahun">Tahun:</label>
            <select id="tahun" class="form-control select2">
                <option value="all">Semua Tahun</option>
            </select>
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <button id="applyFilters" class="btn btn-primary btn-block">
                <i class="fas fa-check mr-1"></i> Terapkan Filter
            </button>
        </div>
        <div class="col-md-2">
            <label>&nbsp;</label>
            <div class="btn-group no-print">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-export"></i> Export
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="#" id="exportPrint">
                        <i class="fas fa-print mr-2"></i> Print Page
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div id="loading-overlay" class="position-fixed w-100 h-100" style="background-color: rgba(0,0,0,0.5); z-index: 1055; top: 0; left: 0; display: flex; justify-content: center; align-items: center;">
        <div class="loader"></div>
    </div>
    {{-- <div id="stats-cards" class="row"></div> --}}
    <!-- Charts Row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Distribusi Tipe Komponen (Pie)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="komponenPieChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Distribusi Tipe Komponen (Bar)</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="komponenBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table Row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Rincian Komponen Program</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="komponenTableV3">
                                    <thead>
                                        <tr>
                                            <th>Program</th>
                                            <th>Tipe Komponen</th>
                                            <th>Total</th>
                                            <th>Satuan</th>
                                            <th>Tahun</th>
                                            <th>Jml. Lokasi</th>
                                            <th>Status</th>
                                            <th class="text-center" title="Lihat Detail">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="komponen-table-body"></tbody>
                                </table>
                            </div>
                </div>
            </div>
        </div>
        <!-- Map Row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Peta Komponen Model</h3>
                    </div>
                    <div class="card-body p-0">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Detail Modal -->
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
@endsection
@push('js')
<style type="text/css">
    @media print {
        .no-print {
            display: none !important;
        }
        .col-md-2:last-child {
            display: none !important;
        }
    }
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
    /* Map sizing and in-map filter control styles to mirror v2 look */
    #map {
        height: 500px;
        width: 100%;
        border-radius: 0.25rem;
    }
    .marker-filter-control {
        background: #fff;
        padding: 8px 10px;
        border-radius: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        color: #333;
        min-width: 200px;
    }
    .marker-filter-control .filter-header {
        font-weight: 600;
        margin-bottom: 6px;
        font-size: 0.9rem;
    }
    .marker-filter-control .checkbox {
        display: block;
        margin-bottom: 4px;
        font-size: 0.85rem;
    }
    .marker-filter-control .filter-actions {
        margin-top: 6px;
        display: flex;
        gap: 6px;
    }
</style>
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsApiKey }}&callback=initMap" defer></script>

<script>
    // --- GLOBAL VARIABLES ---
    let komponenPieChart, komponenBarChart, map;
    let allData = [];
    let markerGroups = {}; // tipe -> array of google.maps.Marker
    let allMarkers = [];
    let markerControlDiv = null; // custom control DOM element
    let komponenDT = null; // DataTable instance

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

    const renderBarChart = (data) => {
        const ctx = document.getElementById('komponenBarChart').getContext('2d');
        const distribution = groupDataByKomponen(data).reduce((acc, item) => {
            acc[item.komponen_tipe] = (acc[item.komponen_tipe] || 0) + 1;
            return acc;
        }, {});
        if (komponenBarChart) komponenBarChart.destroy();
        komponenBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(distribution),
                datasets: [{
                    label: 'Jumlah Komponen',
                    data: Object.values(distribution),
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d'],
                    borderColor: ['#0056b3', '#1e7e34', '#d39e00', '#bd2130', '#138496', '#545b62'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    };

    const clearAllMarkers = () => {
        allMarkers.forEach(m => m.setMap(null));
        allMarkers = [];
        markerGroups = {};
    };

    const renderMap = (data) => {
        if (!map) return; // Wait for Google Maps to initialize via callback

        clearAllMarkers();

        const validLocations = (data || []).filter(item => item.lat && item.long);
        const infoWindow = new google.maps.InfoWindow();
        validLocations.forEach(item => {
            const tipe = item.komponen_tipe || 'Lainnya';
            if (!markerGroups[tipe]) markerGroups[tipe] = [];
            const position = new google.maps.LatLng(parseFloat(item.lat), parseFloat(item.long));
            const marker = new google.maps.Marker({ position, map });
            const html = `<b>${item.komponen_tipe || '-'}</b><br>${item.provinsi || '-'}${item.desa ? ' - ' + item.desa : ''}`;
            marker.addListener('click', () => {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
            });
            markerGroups[tipe].push(marker);
            allMarkers.push(marker);
        });

        buildMarkerFilterControl();

        // Keep current center/zoom; do not auto-fit to markers
        if (allMarkers.length === 0) {
            map.setCenter({ lat: -2.5489, lng: 118.0149 });
            map.setZoom(5);
        }
    };

    const buildMarkerFilterControl = () => {
        const tipos = Object.keys(markerGroups);
        if (!markerControlDiv) {
            markerControlDiv = document.createElement('div');
            markerControlDiv.className = 'marker-filter-control';
            ['click','dblclick','contextmenu','wheel','mousedown','touchstart','pointerdown'].forEach(evt => {
                markerControlDiv.addEventListener(evt, e => e.stopPropagation());
            });
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(markerControlDiv);
        }
        markerControlDiv.innerHTML = `
            <div class="filter-header">Filter Marker</div>
            <div class="filter-body">
                ${tipos.map(t => `<label class=\"checkbox\"><input type=\"checkbox\" class=\"marker-type-toggle\" data-type=\"${t}\" checked> ${t}</label>`).join('')}
                <div class="filter-actions">
                    <button type="button" class="btn btn-xs btn-primary" id="showAllMarkers">Semua</button>
                    <button type="button" class="btn btn-xs btn-secondary" id="hideAllMarkers">Sembunyikan</button>
                    <button type="button" class="btn btn-xs btn-info" id="fitToMarkers">Zoom Data</button>
                    <button type="button" class="btn btn-xs btn-light" id="resetView">Reset Indo</button>
                </div>
            </div>`;

        $(markerControlDiv).find('.marker-type-toggle').off('change').on('change', function() {
            const tipe = this.dataset.type;
            const arr = markerGroups[tipe] || [];
            if (this.checked) {
                arr.forEach(m => m.setMap(map));
            } else {
                arr.forEach(m => m.setMap(null));
            }
        });
        $(markerControlDiv).find('#showAllMarkers').off('click').on('click', function() {
            $(markerControlDiv).find('.marker-type-toggle').prop('checked', true).trigger('change');
        });
        $(markerControlDiv).find('#hideAllMarkers').off('click').on('click', function() {
            $(markerControlDiv).find('.marker-type-toggle').prop('checked', false).trigger('change');
        });
        $(markerControlDiv).find('#fitToMarkers').off('click').on('click', function() {
            const visibleMarkers = [];
            Object.keys(markerGroups).forEach(t => {
                const checked = $(markerControlDiv).find(`.marker-type-toggle[data-type="${t}"]`).is(':checked');
                if (!checked) return;
                (markerGroups[t] || []).forEach(m => { if (m.getMap()) visibleMarkers.push(m); });
            });
            if (visibleMarkers.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                visibleMarkers.forEach(m => bounds.extend(m.getPosition()));
                map.fitBounds(bounds);
            }
        });
        $(markerControlDiv).find('#resetView').off('click').on('click', function() {
            map.setCenter({ lat: -2.5489, lng: 118.0149 });
            map.setZoom(5);
        });
    };

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: -2.5489, lng: 118.0149 },
            zoom: 5
        });
        if (allData && allData.length) {
            renderMap(allData);
        }
    }
    window.initMap = initMap;

    const renderTable = (data) => {
        const tableBody = $('#komponen-table-body');
        tableBody.empty();
        const groupedData = groupDataByKomponen(data);

        // Re-init DataTable cleanly
        if (komponenDT) {
            komponenDT.destroy();
            komponenDT = null;
        }

        if (groupedData.length === 0) {
            tableBody.html('<tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data yang cocok dengan filter.</td></tr>');
            return;
        }
        groupedData.forEach(item => {
            const statusClass = item.status_program === 'Active' ? 'badge-success' : 'badge-warning';
            const locationCount = item.locations ? item.locations.length : 0;
            const total = (item.total_unit || 0).toLocaleString('id-ID');
            const satuan = item.satuan_unit || '';
            const tahun = item.tahun_program || '';
            const rawTotal = Number(item.total_unit) || 0;
            const row = $(`<tr style="cursor: pointer;" data-komponen-id="${item.komponen_id}"></tr>`).html(
                `<td>${item.nama_program || '-'}</td>` +
                `<td><strong>${item.komponen_tipe || '-'}</strong></td>` +
                `<td class="text-right" data-order="${rawTotal}">${total}</td>` +
                `<td>${satuan}</td>` +
                `<td data-order="${tahun}">${tahun || '-'}</td>` +
                `<td class="text-right" data-order="${locationCount}">${locationCount}</td>` +
                `<td><span class="badge ${statusClass}">${item.status_program || 'Unknown'}</span></td>` +
                `<td class="text-center" title="Lihat Detail"><i class="fas fa-eye text-primary"></i></td>`
            );
            row.on('click', () => showDetailModal(item.komponen_id));
            tableBody.append(row);
        });

        // Initialize DataTable with sorting and search
        komponenDT = $('#komponenTableV3').DataTable({
            order: [[4, 'desc'], [2, 'desc']],
            pageLength: 10,
            lengthChange: true,
            autoWidth: false,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [7] },
            ],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ baris',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ entri',
                infoEmpty: 'Tidak ada data',
                zeroRecords: 'Tidak ditemukan data yang cocok',
                paginate: { first: 'Pertama', last: 'Terakhir', next: 'Berikutnya', previous: 'Sebelumnya' }
            }
        });
    };

    const renderDashboard = (data) => {
        renderStatsCards(data);
        renderPieChart(data);
        renderBarChart(data);
        renderMap(data);
        renderTable(data);
    };

    const showDetailModal = (komponenId) => {
        const data = groupDataByKomponen(allData).find(item => item.komponen_id == komponenId);
        if (!data) return;
        $('#modal-title-heading').text(data.komponen_tipe);
        $('#modal-subtitle').text(`Bagian dari ${data.nama_program} (Tahun ${data.tahun_program})`);
        const locs = data.locations || [];
        const unitAgg = {};
        locs.forEach(loc => {
            const u = (loc.satuan_per_lokasi || data.satuan_unit || '').trim();
            const val = parseFloat(loc.jumlah_per_lokasi) || 0;
            if (!unitAgg[u]) unitAgg[u] = { total: 0, count: 0 };
            unitAgg[u].total += val;
            unitAgg[u].count += 1;
        });
        const units = Object.keys(unitAgg).filter(u => u.length > 0);
        let keyInfo = '';
        if (units.length === 1) {
            const u = units[0];
            const sumLoc = unitAgg[u].total;
            const sum = sumLoc > 0 ? sumLoc : (Number(data.total_unit) || 0);
            keyInfo = `Total ${Number(sum).toLocaleString('id-ID')} ${u} diimplementasikan di ${locs.length} lokasi.`;
        } else if (units.length > 1) {
            const parts = units.map(u => `${Number(unitAgg[u].total).toLocaleString('id-ID')} ${u} (${unitAgg[u].count} lokasi)`);
            keyInfo = `Ringkasan kuantitas per satuan: ${parts.join(' • ')}`;
        } else {
            const sum = Number(data.total_unit) || 0;
            const u = data.satuan_unit || '';
            keyInfo = u
                ? `Total ${sum.toLocaleString('id-ID')} ${u} diimplementasikan di ${locs.length} lokasi.`
                : `Diimplementasikan di ${locs.length} lokasi.`;
        }
        $('#modal-key-info').html(keyInfo);
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
            provinsi_id: $('#provinsi_id').val(),
            tahun: $('#tahun').val(),
        });

        try {
            const response = await fetch(`{{ url('/api/dashboard-data') }}?${params.toString()}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            allData = data.dashboard_data || [];
            renderDashboard(allData);
        } catch (error) {
            console.error("Could not fetch dashboard data:", error);
            $('#komponen-table-body').html(`<tr><td colspan="5" class="text-center py-4 text-danger">Gagal memuat data: ${error.message}</td></tr>`);
        } finally {
            hideLoading();
        }
    };

    const initializeDashboard = async () => {
        showLoading();
        try {
            const response = await fetch(`{{ url('/api/dashboard-init') }}`);
            if (!response.ok) throw new Error('Failed to initialize from API');
            const initData = await response.json();

            initData.filters.programs.forEach(p => $('#program_id').append(`<option value="${p.id}">${p.nama}</option>`));
            initData.filters.komponen_models.forEach(k => $('#komponenmodel_id').append(`<option value="${k.id}">${k.nama}</option>`));
            initData.filters.provinces.forEach(p => $('#provinsi_id').append(`<option value="${p.id}">${p.nama}</option>`));
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

    const exportDashboard = () => {
        window.print();
    };

    // --- MAIN EXECUTION ---
    $(document).ready(function() {
        $('.select2').select2();
        initializeDashboard();
        $('#applyFilters').on('click', applyFiltersAndFetch);
        // Dynamic fetch on filter change
        $('#program_id, #komponenmodel_id, #provinsi_id, #tahun').on('change', applyFiltersAndFetch);
        $('#exportPrint').on('click', function(e) {
            e.preventDefault();
            exportDashboard();
        });
    });
</script>
@endpush
