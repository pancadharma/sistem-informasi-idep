{{-- resources/views/tr/feedback/show.blade.php --}}

@extends('layouts.app')

{{-- Menggunakan @section('subtitle') agar konsisten dengan create.blade.php --}}
@section('subtitle', __('Detail Feedback & Response'))
{{-- Menggunakan @section('content_header_title') jika layout Anda mendukungnya --}}
@section('content_header_title', __('Detail Feedback'))
{{-- Menggunakan @section('sub_breadcumb') agar konsisten --}}
@section('sub_breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('feedback.index') }}">{{ __('Feedback & Response') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Detail FRM') }}</li>
@endsection


@push('styles')
{{-- Tambahkan style khusus untuk halaman ini --}}
<style>
    .scrollable-card-body {
        max-height: 65vh; /* Atur tinggi maksimal card body, misal 65% dari tinggi viewport */
        overflow-y: auto; /* Tampilkan scrollbar vertikal jika konten melebihi max-height */
    }
    /* Style untuk definition list agar lebih rapi */
    .dl-horizontal dt {
        white-space: normal; /* Allow text wrapping for long labels */
        text-align: left; /* Align labels to the left */
        margin-bottom: 5px; /* Add some space below label */
    }
    .dl-horizontal dd {
        margin-left: 0; /* Remove default indentation */
        margin-bottom: 10px; /* Add space below value */
        word-wrap: break-word; /* Wrap long values */
    }
    @media (min-width: 768px) { /* Apply grid layout on medium screens and up */
        .dl-horizontal dt {
            float: left;
            width: 160px; /* Adjust width as needed */
            clear: left;
            text-align: right; /* Align labels to the right on larger screens */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-bottom: 0; /* Reset margin for grid layout */
        }
        .dl-horizontal dd {
            margin-left: 180px; /* Adjust margin based on dt width + padding */
            margin-bottom: 10px; /* Keep space below value */
        }
    }
</style>
@endpush

