@extends('layouts.app')

@section('subtitle', 'Dashboard')
@section('content_header_title', 'Dashboard')
@section('sub_breadcumb', '')

@section('content_body')
    <!-- Statistik Section -->
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
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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

    <!-- Filter Section -->
    <div class="row mb-3 ">
        <div class="col-md-4">
            <label for="programFilter">Program:</label>
            <select id="programFilter" class="form-control">
                <option value="">Semua Program</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->kode ?? '' }} - {{ $program->nama ?? 'Tanpa Nama' }}
                    </option>
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
                {{-- IMPORTANT: Add data-lat and data-lng to provinsi options for map centering --}}
                @foreach ($provinsis as $provinsi)
                    <option value="{{ $provinsi->id }}" data-lat="{{ $provinsi->latitude }}"
                        data-lng="{{ $provinsi->longitude }}">
                        {{ $provinsi->nama ?? 'Tanpa Nama' }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- End Filter Section -->

    <!-- Map Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
                <div class="card-header border-0 ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Peta Data
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Maps Section -->


    <!-- Chart Section -->
    <div class="row" id="dashboardCharts">
        <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="card card card-success">
                <div class="card-header">
                    <h3 class="card-title">Bar Chart</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
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
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <div class="card-body">
                    <canvas id="pieChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- End Chart Section -->

    <!-- Table Data Desa & Pie Chart Section Based on Selected Provinsi-->
    <div class="row" id="tableDesaPenerimaManfaat">
        <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Table Data Desa Penerima Manfaat</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableDesa" class="table responsive-table table-bordered datatable-target_progress"
                        width="100%">
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Chart Kabupaten Penerima Manfaat</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pieChartCanvas"
                        style="min-height: 250px; height: 250px; max-height: 400px; max-width: 100%"></canvas>
                </div>
            </div>
        </div>

    </div>

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

        /* CSS for custom bubble markers */
        .map-bubble-marker {
            /* Background color for province markers if they were bubbles */
            /* This style will only apply to dusun markers now */
            color: white;
            font-family: Arial, sans-serif;
            font-weight: bold;
            padding: 5px 8px;
            border-radius: 15px;
            /* Pill shape */
            white-space: nowrap;
            text-align: center;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transform: translate(-50%, -100%);
            /* Adjust to center marker at its bottom-middle point */
            position: relative;
            cursor: pointer;
            min-width: 60px;
            /* Minimum width for small numbers */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .map-bubble-marker.dusun {
            background-color: rgba(234, 67, 53, 0.9);
            /* Google Red for dusun bubbles */
            font-size: 10px;
            padding: 4px 7px;
            border-radius: 12px;
            min-width: 50px;
        }

        /* Optional: Add a pointer/triangle at the bottom of the bubble */
        .map-bubble-marker::after {
            content: '';
            position: absolute;
            bottom: -8px;
            /* Adjust to position below the bubble */
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            /* Default color, but only dusun will have it now */
            border-top: 8px solid rgba(234, 67, 53, 0.9);
        }

        .map-bubble-marker.dusun::after {
            border-top: 8px solid rgba(234, 67, 53, 0.9);
            /* Same color as dusun bubble */
        }
    </style>
@endpush

@push('js')
    @section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Toastr', true)
{{-- <script src="/vendor/chart.js/Chart.min.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"
    integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<!-- prettier-ignore -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
({key: "{{ $googleMapsApiKey }}", v: "weekly"});</script>

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
                $('#totalKeluarga').text('0');
                console.error('Error fetching data');
            }
        });
    }
    // Chart Scripts
    $(document).ready(function() {
        //make the input into select2 
        $('#programFilter, #tahunFilter, #provinsiFilter').select2();
        let barChart, pieChart;

        function loadChartData() {
            const filters = {
                provinsi_id: $('#provinsiFilter').val(),
                program_id: $('#programFilter').val(),
                tahun: $('#tahunFilter').val()
            };

            fetch('/dashboard/data/get-desa-chart-data?' + new URLSearchParams(filters))
                .then(res => res.json())
                .then(data => {
                    const provinsiLabels = data.map(item => item.provinsi);
                    const desaCounts = data.map(item => item.total_desa);

                    // Destroy existing charts if they exist
                    if (barChart) barChart.destroy();
                    if (pieChart) pieChart.destroy();

                    // Create Bar Chart
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

                    // Create Pie Chart
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
                })
                .catch(error => console.error('Error fetching chart data:', error));
        }
        // Load initial data
        loadDashboardData();
        loadChartData();


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
        $('#programFilter, #provinsiFilter, #tahunFilter').change(function() {
            loadDashboardData();
            loadChartData();
        });
        // });

        // // Jalankan initMap saat dokumen siap
        // $(document).ready(function() {
        // Pastikan Google Maps API script sudah dimuat SEBELUM initMap dipanggil
        // Initialize Map
        if (typeof google === 'object' && typeof google.maps === 'object') {
            initMap().then(() => {
                console.log("Peta berhasil diinisialisasi.");
            }).catch(e => {
                console.error("Inisialisasi peta gagal:", e);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal memuat peta',
                    text: 'Terjadi masalah saat memuat peta. Pastikan koneksi internet Anda stabil dan API Key Google Maps valid.',
                    timer: 5000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    position: 'top-end',
                });
            });
        } else {
            console.error("Google Maps API script belum termuat.");
            Swal.fire({
                icon: 'error',
                title: 'Gagal memuat script peta',
                text: 'Periksa koneksi internet Anda dan konfigurasi API Google Maps.',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
            });
        }
        // Event listeners for filters
        $('#provinsiFilter').on('change', function() {
            const selectedProvinsiId = $(this).val();
            if (selectedProvinsiId && selectedProvinsiId !== "") {
                loadMapMarkers(selectedProvinsiId);
            } else {
                loadMapMarkers();
            }
        });

        $('#programFilter, #provinsiFilter, #tahunFilter').on('change', function() {
            loadDashboardData();
            loadChartData();
            reloadTableIfValid();

            const selectedProvinsiId = $('#provinsiFilter').val();
            if (selectedProvinsiId && selectedProvinsiId !== "") {
                const selectedOption = $('#provinsiFilter option:selected');
                const lat = parseFloat(selectedOption.data('lat'));
                const lng = parseFloat(selectedOption.data('lng'));

                if (!isNaN(lat) && !isNaN(lng)) {
                    map.setCenter({
                        lat: lat,
                        lng: lng
                    });
                    // Set zoom to DETAIL_ZOOM_THRESHOLD when a province is selected to immediately show clusters
                    map.setZoom(DETAIL_ZOOM_THRESHOLD);
                }
            } else {
                map.setCenter({
                    lat: centerLat,
                    lng: centerLng
                });
                map.setZoom(initialZoom);
            }
            loadMapMarkers();
        });

        // Initialize DataTable
        const provinsiId = $('#provinsiFilter').val();

        if (provinsiId) {
            url_ajax = `/dashboard/data/get-data-desa/${provinsiId}`;
        } else {
            url_ajax = `/dashboard/data/get-data-desa`;
        }

        let table = $('#tableDesa').DataTable({
            processing: true,
            serverSide: false, // Set to true if you use server-side processing, otherwise false
            paging: true,
            pageLength: 25,
            searching: true,
            ordering: true,
            responsive: true,
            order: [
                [1, 'asc']
            ],
            lengthMenu: [10, 25, 50, 100],
            ajax: {
                url: "{{ route('dashboard.provinsi.data.desa') }}", // Correct route for table data
                data: function(d) {
                    d.program_id = $('#programFilter').val(); // Use programFilter
                    d.tahun = $('#tahunFilter').val(); // Use tahunFilter
                    d.provinsi_id = $('#provinsiFilter').val(); // Pass provinsi_id for table filter
                },
                dataSrc: function(json) {
                    // Ensure data is always an array, even if empty
                    pieChartKabupatenPenerimaManfaat(json.data || []);
                    return json.data || [];
                }
            },
            columns: [{
                    data: 'nama_dusun',
                    title: '{{ __('cruds.dusun.form.nama') }}'
                },
                {
                    data: 'desa',
                    title: '{{ __('cruds.dusun.form.des') }}'
                },
                {
                    data: 'kecamatan',
                    title: '{{ __('cruds.dusun.form.kec') }}'
                },
                {
                    data: 'kabupaten',
                    title: '{{ __('cruds.dusun.form.kab') }}'
                },
                {
                    data: 'provinsi',
                    title: '{{ __('cruds.dusun.form.prov') }}'
                },
                {
                    data: 'total_penerima',
                    title: '{{ __('Total') }}'
                }
            ]
        });

        function reloadTableIfValid() {
            const program = $('#programFilter').val();
            const tahun = $('#tahunFilter').val();
            const provinsi = $('#provinsiFilter').val();

            if (program || tahun || provinsi) {
                table.ajax.reload(null, false);
            } else {
                table.clear().draw();
                pieChartKabupatenPenerimaManfaat([]);
            }
        }

        // pie chart kabupaten
        function pieChartKabupatenPenerimaManfaat(data) {
            const kabupatenTotals = {};

            data.forEach(row => {
                const kabupaten = row.kabupaten || 'Lainnya';
                if (!kabupatenTotals[kabupaten]) {
                    kabupatenTotals[kabupaten] = 0;
                }
                kabupatenTotals[kabupaten] += row.total_penerima;
            });

            const labels = Object.keys(kabupatenTotals);
            const values = Object.values(kabupatenTotals);

            const total = values.reduce((a, b) => a + b, 0);
            const colors = [
                '#666', '#673ab7', '#ff9800', '#4caf50', '#00bcd4',
                '#9c27b0', '#ff1744', '#ffee00', '#ffb300', '#ff5722'
            ];

            const percentages = values.map(v => ((v / total) * 100).toFixed(1) + '%');

            const chartData = {
                labels: labels.map((l, i) => `${l} (${percentages[i]})`),
                datasets: [{
                    data: values,
                    backgroundColor: colors.slice(0, values.length),
                }]
            };

            if (window.kabupatenPieChart instanceof Chart) { // Changed global variable name to avoid conflict
                window.kabupatenPieChart.destroy();
            }

            const ctx = document.getElementById('pieChartCanvas').getContext('2d');
            window.kabupatenPieChart = new Chart(ctx, { // Changed global variable name
                type: 'pie',
                data: chartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label;
                                }
                            }
                        }
                    }
                }
            });
        }
        reloadTableIfValid();

    });
    // 
    // 
    // 
    // 
    let map;
    let markers = [];
    let infoWindow;
    let AdvancedMarkerElement;
    // let markerClusterer;

    // Global variables for map instances and markers
    // let leafletMapInstance = null;
    let googleMapInstance = null;
    let googleMapMarkers = [];
    // let leafletMarkerLayerGroup = null; // Use LayerGroup for Leaflet markers

    // Indonesia Center Coordinates
    const centerLat = -2.711614;
    const centerLng = 121.631757;
    const initialZoom = 5;

    const PROVINCE_ZOOM_THRESHOLD = 8; // Zoom level at which to show province markers
    const DETAIL_ZOOM_THRESHOLD = 8; // Zoom level at which to show detailed (dusun/specific) markers

    // --- STYLE DEFINITION ---
    const mapStyles = [{
            featureType: "poi",
            elementType: "all",
            stylers: [{
                visibility: "off"
            }]
        },
        {
            featureType: "transit",
            elementType: "all",
            stylers: [{
                visibility: "off"
            }]
        },
        {
            featureType: "road",
            elementType: "all",
            stylers: [{
                visibility: "off"
            }]
        },
        {
            featureType: "water",
            elementType: "geometry",
            stylers: [{
                color: "#cccccc"
            }]
        },
        {
            featureType: "landscape",
            elementType: "geometry",
            stylers: [{
                color: "#e5e5e5"
            }]
        },
        {
            featureType: "administrative.country",
            elementType: "geometry.stroke",
            stylers: [{
                color: "#ffffff"
            }, {
                weight: 0.5
            }]
        },
        {
            featureType: "administrative",
            elementType: "geometry.stroke",
            stylers: [{
                visibility: "on"
            }]
        },
        {
            featureType: "administrative.country",
            elementType: "geometry.stroke",
            stylers: [{
                visibility: "on"
            }, {
                color: "#f0f0f0"
            }, {
                weight: 0.6
            }]
        },
        {
            featureType: "administrative.country",
            elementType: "labels.text.fill",
            stylers: [{
                color: "#aaaaaa"
            }]
        },
        {
            featureType: "administrative",
            elementType: "labels.text.fill",
            stylers: [{
                color: "#c5c5c5"
            }, {
                visibility: "off"
            }]
        },
        {
            featureType: "all",
            elementType: "labels.icon",
            stylers: [{
                visibility: "off"
            }]
        },
        {
            featureType: "all",
            elementType: "labels.text.stroke",
            stylers: [{
                visibility: "off"
            }]
        }
    ];
    // --- END STYLE DEFINITION ---

    async function initMap() {
        const {
            Map
        } = await google.maps.importLibrary("maps");
        ({
            AdvancedMarkerElement
        } = await google.maps.importLibrary("marker"));

        map = new Map(document.getElementById("map"), {
            center: {
                lat: centerLat,
                lng: centerLng
            },
            zoom: initialZoom,
            mapId: "7e7fb1bfd929ec61", // Make sure this is set and styles are configured in Cloud Console
            // styles: mapStyles, // REMOVE THIS LINE IF USING mapId
        });

        infoWindow = new google.maps.InfoWindow();

        map.addListener('zoom_changed', () => {
            console.log('Zoom changed to:', map.getZoom());
            loadMapMarkers(); // Re-load markers based on new zoom level
        });

        if (typeof AdvancedMarkerElement === 'undefined') {
            console.error("Failed to import AdvancedMarkerElement from Google Maps Library.");
            Swal.fire({
                icon: 'error',
                title: 'Gagal memuat komponen peta',
                text: 'Terjadi masalah saat memuat peta. Silakan refresh halaman.',
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
            });
            return;
        }

        // Initialize MarkerClusterer
        markerClusterer = new markerClusterer.MarkerClusterer({
            map: map,
            markers: []
        });

        // Load initial markers when map is ready
        loadMapMarkers();
    }

    function clearMarkers() {
        // If using clustering, clear the clusterer and then the markers array
        if (markerClusterer) {
            markerClusterer.clearMarkers();
        }
        // Clear individual markers if they were added without clusterer (e.g., province markers)
        for (let i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
        markers = [];
    }

    async function loadMapMarkers() {
        const programId = $('#programFilter').val();
        const tahun = $('#tahunFilter').val();
        const provinsiId = $('#provinsiFilter').val();
        const currentZoom = map.getZoom();

        let apiUrl;
        let params = new URLSearchParams();

        if (programId) params.append('program_id', programId);
        if (tahun) params.append('tahun', tahun);

        // --- Updated Logic for API Call and Marker Type ---
        let isClusteredDesaView = false; // Flag for new clustered desa view

        if (provinsiId && provinsiId !== "") {
            // If a specific province is selected, load the new combined desa data for clustering
            apiUrl = `{{ route('dashboard.api.combined_desa_map_data', ['provinsi_id' => ':provinsi_id']) }}`
                .replace(':provinsi_id', provinsiId);
            isClusteredDesaView = true;
        } else {
            // If no province is selected, show all province-level markers (red pins)
            apiUrl = `{{ route('dashboard.api.markers') }}`; // This is HomeController::getFilteredProvinsi
            isClusteredDesaView = false;
        }

        const queryString = params.toString();
        if (queryString) {
            apiUrl += `?${queryString}`;
        }

        console.log("Fetching map data from:", apiUrl);

        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();

            clearMarkers(); // Clear all previous markers/clusters

            if (!data || data.length === 0) {
                console.log("No map data received for current filters/zoom.");
                return;
            }

            // Temporary array to hold markers before adding to clusterer
            const newMarkers = [];

            data.forEach(item => {
                const lat = parseFloat(item.latitude); // Ensure it's latitude
                const lng = parseFloat(item.longitude); // Ensure it's longitude

                if (isNaN(lat) || isNaN(lng)) {
                    console.warn(`Koordinat tidak valid untuk ${item.desa_name || item.nama || 'item'}:`,
                        item);
                    return;
                }

                let marker;
                let infoContent;

                if (isClusteredDesaView) {
                    // Create AdvancedMarkerElement for clustered Desa bubbles
                    const markerContent = createDesaBubbleMarkerContent(
                        item); // New function for desa bubbles
                    marker = new AdvancedMarkerElement({
                        position: {
                            lat,
                            lng
                        },
                        map: map, // Assign map initially, clusterer will manage it
                        title: `Desa: ${item.desa_name || 'N/A'}`, // Tooltip for clusterer
                        content: markerContent,
                    });
                    infoContent = generateDesaInfoContent(item); // New function for desa info window
                    newMarkers.push(marker); // Add to newMarkers array for clustering
                } else {
                    // Create standard google.maps.Marker for Province data (red pin)
                    marker = new google.maps.Marker({
                        position: {
                            lat,
                            lng
                        },
                        map: map,
                        title: item.nama || 'Provinsi',
                        // No 'icon' property means default red pin
                    });
                    infoContent = generateProvinceInfoContent(item);
                    markers.push(marker); // Add directly to map (no clustering for provinces)
                }

                // Attach info window listener to the created marker
                marker.addListener('click', () => {
                    infoWindow.close();
                    infoWindow.setContent(infoContent);
                    infoWindow.open(map, marker);
                });
            });

            // Add all newMarkers to the clusterer if in clustered view
            if (isClusteredDesaView && markerClusterer) {
                markerClusterer.addMarkers(newMarkers);
            }

            console.log(`Added ${newMarkers.length || markers.length} markers/clusters.`);

        } catch (error) {
            console.error('Error fetching map markers:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal memuat data lokasi peta',
                text: 'Terjadi kesalahan saat mengambil data peta. Silakan coba lagi.',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
            });
        }
    }

    /**
     * Creates the custom HTML element for the AdvancedMarkerElement (the "bubble") for Desa.
     * @param {Object} item The data object for the desa marker (from getCombinedDesaMapData).
     * @returns {HTMLElement} The HTML element to be used as the marker content.
     */
    function createDesaBubbleMarkerContent(item) {
        const element = document.createElement('div');
        element.className = 'map-bubble-marker dusun'; // Keep 'dusun' class for styling or rename to 'desa-bubble'
        element.innerHTML = `<span class="marker-value">${item.total_beneficiaries_in_desa || 0}</span>`;
        return element;
    }

    // Info Window content for province-level markers
    function generateProvinceInfoContent(item) {
        return `
                <div style="font-family: sans-serif; font-size: 14px;">
                    <h4 style="margin: 0 0 5px 0;">${item.nama || 'Provinsi'}</h4>
                    <p style="margin: 0;">🧩Desa Penerima Manfaat: <strong>${item.total_desa || 0}</strong> Desa </p>
                    <p style="margin: 0;">👥Total Penerima: <strong>${item.total_penerima || 0}</strong> Orang</p>
                </div>`;
    }

    // Info Window content for detailed desa markers (from getCombinedDesaMapData)
    function generateDesaInfoContent(item) {
        let sourceText = '';
        switch (item.coordinate_source) {
            case 'exact':
                sourceText = 'Koordinat lokasi kegiatan';
                break;
            case 'averaged':
                sourceText = 'Rata-rata koordinat kegiatan';
                break;
            case 'dusun':
                sourceText = 'Koordinat Dusun';
                break;
            case 'kabupaten':
                sourceText = 'Koordinat Kabupaten';
                break;
            default:
                sourceText = 'Sumber koordinat tidak diketahui';
        }
        return `
        <div style="font-family: sans-serif; font-size: 14px;">
            <h4 style="margin: 0 0 5px 0;">Desa: ${item.desa_name || 'N/A'}</h4>
            <p style="margin: 0;">Kecamatan: ${item.kecamatan_name || 'N/A'}</p>
            <p style="margin: 0;">Kabupaten: ${item.kabupaten_name || 'N/A'}</p>
            <p style="margin: 0;">Jumlah Penerima: <strong>${item.total_beneficiaries_in_desa || 0}</strong> Orang</p>
            <p style="margin: 0; font-style: italic;">${sourceText}</p>
        </div>`;
    }

    function createBubbleMarkerContent(item) {
        const element = document.createElement('div');
        element.className = 'map-bubble-marker dusun'; // Always 'dusun' class for bubbles

        // Content inside the bubble is the total beneficiaries for dusun
        element.innerHTML = `<span class="marker-value">${item.total_beneficiaries_in_dusun || 0}</span>`;

        return element;
    }

    // Info Window content for detailed dusun markers (from DashboardProvinsiController::getKegiatanMarkers)
    function generateDetailedInfoContent(item) {
        return `
                <div style="font-family: sans-serif; font-size: 14px;">
                    <h4 style="margin: 0 0 5px 0;">Dusun: ${item.nama || 'N/A'}</h4>
                    <p style="margin: 0;">Desa: ${item.desa_name || 'N/A'}</p>
                    <p style="margin: 0;">Kecamatan: ${item.kecamatan_name || 'N/A'}</p>
                    <p style="margin: 0;">👥Jumlah Penerima: <strong>${item.total_beneficiaries_in_dusun || 0}</strong> Orang</p>
                </div>`;
    }

    //
    // reusable function
    function generateInfoContent(prov) {
        return `
                <div style="font-family: sans-serif; font-size: 14px;">
                    <h4 style="margin: 0 0 5px 0;">${prov.nama}</h4>
                    <p style="margin: 0;">🧩Desa Penerima Manfaat: ${prov.total_desa} Desa </p>
                    <p style="margin: 0;">👥Total Penerima: ${prov.total_penerima} Orang</p>
                </div>`;
    }
</script>
@endpush
