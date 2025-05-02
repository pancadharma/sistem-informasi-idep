@extends('layouts.app')

@section('subtitle', 'Dashboard')
@section('content_header_title', 'Dashboard')
@section('sub_breadcumb', '')

@section('content_body')

    <div class="row mb-3">
        <div class="col-md-4">
            <label for="programFilter">Program:</label>
            <select id="programFilter" class="form-control">
                <option value="">Semua Program</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->nama ?? 'Tanpa Nama' }}</option>
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

    {{-- <div class="row" id="dashboardCards">
        <div class="col-md-4">
            <div class="info-box bg-primary">
                <span class="info-box-text">Total Penerima Manfaat</span>
                <span class="info-box-number" id="totalSemua">-</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-info">
                <span class="info-box-text">Laki-laki</span>
                <span class="info-box-number" id="totalLaki">-</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-danger">
                <span class="info-box-text">Perempuan</span>
                <span class="info-box-number" id="totalPerempuan">-</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-warning">
                <span class="info-box-text">Anak Laki-laki</span>
                <span class="info-box-number" id="totalAnakLaki">-</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-pink">
                <span class="info-box-text">Anak Perempuan</span>
                <span class="info-box-number" id="totalAnakPerempuan">-</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box bg-success">
                <span class="info-box-text">Disabilitas</span>
                <span class="info-box-number" id="totalDisabilitas">-</span>
            </div>
        </div>
    </div> --}}

    <div class="row" id="dashboardCards">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3><span class="info-box-number" id="totalSemua">-</span></h3>

                    <p><span class="info-box-text">Total Penerima Manfaat</span></p>
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

                    <p>Laki-laki</p>
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

                    <p>Perempuan</p>
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

                    <p>Anak Laki-laki</p>
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

                    <p>Anak Perempuan</p>
                </div>
                <div class="icon">
                    <span class="material-symbols-outlined">girl</span>
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

                    <p>Disabilitas</p>
                </div>
                <div class="icon">
                    <span class="material-symbols-outlined"> not_accessible_forward </span>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
@endsection

@push('js')
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
                    console.error('Error fetching data');
                }
            });
        }

        $(document).ready(function() {
            $('#programFilter, #provinsiFilter, #tahunFilter').change(loadDashboardData);
            loadDashboardData(); // initial load
        });
    </script>
@endpush