{{-- Menggunakan @section('content_body') agar konsisten --}}
@section('content_body')
<div class="card card-outline card-primary mb-4"> {{-- Menambahkan card-outline --}}
    <div class="card-header d-flex justify-content-between align-items-center">
        {{-- Menambahkan ikon pada judul --}}
        <h3 class="card-title mb-0">
            <i class="fas fa-info-circle me-1"></i>
            Detail Feedback ID: {{ $feedback->id }}
        </h3>
        <a href="{{ route('feedback.edit', $feedback->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit me-1"></i> Edit Data
        </a>
    </div>
    {{-- Tambahkan class scrollable-card-body di sini --}}
    <div class="card-body scrollable-card-body">
        <div class="row">
            <div class="col-md-6">
                {{-- Menggunakan class dl-horizontal untuk layout yang lebih baik --}}
                <dl class="dl-horizontal row">
                    {{-- =========================================== --}}
                    {{-- PERUBAHAN UNTUK MENAMPILKAN PROGRAM --}}
                    {{-- =========================================== --}}
                    <dt class="col-sm-4">Kode Program</dt>
                    {{-- Akses kode melalui relasi 'program'. Ganti 'kode' jika nama kolom di trprogram beda --}}
                    <dd class="col-sm-8">{{ $feedback->Program?->kode ?? '-' }}</dd>

                    <dt class="col-sm-4">Nama Program</dt>
                    {{-- Akses nama melalui relasi 'program'. Ganti 'nama' jika nama kolom di trprogram beda --}}
                    <dd class="col-sm-8">{{ $feedback->Program?->nama ?? '-' }}</dd>
                    {{-- =========================================== --}}

                    <dt class="col-sm-4">Tgl Registrasi</dt>
                    {{-- Format tanggal bisa disesuaikan --}}
                    <dd class="col-sm-8">{{ $feedback->tanggal_registrasi ? $feedback->tanggal_registrasi->format('d M Y') : '-' }}</dd>

                    <dt class="col-sm-4">Umur</dt>
                    <dd class="col-sm-8">{{ $feedback->umur ?? '-' }}</dd>

                    <dt class="col-sm-4">Penerima</dt>
                    <dd class="col-sm-8">{{ $feedback->penerima ?? '-' }}</dd>

                    <dt class="col-sm-4">Jenis Keluhan</dt>
                    <dd class="col-sm-8">{{ $feedback->sort_of_complaint ?? '-' }}</dd>

                    <dt class="col-sm-4">Kelompok Usia</dt>
                    <dd class="col-sm-8">{{ $feedback->age_group ?? '-' }}</dd>

                    <dt class="col-sm-4">Posisi Penerima</dt>
                    <dd class="col-sm-8">{{ $feedback->position ?? '-' }}</dd>

                    <dt class="col-sm-4">Tgl Selesai</dt>
                    <dd class="col-sm-8">{{ $feedback->tanggal_selesai ? $feedback->tanggal_selesai->format('d M Y') : '-' }}</dd>

                    <dt class="col-sm-4">Jenis Kelamin</dt>
                    <dd class="col-sm-8">{{ $feedback->sex ?? '-' }}</dd>

                    <dt class="col-sm-4">Kontak Penerima</dt>
                    <dd class="col-sm-8">{{ $feedback->kontak_penerima ?? '-' }}</dd>

                    <dt class="col-sm-4">Alamat</dt>
                    <dd class="col-sm-8">{{ $feedback->address ?? '-' }}</dd>
                 </dl>
            </div>
            <div class="col-md-6">
                <dl class="dl-horizontal row">
                    <dt class="col-sm-4">Phone Number</dt>
                    <dd class="col-sm-8">{{ $feedback->phone_number ?? '-' }}</dd>

                    <dt class="col-sm-4">Channel</dt>
                    <dd class="col-sm-8">{{ $feedback->channels ?? '-' }}</dd>

                    <dt class="col-sm-4">Channel Lain</dt>
                    <dd class="col-sm-8">{{ $feedback->other_channel ?? '-' }}</dd>

                    <dt class="col-sm-4">Kategori Komplain</dt>
                    <dd class="col-sm-8">{{ $feedback->kategori_komplain ?? '-' }}</dd>

                    <dt class="col-sm-4">Handler</dt>
                    <dd class="col-sm-8">{{ $feedback->handler ?? '-' }}</dd>

                    <dt class="col-sm-4">Posisi Handler</dt>
                    <dd class="col-sm-8">{{ $feedback->position_handler ?? '-' }}</dd>

                    <dt class="col-sm-4">Kontak Handler</dt>
                    <dd class="col-sm-8">{{ $feedback->kontak_handler ?? '-' }}</dd>

                    <dt class="col-sm-4">Status</dt>
                    <dd class="col-sm-8">
                        {{-- Logika badge status tetap sama --}}
                        <span class="badge
                            @if($feedback->status_complaint == 'Baru') bg-info
                            @elseif($feedback->status_complaint == 'Diproses') bg-warning text-dark
                            @elseif($feedback->status_complaint == 'Selesai') bg-success
                            @elseif($feedback->status_complaint == 'Ditolak') bg-danger
                            @else bg-secondary @endif">
                            {{ $feedback->status_complaint ?? 'N/A' }}
                        </span>
                    </dd>

                    <dt class="col-sm-4">Dibuat Pada</dt>
                    <dd class="col-sm-8">{{ $feedback->created_at ? $feedback->created_at->format('d M Y H:i:s') : '-' }}</dd>

                    <dt class="col-sm-4">Diperbarui Pada</dt>
                    <dd class="col-sm-8">{{ $feedback->updated_at ? $feedback->updated_at->format('d M Y H:i:s') : '-' }}</dd>
                </dl>
            </div>
            <div class="col-12 mt-3">
                 <h5>Deskripsi Keluhan:</h5>
                 {{-- Menggunakan nl2br untuk mengubah baris baru menjadi <br>, atau style pre-wrap --}}
                 <p style="white-space: pre-wrap;">{{ $feedback->deskripsi ?? '-' }}</p>
            </div>
        </div>
    </div> {{-- Akhir dari scrollable-card-body --}}

    <div class="card-footer bg-light">
        <a href="{{ route('feedback.index') }}" class="btn btn-secondary">
             <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
        </a>
         {{-- Tombol Hapus (jika ingin ada di halaman detail juga) --}}
         <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
             @csrf
             @method('DELETE')
             <button type="submit" class="btn btn-danger ms-2">
                 <i class="fas fa-trash me-1"></i> Hapus Data
             </button>
         </form>
    </div>
</div>
@endsection

{{-- Tidak perlu push scripts Font Awesome jika sudah ada di layout utama --}}
{{-- @push('scripts')
 <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
 @endpush --}}
