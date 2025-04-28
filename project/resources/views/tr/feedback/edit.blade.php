{{-- resources/views/tr/feedback/edit.blade.php --}}

@extends('layouts.app')

{{-- Sesuaikan section agar konsisten --}}
@section('subtitle', __('Edit Feedback & Response'))
@section('content_header_title', __('Edit Feedback'))
@section('sub_breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('feedback.index') }}">{{ __('Feedback & Response') }}</a></li>
    <li class="breadcrumb-item active">{{ __('Edit FRM') }}</li>
@endsection

{{-- Menggunakan @section('content_body') agar konsisten --}}
@section('content_body')
<div class="card card-outline card-warning mb-4"> {{-- Card warna warning untuk edit --}}
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit me-1"></i>
            Edit Data Feedback ID: {{ $feedback->id }}
        </h3>
    </div>
    <div class="card-body">
         {{-- Tampilkan error validasi global --}}
         @if ($errors->any())
             <div class="alert alert-danger alert-dismissible">
                 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 <h5><i class="icon fas fa-ban"></i> Error!</h5>
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div>
         @endif

        <form action="{{ route('feedback.update', $feedback->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Method spoofing untuk request PUT/PATCH --}}

            <div class="row g-3">
                {{-- Kolom Kiri --}}
                <div class="col-md-6">

                    {{-- =========================================== --}}
                    {{-- BAGIAN INPUT PROGRAM YANG DISESUAIKAN --}}
                    {{-- =========================================== --}}
                    {{-- Input untuk Kode Program (Pemicu Modal & Display) --}}
                    <div class="mb-3">
                        <label for="edit_kode_program_display" class="form-label">Kode Program <span class="text-info">(Klik untuk memilih/mengubah)</span></label>
                        <input type="text"
                               class="form-control"
                               id="edit_kode_program_display"
                               {{-- Isi value dengan kode program dari relasi --}}
                               value="{{ old('kode_program_display', $feedback->Program?->kode) }}"
                               placeholder="Pilih Kode Program..."
                               readonly
                               data-bs-toggle="modal"
                               data-bs-target="#programSelectionModal"
                               style="cursor: pointer;">
                         {{-- Error untuk program_id --}}
                         @error('program_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Input untuk Nama Program (Hanya Display) --}}
                    <div class="mb-3">
                        <label for="edit_nama_program_display" class="form-label">Nama Program</label>
                        <input type="text"
                               class="form-control"
                               id="edit_nama_program_display"
                               {{-- Isi value dengan nama program dari relasi --}}
                               value="{{ old('nama_program_display', $feedback->Program?->nama) }}"
                               placeholder="Nama program akan terisi otomatis"
                               readonly>
                    </div>

                    {{-- Input Hidden untuk Menyimpan ID Program ke DB --}}
                    {{-- Isi value dengan ID program yang sudah ada --}}
                    <input type="hidden" id="edit_program_id" name="program_id" value="{{ old('program_id', $feedback->program_id) }}">
                    {{-- =========================================== --}}
                    {{-- AKHIR BAGIAN INPUT PROGRAM --}}
                    {{-- =========================================== --}}


                    <div class="mb-3">
                         <label for="edit_tanggal_registrasi" class="form-label">Tanggal Registrasi <span class="text-danger">*</span></label>
                         {{-- Format tanggal Y-m-d untuk input type date --}}
                         <input type="date" class="form-control @error('tanggal_registrasi') is-invalid @enderror" id="edit_tanggal_registrasi" name="tanggal_registrasi" value="{{ old('tanggal_registrasi', $feedback->tanggal_registrasi ? $feedback->tanggal_registrasi->format('Y-m-d') : '') }}" required>
                         @error('tanggal_registrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_umur" class="form-label">Umur</label>
                         <input type="number" class="form-control @error('umur') is-invalid @enderror" id="edit_umur" name="umur" value="{{ old('umur', $feedback->umur) }}">
                         @error('umur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="mb-3">
                         <label for="edit_penerima" class="form-label">Penerima</label>
                         <input type="text" class="form-control @error('penerima') is-invalid @enderror" id="edit_penerima" name="penerima" value="{{ old('penerima', $feedback->penerima) }}">
                         @error('penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                     <div class="mb-3">
                         <label for="edit_sort_of_complaint" class="form-label">Jenis Keluhan <span class="text-danger">*</span></label>
                         <input type="text" class="form-control @error('sort_of_complaint') is-invalid @enderror" id="edit_sort_of_complaint" name="sort_of_complaint" value="{{ old('sort_of_complaint', $feedback->sort_of_complaint) }}" required>
                         @error('sort_of_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                     </div>
                    <div class="mb-3">
                         <label for="edit_age_group" class="form-label">Kelompok Usia</label>
                         <input type="text" class="form-control @error('age_group') is-invalid @enderror" id="edit_age_group" name="age_group" value="{{ old('age_group', $feedback->age_group) }}">
                         @error('age_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_position" class="form-label">Posisi Penerima</label>
                         <input type="text" class="form-control @error('position') is-invalid @enderror" id="edit_position" name="position" value="{{ old('position', $feedback->position) }}">
                         @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_tanggal_selesai" class="form-label">Tanggal Selesai</label>
                         <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="edit_tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $feedback->tanggal_selesai ? $feedback->tanggal_selesai->format('Y-m-d') : '') }}">
                         @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_sex" class="form-label">Jenis Kelamin</label>
                         <select class="form-select @error('sex') is-invalid @enderror" id="edit_sex" name="sex">
                             <option value="" {{ old('sex', $feedback->sex) == null ? 'selected' : '' }} >-- Pilih --</option> {{-- Handle null case --}}
                             <option value="Laki-laki" {{ old('sex', $feedback->sex) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                             <option value="Perempuan" {{ old('sex', $feedback->sex) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                             <option value="Lainnya" {{ old('sex', $feedback->sex) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                         </select>
                         @error('sex') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                   <div class="mb-3">
                         <label for="edit_kontak_penerima" class="form-label">Kontak Penerima</label>
                         <input type="text" class="form-control @error('kontak_penerima') is-invalid @enderror" id="edit_kontak_penerima" name="kontak_penerima" value="{{ old('kontak_penerima', $feedback->kontak_penerima) }}">
                         @error('kontak_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                   <div class="mb-3">
                         <label for="edit_address" class="form-label">Alamat</label>
                         <textarea class="form-control @error('address') is-invalid @enderror" id="edit_address" name="address" rows="3">{{ old('address', $feedback->address) }}</textarea>
                         @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                </div>

                {{-- Kolom Kanan --}}
                <div class="col-md-6">
                    <div class="mb-3">
                         <label for="edit_phone_number" class="form-label">Phone Number (Pelapor)</label>
                         <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="edit_phone_number" name="phone_number" value="{{ old('phone_number', $feedback->phone_number) }}">
                         @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_channels" class="form-label">Channel Pengaduan</label>
                         <input type="text" class="form-control @error('channels') is-invalid @enderror" id="edit_channels" name="channels" value="{{ old('channels', $feedback->channels) }}">
                         @error('channels') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_other_channel" class="form-label">Channel Lain</label>
                         <input type="text" class="form-control @error('other_channel') is-invalid @enderror" id="edit_other_channel" name="other_channel" value="{{ old('other_channel', $feedback->other_channel) }}">
                         @error('other_channel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_kategori_komplain" class="form-label">Kategori Komplain</label>
                         <input type="text" class="form-control @error('kategori_komplain') is-invalid @enderror" id="edit_kategori_komplain" name="kategori_komplain" value="{{ old('kategori_komplain', $feedback->kategori_komplain) }}">
                         @error('kategori_komplain') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_handler" class="form-label">Handler (Petugas)</label>
                         <input type="text" class="form-control @error('handler') is-invalid @enderror" id="edit_handler" name="handler" value="{{ old('handler', $feedback->handler) }}">
                         @error('handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_position_handler" class="form-label">Posisi Handler</label>
                         <input type="text" class="form-control @error('position_handler') is-invalid @enderror" id="edit_position_handler" name="position_handler" value="{{ old('position_handler', $feedback->position_handler) }}">
                         @error('position_handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_kontak_handler" class="form-label">Kontak Handler</label>
                         <input type="text" class="form-control @error('kontak_handler') is-invalid @enderror" id="edit_kontak_handler" name="kontak_handler" value="{{ old('kontak_handler', $feedback->kontak_handler) }}">
                         @error('kontak_handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_deskripsi" class="form-label">Deskripsi Keluhan <span class="text-danger">*</span></label>
                         <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="edit_deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $feedback->deskripsi) }}</textarea>
                         @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                         <label for="edit_status_complaint" class="form-label">Status <span class="text-danger">*</span></label>
                         <select class="form-select @error('status_complaint') is-invalid @enderror" id="edit_status_complaint" name="status_complaint" required>
                             <option value="Baru" {{ old('status_complaint', $feedback->status_complaint) == 'Baru' ? 'selected' : '' }}>Baru</option>
                             <option value="Diproses" {{ old('status_complaint', $feedback->status_complaint) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                             <option value="Selesai" {{ old('status_complaint', $feedback->status_complaint) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                             <option value="Ditolak" {{ old('status_complaint', $feedback->status_complaint) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                         </select>
                         @error('status_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer mt-4 bg-light border-top"> {{-- Footer di dalam card-body atau terpisah --}}
                <a href="{{ route('feedback.index') }}" class="btn btn-secondary">
                     <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary float-end"> {{-- Tombol simpan di kanan --}}
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div> {{-- End card-body --}}
</div> {{-- End card --}}

{{-- =========================================== --}}
{{-- MODAL UNTUK PEMILIHAN PROGRAM (Sama seperti di create.blade.php) --}}
{{-- =========================================== --}}
<div class="modal fade" id="programSelectionModal" tabindex="-1" aria-labelledby="programSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="programSelectionModalLabel">Pilih Program</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover table-bordered table-striped" id="programListTable" style="width:100%;">
                    <thead>
                        <tr>
                            <th>Kode Program</th>
                            <th>Nama Program</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables akan mengisi bagian ini --}}
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
{{-- =========================================== --}}
{{-- AKHIR MODAL --}}
{{-- =========================================== --}}

@endsection

@push('js')
{{-- Include JavaScript yang sama persis seperti di create.blade.php --}}
{{-- Pastikan ID input di JS cocok dengan ID input di HTML edit ini --}}
<script>
$(document).ready(function() {
    let programDataTable = null;

    // Trigger modal dari input Kode Program (gunakan ID untuk edit)
    $('#edit_kode_program_display').on('click', function(e) { // <-- Gunakan ID #edit_kode_program_display
        e.preventDefault();
        openProgramModalAndInitDataTable();
    });

    function openProgramModalAndInitDataTable() {
        setTimeout(() => {
            if (!programDataTable) {
                programDataTable = $('#programListTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "{{ route('api.beneficiary.program') }}", // API Tim Anda
                        type: "GET"
                    },
                    columns: [
                        { data: 'kode', name: 'kode', width: '20%' },
                        { data: 'nama', name: 'nama' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%', className: 'text-center'}
                    ],
                    language: { /* ... opsi bahasa ... */ },
                    lengthMenu: [5, 10, 25, 50, 100],
                    pagingType: 'full_numbers',
                    bDestroy: true
                });
            }
        }, 50);
        $('#programSelectionModal').modal('show');
    }

    $('#programSelectionModal').on('hidden.bs.modal', function (e) {
        if (programDataTable) {
            programDataTable.destroy();
            programDataTable = null;
        }
    });

    // Handler Tombol Pilih (mengisi input di form edit)
    $('#programListTable tbody').on('click', '.select-program', function() {
        const programId   = $(this).data('id');
        const programKode = $(this).data('kode');
        const programNama = $(this).data('nama');

        // ==================================================
        // Isi input di form EDIT
        // ==================================================
        $('#edit_kode_program_display').val(programKode); // <-- Gunakan ID #edit_kode_program_display
        $('#edit_nama_program_display').val(programNama); // <-- Gunakan ID #edit_nama_program_display
        $('#edit_program_id').val(programId);             // <-- Gunakan ID #edit_program_id
        // ==================================================

        $('#programSelectionModal').modal('hide');
    });
});
</script>
@endpush
