@extends('layouts.app') {{-- Menggunakan layout utama --}}

@section('title', 'Daftar Feedback & Response') {{-- Judul Halaman --}}

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Feedback & Response Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('feedback.index') }}">MEALS</a></li>
        <li class="breadcrumb-item active">Feedback & Response</li>
    </ol>

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
    <div class="card-header d-flex align-items-center"> {{-- Hapus justify-content-between --}}
    <span><i class="fas fa-table me-1"></i> Daftar Feedback</span>

    <div class="ms-auto"> {{-- Tambahkan ms-auto di sini --}}
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFeedbackModal">
            <i class="fas fa-plus me-1"></i> Tambah FRM
        </button>
        <button class="btn btn-secondary btn-sm ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
            <i class="fas fa-filter me-1"></i> Filter Program
        </button>
    </div>
</div>
         {{-- Area Filter (Collapse) --}}
         <div class="collapse" id="filterCollapse">
             <div class="card-body bg-light">
                 <form action="{{ route('feedback.index') }}" method="GET">
                     <div class="row g-2">
                         <div class="col-md-4">
                             <input type="text" name="program" class="form-control form-control-sm" placeholder="Filter berdasarkan Program..." value="{{ request('program') }}">
                         </div>
                         <div class="col-md-auto">
                             <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                             <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                         </div>
                     </div>
                 </form>
             </div>
         </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Program</th>
                            <th>Tgl Registrasi</th>
                            <th>Jenis Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($feedbackItems as $index => $item)
                            <tr>
                                <td>{{ $feedbackItems->firstItem() + $index }}</td> {{-- Penomoran untuk pagination --}}
                                <td>{{ $item->program ?? '-' }}</td>
                                <td>{{ $item->tanggal_registrasi->format('d M Y') }}</td> {{-- Format tanggal --}}
                                <td>{{ $item->sort_of_complaint }}</td>
                                <td>
                                    <span class="badge
                                        @if($item->status_complaint == 'Baru') bg-info
                                        @elseif($item->status_complaint == 'Diproses') bg-warning text-dark
                                        @elseif($item->status_complaint == 'Selesai') bg-success
                                        @elseif($item->status_complaint == 'Ditolak') bg-danger
                                        @else bg-secondary @endif">
                                        {{ $item->status_complaint }}
                                    </span>
                                </td>
                                <td>
                                    {{-- Tombol View (arah ke halaman show) --}}
                                    <a href="{{ route('feedback.show', $item->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i> View
                                    </a>

                                    {{-- Tombol Edit (arah ke halaman edit) --}}
                                    <a href="{{ route('feedback.edit', $item->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    {{-- Tombol Hapus (menggunakan form) --}}
                                    <form action="{{ route('feedback.destroy', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data feedback.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             {{-- Pagination Links --}}
             <div class="d-flex justify-content-center">
                  {{ $feedbackItems->appends(request()->query())->links() }}  {{-- Tampilkan link pagination & pertahankan query filter --}}
             </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Feedback --}}
@include('tr.feedback._add_modal')

@endsection

@push('scripts')
{{-- Tambahkan Font Awesome jika belum ada di layout utama (atau gunakan ikon Bootstrap) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
{{-- Script JS spesifik untuk halaman ini jika diperlukan --}}
@endpush