@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 mt-2">
            <h2 class="text-muted" style="font-family: 'Figtree', sans-serif; font-weight: 700;">Dashboard Beneficiaries</h2>
            {{-- <p class="text-muted" style="font-family: 'Figtree', sans-serif;">Monitoring penerima manfaat dan sebaran lokasi kegiatan.</p> --}}
        </div>
    </div>

    {{-- Statistics Cards - Original & New --}}
    <div class="row">
        {{-- Row 1: Programs, Locations, Total Beneficiaries (Blue), Family (Yellow/Orange) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="totalPrograms">0</h3>
                    <p>Total Program</p>
                </div>
                <div class="icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="totalLocations">0</h3>
                    <p>Total Lokasi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                {{-- Distinct Blue as per user image for Total Beneficiaries --}}
                <div class="inner">
                    <h3 id="totalBeneficiaries">0</h3>
                    <p>Total Penerima Manfaat</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
         <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="families">0</h3>
                    <p>Keluarga</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Row 2: Male (Green), Female (Yellow), Child Male (Red), Child Female (Teal/Blue) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="maleBeneficiaries">0</h3>
                    <p>Laki-Laki</p>
                </div>
                <div class="icon">
                    <i class="fas fa-male"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="femaleBeneficiaries">0</h3>
                    <p>Perempuan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-female"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 id="childrenMale">0</h3>
                    <p>Anak Laki-Laki</p>
                </div>
                <div class="icon">
                    <i class="fas fa-child"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                 {{-- Teal/Blue-ish for Child Female --}}
                <div class="inner">
                    <h3 id="childrenFemale">0</h3>
                    <p>Anak Perempuan</p>
                </div>
                <div class="icon">
                    <i class="fas fa-child-dress"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Row 3: Disability (Green) --}}
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="disability">0</h3>
                    <p>Disabilitas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wheelchair"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <fieldset class="mb-4 border p-3 rounded">
        <legend class="w-auto px-2 h6 fw-bold text-primary">Filter</legend>
            <div class="row">
                <div class="col-md-3">
                    <label for="programFilter">{{ __('cruds.program.title') }}:</label>
                    <select id="programFilter" class="form-control">
                        <option value="">{{ __('cruds.program.all') }}</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->kode ?? '' }} - {{ $program->nama ?? 'Tanpa Nama' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="provinsiFilter">{{ __('cruds.program.lokasi.pro') }}:</label>
                    <select id="provinsiFilter" class="form-control">
                        <option value="">{{ __('cruds.program.lokasi.all_provinsi') }}</option>
                        @foreach ($provinsis as $provinsi)
                            <option value="{{ $provinsi->id }}">{{ $provinsi->nama ?? 'Tanpa Nama' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="tahunFilter">{{ __('cruds.program.periode') }}:</label>
                    <select id="tahunFilter" class="form-control">
                        <option value="">{{ __('cruds.program.all_years') }}</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="statusFilter">Status:</label>
                    <select id="statusFilter" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="running">Sedang Berjalan</option>
                        <option value="completed">Sudah Selesai</option>
                        <option value="pending">Belum Dimulai</option>
                    </select>
                </div>
            </div>
    </fieldset> 

    <div class="row">
        {{-- Map Section --}}
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marked-alt"></i> Peta Sebaran Lokasi Kegiatan
                        <span class="badge bg-primary ms-2" id="markerCount">0 Lokasi</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px; width: 100%;"></div>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="col-lg-6">
            {{-- Gender Distribution --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Persebaran Gender</h3>
                </div>
                <div class="card-body">
                    <canvas id="genderChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            {{-- Kelompok Marjinal --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users-between-lines"></i> Kelompok Marjinal</h3>
                </div>
                <div class="card-body">
                    <canvas id="marjinalChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Program Status Table --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header"> --}}
                    {{-- <h3 class="card-title"><i class="fas fa-list-check"></i> Status Program</h3> --}}
                {{-- </div> --}}
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover text-nowrap datatable" width="100%">
                        <thead>
                            <tr>
                                <th>Nama Program</th>
                                <th>Kode</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Beneficiary (BTOR/MEALS)</th>
                                <th>Status</th>
                                {{-- <th>PIC</th> --}}
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="programStatusTableBody">
                            {{-- Table rows will be inserted here via AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Program Detail Modal --}}
    <div class="modal fade" id="programDetailModal" tabindex="-1" role="dialog" aria-labelledby="programDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="programDetailModalLabel">Detail Program</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-3">Nama Program</dt>
                        <dd class="col-sm-9" id="modalProgramName"></dd>

                        <dt class="col-sm-3">Kode</dt>
                        <dd class="col-sm-9" id="modalProgramCode"></dd>

                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9" id="modalProgramStatus"></dd>

                        <dt class="col-sm-3">Periode</dt>
                        <dd class="col-sm-9" id="modalProgramPeriod"></dd>

                        {{-- <dt class="col-sm-3">PIC</dt>
                        <dd class="col-sm-9" id="modalProgramPic"></dd> --}}

                        <dt class="col-sm-3">Total Nilai</dt>
                        <dd class="col-sm-9" id="modalProgramValue"></dd>

                        <dt class="col-sm-3">BTOR / Meals Beneficiary</dt>
                        <dd class="col-sm-9" id="modalProgramBeneficiaries"></dd>

                        <dt class="col-sm-3">Deskripsi</dt>
                        <dd class="col-sm-9" id="modalProgramDescription"></dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    {{-- Google Fonts - Figtree --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* FIX: Apply font only to body to avoid overriding FontAwesome icons */
        body {
            font-family: 'Figtree', sans-serif !important;
        }
        
        .small-box .icon > i {
            font-size: 90px !important;
        }
        
        @media (min-width: 1200px) {
            .small-box > .inner > h3 {
                font-size: 60px !important;
            }
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-running {
            background: #10b981;
            color: rgb(255, 255, 255);
        }
        .badge-submit {
            background: #1097b9;
            color: rgb(255, 255, 255);
        }
        .badge-draft {
            background: #1b1b1b;
            color: rgb(255, 255, 255);
        }
        
        .badge-completed {
            background: #6366f1;
            color: white;
        }
        
        .badge-pending {
            background: #f59e0b;
            color: white;
        }

        .table-hover tbody tr {
            cursor: pointer;
        }
        b, strong {
            font-weight: 800!important;
        }
    </style>
@endpush

@push('js')
    @section('plugins.Select2', true)
    @section('plugins.Sweetalert2', true)
    
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
    
    {{-- Google Maps API --}}
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
    ({key: "{{ $googleMapsApiKey }}", v: "weekly"});</script>
    
    <script>
        let map, genderChart, marjinalChart;
        let markers = [];
        let AdvancedMarkerElement;
        // Store programs data globally to access in modal
        let currentPrograms = [];
        let mapReadyPromise;
        
        $(document).ready(function() {
            // Initialize Select2
            $('#programFilter, #provinsiFilter, #tahunFilter, #statusFilter').select2();
            
            // Initialize Google Maps
            mapReadyPromise = initMap();
            
            // Load initial data
            loadDashboardData();
            
            // Initialize charts
            initCharts();
            
            // Filter change events
            $('#programFilter, #provinsiFilter, #tahunFilter, #statusFilter').on('change', function() {
                loadDashboardData();
            });

            // Table Row Click Event for Modal
            $(document).on('click', '.program-row', function() {
                const programId = $(this).data('id');
                const program = currentPrograms.find(p => p.id === programId);
                
                if (program) {
                    $('#modalProgramName').text(program.nama);
                    $('#modalProgramCode').text(program.kode);
                    $('#modalProgramStatus').html(`<span class="status-badge badge-${program.statusClass}">${program.status}</span>`);
                    $('#modalProgramPeriod').text(`${program.tanggal_mulai} - ${program.tanggal_selesai}`);
                    // $('#modalProgramPic').text(program.pic);
                    $('#modalProgramValue').text(program.total_nilai);
                    $('#modalProgramBeneficiaries').text(`${program.target_beneficiaries} / ${program.actual_beneficiaries}`);
                    
                    // Decode HTML entities if description contains HTML, or just text
                    const description = program.deskripsi;
                    // Safe approach: create temp element to decode
                    const temp = document.createElement('div');
                    temp.innerHTML = description;
                    $('#modalProgramDescription').html(description); // Trusting backend content here? Or use text() if raw.
                    // Assuming description might be rich text from WYSIWYG
                    
                    $('#programDetailModal').modal('show');
                }
            });
        });
        
        async function initMap() {
            try {
                const { Map } = await google.maps.importLibrary("maps");
                const { AdvancedMarkerElement: MarkerElement } = await google.maps.importLibrary("marker");
                
                // Assign to global for fallback/other usages
                AdvancedMarkerElement = MarkerElement;
                
                console.log("Google Maps Libraries Loaded. AdvancedMarkerElement:", !!AdvancedMarkerElement);

                map = new Map(document.getElementById("map"), {
                    center: { lat: -2.711614, lng: 121.631757 }, // Indonesia center
                    zoom: 5,
                    mapId: "7e7fb1bfd929ec61",
                });
                
                return { Map, AdvancedMarkerElement: MarkerElement };
            } catch (error) {
                console.error("Error initializing map:", error);
                throw error;
            }
        }
        
        function loadDashboardData() {
            const filters = {
                program_id: $('#programFilter').val(),
                provinsi_id: $('#provinsiFilter').val(),
                tahun: $('#tahunFilter').val(),
                status: $('#statusFilter').val()
            };
            
            $.ajax({
                url: "{{ route('dashboard.beneficiary.data') }}",
                method: 'GET',
                data: filters,
                success: function(data) {
                    // Update global programs data
                    currentPrograms = data.programs;

                    updateStatistics(data.stats);
                    updateProgramTable(data.programs);
                    
                    // Wait for map to be ready
                    if (mapReadyPromise) {
                        mapReadyPromise.then((libraries) => {
                            // Use the explicitly returned class if available, or fall back to global
                            const MarkerClass = libraries ? libraries.AdvancedMarkerElement : AdvancedMarkerElement;
                            updateMapMarkers(data.locations, MarkerClass);
                        }).catch(err => {
                            console.error("Map initialization failed, cannot update markers:", err);
                        });
                    }
                    
                    updateCharts(data.genderData, data.marjinalData);
                },
                error: function(xhr) {
                    console.error('Error loading data:', xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal memuat data',
                        text: 'Terjadi kesalahan saat memuat data dashboard',
                        timer: 3000
                    });
                }
            });
        }
        
        function updateStatistics(stats) {
            $('#totalPrograms').text(stats.totalPrograms || 0);
            $('#totalLocations').text(stats.totalLocations || 0);
            $('#totalBeneficiaries').text((stats.totalBeneficiaries || 0).toLocaleString('id-ID'));
            
            // New Detail Cards
            $('#maleBeneficiaries').text((stats.maleBeneficiaries || 0).toLocaleString('id-ID'));
            $('#femaleBeneficiaries').text((stats.femaleBeneficiaries || 0).toLocaleString('id-ID'));
            $('#childrenMale').text((stats.childrenMale || 0).toLocaleString('id-ID'));
            $('#childrenFemale').text((stats.childrenFemale || 0).toLocaleString('id-ID'));
            $('#disability').text((stats.disability || 0).toLocaleString('id-ID'));
            $('#families').text((stats.families || 0).toLocaleString('id-ID'));
        }
        
        function updateProgramTable(programs) {
            const container = $('#programStatusTableBody');
            container.empty();
            
            if (!programs || programs.length === 0) {
                container.html('<tr><td colspan="7" class="text-center text-muted">Tidak ada data program</td></tr>');
                return;
            }
            
            programs.forEach(program => {
                const row = `
                    <tr class="program-row" data-id="${program.id}">
                        <td>${program.nama}</td>
                        <td>${program.kode}</td>
                        <td>${program.tanggal_mulai}</td>
                        <td>${program.tanggal_selesai}</td>
                        <td>
                            <strong class="text-primary">${program.target_beneficiaries}</strong> / <strong class="text-success">${program.actual_beneficiaries}</strong>
                        </td>
                        <td><span class="status-badge badge-${program.statusClass}">${program.status}</span></td>
                        <td>
                             <button class="btn btn-sm btn-info view-details" title="Lihat Detail"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                `;
                container.append(row);
            });
        }
        
        const beneficiaryUrlTemplate = "{{ route('beneficiary.show.data', ':id') }}";
        const kegiatanUrlTemplate = "{{ route('kegiatan.show', ':id') }}";

        function updateMapMarkers(locations, MarkerClass) {
            // Use passed class or global variable
            const AMElement = MarkerClass || AdvancedMarkerElement;

            if (!AMElement) {
                console.error("AdvancedMarkerElement is not ready yet. Skipping marker update.");
                return;
            }

            // Clear existing markers
            markers.forEach(marker => marker.map = null);
            markers = [];
            
            if (!locations || locations.length === 0) {
                $('#markerCount').text('0 Lokasi');
                return;
            }
            
            // Add new markers
            locations.forEach(location => {
                if (location.lat && location.long) {
                    try {
                        const marker = new AMElement({
                            map: map,
                            position: { lat: location.lat, lng: location.long },
                            title: location.program_nama
                        });

                        
                        const beneficiaryUrl = beneficiaryUrlTemplate.replace(':id', location.program_id);
                        const kegiatanUrl = kegiatanUrlTemplate.replace(':id', location.kegiatan_id);

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div style="font-family: Figtree, sans-serif; min-width: 250px;">
                                    <h6 style="color: #667eea; font-weight: 700;">${location.program_nama}</h6>
                                    <p><strong>Kode:</strong> ${location.program_kode}<br>
                                    <strong>Objektif:</strong> ${location.program_objektif}<br>
                                    <strong>Status:</strong> <span class="badge badge-${location.program_status}">${location.program_status}</span></p>
                                    <hr>
                                    <p><strong>Desa:</strong> ${location.desa_nama}<br>
                                    <strong>Kecamatan:</strong> ${location.kecamatan_nama}<br>
                                    <strong>Kabupaten:</strong> ${location.kabupaten_nama}<br>
                                    <strong>Provinsi:</strong> ${location.provinsi_nama}</p>
                                    <hr>
                                    <strong>Kode Kegiatan:</strong> ${location.kode_kegiatan}<br>
                                    <strong>Nama Kegiatan:</strong> ${location.nama_kegiatan}<br>
                                    <strong>Jenis Kegiatan:</strong> ${location.aktivitas_list}<br>
                                    <strong>Status Kegiatan:</strong> <span class="badge badge-${location.kegiatan_status}">${location.kegiatan_status}</span><br><br>
                                    <strong>Beneficiary:</strong>
                                        <ul class="m-0">
                                            <li><strong><a href="${kegiatanUrl}" title="Lihat Detail Kegiatan">BTOR : <span class="text-danger">${location.penerimamanfaat_btor_total}</span> </a></strong></li>
                                            <li><strong><a href="${beneficiaryUrl}" title="Lihat Detail Penerima Manfaat">MEALS : <span class="text-success">${location.penerimamanfaat_meals_total}</span></a></strong></li>
                                        </ul>
                                    <p><small><i class="far fa-calendar"></i> ${location.kegiatan_mulai} - ${location.kegiatan_selesai}</small></p>
                                </div>
                            `
                        });
                        
                        marker.addListener('click', () => {
                            infoWindow.open(map, marker);
                        });
                        
                        markers.push(marker);
                    } catch (e) {
                         console.error("Error creating marker:", e);
                    }
                }
            });
            
            $('#markerCount').text(`${markers.length} Lokasi`);
            
            // Fit bounds to markers
            if (markers.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                markers.forEach(marker => {
                    bounds.extend(marker.position);
                });
                map.fitBounds(bounds);
            }
        }
        
        function initCharts() {
            // Gender Chart
            const ctxGender = document.getElementById('genderChart').getContext('2d');
            genderChart = new Chart(ctxGender, {
                type: 'pie',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(36, 150, 44, 0.8)',
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Marjinal Chart
            const ctxMarjinal = document.getElementById('marjinalChart').getContext('2d');
            marjinalChart = new Chart(ctxMarjinal, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Jumlah',
                        data: [],
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
        
        function updateCharts(genderData, marjinalData) {
            // Update Gender Chart
            if (genderData && genderData.length > 0) {
                const totalGender = genderData.reduce((acc, item) => acc + (parseInt(item.total) || 0), 0);
                
                genderChart.data.labels = genderData.map(item => {
                    const val = parseInt(item.total) || 0;
                    const percentage = totalGender > 0 ? ((val / totalGender) * 100).toFixed(1) : 0;
                    return `${item.jenis_kelamin} (${percentage}%)`;
                });
                
                genderChart.data.datasets[0].data = genderData.map(item => item.total);
                genderChart.update();
            }
            
            // Update Marjinal Chart
            if (marjinalData && marjinalData.length > 0) {
                marjinalChart.data.labels = marjinalData.map(item => item.kelompok);
                marjinalChart.data.datasets[0].data = marjinalData.map(item => item.jumlah);
                marjinalChart.update();
            }
        }
    </script>
@endpush