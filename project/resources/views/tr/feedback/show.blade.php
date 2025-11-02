{{-- resources/views/tr/feedback/show.blade.php --}}

@extends('layouts.app')

@section('subtitle', __('Detail Feedback & Response'))
@section('content_header_title', __('Detail Feedback'))
@section('sub_breadcumb')
    <a href="{{ route('feedback.index') }}">{{ __('Feedback & Response') }}</a>
    <li class="breadcrumb-item active">{{ __('Detail FRM') }}</li>
@endsection

@push('styles')
{{-- Style untuk dl-horizontal dan scrollable --}}
<style>
    .scrollable-card-body {
        max-height: 75vh; 
        overflow-y: auto;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    .dl-horizontal dt { white-space: normal; text-align: left; margin-bottom: 5px; }
    .dl-horizontal dd { margin-left: 0; margin-bottom: 10px; word-wrap: break-word; }
    @media (min-width: 768px) {
        .dl-horizontal dt { float: left; width: 170px; clear: left; text-align: right; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-bottom: 0; padding-right: 10px; }
        .dl-horizontal dd { margin-left: 180px; margin-bottom: 10px; }
    }
    .nested-card .card-header {
        background-color: #f8f9fa; 
        padding: 0.5rem 1rem; 
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .nested-card .card-body {
        padding: 0.75rem 1rem; 
    }
    .nested-card .card-title {
        font-size: 0.95rem; 
        font-weight: 600; 
    }
    .nested-card .dl-horizontal {
        margin-bottom: 0; 
    }
</style>
@endpush

@section('content_body')
<div class="card card-outline card-primary mb-4">
    {{-- Card Header Utama --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
            <i class="fas fa-info-circle me-1"></i>
            Detail Feedback ID: {{ $feedback->id }}
        </h3>
        <a href="{{ route('feedback.edit', $feedback->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Edit Data
        </a>
    </div>

    {{-- Card Body Utama (Scrollable) --}}
    <div class="card-body scrollable-card-body">
        <div class="row">
            {{-- ====================================== --}}
            {{-- KOLOM KIRI --}}
            {{-- ====================================== --}}
            <div class="col-md-6">

                {{-- Card: Informasi Program & Registrasi --}}
                <div class="card nested-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Informasi Program & Registrasi</h6>
                    </div>
                    <div class="card-body">
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-4">Kode Program</dt>
                            <dd class="col-sm-8">{{ $feedback->Program?->kode ?? '-' }}</dd>
                            <dt class="col-sm-4">Nama Program</dt>
                            <dd class="col-sm-8">{{ $feedback->Program?->nama ?? '-' }}</dd>
                            <dt class="col-sm-4">Tgl Registrasi</dt>
                            <dd class="col-sm-8">{{ optional($feedback->tanggal_registrasi)->format('d M Y') ?? '-' }}</dd>
                            
                            {{-- FIELD BARU: Field Office --}}
                            <dt class="col-sm-4">Field Office</dt>
                            <dd class="col-sm-8">{{ $feedback->field_office ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>

                {{-- Card: Informasi Penerima Manfaat --}}
                <div class="card nested-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Informasi Penerima Manfaat</h6>
                    </div>
                    <div class="card-body">
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-4">Nama Penerima</dt>
                            <dd class="col-sm-8">{{ $feedback->penerima ?? '-' }}</dd>
                            <dt class="col-sm-4">Umur</dt>
                            <dd class="col-sm-8">{{ $feedback->umur ?? '-' }}</dd>
                            <dt class="col-sm-4">Kelompok Usia</dt>
                            <dd class="col-sm-8">{{ $feedback->age_group ?? '-' }}</dd>
                            <dt class="col-sm-4">Jenis Kelamin</dt>
                            <dd class="col-sm-8">{{ $feedback->sex ?? '-' }}</dd>
                            <dt class="col-sm-4">Posisi Penerima</dt>
                            <dd class="col-sm-8">{{ $feedback->position ?? '-' }}</dd>
                            <dt class="col-sm-4">Kontak Penerima</dt>
                            <dd class="col-sm-8">{{ $feedback->kontak_penerima ?? '-' }}</dd>
                            <dt class="col-sm-4">Alamat</dt>
                            <dd class="col-sm-8" style="white-space: pre-wrap;">{{ $feedback->address ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>

            </div> {{-- End Kolom Kiri --}}

            {{-- ====================================== --}}
            {{-- KOLOM KANAN --}}
            {{-- ====================================== --}}
            <div class="col-md-6">

                {{-- Card: Informasi Pelapor --}}
                <div class="card nested-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Informasi Pelapor</h6>
                    </div>
                    <div class="card-body">
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-4">Nama Pelapor</dt>
                            <dd class="col-sm-8">{{ $feedback->nama_pelapor ?? '-' }}</dd>
                            <dt class="col-sm-4">Phone Number</dt>
                            <dd class="col-sm-8">{{ $feedback->phone_number ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>

                {{-- Card: Detail Keluhan / Feedback --}}
                <div class="card nested-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Detail Keluhan / Feedback</h6>
                    </div>
                    <div class="card-body">
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-4">Jenis Keluhan</dt>
                            <dd class="col-sm-8">{{ $feedback->sort_of_complaint ?? '-' }}</dd>
                            <dt class="col-sm-4">Kategori Komplain</dt>
                            <dd class="col-sm-8">{{ $feedback->kategori_komplain ?? '-' }}</dd>
                            <dt class="col-sm-4">Channel</dt>
                            <dd class="col-sm-8">{{ $feedback->channels ?? '-' }}</dd>
                            <dt class="col-sm-4">Channel Lain</dt>
                            <dd class="col-sm-8">{{ $feedback->other_channel ?? '-' }}</dd>
                            <dt class="col-sm-4">Status Complaint</dt>
                            <dd class="col-sm-8">
                                <span class="badge
                                    @if($feedback->status_complaint == 'Baru') bg-info
                                    @elseif($feedback->status_complaint == 'Process') bg-primary {{-- Disesuaikan dengan create.blade.php --}}
                                    @elseif($feedback->status_complaint == 'Resolved') bg-success {{-- Disesuaikan dengan create.blade.php --}}
                                    @elseif($feedback->status_complaint == 'Diproses') bg-warning text-dark
                                    @elseif($feedback->status_complaint == 'Selesai') bg-success
                                    @elseif($feedback->status_complaint == 'Ditolak') bg-danger
                                    @else bg-secondary @endif">
                                    {{ $feedback->status_complaint ?? 'N/A' }}
                                </span>
                            </dd>
                            {{-- FIELD BARU: Status Tampilan (Hide/Unhide) --}}
                            <dt class="col-sm-4">Status Tampilan</dt>
                            <dd class="col-sm-8">
                                @if($feedback->is_hidden)
                                    <span class="badge bg-secondary">Sembunyikan (Hide)</span>
                                @else
                                    <span class="badge bg-success">Tampilkan (Unhide)</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>

                {{-- Card: Informasi Penanganan (Handler) --}}
                <div class="card nested-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Informasi Penanganan (Handler)</h6>
                    </div>
                    <div class="card-body">
                        <dl class="dl-horizontal row">
                            {{-- ====================================================== --}}
                            {{-- PENYESUAIAN UNTUK HANDLER DAN POSISI HANDLER --}}
                            {{-- ====================================================== --}}
                            <dt class="col-sm-4">Handler (Petugas)</dt>
                            {{-- Menggunakan relasi handlerUser untuk mendapatkan nama User --}}
                            <dd class="col-sm-8">{{ $feedback->handlerUser?->nama ?? ($feedback->handler_id ? 'Handler ID: '.$feedback->handler_id.' (Tidak Ditemukan)' : '-') }}</dd>
                            
                            <dt class="col-sm-4">Posisi Handler</dt>
                            {{-- Menampilkan dari kolom position_handler yang sudah diisi otomatis --}}
                            {{-- Atau bisa juga dari relasi jika ingin lebih dinamis: $feedback->handlerUser?->jabatan?->nama --}}
                            <dd class="col-sm-8">{{ $feedback->handlerUser?->jabatan?->nama ?? '-' }}</dd>
                            {{-- ====================================================== --}}
                            <dt class="col-sm-4">Kontak Handler</dt>
                            <dd class="col-sm-8">{{ $feedback->kontak_handler ?? '-' }}</dd>
                            <dt class="col-sm-4">Tgl Selesai</dt>
                            <dd class="col-sm-8">{{ optional($feedback->tanggal_selesai)->format('d M Y') ?? '-' }}</dd>
                            
                            {{-- FIELD BARU: Handler Description --}}
                            <dt class="col-sm-4">Deskripsi Penanganan</dt> {{-- Label disesuaikan --}}
                            <dd class="col-sm-8" style="white-space: pre-wrap;">{{ $feedback->handler_description ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>

                {{-- Card: Lainnya (Timestamps) --}}
                <div class="card nested-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Lainnya</h6>
                    </div>
                    <div class="card-body">
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-4">Dibuat Pada</dt>
                            <dd class="col-sm-8">{{ optional($feedback->created_at)->format('d M Y H:i:s') ?? '-' }}</dd>
                            <dt class="col-sm-4">Diperbarui Pada</dt>
                            <dd class="col-sm-8">{{ optional($feedback->updated_at)->format('d M Y H:i:s') ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>

            </div> {{-- End Kolom Kanan --}}

            {{-- Bagian Deskripsi (Full width, dalam card sendiri) --}}
            <div class="col-12 mt-0">
                <div class="card nested-card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Deskripsi Keluhan</h6>
                    </div>
                    <div class="card-body">
                        <p style="white-space: pre-wrap; margin-bottom: 0;">{{ $feedback->deskripsi ?? '-' }}</p>
                    </div>
                </div>
            </div>

        </div> {{-- End row --}}
    </div> {{-- Akhir dari scrollable-card-body --}}

    <div class="card-footer bg-light">
        <a href="{{ route('feedback.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
        {{-- Tombol Hapus --}}
        <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" class="float-end" style="display: inline-block; margin-left: 5px;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash me-1"></i> Hapus Data
            </button>
        </form>
    </div>
</div>
@endsection