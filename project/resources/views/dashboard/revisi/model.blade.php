@extends('layouts.app')

@section('subtitle', 'Model Dashboard')
@section('content_header_title', 'Model Dashboard')

@section('content_body')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Filter Section -->
<div class="row mb-3">
    <div class="col-md-3">
        <label for="programFilter">{{ __('cruds.program.title') }}:</label>
        <select id="programFilter" class="form-control select2">
            <option value="">{{ __('cruds.program.all') }}</option>
            @foreach($programs as $p)
                <option value="{{ $p->id }}">{{ $p->kode }} - {{ $p->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="tahunFilter">{{ __('cruds.program.periode') }}:</label>
        <select id="tahunFilter" class="form-control select2">
            <option value="">{{ __('cruds.program.all_years') }}</option>
            @foreach($years as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="provinsiFilter">{{ __('cruds.program.lokasi.pro') }}:</label>
        <select id="provinsiFilter" class="form-control select2">
            <option value="">{{ __('cruds.program.lokasi.all_provinsi') }}</option>
            @foreach($provinsis as $pr)
                <option value="{{ $pr->id }}">{{ $pr->nama }}</option>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Global variables
    let map;
    let markers = [];
    let trendChart, sektorChart, distributionChart;
    let colorMap = {}; // Name -> Color
    
    $(document).ready(function() {
        // Initialize Select2 with Bootstrap 4 theme if available, otherwise default
        $('.select2').select2({
            width: '100%'
        });
        
        initDashboard();
        
        // Filter Changes
        $('#programFilter, #tahunFilter, #provinsiFilter, #sektorFilter').change(function() {
            loadDashboardData();
        });
    });
    
    function initDashboard() {
        initMap();
        initCharts(); // Init empty charts
        loadDashboardData();
    }
    
    function initMap() {
        // Center on Indonesia
        map = L.map('map').setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 18
        }).addTo(map);
    }
    
    function loadDashboardData() {
        const filters = {
            program_id: $('#programFilter').val(),
            provinsi_id: $('#provinsiFilter').val(),
            tahun: $('#tahunFilter').val(),
            sektor_id: $('#sektorFilter').val()
        };
        
        $.ajax({
            url: "{{ route('revisi.dashboard.model.data') }}",
            type: "GET",
            data: filters,
            success: function(response) {
                // Build Color Map from response
                buildColorMap(response.jenisModel);
                renderModelLegend(response.jenisModel);
                
                updateStatistics(response.stats);
                updateMapMarkers(response.locations);
                updateChartsData(response);
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
        markers.forEach(marker => map.removeLayer(marker));
        markers = [];
        
        const bounds = new L.LatLngBounds();

        locations.forEach(location => {
            if (location.lat && location.long) {
                const modelColor = colorMap[location.jenis_model] || '#667eea';
                
                 const customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `<div style="background-color: ${modelColor}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 3px rgba(0,0,0,0.3);"></div>`,
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });
                
                const marker = L.marker([location.lat, location.long], { icon: customIcon }).addTo(map);
                
                const popupContent = `
                    <div class='p-2'>
                        <h6 class="text-primary font-weight-bold mb-1">${location.program_nama}</h6>
                        <hr class="my-1">
                        <div class="mb-1">
                            <span class="badge" style="background-color: ${modelColor}; color: white;">
                                ${location.jenis_model}
                            </span>
                        </div>
                        <div class="small"><strong>Jumlah:</strong> ${location.jumlah} ${location.satuan}</div>
                        <div class="small text-muted mt-1">
                            ${location.desa}, ${location.kecamatan}, <br>${location.kabupaten}, ${location.provinsi}
                        </div>
                    </div>
                `;
                
                marker.bindPopup(popupContent);
                markers.push(marker);
                bounds.extend([location.lat, location.long]);
            }
        });
        
        $('#markerCount').text(`${markers.length} Lokasi`);
        
        if (markers.length > 0) {
            map.fitBounds(bounds.pad(0.1));
        } else {
             map.setView([-2.5489, 118.0149], 5);
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
            type: 'bar',
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
        const distMap = {};
        response.locations.forEach(loc => {
            if (!distMap[loc.jenis_model]) distMap[loc.jenis_model] = 0;
            distMap[loc.jenis_model] += loc.jumlah;
        });
        
        const distLabels = Object.keys(distMap);
        const distValues = Object.values(distMap);
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
</script>
@endpush