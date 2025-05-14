@extends('layouts.app')

@section('subtitle', 'Dashboard Departemen Program')
@section('content_header_title', 'Dashboard Departemen Program')
@section('sub_breadcumb', 'Dashboard Departemen Program')

@section('content_body')

    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="programFilter">Program:</label>
            <select id="programFilter" class="form-control">
                <option value="">Semua Program</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->kode ?? '' }} - {{ $program->nama ?? '-' }}</option>
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
                    <option value="{{ $provinsi->id }}" {{ $selectedProvinsi->id == $provinsi->id ? 'selected' : '' }}>{{ $provinsi->nama ?? '-' }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <!-- End Filter Section -->

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
                {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
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



    <!-- Table Stats Section -->
    {{-- <table id="tableDesa" class="table table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Desa / Village</th>
            <th>Penerima Manfaat</th>
          </tr>
        </thead>
    </table> --}}

    <table id="tableDesa" class="table responsive-table table-bordered datatable-target_progress" width="100%">
    </table>
      
    <!-- End Maps Section -->



    <!-- Chart Section -->
    <div class="row" id="dashboardCharts">
        <div class="col-6">
            <div class="card card card-success">
                <div class="card-header">
                    <h3 class="card-title">Bar Chart</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Pie Chart</h3>
                    <div class="card-tools">
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- End Chart Section -->


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
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- 
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    <!-- jQuery-FusionCharts -->
    <script type="text/javascript" src="https://rawgit.com/fusioncharts/fusioncharts-jquery-plugin/develop/dist/fusioncharts.jqueryplugin.min.js"></script>
    <!-- Fusion Theme -->
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    
    --}}
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

        });

    </script>
    <!-- prettier-ignore -->
    <script async>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ", v: "weekly", libraries: "marker"});</script>

    <script>
        let map;
        let markers = [];
        let infoWindow;
        let AdvancedMarkerElement;
        
        let googleMapInstance = null;
        let googleMapMarkers = [];

        // Bali Center Coordinates
        const centerLat = -8.409518;
        const centerLng = 115.188919;
        const initialZoom = 9;

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
                styles: mapStyles,
                mapId: "DEMO_MAP_ID",
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
            if (provinsiId && provinsiId !== "") {
                $('#provinsiFilter').val();
            }else {
                const provinsiId = {{ $selectedProvinsi->id }};
                alert("Silakan pilih provinsi untuk menampilkan data");
                return;
            }

            const programId = $('#programFilter').val();
            const tahun = $('#tahunFilter').val();

            // Construct URL with route parameter
            let url = "{{ route('dashboard.api.markers', ['id' => ':id']) }}".replace(':id', provinsiId || '');
            let urlMarkers = "{{ route('dashboard.api.markers.provinsi', ['id' => ':id']) }}".replace(':id', provinsiId || '');

            const params = new URLSearchParams();

            if (programId) params.append('program_id', programId);
            if (tahun) params.append('tahun', tahun);
            const queryString = params.toString();
            if (queryString) {
                url += `?${queryString}`;
            }

            fetch(urlMarkers).then(response => response.json()).then(data => {
                clearMarkers();
                data.forEach(data => {
                    const lat = parseFloat(data.lat);
                    const lng = parseFloat(data.long);
                    if (isNaN(lat) || isNaN(lng)) {
                        console.warn(`Koordinat tidak valid untuk ${data.nama_kegiatan} di ${data.desa}`);
                        return;
                    }
                    const pinData = new google.maps.Marker({
                        position: { lat: data.lat, lng: data.long },
                        map: map,
                        title: data.nama_kegiatan
                    });
                    
                    // const pinData = new AdvancedMarkerElement({
                    //     position: { lat: data.lat, lng: data.long },
                    //     map: map,
                    //     title: data.nama_kegiatan,
                    //     content: pin.element,
                    //     gmpClickable: true,
                    // });


                    const infowindow = new google.maps.InfoWindow({
                            content: markerInfoContent(data)
                    });

                    pinData.addListener('click', () => {
                        infowindow.open(map, pinData);
                    });

                    markers.push(pinData);
                });
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
        function markerInfoContent(marker) {
            return `
                <div style="font-family: sans-serif; font-size: 14px;">
                    <h4 style="margin: 0 0 5px 0;">${marker.nama_kegiatan}</h4>
                    <p style="margin: 0;">#️⃣ Kode Kegiatan: ${marker.kode_kegiatan} </p>
                    <p style="margin: 0;">📃 Program: ${marker.program ?? 'Tidak ada'} </p>
                    <p style="margin: 0;">📌 Lokasi: ${marker.lokasi ?? 'Tidak ada'} </p>
                    <p style="margin: 0;">🗺️ Desa: ${marker.desa}</p>
                    <p style="margin: 0;">🗺️ Kecamatan: ${marker.kecamatan}</p>
                    <p style="margin: 0;">🗺️ Kecamatan: ${marker.kabupaten}</p>
                    <p style="margin: 0;">🗺️ Provinsi: ${marker.provinsi}</p>
                    <p style="margin: 0;">📌 Coordinate: ${marker.lat}, ${marker.long}</p>
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
            let debounceTimer;
            $('#programFilter, #provinsiFilter, #tahunFilter').on('change', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    loadDashboardData();
                    loadMapMarkers();
                }, 500);
            });
        });
    </script>
    

    {{-- dataables --}}
    <script>
        $(document).ready(function () {
            // $('#tableDesa').DataTable({
            //     serverSide: true,
            //     processing: true,
            //     ajax: {
            //         url: '{{ route("dashboard.data.desa") }}',
            //         data: () => ({
            //             provinsi_id: $('#provinsiFilter').val(),
            //             program_id:  $('#programFilter').val(),
            //             tahun:       $('#tahunFilter').val(),
            //         })
            //     },
            //     columns: [
            //         {data: 'DT_RowIndex',name: 'No.',className: "text-center align-middle",title: '{ __('No.') }}',orderable: true,searchable: false},
            //         { data: 'kelurahan', name: 'kelurahan', title: 'Desa/Kelurahan' },
            //         { data: 'penerima', name: 'penerima', title: '{{ __('cruds.beneficiary.title') }}' }
            //         // { data: 'desa', render:(_,__,row,meta)=> meta.row+1 },
            //         // { data: 'desa' },
            //         // { data: 'penerima' }
            //     ]
            // });

            $('#tableDesa').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ route('dashboard.data.desa') }}",
                    data: function (d) {
                        d.provinsi_id = $('#provinsiFilter').val();
                        d.program_id = $('#programFilter').val();
                        d.tahun = $('#tahunFilter').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex',name: 'No.',className: "text-center align-middle",title: '{ __('No.') }}',orderable: true,searchable: false},
                    { data: 'kelurahan', name: 'kelurahan' title: 'kelurahan'},
                    { data: 'kabupaten', name: 'kabupaten' title: 'kabupaten'},
                    { data: 'penerima', name: 'penerima' title: 'total penerima manfaat'},
                ],
                order: [[1, 'asc']],
            });
            // tableDesa.on('draw', function () {
            //     var info = tableDesa.page.info();
            //     tableDesa.column(0, { search: 'applied', order: 'applied', page: 'applied' }).nodes().each(function (cell, i) {
            //         cell.innerHTML = i + 1 + info.start;
            //     });
            // });

            // tableDesa.DataTable();
        });
    </script>
@endpush
