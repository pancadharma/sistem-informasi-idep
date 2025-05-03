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

    <!-- Filter Section -->
    <div class="row mb-3">
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


    <!-- Chart Section -->
    <div class="row" id="dashboardCharts">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="barChart" height="200" style="display: block;" width="765"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" height="200" style="display: block;" width="765"
                        class="chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- End Chart Section -->

    <!-- Map Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0 ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    Peta Data
                </h3>
                <!-- card tools -->
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
                </div>
                <div class="card-body" style="display: block;">
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>
                <!-- /.card-body-->
            </div>
        </div>
    </div>
    <!-- End Maps Section -->
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

        $(document).ready(function () {
            $('#programFilter, #provinsiFilter, #tahunFilter').change(loadDashboardData);
            loadDashboardData(); // initial load

            fetch('/dashboard/data/get-desa-chart-data')
                .then(res => res.json())
                .then(data => {
                    const provinsiLabels = data.map(item => item.provinsi);
                    const desaCounts = data.map(item => item.total_desa);

                    const barChart = new Chart(document.getElementById('barChart'), {
                        type: 'bar',
                        data: {
                            labels: provinsiLabels,
                            datasets: [{
                                label: 'Jumlah Desa Penerima Manfaat',
                                data: desaCounts,
                                backgroundColor: generateColors(desaCounts.length),
                                borderWidth: 2,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'x',
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (ctx) => `${ctx.raw} desa`
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        color: '#495057',
                                        font: {
                                            weight: 'bold'
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#495057',
                                        font: {
                                            weight: 'bold'
                                        },
                                        callback: function (value) {
                                            return value >= 1000 ? value / 1000 : value;
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, .2)',
                                        zeroLineColor: '#000' // Not used in v4 but retained for reference
                                    }
                                }
                            }
                        }
                    });

                    const pieChart = new Chart(document.getElementById('pieChart'), {
                        type: 'pie',
                        data: {
                            labels: provinsiLabels,
                            datasets: [{
                                data: desaCounts,
                                backgroundColor: generateColors(desaCounts.length),
                            }]
                        },
                        options: {
                            responsive: false,
                            plugins: {
                                legend: {
                                    position: 'right'
                                },
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
                            },
                        }
                    });
                });

            function generateColors(count) {
                const baseColors = ['#4caf50', '#03a9f4', '#00bcd4', '#e91e63', '#ff9800', '#ff5722', '#9c27b0'];
                const generatedColors = new Set(baseColors); // Use a Set to ensure uniqueness

                while (generatedColors.size < count) {
                    // Generate a random color
                    const randomColor = generateReadableColor();
                    generatedColors.add(randomColor); // Add to the Set (duplicates are automatically ignored)
                }

                return Array.from(generatedColors).slice(0, count); // Convert Set to Array and return the required count
            }

            // Helper function to generate readable random colors
            function generateReadableColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }

                // Ensure the color is not too light or too dark
                if (isReadableColor(color)) {
                    return color;
                } else {
                    return generateReadableColor(); // Retry if the color is not readable
                }
            }

            // Helper function to check if a color is readable
            function isReadableColor(color) {
                // Convert hex color to RGB
                const r = parseInt(color.slice(1, 3), 16);
                const g = parseInt(color.slice(3, 5), 16);
                const b = parseInt(color.slice(5, 7), 16);

                // Calculate brightness (0 = dark, 255 = bright)
                const brightness = (r * 299 + g * 587 + b * 114) / 1000;

                // Return true if brightness is within a readable range
                return brightness > 50 && brightness < 200;
            }
        });

    </script>

    <!-- prettier-ignore -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "AIzaSyCqxb0Be7JWTChc3E_A8rTlSmiVDLPUSfQ", v: "weekly"});</script>



    {{-- <script>
        //GROK
        let map;
        let markers = [];

        async function initMap() {
            // Request needed libraries
            const { Map } = await google.maps.importLibrary("maps");

            // Inisialisasi map ke variabel global
            map = new Map(document.getElementById("map"), {
                center: { lat: -8.1961057, lng: 115.3231341 }, // Pusat Indonesia
                zoom: 5,
                mapId: "4504f8b37365c3d0",
            });
            loadMapMarkers();
        }

        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }

        function loadMapMarkers() {
            const provinsiId = $('#provinsiFilter').val(); // Ambil nilai dari select filter
            let url = "{{ route('dashboard.api.markers') }}";

            // Tambahkan parameter provinsi_id jika ada
            if (provinsiId) {
                url += `?provinsi_id=${provinsiId}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    clearMarkers();

                    data.forEach(prov => {
                        // Pastikan latitude dan longitude adalah angka yang valid
                        const lat = parseFloat(prov.latitude);
                        const lng = parseFloat(prov.longitude);
                        if (isNaN(lat) || isNaN(lng)) {
                            console.warn(`Koordinat tidak valid untuk ${prov.nama}`);
                            return;
                        }

                        const marker = new google.maps.Marker({
                            position: { lat: lat, lng: lng },
                            map: map,
                            title: prov.nama // Nama provinsi muncul saat hover
                        });

                        // Tambahkan info window untuk klik (opsional)
                        const infowindow = new google.maps.InfoWindow({
                            content: `<strong>${prov.nama}</strong>`
                        });

                        marker.addListener('click', () => {
                            infowindow.open(map, marker);
                        });

                        markers.push(marker);
                    });
                })
                .catch(error => console.error('Error fetching markers:', error));
        }

        // Event listener untuk perubahan filter
        $('#provinsiFilter').on('change', function () {
            loadMapMarkers();
        });

        // Inisialisasi map saat dokumen siap
        $(document).ready(function () {
            initMap();
        });
    </script> --}}

    <script>
        let map;
        let markers = [];
        let infoWindow;
        let AdvancedMarkerElement; // <-- DEKLARASIKAN DI SINI (scope global script)

        async function initMap() {
            // Request needed libraries.
            const { Map } = await google.maps.importLibrary("maps");

            // --- PERUBAHAN DI SINI ---
            // Import dan assign ke variabel global yang sudah dideklarasi
            // Perhatikan tanda kurung () saat destructuring assignment ke variabel yang sudah ada
            ({ AdvancedMarkerElement } = await google.maps.importLibrary("marker"));
            // -------------------------

            // Inisialisasi Peta
            map = new Map(document.getElementById("map"), {
                center: { lat: -2.548926, lng: 118.0148634 },
                zoom: 5,
                mapId: "YOUR_MAP_ID" // GANTI DENGAN MAP ID ANDA
            });

            // Inisialisasi InfoWindow
            infoWindow = new google.maps.InfoWindow();

            // Pastikan AdvancedMarkerElement sudah terdefinisi sebelum load marker
            if (typeof AdvancedMarkerElement === 'undefined') {
                 console.error("Gagal mengimpor AdvancedMarkerElement dari Google Maps Library.");
                 alert("Terjadi masalah saat memuat komponen peta. Silakan refresh halaman.");
                 return; // Hentikan eksekusi jika library gagal dimuat
            }


            // Load marker saat peta pertama kali dimuat
            loadMapMarkers();
        }

        // Fungsi untuk menghapus semua marker dari peta
        // function clearMarkers() {
        //     markers.forEach(marker => {
        //         marker.map = null; // Hapus dari peta
        //     });
        //     markers = [];
        // }

        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }

        // Fungsi untuk memuat dan menampilkan marker dari API
        // function loadMapMarkers(provinsiId = null) {
        //     // Pastikan AdvancedMarkerElement sudah siap digunakan
        //     if (typeof AdvancedMarkerElement === 'undefined') {
        //          console.error("AdvancedMarkerElement belum siap saat loadMapMarkers dipanggil.");
        //          return; // Jangan lanjutkan jika library belum ada
        //     }

        //     let url = "{{ route('dashboard.api.markers') }}";
        //     if (provinsiId) {
        //         url = "{{ url('dashboard/data/get-provinsi-koordinat') }}/" + provinsiId;
        //     }

        //     fetch(url)
        //         .then(res => {
        //             if (!res.ok) {
        //                 throw new Error(`HTTP error! status: ${res.status}`);
        //             }
        //             return res.json();
        //         })
        //         .then(data => {
        //             clearMarkers();

        //             if (!data || data.length === 0) {
        //                 console.log("Tidak ada data marker yang diterima dari API.");
        //                 return;
        //             }

        //             data.forEach(prov => {
        //                 const lat = parseFloat(prov.latitude);
        //                 const lng = parseFloat(prov.longitude);

        //                 if (isNaN(lat) || isNaN(lng)) {
        //                     console.warn(`Koordinat tidak valid untuk provinsi ${prov.nama}:`, prov.latitude, prov.longitude);
        //                     return;
        //                 }

        //                 const position = { lat: lat, lng: lng };
        //                 const jumlahDesa = prov.jumlah_desa ?? 0;
        //                 const contentString = `
        //                     <div style="font-family: sans-serif; font-size: 14px;">
        //                         <h4 style="margin: 0 0 5px 0;">${prov.nama}</h4>
        //                         <p style="margin: 0;">Jumlah Desa Terdaftar: ${jumlahDesa}</p>
        //                     </div>`;

        //                 // SEKARANG AdvancedMarkerElement sudah terdefinisi di scope ini
        //                 const marker = new AdvancedMarkerElement({
        //                     map: map,
        //                     position: position,
        //                     title: prov.nama
        //                 });

        //                 marker.addListener('click', () => {
        //                     infoWindow.close();
        //                     infoWindow.setContent(contentString);
        //                     infoWindow.open(map, marker);
        //                 });

        //                 markers.push(marker);
        //             });
        //         })
        //         .catch(error => {
        //             console.error("Gagal memuat data marker:", error);
        //             alert("Terjadi kesalahan saat memuat data peta. Silakan coba lagi.");
        //         });
        // }

        function loadMapMarkers() {
            const provinsiId = $('#provinsiFilter').val();
            let url = "{{ route('dashboard.api.markers', ['id' => ':id']) }}".replace(':id', provinsiId || '');

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    clearMarkers(); // Fungsi untuk menghapus marker sebelumnya
                    data.forEach(prov => {
                        const marker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(prov.latitude),
                                lng: parseFloat(prov.longitude)
                            },
                            map: map, // Variabel map harus sudah didefinisikan sebelumnya
                            title: prov.nama
                        });

                        const infowindow = new google.maps.InfoWindow({
                            content: `<strong>${prov.nama}</strong><br>Jumlah Desa: ${prov.total_desa}`
                        });

                        marker.addListener('click', () => {
                            infowindow.open(map, marker);
                        });

                        markers.push(marker); // Simpan marker ke array jika perlu
                    });
                })
                .catch(error => console.error('Error fetching markers:', error));
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
        });
    </script>

@endpush
