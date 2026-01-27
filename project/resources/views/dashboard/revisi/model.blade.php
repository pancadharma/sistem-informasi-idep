@extends('layouts.app')

@section('subtitle', 'Model Dashboard')
@section('content_header_title', 'Model Dashboard')

@section('content_body')
<!-- Filter Section -->
<div class="row mb-3">
    <div class="col-md-2">
        <label for="programFilter">{{ __('cruds.program.title') }}:</label>
        <select id="programFilter" class="form-control select2">
            <option value="">{{ __('cruds.program.all') }}</option>
            @foreach($programs as $p)
                <option value="{{ $p->id }}">{{ $p->kode }} - {{ $p->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label for="tahunFilter">{{ __('cruds.program.periode') }}:</label>
        <select id="tahunFilter" class="form-control select2">
            <option value="">{{ __('cruds.program.all_years') }}</option>
            @foreach($years as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label for="provinsiFilter">{{ __('cruds.program.lokasi.pro') }}:</label>
        <select id="provinsiFilter" class="form-control select2">
            <option value="">{{ __('cruds.program.lokasi.all_provinsi') }}</option>
            @foreach($provinsis as $pr)
                <option value="{{ $pr->id }}">{{ $pr->nama }}</option>
             @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="komponenModelFilter">Jenis Model:</label>
        <select id="komponenModelFilter" class="form-control select2">
            <option value="">Semua Jenis Model</option>
            @foreach($komponenModels as $km)
                <option value="{{ $km->id }}">{{ $km->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="sektorFilter">Sektor:</label>
        <select id="sektorFilter" class="form-control select2">
            <option value="">Semua Sektor</option>
            @foreach($sektors as $s)
                <option value="{{ $s->id }}">{{ $s->nama }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Statistics Grid (Small Boxes) -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="totalModels">0</h3>
                <p>Total Komponen Model</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="totalTypes">0</h3>
                <p>Jenis Model</p>
            </div>
            <div class="icon">
                <i class="fas fa-shapes"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="totalLocations">0</h3>
                <p>Lokasi Sebaran</p>
            </div>
            <div class="icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="totalQuantity">0</h3>
                <p>Total Unit</p>
            </div>
            <div class="icon">
                <i class="fas fa-cubes"></i>
            </div>
        </div>
    </div>
</div>

<!-- Info Callout -->
<div class="callout callout-info">
    <h5><i class="fas fa-info"></i> Informasi:</h5>
    Dashboard ini menampilkan sebaran komponen model yang telah diimplementasikan dalam program-program IDEP.
</div>

<div class="row">
    <!-- Map Section -->
    <div class="col-lg-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marked-alt mr-1"></i>
                    Sebaran Lokasi Model per Provinsi
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
             <div class="card-footer">
                 <span class="badge badge-info" id="markerCount">0 Lokasi</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Trend Chart -->
    <div class="col-lg-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Trend Jenis Model per Tahun</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 350px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sektor Contribution -->
    <div class="col-lg-6">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-layer-group mr-1"></i> Kontribusi Sektor terhadap Komponen</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 350px;">
                    <canvas id="sektorChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Distribution Chart -->
    <div class="col-lg-6">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Distribusi Jenis Model</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 350px;">
                     <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="col-lg-6">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Legend Jenis Model</h3>
            </div>
             <div class="card-body">
                <div id="modelLegend" class="d-flex flex-wrap">
                    <!-- Legend items will be inserted here -->
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Data Table Section --}}
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table mr-1"></i> Rincian Komponen Program</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap" id="komponenTable">
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

{{-- Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="modal-title-heading" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="modal-title-heading">Detail Komponen</h5>
                    <p id="modal-subtitle" class="text-muted mb-0"></p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="callout callout-info">
                    <p id="modal-key-info" class="mb-0"></p>
                </div>
                <div class="mb-3">
                    <h5 class="mb-2">Target Reinstra yang Didukung</h5>
                    <ul id="modal-targets" class="list-unstyled"></ul>
                </div>
                <div>
                    <h5>Rincian Lokasi Implementasi</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Provinsi</th>
                                    <th>Kabupaten</th>
                                    <th>Kecamatan</th>
                                    <th>Desa</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="modal-locations-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
    /* Consistent Map Marker & Legend Styles */
    .legend-item { display: inline-flex; align-items: center; margin: 5px 10px; font-size: 0.9rem; }
    .legend-color { width: 15px; height: 15px; border-radius: 3px; margin-right: 8px; }
    .badge-model { padding: 5px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; color: #fff; }
    
    .small-box .icon > i { opacity: 0.4; }
</style>
@endpush

@push('js')
@section('plugins.Select2', true)
@section('plugins.DatatablesNew', true)

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Google Maps - loaded without callback, will init manually -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}" async defer></script>

<script>
    // Global variables
    let map;
    let markers = [];
    let trendChart, sektorChart, distributionChart;
    let colorMap = {}; // Name -> Color
    let infoWindow;
    let komponenDT = null; // DataTable instance
    let allTableData = []; // Store table data globally
    
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
        
        initCharts(); // Init empty charts
        
        // Wait for Google Maps to load, then initialize
        waitForGoogleMaps();
    });
    
    function waitForGoogleMaps() {
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
            initMap();
        } else {
            setTimeout(waitForGoogleMaps, 100);
        }
    }
    
    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -2.5489, lng: 118.0149 },
            zoom: 5,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        infoWindow = new google.maps.InfoWindow();
        
        // Load data after map is ready
        loadDashboardData();
        
        // Filter Changes - include komponenModelFilter
        $('#programFilter, #tahunFilter, #provinsiFilter, #komponenModelFilter, #sektorFilter').change(function() {
            loadDashboardData();
        });
    }
    

    
    function loadDashboardData() {
        const filters = {
            program_id: $('#programFilter').val(),
            provinsi_id: $('#provinsiFilter').val(),
            tahun: $('#tahunFilter').val(),
            komponenmodel_id: $('#komponenModelFilter').val(),
            sektor_id: $('#sektorFilter').val()
        };
        
        $.ajax({
            url: "{{ route('dashboard.model.data') }}",
            type: "GET",
            data: filters,
            success: function(response) {
                // Build Color Map from response
                buildColorMap(response.jenisModel);
                renderModelLegend(response.jenisModel);
                
                updateStatistics(response.stats);
                updateMapMarkers(response.locations);
                updateChartsData(response);
                
                // Store and render table data
                allTableData = response.tableData || [];
                renderTable(allTableData);
            },
            error: function(err) {
                console.error("Error loading data", err);
            }
        });
    }
    
     function buildColorMap(jenisModels) {
        colorMap = {};
        jenisModels.forEach(m => {
            colorMap[m.nama] = m.color;
        });
        // Default fallback
        colorMap['Unknown'] = '#ccc';
        colorMap['Lainnya'] = '#6c757d';
    }
    
    function updateStatistics(stats) {
        $('#totalModels').text(stats.totalModels);
        $('#totalTypes').text(stats.totalTypes);
        $('#totalLocations').text(stats.totalLocations);
        $('#totalQuantity').text(Number(stats.totalQuantity).toLocaleString('id-ID'));
    }
    
    function updateMapMarkers(locations) {
        // Clear existing markers
        markers.forEach(marker => marker.setMap(null));
        markers = [];
        
        const bounds = new google.maps.LatLngBounds();

        locations.forEach(location => {
            if (location.lat && location.long) {
                const modelColor = colorMap[location.jenis_model] || '#667eea';
                
                // Create SVG Circle Icon
                const markerIcon = {
                    path: google.maps.SymbolPath.CIRCLE,
                    fillColor: modelColor,
                    fillOpacity: 1,
                    strokeColor: '#FFFFFF',
                    strokeWeight: 2,
                    scale: 7 // Size roughly matches the 14px (radius 7)
                };

                const position = { lat: location.lat, lng: location.long };
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: markerIcon,
                    title: location.program_nama
                });

                const contentString = `
                    <div style="font-family: inherit; width: 200px;">
                         <h6 style="color: #007bff; font-weight: bold; margin-bottom: 5px;">${location.program_nama}</h6>
                        <hr style="margin: 5px 0;">
                        <div style="margin-bottom: 5px;">
                            <span style="background-color: ${modelColor}; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px; font-weight: 600;">
                                ${location.jenis_model}
                            </span>
                        </div>
                        <div style="font-size: 13px;"><strong>Jumlah:</strong> ${location.jumlah} ${location.satuan}</div>
                        <div style="font-size: 12px; color: #6c757d; margin-top: 5px;">
                            ${location.dusun}, ${location.desa}, ${location.kecamatan}, <br>${location.kabupaten}, ${location.provinsi}
                        </div>
                    </div>
                `;

                marker.addListener("click", () => {
                    infoWindow.setContent(contentString);
                    infoWindow.open(map, marker);
                });
                
                markers.push(marker);
                bounds.extend(position);
            }
        });
        
        $('#markerCount').text(`${markers.length} Lokasi`);
        
        if (markers.length > 0) {
            // Check if Province Filter is active
            const provinceId = $('#provinsiFilter').val();
            
            if (provinceId && provinceId !== "") {
                // If filtering by province, zoom to fit markers
                map.fitBounds(bounds);
            } else {
                // If "All Provinces" (or initial load), default to Indonesia view
                map.setCenter({ lat: -2.5489, lng: 118.0149 });
                map.setZoom(5); 
            }
        } else {
             map.setCenter({ lat: -2.5489, lng: 118.0149 });
             map.setZoom(5);
        }
    }
    
    function initCharts() {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
             plugins: { legend: { position: 'bottom', labels: { usePointStyle: true } } }
        };

        // Trend
        const ctxTrend = document.getElementById('trendChart').getContext('2d');
        trendChart = new Chart(ctxTrend, {
            type: 'line',
            data: { labels: [], datasets: [] },
            options: { ...commonOptions, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
        
        // Sektor
        const ctxSektor = document.getElementById('sektorChart').getContext('2d');
        sektorChart = new Chart(ctxSektor, {
            type: 'bar',
            data: { labels: [], datasets: [] },
            options: { ...commonOptions, scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } } }
        });
        
         // Distribution
        const ctxDist = document.getElementById('distributionChart').getContext('2d');
        distributionChart = new Chart(ctxDist, {
            type: 'bar', // Horizontal bar is type 'bar' with indexAxis 'y' in Chart.js 3+
            data: { labels: [], datasets: [] },
            options: { 
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }, // Hide legend for single dataset
                scales: { x: { beginAtZero: true } }
            }
        });
    }
    
    function updateChartsData(response) {
        // 1. Trend Chart
        const years = Object.keys(response.trendData);
        let allTypes = new Set();
        years.forEach(y => {
            response.trendData[y].forEach(item => allTypes.add(item.jenis_model));
        });
        allTypes = Array.from(allTypes);
        
        const trendDatasets = allTypes.map(type => {
            const data = years.map(year => {
                const found = response.trendData[year].find(d => d.jenis_model === type);
                return found ? found.total : 0;
            });
            const c = colorMap[type] || '#ccc';
            return {
                label: type,
                data: data,
                borderColor: c,
                backgroundColor: c,
                tension: 0.3,
                borderWidth: 2,
                pointRadius: 3
            };
        });
        
        trendChart.data.labels = years;
        trendChart.data.datasets = trendDatasets;
        trendChart.update();
        
        // 2. Sektor Chart
        if (response.sektorKontribusi.length > 0) {
            const sektorLabels = response.sektorKontribusi.map(item => item.sektor);
            let modelKeys = new Set();
            response.sektorKontribusi.forEach(item => {
                Object.keys(item).forEach(k => {
                    if (k !== 'sektor') modelKeys.add(k);
                });
            });
            modelKeys = Array.from(modelKeys);
            
            const sektorDatasets = modelKeys.map(key => {
                const data = response.sektorKontribusi.map(item => item[key] || 0);
                const c = colorMap[key] || '#ccc';
                return {
                    label: key,
                    data: data,
                    backgroundColor: c,
                    borderColor: c,
                    borderWidth: 1
                };
            });
            
            sektorChart.data.labels = sektorLabels;
            sektorChart.data.datasets = sektorDatasets;
            sektorChart.update();
        } else {
             sektorChart.data.labels = [];
             sektorChart.data.datasets = [];
             sektorChart.update();
        }

        // 3. Distribution Chart
        let distMap = {};
        response.locations.forEach(loc => {
            if (!distMap[loc.jenis_model]) distMap[loc.jenis_model] = 0;
            distMap[loc.jenis_model] += loc.jumlah;
        });
        
        // Sort by value desc
        const sortedEntries = Object.entries(distMap).sort((a,b) => b[1] - a[1]);
        const distLabels = sortedEntries.map(e => e[0]);
        const distValues = sortedEntries.map(e => e[1]);
        const distColors = distLabels.map(l => colorMap[l] || '#ccc');
        
        distributionChart.data.labels = distLabels;
        distributionChart.data.datasets = [{
            label: 'Total Unit',
            data: distValues,
            backgroundColor: distColors,
            borderRadius: 4
        }];
        distributionChart.update();
    }
    
    function renderModelLegend(jenisModels) {
        const container = $('#modelLegend');
        container.empty();
        jenisModels.forEach(m => {
            const html = `
                <div class="legend-item">
                    <span class="legend-color" style="background-color: ${m.color};"></span>
                    <span>${m.nama}</span>
                </div>
            `;
            container.append(html);
        });
    }
    
    function renderTable(tableData) {
        const tableBody = $('#komponen-table-body');
        
        // Destroy existing DataTable instance completely
        if (komponenDT) {
            komponenDT.destroy();
            komponenDT = null;
        }
        
        // Clear table body completely
        tableBody.empty();
        
        if (!tableData || tableData.length === 0) {
            tableBody.html('<tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data yang cocok dengan filter.</td></tr>');
            return;
        }
        
        tableData.forEach(item => {
            const statusClass = item.status_program === 'Active' ? 'badge-success' : 'badge-warning';
            const total = (item.total_unit || 0).toLocaleString('id-ID');
            const rawTotal = Number(item.total_unit) || 0;
            const tahun = item.tahun_program || '-';
            
            const row = $(`<tr style="cursor: pointer;" data-komponen-id="${item.komponen_id}"></tr>`).html(
                `<td>${item.nama_program || '-'}</td>` +
                `<td><strong>${item.komponen_tipe || '-'}</strong></td>` +
                `<td class="text-right" data-order="${rawTotal}">${total}</td>` +
                `<td>${item.satuan_unit || ''}</td>` +
                `<td data-order="${tahun}">${tahun}</td>` +
                `<td class="text-right" data-order="${item.location_count}">${item.location_count}</td>` +
                `<td><span class="badge ${statusClass}">${item.status_program || 'Unknown'}</span></td>` +
                `<td class="text-center" title="Lihat Detail"><i class="fas fa-eye text-primary"></i></td>`
            );
            
            row.on('click', () => showDetailModal(item.komponen_id));
            tableBody.append(row);
        });
        
        // Initialize DataTable fresh
        komponenDT = $('#komponenTable').DataTable({
            order: [[4, 'desc'], [2, 'desc']],
            lengthChange: true,
            autoWidth: false,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [7] }
            ],
            destroy: true  // Allow re-initialization
        });
    }
    
    function showDetailModal(komponenId) {
        const data = allTableData.find(item => item.komponen_id == komponenId);
        if (!data) return;
        
        $('#modal-title-heading').text(data.komponen_tipe);
        $('#modal-subtitle').text(`Bagian dari ${data.nama_program} (Tahun ${data.tahun_program})`);
        
        const locs = data.locations || [];
        const total = Number(data.total_unit) || 0;
        const satuan = data.satuan_unit || '';
        
        let keyInfo = satuan
            ? `Total ${total.toLocaleString('id-ID')} ${satuan} diimplementasikan di ${locs.length} lokasi.`
            : `Diimplementasikan di ${locs.length} lokasi.`;
        
        $('#modal-key-info').html(keyInfo);
        $('#modal-targets').html(
            data.targets && data.targets.length > 0
                ? data.targets.map(t => `<li><i class="fas fa-check-circle text-success mr-2"></i>${t}</li>`).join('')
                : '<li>Tidak ada target yang terhubung.</li>'
        );
        
        $('#modal-locations-body').html(locs.map(loc => `
            <tr>
                <td>${loc.provinsi || '-'}</td>
                <td>${loc.kabupaten || '-'}</td>
                <td>${loc.kecamatan || '-'}</td>
                <td>${loc.desa || '-'}</td>
                <td>${(loc.jumlah_per_lokasi || 0).toLocaleString('id-ID')} ${loc.satuan_per_lokasi || ''}</td>
            </tr>
        `).join(''));
        
        $('#detailModal').modal('show');
    }
</script>
@endpush