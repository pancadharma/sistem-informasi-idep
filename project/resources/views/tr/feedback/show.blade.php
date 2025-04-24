@extends('layouts.app')

@section('title', 'Detail Feedback & Response')

@push('styles')
{{-- Tambahkan style khusus untuk halaman ini --}}
<style>
    .scrollable-card-body {
        max-height: 65vh; /* Atur tinggi maksimal card body, misal 65% dari tinggi viewport */
        overflow-y: auto; /* Tampilkan scrollbar vertikal jika konten melebihi max-height */
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Feedback & Response</h1>
     <ol class="breadcrumb mb-4">
         <li class="breadcrumb-item"><a href="{{ route('feedback.index') }}">MEAL</a></li>
         <li class="breadcrumb-item"><a href="{{ route('feedback.index') }}">Feedback & Response</a></li>
         <li class="breadcrumb-item active">Detail FRM</li>
     </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Detail Feedback ID: {{ $feedback->id }}</span>
            <a href="{{ route('feedback.edit', $feedback->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Edit Data
            </a>
        </div>
        {{-- Tambahkan class scrollable-card-body di sini --}}
        <div class="card-body scrollable-card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                         <dt class="col-sm-4">Program</dt>
                         <dd class="col-sm-8">{{ $feedback->program ?? '-' }}</dd>

                         <dt class="col-sm-4">Tgl Registrasi</dt>
                         <dd class="col-sm-8">{{ $feedback->tanggal_registrasi->format('d F Y') }}</dd>

                         <dt class="col-sm-4">Umur</dt>
                         <dd class="col-sm-8">{{ $feedback->umur ?? '-' }}</dd>

                         <dt class="col-sm-4">Penerima</dt>
                         <dd class="col-sm-8">{{ $feedback->penerima ?? '-' }}</dd>

                         <dt class="col-sm-4">Jenis Keluhan</dt>
                         <dd class="col-sm-8">{{ $feedback->sort_of_complaint }}</dd>

                         <dt class="col-sm-4">Kelompok Usia</dt>
                         <dd class="col-sm-8">{{ $feedback->age_group ?? '-' }}</dd>

                         <dt class="col-sm-4">Posisi Penerima</dt>
                         <dd class="col-sm-8">{{ $feedback->position ?? '-' }}</dd>

                         <dt class="col-sm-4">Tgl Selesai</dt>
                         <dd class="col-sm-8">{{ $feedback->tanggal_selesai ? $feedback->tanggal_selesai->format('d F Y') : '-' }}</dd>

                         <dt class="col-sm-4">Jenis Kelamin</dt>
                         <dd class="col-sm-8">{{ $feedback->sex ?? '-' }}</dd>

                         <dt class="col-sm-4">Kontak Penerima</dt>
                         <dd class="col-sm-8">{{ $feedback->kontak_penerima ?? '-' }}</dd>

                          <dt class="col-sm-4">Alamat</dt>
                          <dd class="col-sm-8">{{ $feedback->address ?? '-' }}</dd>
                     </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
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
                            <span class="badge
                                @if($feedback->status_complaint == 'Baru') bg-info
                                @elseif($feedback->status_complaint == 'Diproses') bg-warning text-dark
                                @elseif($feedback->status_complaint == 'Selesai') bg-success
                                @elseif($feedback->status_complaint == 'Ditolak') bg-danger
                                @else bg-secondary @endif">
                                {{ $feedback->status_complaint }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Dibuat Pada</dt>
                        <dd class="col-sm-8">{{ $feedback->created_at->format('d F Y H:i:s') }}</dd>

                        <dt class="col-sm-4">Diperbarui Pada</dt>
                        <dd class="col-sm-8">{{ $feedback->updated_at->format('d F Y H:i:s') }}</dd>
                    </dl>
                </div>
                <div class="col-12 mt-3">
                     <h5>Deskripsi Keluhan:</h5>
                     <p style="white-space: pre-wrap;">{{ $feedback->deskripsi }}</p> {{-- pre-wrap menjaga format spasi dan baris baru --}}
                </div>
            </div>
        </div> {{-- Akhir dari scrollable-card-body --}}

        {{-- Bagian ini akan tetap di luar area scroll --}}
        <div class="card-footer bg-light"> {{-- Menambahkan card-footer untuk konsistensi --}}
            <div class="mt-0"> {{-- Hapus margin-top jika sudah di footer --}}
                <a href="{{ route('feedback.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
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
    </div>
</div>
@endsection

@push('scripts')
 {{-- Tambahkan Font Awesome jika belum ada di layout utama --}}
 <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
 @endpush