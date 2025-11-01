<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kegiatan - {{ data_get($kegiatan, 'programActivity.nama', 'N/A') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .section-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease-in-out;
        }
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            padding: 1rem 1.5rem;
            margin: 0;
        }
        .badge-custom {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }
        .stat-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            border-left: 4px solid #667eea;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        .timeline-item {
            border-left: 3px solid #667eea;
            padding-left: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #667eea;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .nav-pills .nav-link {
            border-radius: 20px;
            margin-right: 0.5rem;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .location-card {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #2196f3;
        }
        .partner-badge {
            background: #e8f5e8;
            color: #2e7d32;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            margin: 0.2rem;
            display: inline-block;
            border: 1px solid #c8e6c9;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header Info Card -->
        <div class="info-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-3">
                        <i class="fas fa-calendar-alt me-3"></i>
                        {{ data_get($kegiatan, 'programActivity.nama', 'Nama Kegiatan Tidak Tersedia') }}
                    </h1>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><i class="fas fa-project-diagram me-2"></i><strong>Program:</strong> {{ data_get($kegiatan, 'programActivity.output.outcome.program.nama', 'N/A') }}</p>
                            <p class="mb-1"><i class="fas fa-code me-2"></i><strong>Kode Activity:</strong> {{ data_get($kegiatan, 'programActivity.kode', 'N/A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><i class="fas fa-tag me-2"></i><strong>Jenis:</strong> {{ data_get($kegiatan, 'jenisKegiatan.nama', 'N/A') }}</p>
                            <p class="mb-1"><i class="fas fa-user me-2"></i><strong>PIC:</strong> {{ data_get($kegiatan, 'user.nama', 'N/A') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="badge badge-custom {{ $kegiatan->status == 'completed' ? 'bg-success' : ($kegiatan->status == 'ongoing' ? 'bg-warning' : 'bg-secondary') }}">
                        {{ ucfirst($kegiatan->status ?? 'Unknown') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills mb-4" id="kegiatanTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">
                    <i class="fas fa-info-circle me-2"></i>Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="beneficiaries-tab" data-bs-toggle="pill" data-bs-target="#beneficiaries" type="button" role="tab">
                    <i class="fas fa-users me-2"></i>Penerima Manfaat
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="locations-tab" data-bs-toggle="pill" data-bs-target="#locations" type="button" role="tab">
                    <i class="fas fa-map-marker-alt me-2"></i>Lokasi & Mitra
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="details-tab" data-bs-toggle="pill" data-bs-target="#details" type="button" role="tab">
                    <i class="fas fa-clipboard-list me-2"></i>Detail Aktivitas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="team-tab" data-bs-toggle="pill" data-bs-target="#team" type="button" role="tab">
                    <i class="fas fa-users-cog me-2"></i>Tim Penulis
                </button>
            </li>
        </ul>

        <div class="tab-content" id="kegiatanTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <!-- Timeline -->
                    <div class="col-md-6">
                        <div class="card section-card">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Timeline Kegiatan</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline-item">
                                    <h6 class="mb-1">Tanggal Mulai</h6>
                                    <p class="text-muted mb-0">{{ $kegiatan->tanggalmulai ? \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d F Y, H:i') : 'Tidak tersedia' }}</p>
                                </div>
                                <div class="timeline-item">
                                    <h6 class="mb-1">Tanggal Selesai</h6>
                                    <p class="text-muted mb-0">{{ $kegiatan->tanggalselesai ? \Carbon\Carbon::parse($kegiatan->tanggalselesai)->format('d F Y, H:i') : 'Tidak tersedia' }}</p>
                                </div>
                                @if($kegiatan->tanggalmulai && $kegiatan->tanggalselesai)
                                <div class="timeline-item">
                                    <h6 class="mb-1">Durasi</h6>
                                    <p class="text-muted mb-0">{{ ($kegiatan->tanggalmulai && $kegiatan->tanggalselesai) ? \Carbon\Carbon::parse($kegiatan->tanggalmulai)->diffInDays(\Carbon\Carbon::parse($kegiatan->tanggalselesai)) + 1 : 0 }} hari</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Basic Info -->
                    <div class="col-md-6">
                        <div class="card section-card">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-info me-2"></i>Informasi Dasar</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 mb-2">
                                        <small class="text-muted">Fase Pelaporan</small>
                                        <p class="mb-0 fw-bold">{{ $kegiatan->fasepelaporan ?? 1 }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <small class="text-muted">Status</small>
                                        <p class="mb-0 fw-bold">{{ ucfirst($kegiatan->status ?? 'Unknown') }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <small class="text-muted">Program Context</small>
                                    <p class="mb-0">{{ $kegiatan->programActivity->output->outcome->program->deskripsiprojek ?? 'Tidak ada deskripsi program' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Assessment Details -->
                @if($kegiatan->assessmentDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Detail Assessment</h5>
                    </div>
                    <div class="card-body">
                        <h6>Yang Terlibat dalam Assessment</h6>
                        <p>{{ $kegiatan->assessmentDetail->assessmentyangterlibat ?? 'Tidak tersedia' }}</p>

                        <h6>Temuan Assessment</h6>
                        <p>{{ $kegiatan->assessmentDetail->assessmenttemuan ?? 'Tidak ada temuan yang dilaporkan.' }}</p>

                        @if($kegiatan->assessmentDetail->assessmenttambahan)
                        <div class="alert alert-info">
                            <h6><i class="fas fa-plus-circle me-2"></i>Assessment Tambahan</h6>
                            <p class="mb-0">{{ $kegiatan->assessmentDetail->assessmenttambahan_ket ?? 'Ada assessment tambahan yang dilakukan.' }}</p>
                        </div>
                        @endif

                        @if($kegiatan->assessmentDetail->assessmentkendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->assessmentDetail->assessmentkendala }}</p>
                        @endif

                        @if($kegiatan->assessmentDetail->assessmentpembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->assessmentDetail->assessmentpembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Campaign Details -->
                @if($kegiatan->kampanyeDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Detail Kampanye</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Yang Dikampanyekan</h6>
                                <p>{{ $kegiatan->kampanyeDetail->kampanyeyangdikampanyekan ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Jenis Kampanye</h6>
                                <p>{{ $kegiatan->kampanyeDetail->kampanyejenis ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>

                        <h6>Bentuk Kegiatan</h6>
                        <p>{{ $kegiatan->kampanyeDetail->kampanyebentukkegiatan ?? 'Tidak ada deskripsi.' }}</p>

                        <h6>Target Sasaran</h6>
                        <p>{{ $kegiatan->kampanyeDetail->kampanyeyangdisasar ?? 'Tidak ada target yang ditetapkan.' }}</p>

                        <h6>Jangkauan</h6>
                        <p>{{ $kegiatan->kampanyeDetail->kampanyejangkauan ?? 'Tidak ada data jangkauan.' }}</p>

                        @if($kegiatan->kampanyeDetail->kampanyekendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->kampanyeDetail->kampanyekendala }}</p>
                        @endif

                        @if($kegiatan->kampanyeDetail->kampanyepembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->kampanyeDetail->kampanyepembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Monitoring Details -->
                @if($kegiatan->monitoringDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Detail Monitoring</h5>
                    </div>
                    <div class="card-body">
                        <h6>Yang Dipantau</h6>
                        <p>{{ $kegiatan->monitoringDetail->monitoringyangdipantau ?? 'Tidak tersedia' }}</p>

                        <h6>Data Monitoring</h6>
                        <p>{{ $kegiatan->monitoringDetail->monitoringdata ?? 'Tidak ada data.' }}</p>

                        <h6>Metode Monitoring</h6>
                        <p>{{ $kegiatan->monitoringDetail->monitoringmetode ?? 'Tidak ada metode yang dijelaskan.' }}</p>

                        <h6>Hasil Monitoring</h6>
                        <p>{{ $kegiatan->monitoringDetail->monitoringhasil ?? 'Tidak ada hasil yang dilaporkan.' }}</p>

                        @if($kegiatan->monitoringDetail->monitoringkegiatanselanjutnya)
                        <div class="alert alert-success">
                            <h6><i class="fas fa-forward me-2"></i>Kegiatan Selanjutnya</h6>
                            <p class="mb-0">{{ $kegiatan->monitoringDetail->monitoringkegiatanselanjutnya_ket ?? 'Ada kegiatan lanjutan yang direncanakan.' }}</p>
                        </div>
                        @endif

                        @if($kegiatan->monitoringDetail->monitoringkendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->monitoringDetail->monitoringkendala }}</p>
                        @endif

                        @if($kegiatan->monitoringDetail->monitoringpembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->monitoringDetail->monitoringpembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Mapping Details -->
                @if($kegiatan->pemetaanDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-map me-2"></i>Detail Pemetaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Yang Dihasilkan</h6>
                                <p>{{ $kegiatan->pemetaanDetail->pemetaanyangdihasilkan ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Luasan</h6>
                                <p>{{ $kegiatan->pemetaanDetail->pemetaanluasan ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>

                        <h6>Unit Pemetaan</h6>
                        <p>{{ $kegiatan->pemetaanDetail->pemetaanunit ?? 'Tidak ada unit yang ditetapkan.' }}</p>

                        <h6>Yang Terlibat</h6>
                        <p>{{ $kegiatan->pemetaanDetail->pemetaanyangterlibat ?? 'Tidak ada informasi.' }}</p>

                        @if($kegiatan->pemetaanDetail->pemetaanisu)
                        <h6>Isu</h6>
                        <p class="text-warning">{{ $kegiatan->pemetaanDetail->pemetaanisu }}</p>
                        @endif

                        @if($kegiatan->pemetaanDetail->pemetaanpembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->pemetaanDetail->pemetaanpembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Development Details -->
                @if($kegiatan->pengembanganDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Detail Pengembangan</h5>
                    </div>
                    <div class="card-body">
                        <h6>Jenis Komponen</h6>
                        <p>{{ $kegiatan->pengembanganDetail->pengembanganjeniskomponen ?? 'Tidak tersedia' }}</p>

                        <h6>Berapa Komponen</h6>
                        <p>{{ $kegiatan->pengembanganDetail->pengembanganberapakomponen ?? 'Tidak tersedia' }}</p>

                        <h6>Lokasi Komponen</h6>
                        <p>{{ $kegiatan->pengembanganDetail->pengembanganlokasikomponen ?? 'Tidak tersedia' }}</p>

                        <h6>Yang Terlibat</h6>
                        <p>{{ $kegiatan->pengembanganDetail->pengembanganyangterlibat ?? 'Tidak ada informasi.' }}</p>

                        @if($kegiatan->pengembanganDetail->pengembangankendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->pengembanganDetail->pengembangankendala }}</p>
                        @endif

                        @if($kegiatan->pengembanganDetail->pengembanganpembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->pengembanganDetail->pengembanganpembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Procurement Details -->
                @if($kegiatan->pembelanjaanDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Detail Pembelanjaan</h5>
                    </div>
                    <div class="card-body">
                        <h6>Detail Barang</h6>
                        <p>{{ $kegiatan->pembelanjaanDetail->pembelanjaandetailbarang ?? 'Tidak tersedia' }}</p>

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Periode Pembelanjaan</h6>
                                <p>
                                    @if($kegiatan->pembelanjaanDetail->pembelanjaanmulai)
                                        {{ \Carbon\Carbon::parse($kegiatan->pembelanjaanDetail->pembelanjaanmulai)->format('d F Y') }}
                                    @endif
                                    @if($kegiatan->pembelanjaanDetail->pembelanjaanmulai && $kegiatan->pembelanjaanDetail->pembelanjaanselesai)
                                        -
                                    @endif
                                    @if($kegiatan->pembelanjaanDetail->pembelanjaanselesai)
                                        {{ \Carbon\Carbon::parse($kegiatan->pembelanjaanDetail->pembelanjaanselesai)->format('d F Y') }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Periode Distribusi</h6>
                                <p>
                                    @if($kegiatan->pembelanjaanDetail->pembelanjaandistribusimulai)
                                        {{ \Carbon\Carbon::parse($kegiatan->pembelanjaanDetail->pembelanjaandistribusimulai)->format('d F Y') }}
                                    @endif
                                    @if($kegiatan->pembelanjaanDetail->pembelanjaandistribusimulai && $kegiatan->pembelanjaanDetail->pembelanjaandistribusiselesai)
                                        -
                                    @endif
                                    @if($kegiatan->pembelanjaanDetail->pembelanjaandistribusiselesai)
                                        {{ \Carbon\Carbon::parse($kegiatan->pembelanjaanDetail->pembelanjaandistribusiselesai)->format('d F Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6>Status Distribusi</h6>
                                <span class="badge {{ $kegiatan->pembelanjaanDetail->pembelanjaanterdistribusi ? 'bg-success' : 'bg-warning' }}">
                                    {{ $kegiatan->pembelanjaanDetail->pembelanjaanterdistribusi ? 'Sudah Didistribusi' : 'Belum Didistribusi' }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <h6>Akan Didistribusi</h6>
                                <span class="badge {{ $kegiatan->pembelanjaanDetail->pembelanjaanakandistribusi ? 'bg-info' : 'bg-secondary' }}">
                                    {{ $kegiatan->pembelanjaanDetail->pembelanjaanakandistribusi ? 'Ya' : 'Tidak' }}
                                </span>
                            </div>
                        </div>

                        @if($kegiatan->pembelanjaanDetail->pembelanjaanakandistribusi_ket)
                        <h6>Keterangan Distribusi</h6>
                        <p>{{ $kegiatan->pembelanjaanDetail->pembelanjaanakandistribusi_ket }}</p>
                        @endif

                        @if($kegiatan->pembelanjaanDetail->pembelanjaankendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->pembelanjaanDetail->pembelanjaankendala }}</p>
                        @endif

                        @if($kegiatan->pembelanjaanDetail->pembelanjaanpembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->pembelanjaanDetail->pembelanjaanpembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Socialization Details -->
                @if($kegiatan->sosialisasiDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Detail Sosialisasi</h5>
                    </div>
                    <div class="card-body">
                        <h6>Yang Terlibat</h6>
                        <p>{{ $kegiatan->sosialisasiDetail->sosialisasiyangterlibat ?? 'Tidak tersedia' }}</p>

                        <h6>Temuan</h6>
                        <p>{{ $kegiatan->sosialisasiDetail->sosialisasitemuan ?? 'Tidak ada temuan.' }}</p>

                        @if($kegiatan->sosialisasiDetail->sosialisasitambahan)
                        <div class="alert alert-info">
                            <h6><i class="fas fa-plus-circle me-2"></i>Sosialisasi Tambahan</h6>
                            <p class="mb-0">{{ $kegiatan->sosialisasiDetail->sosialisasitambahan_ket ?? 'Ada sosialisasi tambahan yang dilakukan.' }}</p>
                        </div>
                        @endif

                        @if($kegiatan->sosialisasiDetail->sosialisasikendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->sosialisasiDetail->sosialisasikendala }}</p>
                        @endif

                        @if($kegiatan->sosialisasiDetail->sosialisasipembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->sosialisasiDetail->sosialisasipembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Other Activities Details -->
                @if($kegiatan->lainnyaDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-ellipsis-h me-2"></i>Detail Kegiatan Lainnya</h5>
                    </div>
                    <div class="card-body">
                        <h6>Mengapa Dilakukan</h6>
                        <p>{{ $kegiatan->lainnyaDetail->lainnyamengapadilakukan ?? 'Tidak tersedia' }}</p>

                        <h6>Dampak</h6>
                        <p>{{ $kegiatan->lainnyaDetail->lainnyadampak ?? 'Tidak ada dampak yang dilaporkan.' }}</p>

                        <h6>Sumber Pendanaan</h6>
                        <p>
                            {{ $kegiatan->lainnyaDetail->lainnyasumberpendanaan ?? 'Tidak tersedia' }}
                            @if($kegiatan->lainnyaDetail->lainnyasumberpendanaan_ket)
                                <br><small class="text-muted">{{ $kegiatan->lainnyaDetail->lainnyasumberpendanaan_ket }}</small>
                            @endif
                        </p>

                        <h6>Yang Terlibat</h6>
                        <p>{{ $kegiatan->lainnyaDetail->lainnyayangterlibat ?? 'Tidak ada informasi.' }}</p>

                        @if($kegiatan->lainnyaDetail->lainnyakendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->lainnyaDetail->lainnyakendala }}</p>
                        @endif

                        @if($kegiatan->lainnyaDetail->lainnyapembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->lainnyaDetail->lainnyapembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- No Details Message -->
                @if(!$kegiatan->pelatihanDetail && !$kegiatan->kunjunganDetail && !$kegiatan->konsultasiDetail && !$kegiatan->assessmentDetail && !$kegiatan->kampanyeDetail && !$kegiatan->monitoringDetail && !$kegiatan->pemetaanDetail && !$kegiatan->pengembanganDetail && !$kegiatan->pembelanjaanDetail && !$kegiatan->sosialisasiDetail && !$kegiatan->lainnyaDetail)
                <div class="card section-card">
                    <div class="card-body text-center">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak Ada Detail Aktivitas</h5>
                        <p class="text-muted">Belum ada detail khusus aktivitas yang tersimpan untuk kegiatan ini.</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Team Writers Tab -->
            <div class="tab-pane fade" id="team" role="tabpanel">
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Tim Penulis & Kontributor</h5>
                    </div>
                    <div class="card-body">
                        @php $penulisList = $kegiatan->penulis ?? collect(); @endphp
                        @if($penulisList->count() > 0)
                        <div class="row">
                            @foreach($penulisList as $penulis)
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $penulis->user->nama ?? 'N/A' }}</h6>
                                                <span class="badge bg-secondary">{{ $penulis->peran->nama ?? 'Unknown Role' }}</span>
                                                @if($penulis->user->email)
                                                <p class="mb-0 text-muted small"><i class="fas fa-envelope me-1"></i>{{ $penulis->user->email }}</p>
                                                @endif
                                                @if($penulis->user->jabatan)
                                                <p class="mb-0 text-muted small"><i class="fas fa-briefcase me-1"></i>{{ $penulis->user->jabatan->nama ?? 'N/A' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak Ada Tim Penulis</h5>
                            <p class="text-muted">Belum ada tim penulis yang ditetapkan untuk kegiatan ini.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-end">
                <a href="{{ route('kegiatan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>
                <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>Edit Kegiatan
                </a>
                <button type="button" class="btn btn-success" onclick="exportToPdf()">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </button>
                <form action="{{ route('export.activity.report') }}" method="POST" class="d-inline" id="exportForm">
                    @csrf
                    <input type="hidden" name="report_type" value="laporan_kegiatan">
                    <input type="hidden" name="program_id" value="{{ $kegiatan->programActivity->output->outcome->program->id ?? '' }}">
                    <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-word me-2"></i>Export BTOR
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportToPdf() {
            window.print();
        }

        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-activate first tab with content
            const tabs = document.querySelectorAll('.nav-link');
            const tabContents = document.querySelectorAll('.tab-pane');

            // Smooth scrolling for internal links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add tooltips to badges
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

    <style media="print">
        .btn, .nav-pills, .card-header { display: none !important; }
        .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
        body { font-size: 12px; }
        .info-card { background: white !important; color: black !important; border: 1px solid #dee2e6; }
    </style>
                                    <div class="col-sm-6 mb-2">
                                        <small class="text-muted">Fase Pelaporan</small>
                                        <p class="mb-0 fw-bold">{{ $kegiatan->fasepelaporan ?? 1 }}</p>
                                    </div>
                                    <div class="col-sm-6 mb-2">
                                        <small class="text-muted">Status</small>
                                        <p class="mb-0 fw-bold">{{ ucfirst($kegiatan->status ?? 'Unknown') }}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <small class="text-muted">Program Context</small>
                                    <p class="mb-0">{{ $kegiatan->programActivity->output->outcome->program->deskripsiprojek ?? 'Tidak ada deskripsi program' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card section-card">
                            <div class="section-header">
                                <h6 class="mb-0"><i class="fas fa-align-left me-2"></i>Latar Belakang</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-justify">{{ $kegiatan->deskripsilatarbelakang ?? 'Tidak ada deskripsi latar belakang.' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card section-card">
                            <div class="section-header">
                                <h6 class="mb-0"><i class="fas fa-bullseye me-2"></i>Tujuan</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-justify">{{ $kegiatan->deskripsitujuan ?? 'Tidak ada deskripsi tujuan.' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card section-card">
                            <div class="section-header">
                                <h6 class="mb-0"><i class="fas fa-trophy me-2"></i>Keluaran</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-justify">{{ $kegiatan->deskripsikeluaran ?? 'Tidak ada deskripsi keluaran.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Beneficiaries Tab -->
            <div class="tab-pane fade" id="beneficiaries" role="tabpanel">
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Statistik Penerima Manfaat</h5>
                    </div>
                    <div class="card-body">
                        <!-- Summary Stats -->
                        <div class="row mb-4">
                            <div class="col-md-2">
                                <div class="stat-box">
                                    <div class="stat-number">{{ $kegiatan->penerimamanfaattotal ?? 0 }}</div>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="stat-box">
                                    <div class="stat-number">{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}</div>
                                    <small class="text-muted">Perempuan</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="stat-box">
                                    <div class="stat-number">{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}</div>
                                    <small class="text-muted">Laki-laki</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="stat-box">
                                    <div class="stat-number">{{ $kegiatan->penerimamanfaatdisabilitastotal ?? 0 }}</div>
                                    <small class="text-muted">Disabilitas</small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="stat-box">
                                    <div class="stat-number">{{ $kegiatan->penerimamanfaatmarjinaltotal ?? 0 }}</div>
                                    <small class="text-muted">Marjinal</small>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-center">Perempuan</th>
                                        <th class="text-center">Laki-laki</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Dewasa (25-59 tahun)</strong></td>
                                        <td class="text-center">{{ $kegiatan->penerimamanfaatdewasaperempuan ?? 0 }}</td>
                                        <td class="text-center">{{ $kegiatan->penerimamanfaatdewasalakilaki ?? 0 }}</td>
                                        <td class="text-center"><strong>{{ $kegiatan->penerimamanfaatdewasatotal ?? 0 }}</strong></td>
                                        <td class="text-center">
                                            {{ $kegiatan->penerimamanfaattotal > 0 ? number_format(($kegiatan->penerimamanfaatdewasatotal / $kegiatan->penerimamanfaattotal) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Lansia (60+ tahun)</strong></td>
                                        <td class="text-center">{{ $kegiatan->penerimamanfaatlansiaperempuan ?? 0 }}</td>
                                        <td class="text-center">{{ $kegiatan->penerimamanfaatlansialakilaki ?? 0 }}</td>
                                        <td class="text-center"><strong>{{ $kegiatan->penerimamanfaatlansiatotal ?? 0 }}</strong></td>
                                        <td class="text-center">
                                            {{ $kegiatan->penerimamanfaattotal > 0 ? number_format(($kegiatan->penerimamanfaatlansiatotal / $kegiatan->penerimamanfaattotal) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Remaja (18-24 tahun)</strong></td>
                                        <td class="text-center">{{ $kegiatan->penerimamanfaatremajaperempuan ?? 0 }}</td>
                                        <td class="text-center">{{ $kegiatan->penerimamanfaatremajalakilaki ?? 0 }}</td>
                                        <td class="text-center"><strong>{{ $kegiatan->penerimamanfaatremajatotal ?? 0 }}</strong></td>
                                        <td class="text-center">
                                            {{ $kegiatan->penerimamanfaattotal > 0 ? number_format(($kegiatan->penerimamanfaatremajatotal / $kegiatan->penerimamanfaattotal) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Anak (<18 tahun)</strong></td>
                                        <td class="text-center">{{ $kegiatan->penerimamanfaatanakperempuan ?? 0 }}</td>
                                        <td class="text-center">{{ $kegiatan->penerimamanfaatanaklakilaki ?? 0 }}</td>
                                        <td class="text-center"><strong>{{ $kegiatan->penerimamanfaatanaktotal ?? 0 }}</strong></td>
                                        <td class="text-center">
                                            {{ $kegiatan->penerimamanfaattotal > 0 ? number_format(($kegiatan->penerimamanfaatanaktotal / $kegiatan->penerimamanfaattotal) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th><strong>Grand Total</strong></th>
                                        <th class="text-center">{{ $kegiatan->penerimamanfaatperempuantotal ?? 0 }}</th>
                                        <th class="text-center">{{ $kegiatan->penerimamanfaatlakilakitotal ?? 0 }}</th>
                                        <th class="text-center"><strong>{{ $kegiatan->penerimamanfaattotal ?? 0 }}</strong></th>
                                        <th class="text-center">100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Locations & Partners Tab -->
            <div class="tab-pane fade" id="locations" role="tabpanel">
                <div class="row">
                    <!-- Locations -->
                    <div class="col-md-6">
                        <div class="card section-card">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Kegiatan</h5>
                            </div>
                            <div class="card-body">
                                @if(($kegiatan->lokasi ?? collect())->count() > 0)
                                    @foreach($kegiatan->lokasi as $lokasi)
                                    <div class="location-card">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    @if($lokasi->desa)
                                                        {{ $lokasi->desa->nama }}
                                                        @if($lokasi->desa->kecamatan)
                                                            , {{ $lokasi->desa->kecamatan->nama }}
                                                            @if($lokasi->desa->kecamatan->kabupaten)
                                                                , {{ $lokasi->desa->kecamatan->kabupaten->nama }}
                                                                @if($lokasi->desa->kecamatan->kabupaten->provinsi)
                                                                    , {{ $lokasi->desa->kecamatan->kabupaten->provinsi->nama }}
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endif
                                                </h6>
                                                @if($lokasi->lokasi)
                                                    <p class="mb-0 text-muted"><i class="fas fa-info-circle me-1"></i>{{ $lokasi->lokasi }}</p>
                                                @endif
                                            </div>
                                            @if($lokasi->lat && $lokasi->long)
                                            <small class="badge bg-info">
                                                <i class="fas fa-map-pin me-1"></i>
                                                {{ number_format($lokasi->lat, 6) }}, {{ number_format($lokasi->long, 6) }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0"><i class="fas fa-info-circle me-2"></i>Tidak ada data lokasi.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Partners -->
                    <div class="col-md-6">
                        <div class="card section-card">
                            <div class="section-header">
                                <h5 class="mb-0"><i class="fas fa-handshake me-2"></i>Mitra Terlibat</h5>
                            </div>
                            <div class="card-body">
                                @if(($kegiatan->mitra ?? collect())->count() > 0)
                                    <div class="d-flex flex-wrap">
                                        @foreach($kegiatan->mitra as $mitra)
                                        <span class="partner-badge">
                                            <i class="fas fa-building me-1"></i>{{ $mitra->partner->nama ?? 'N/A' }}
                                        </span>
                                        @endforeach
                                    </div>

                                    <hr>
                                    <h6 class="mb-3">Detail Mitra</h6>
                                    @foreach($kegiatan->mitra as $mitra)
                                    <div class="mb-3 p-2 bg-light rounded">
                                        <strong>{{ $mitra->partner->nama ?? 'N/A' }}</strong>
                                        @if($mitra->partner->keterangan)
                                            <p class="mb-0 mt-1 text-muted small">{{ $mitra->partner->keterangan }}</p>
                                        @endif
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-muted mb-0"><i class="fas fa-info-circle me-2"></i>Tidak ada mitra yang terlibat.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sektor/Target -->
                @if(($kegiatan->sektor ?? collect())->count() > 0)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-target me-2"></i>Target/Sektor Terkait</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($kegiatan->sektor as $sektor)
                            <div class="col-md-6 mb-2">
                                <span class="badge bg-secondary">{{ $sektor->targetReinstra->nama ?? 'N/A' }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Activity Details Tab -->
            <div class="tab-pane fade" id="details" role="tabpanel">
                <!-- Training Details -->
                @if($kegiatan->pelatihanDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Detail Pelatihan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Pelatih</h6>
                                <p>{{ $kegiatan->pelatihanDetail->pelatihanpelatih ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Status Distribusi</h6>
                                <span class="badge {{ $kegiatan->pelatihanDetail->pelatihandistribusi ? 'bg-success' : 'bg-warning' }}">
                                    {{ $kegiatan->pelatihanDetail->pelatihandistribusi ? 'Sudah Didistribusi' : 'Belum Didistribusi' }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <h6>Hasil Pelatihan</h6>
                        <p>{{ $kegiatan->pelatihanDetail->pelatihanhasil ?? 'Tidak ada deskripsi hasil.' }}</p>

                        @if($kegiatan->pelatihanDetail->pelatihanrencana)
                        <h6>Rencana Tindak Lanjut</h6>
                        <p>{{ $kegiatan->pelatihanDetail->pelatihanrencana }}</p>
                        @endif

                        @if($kegiatan->pelatihanDetail->pelatihanisu)
                        <h6>Isu yang Dihadapi</h6>
                        <p class="text-warning">{{ $kegiatan->pelatihanDetail->pelatihanisu }}</p>
                        @endif

                        @if($kegiatan->pelatihanDetail->pelatihanpembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->pelatihanDetail->pelatihanpembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Visit Details -->
                @if($kegiatan->kunjunganDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-plane me-2"></i>Detail Kunjungan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Lembaga yang Dikunjungi</h6>
                                <p>{{ $kegiatan->kunjunganDetail->kunjunganlembaga ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Peserta Kunjungan</h6>
                                <p>{{ $kegiatan->kunjunganDetail->kunjunganpeserta ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                        <hr>
                        <h6>Aktivitas yang Dilakukan</h6>
                        <p>{{ $kegiatan->kunjunganDetail->kunjunganyangdilakukan ?? 'Tidak ada deskripsi.' }}</p>

                        <h6>Hasil Kunjungan</h6>
                        <p>{{ $kegiatan->kunjunganDetail->kunjunganhasil ?? 'Tidak ada hasil yang dilaporkan.' }}</p>

                        @if($kegiatan->kunjunganDetail->kunjunganpotensipendapatan)
                        <h6>Potensi Pendapatan</h6>
                        <p class="text-info">{{ $kegiatan->kunjunganDetail->kunjunganpotensipendapatan }}</p>
                        @endif

                        @if($kegiatan->kunjunganDetail->kunjungankendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->kunjunganDetail->kunjungankendala }}</p>
                        @endif

                        @if($kegiatan->kunjunganDetail->kunjunganpembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->kunjunganDetail->kunjunganpembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Consultation Details -->
                @if($kegiatan->konsultasiDetail)
                <div class="card section-card">
                    <div class="section-header">
                        <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Detail Konsultasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Lembaga Konsultasi</h6>
                                <p>{{ $kegiatan->konsultasiDetail->konsultasilembaga ?? 'Tidak tersedia' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Komponen yang Dikonsultasikan</h6>
                                <p>{{ $kegiatan->konsultasiDetail->konsultasikomponen ?? 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                        <hr>
                        <h6>Yang Dilakukan</h6>
                        <p>{{ $kegiatan->konsultasiDetail->konsultasiyangdilakukan ?? 'Tidak ada deskripsi.' }}</p>

                        <h6>Hasil Konsultasi</h6>
                        <p>{{ $kegiatan->konsultasiDetail->konsultasihasil ?? 'Tidak ada hasil yang dilaporkan.' }}</p>

                        @if($kegiatan->konsultasiDetail->konsultasipotensipendapatan)
                        <h6>Potensi Pendapatan</h6>
                        <p class="text-info">{{ $kegiatan->konsultasiDetail->konsultasipotensipendapatan }}</p>
                        @endif

                        @if($kegiatan->konsultasiDetail->konsultasikendala)
                        <h6>Kendala</h6>
                        <p class="text-warning">{{ $kegiatan->konsultasiDetail->konsultasikendala }}</p>
                        @endif

                        @if($kegiatan->konsultasiDetail->konsultasipembelajaran)
                        <h6>Pembelajaran</h6>
                        <p class="text-success">{{ $kegiatan->konsultasiDetail->konsultasipembelajaran }}</p>
                        @endif
                    </div>
                </div>
                @endif

            </div>

    </div>

</body>
</html>
