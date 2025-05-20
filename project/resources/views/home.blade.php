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
                    <option value="{{ $program->id }}">{{ $program->kode ?? '' }} - {{ $program->nama ?? 'Tanpa Nama' }}</option>
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

    <!-- Map Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-primary card-outline">
                <div class="card-header border-0 ui-sortable-handle" style="cursor: move;">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Peta Data
                    </h3>
                    <!-- card tools -->
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <div class="card-body">
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>
                <!-- /.card-body-->
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
                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- End Chart Section -->

    <div class="row" id="tableDesaPenerimaManfaat">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    {{-- <h3 class="card-title">Data Penerima Manfaat</h3> --}}
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>

                <div class="card-body">
                    <table id="tableDesa" class="table responsive-table table-bordered datatable-target_progress" width="100%">
                    </table>
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
    </style>
@endpush

@push('js')
@section('plugins.Select2', true)
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Toastr', true)
    {{-- <script src="/vendor/chart.js/Chart.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
        $(document).ready(function () {
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
                                    legend: { position: 'bottom', display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: (ctx) => `${ctx.raw} Desa`
                                        }
                                    }
                                },
                                scales: {
                                    y: { beginAtZero: true }
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
                                    legend: { position: 'right' },
                                    tooltip: {
                                        callbacks: {
                                            label: function(ctx) {
                                                const value = ctx.raw;
                                                const total = ctx.chart._metasets[ctx.datasetIndex].total;
                                                const percentage = ((value / total) * 100).toFixed(1);
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
            $('#programFilter, #provinsiFilter, #tahunFilter').change(function () {
                loadDashboardData();
                loadChartData();
            });
        });

    </script>
    <!-- prettier-ignore -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ", v: "weekly"});</script>

    <script>
        let map;
        let markers = [];
        let infoWindow;
        let AdvancedMarkerElement;

        // Global variables for map instances and markers
        // let leafletMapInstance = null;
        let googleMapInstance = null;
        let googleMapMarkers = [];
        // let leafletMarkerLayerGroup = null; // Use LayerGroup for Leaflet markers

        // Indonesia Center Coordinates
        const centerLat = -2.711614;
        const centerLng = 121.631757;
        const initialZoom = 5;



        // --- STYLE DEFINITION ---
        const mapStyles = [
            { // Hide Points of Interest (businesses, landmarks, etc.)
                featureType: "poi",
                elementType: "all",
                stylers: [{ visibility: "off" }],
            },
            { // Hide transit lines and icons
                featureType: "transit",
                elementType: "all",
                stylers: [{ visibility: "off" }],
            },
            { // Hide all road types
                featureType: "road",
                elementType: "all",
                stylers: [{ visibility: "off" }],
            },
            { // Style water bodies (e.g., oceans, lakes)
                featureType: "water",
                elementType: "geometry",
                stylers: [
                    { color: "#cccccc" } // Medium gray color for water
                    ],
            },
            { // Style landmasses
                featureType: "landscape",
                elementType: "geometry",
                stylers: [
                    { color: "#e5e5e5" } // Lighter gray for land
                ],
            },
            { // Style administrative boundaries (like country borders)
                featureType: "administrative.country",
                elementType: "geometry.stroke",
                stylers: [
                    { color: "#ffffff" }, // White or very light border
                    { weight: 0.5 }      // Make it thin
                ],
            },
            { // Hide boundaries other than countries (provinces, etc.)
                featureType: "administrative",
                elementType: "geometry.stroke",
                stylers: [
                    // Apply a general rule first to potentially hide others
                    { visibility: "on" }
                ],
            },
                { // Re-apply country border specifically after the general rule
                featureType: "administrative.country",
                elementType: "geometry.stroke",
                stylers: [
                    { visibility: "on" }, // Ensure it's visible
                    { color: "#f0f0f0" }, // Light gray border
                    { weight: 0.6 }
                ],
            },
            { // Style country labels
                featureType: "administrative.country",
                elementType: "labels.text.fill",
                stylers: [
                        { color: "#aaaaaa" } // Subtle gray text color
                ]
            },
            {
                // Style other administrative labels (provinces, cities) - make them subtle or hide
                featureType: "administrative",
                elementType: "labels.text.fill",
                stylers: [{
                    color: "#c5c5c5"
                    }, // Even more subtle gray, or use visibility: "off" to hide
                    { visibility: "off" } // Uncomment to hide province/city labels completely
                ]
            },
            {
                // Hide icons associated with labels (like city dots)
                featureType: "all",
                elementType: "labels.icon",
                stylers: [ { visibility: "off" } ]
            },
            {
                // General text styling (make strokes invisible for cleaner look)
                featureType: "all",
                elementType: "labels.text.stroke",
                stylers: [ { visibility: "off" } ]
            }

            // Add more rules as needed to fine-tune
        ];
        // --- END STYLE DEFINITION ---

        async function initMap() {
            const { Map } = await google.maps.importLibrary("maps");
            ({ AdvancedMarkerElement } = await google.maps.importLibrary("marker"));
            // Inisialisasi Peta
            map = new Map(document.getElementById("map"), {
                center: { lat: centerLat, lng: centerLng },
                zoom: initialZoom,
                // mapId: "YOUR_MAP_ID" // GANTI DENGAN MAP ID
                styles: mapStyles,
                // mapTypeId: 'roadmap'

            });

            // Inisialisasi InfoWindow
            infoWindow = new google.maps.InfoWindow();

            // Pastikan AdvancedMarkerElement sudah terdefinisi sebelum load marker
            if (typeof AdvancedMarkerElement === 'undefined') {
                 console.error("Gagal mengimpor AdvancedMarkerElement dari Google Maps Library.");
                 alert("Terjadi masalah saat memuat komponen peta. Silakan refresh halaman.");
                 return;
            }
            // Load marker saat peta pertama kali dimuat
            loadMapMarkers();
        }

        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }

        function loadMapMarkers() {
            const provinsiId = $('#provinsiFilter').val();
            const programId = $('#programFilter').val();
            const tahun = $('#tahunFilter').val();

            // Construct URL with route parameter
            let url = "{{ route('dashboard.api.markers', ['id' => ':id']) }}".replace(':id', provinsiId || '');

            // Add query parameters for program and year
            const params = new URLSearchParams();
            if (programId) params.append('program_id', programId);
            if (tahun) params.append('tahun', tahun);
            const queryString = params.toString();
            if (queryString) {
                url += `?${queryString}`;
            }

            fetch(url)
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    clearMarkers();
                    data.forEach(prov => {
                        const lat = parseFloat(prov.latitude);
                        const lng = parseFloat(prov.longitude);
                        if (isNaN(lat) || isNaN(lng)) {
                            console.warn(`Koordinat tidak valid untuk ${prov.nama}`);
                            return;
                        }

                        const marker = new google.maps.Marker({
                            position: { lat, lng },
                            map: map,
                            title: prov.nama
                        });

                        const infowindow = new google.maps.InfoWindow({
                            content: generateInfoContent(prov)
                        });

                        marker.addListener('click', () => {
                            infowindow.open(map, marker);
                        });

                        markers.push(marker);
                    });
                })
                .catch(error => {
                    console.error('Error fetching markers:', error);
                });
        }

        // reusable function
        function generateInfoContent(prov) {
            return `
                <div style="font-family: sans-serif; font-size: 14px;">
                    <h4 style="margin: 0 0 5px 0;">${prov.nama}</h4>
                    <p style="margin: 0;">🧩Desa Penerima Manfaat: ${prov.total_desa} Desa </p>
                    <p style="margin: 0;">👥Total Penerima: ${prov.total_penerima} Orang</p>
                </div>`
            ;
        }

        // Jalankan initMap saat dokumen siap
        $(document).ready(function () {
            // Pastikan Google Maps API script sudah dimuat SEBELUM initMap dipanggil
            if (typeof google === 'object' && typeof google.maps === 'object') {
                initMap().then(() => {
                    console.log("Peta berhasil diinisialisasi.");
                }).catch(e => {
                    console.error("Inisialisasi peta gagal:", e);
                    alert("Gagal memuat peta. Pastikan koneksi internet Anda stabil dan API Key/Map ID Google Maps valid.");
                });
            } else {
                console.error("Google Maps API script belum termuat.");
                alert("Gagal memuat script Google Maps. Periksa koneksi internet dan konfigurasi API Anda.");
            }


            $('#provinsiFilter').on('change', function () {
                const selectedProvinsiId = $(this).val();
                if (selectedProvinsiId && selectedProvinsiId !== "") {
                     loadMapMarkers(selectedProvinsiId);
                } else {
                     loadMapMarkers();
                }
            });
            $('#programFilter, #provinsiFilter, #tahunFilter').on('change', function () {
                loadDashboardData();
                loadMapMarkers();
                reloadTableIfValid();
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
                serverSide: false,
                paging: true,
                pageLength: 25,
                searching: true,
                ordering: true,
                responsive: true,

                order: [[1, 'asc']],
                lengthMenu: [10, 25, 50, 100],
                ajax: {
                    url: url_ajax,
                    data: function (d) {
                        d.program_id = $('#filterProgram').val();
                        d.tahun = $('#filterTahun').val();
                    },
                    dataSrc: function (json) {
                        return json.data || [];
                    }
                },
                columns: [
                    { data: 'nama_dusun', title: 'Nama Dusun' },
                    { data: 'desa', title: 'Desa' },
                    { data: 'kecamatan', title: 'Kecamatan' },
                    { data: 'kabupaten', title: 'Kabupaten' },
                    { data: 'provinsi', title: 'Provinsi' },
                    // { data: 'total_dusun', title: 'Total Dusun' },
                    { data: 'total_penerima', title: 'Total Penerima' }
                ]
            });

            function reloadTableIfValid() {
                const program = $('#programFilter').val();
                const tahun = $('#tahunFilter').val();
                const provinsi = $('#provinsiFilter').val();

                if (program && tahun && provinsi) {
                    table.ajax.url(`/dashboard/data/get-data-desa/${provinsi}`).load();
                }
                else if (program && tahun) {
                    table.ajax.url(`/dashboard/data/get-data-desa`).load();
                } else if (program && provinsi) {
                    table.ajax.url(`/dashboard/data/get-data-desa/${provinsi}`).load();
                } else if (tahun && provinsi) {
                    table.ajax.url(`/dashboard/data/get-data-desa/${provinsi}`).load();
                }else if (program) {
                    table.ajax.url(`/dashboard/data/get-data-desa`).load();
                } else if (tahun) {
                    table.ajax.url(`/dashboard/data/get-data-desa`).load();
                } else if (provinsi) {
                    table.ajax.url(`/dashboard/data/get-data-desa/${provinsi}`).load();
                }
                else {
                    table.ajax.url(url_ajax).load();
                }
            }

            reloadTableIfValid();
        });
    </script>


@endpush
