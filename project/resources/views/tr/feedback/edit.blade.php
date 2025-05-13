{{-- resources/views/tr/feedback/edit.blade.php --}}

@extends('layouts.app')

{{-- Sesuaikan section agar konsisten --}}
@section('subtitle', __('Edit Feedback & Response'))
@section('content_header_title', __('Edit Feedback'))
@section('sub_breadcumb')
<a href="{{ route('feedback.index') }}">{{ __('Feedback & Response') }}</a>
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
                {{-- ====================================== --}}
                {{-- KOLOM KIRI --}}
                {{-- ====================================== --}}
                <div class="col-md-6">

                    {{-- Grup: Informasi Program & Registrasi --}}
                    <fieldset class="mb-4 border p-3 rounded">
                        <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Program & Registrasi</legend>

                        <div class="mb-3">
                            <label for="edit_kode_program_display" class="form-label">Kode Program <span class="text-info">(Klik untuk memilih/mengubah)</span></label>
                            <input type="text" class="form-control @error('program_id') is-invalid @enderror" id="edit_kode_program_display" placeholder="Pilih Kode Program..." readonly data-bs-toggle="modal" data-bs-target="#programSelectionModal" style="cursor: pointer;" value="{{ old('kode_program_display', $feedback->Program?->kode) }}">
                            <input type="hidden" id="edit_program_id" name="program_id" value="{{ old('program_id', $feedback->program_id) }}">
                            @error('program_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_program_display" class="form-label">Nama Program</label>
                            <input type="text" class="form-control" id="edit_nama_program_display" placeholder="Nama program akan terisi otomatis" readonly value="{{ old('nama_program_display', $feedback->Program?->nama) }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_registrasi" class="form-label">Tanggal Registrasi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_registrasi') is-invalid @enderror" id="edit_tanggal_registrasi" name="tanggal_registrasi" value="{{ old('tanggal_registrasi', optional($feedback->tanggal_registrasi)->format('Y-m-d')) }}" required>
                            @error('tanggal_registrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </fieldset>

                    {{-- Grup: Informasi Penerima Manfaat --}}
                    <fieldset class="mb-4 border p-3 rounded">
                        <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Penerima Manfaat</legend>

                        <div class="mb-3">
                            <label for="edit_penerima" class="form-label">Nama Penerima</label>
                            <input type="text" class="form-control @error('penerima') is-invalid @enderror" id="edit_penerima" name="penerima" value="{{ old('penerima', $feedback->penerima) }}">
                            @error('penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_umur" class="form-label">Umur</label>
                                    <input type="number" class="form-control @error('umur') is-invalid @enderror" id="edit_umur" name="umur" value="{{ old('umur', $feedback->umur) }}" min="0">
                                    @error('umur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_age_group" class="form-label">Kelompok Usia</label>
                                    <input type="text" class="form-control @error('age_group') is-invalid @enderror" id="edit_age_group" name="age_group" value="{{ old('age_group', $feedback->age_group) }}" readonly>
                                    @error('age_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                         </div>
                         <div class="mb-3">
                            <label for="edit_sex" class="form-label">Jenis Kelamin</label>
                            <select class="form-select @error('sex') is-invalid @enderror" id="edit_sex" name="sex">
                                <option value="" {{ old('sex', $feedback->sex) == null ? 'selected' : '' }} >-- Pilih --</option>
                                <option value="Laki-laki" {{ old('sex', $feedback->sex) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('sex', $feedback->sex) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                <option value="Lainnya" {{ old('sex', $feedback->sex) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('sex') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <div class="mb-3">
                            <label for="edit_position" class="form-label">Posisi Penerima</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" id="edit_position" name="position" value="{{ old('position', $feedback->position) }}">
                            @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    </fieldset>

                </div> {{-- End Kolom Kiri --}}

                {{-- ====================================== --}}
                {{-- KOLOM KANAN --}}
                {{-- ====================================== --}}
                <div class="col-md-6">

                    {{-- Grup: Informasi Pelapor --}}
                    <fieldset class="mb-4 border p-3 rounded">
                        <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Pelapor</legend>

                        <div class="mb-3">
                            <label for="nama_pelapor" class="form-label">Nama Pelapor</label>
                            <input type="text" class="form-control @error('nama_pelapor') is-invalid @enderror" id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor', $feedback->nama_pelapor) }}">
                            @error('nama_pelapor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone_number" class="form-label">Phone Number (Pelapor)</label>
                            <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="edit_phone_number" name="phone_number" value="{{ old('phone_number', $feedback->phone_number) }}">
                            @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </fieldset>

                    {{-- Grup: Detail Keluhan / Feedback --}}
                    <fieldset class="mb-4 border p-3 rounded">
                        <legend class="w-auto px-2 h6 fw-bold text-primary">Detail Keluhan / Feedback</legend>

                         <div class="mb-3">
                            <label for="edit_sort_of_complaint" class="form-label">Jenis Keluhan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sort_of_complaint') is-invalid @enderror" id="edit_sort_of_complaint" name="sort_of_complaint" value="{{ old('sort_of_complaint', $feedback->sort_of_complaint) }}" required>
                            @error('sort_of_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         </div>
                         <div class="mb-3">
                            <label for="edit_kategori_komplain" class="form-label">Kategori Komplain</label>
                            <input type="text" class="form-control @error('kategori_komplain') is-invalid @enderror" id="edit_kategori_komplain" name="kategori_komplain" value="{{ old('kategori_komplain', $feedback->kategori_komplain) }}">
                            @error('kategori_komplain') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    </fieldset>

                     {{-- Grup: Informasi Penanganan (Handler) --}}
                    <fieldset class="mb-4 border p-3 rounded">
                         <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Penanganan (Handler)</legend>

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
                            <label for="edit_tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="edit_tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', optional($feedback->tanggal_selesai)->format('Y-m-d')) }}">
                            @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         </div>
                    </fieldset>

                </div> {{-- End Kolom Kanan --}}
            </div> {{-- End row g-3 --}}

            <div class="card-footer mt-4 bg-light border-top">
                <a href="{{ route('feedback.index') }}" class="btn btn-secondary">
                     <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary float-end">
                     <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div> {{-- End card-body --}}
</div> {{-- End card --}}

{{-- =========================================== --}}
{{-- MODAL UNTUK PEMILIHAN PROGRAM (Tetap sama) --}}
{{-- =========================================== --}}
<div class="modal fade" id="programSelectionModal" tabindex="-1" aria-labelledby="programSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header"> {{-- Lebih baik tanpa bg-danger di edit? Atau sesuaikan warna --}}
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
<script>
$(document).ready(function() {
    // --- Script untuk Otomatisasi Kelompok Usia ---
    // Fungsi untuk menghitung kelompok usia
    function calculateAgeGroup(age) {
        if (age === null || age === '' || isNaN(age) || age < 0) {
            return ''; // Kosongkan jika umur tidak valid atau kosong
        } else if (age >= 0 && age <= 17) {
            return '0 - 17';
        } else if (age >= 18 && age <= 24) {
            return '18 - 24';
        } else if (age >= 25 && age <= 59) {
            return '25 - 59';
        } else if (age >= 60) {
            return '>60';
        } else {
            return ''; // Default jika tidak masuk kategori
        }
    }

    // Ambil elemen input umur dan kelompok usia (gunakan ID untuk form edit)
    const umurInput = $('#edit_umur'); // <-- Ganti ID
    const ageGroupInput = $('#edit_age_group'); // <-- Ganti ID

    // Event listener saat input umur berubah
    umurInput.on('input', function() {
        const umur = parseInt($(this).val(), 10);
        const kelompokUsia = calculateAgeGroup(umur);
        ageGroupInput.val(kelompokUsia);
    });

    // Panggil sekali saat halaman load untuk menghitung dari data yang ada / old value
    const initialUmur = parseInt(umurInput.val(), 10);
    if (!isNaN(initialUmur)) {
        ageGroupInput.val(calculateAgeGroup(initialUmur));
    }
    // --- Akhir Script Kelompok Usia ---


    // --- Script untuk Modal Program (sudah ada sebelumnya, pastikan ID sesuai) ---
    let programDataTable = null;

    // Trigger modal dari input Kode Program (gunakan ID untuk edit)
    $('#edit_kode_program_display').on('click', function(e) { // <-- Gunakan ID #edit_kode_program_display
        e.preventDefault();
        openProgramModalAndInitDataTable();
    });

    function openProgramModalAndInitDataTable() {
        // Cek jika DataTable sudah ada sebelum init ulang
        if (!$.fn.DataTable.isDataTable('#programListTable')) {
             programDataTable = $('#programListTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('api.beneficiary.program') }}", // Pastikan route API benar
                    type: "GET"
                },
                columns: [
                    { data: 'kode', name: 'kode', width: '20%' },
                    { data: 'nama', name: 'nama' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%', className: 'text-center'}
                ],
                language: {
                     // Opsi bahasa Indonesia
                     "sEmptyTable":     "Tidak ada data yang tersedia pada tabel ini",
                     "sProcessing":   "Sedang memproses...",
                     "sLengthMenu":   "Tampilkan _MENU_ entri",
                     "sZeroRecords":  "Tidak ditemukan data yang sesuai",
                     "sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                     "sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 entri",
                     "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
                     "sInfoPostFix":  "",
                     "sSearch":       "Cari:",
                     "sUrl":          "",
                     "oPaginate": {
                         "sFirst":    "Pertama",
                         "sPrevious": "Sebelumnya",
                         "sNext":     "Selanjutnya",
                         "sLast":     "Terakhir"
                     }
                },
                lengthMenu: [5, 10, 25, 50, 100],
                pagingType: 'full_numbers',
                // bDestroy: true // Tidak perlu jika pakai isDataTable check
            });
        }
        $('#programSelectionModal').modal('show');
    }

    // Opsional: Hancurkan DataTable saat modal ditutup
    // $('#programSelectionModal').on('hidden.bs.modal', function (e) {
    //     if (programDataTable) {
    //         programDataTable.destroy();
    //         $('#programListTable tbody').empty();
    //         programDataTable = null;
    //     }
    // });

    // Handler Tombol Pilih (mengisi input di form edit)
    $('#programListTable tbody').on('click', '.select-program', function() {
        const programId   = $(this).data('id');
        const programKode = $(this).data('kode');
        const programNama = $(this).data('nama');

        // Isi input di form EDIT (gunakan ID edit)
        $('#edit_kode_program_display').val(programKode); // <-- ID Edit
        $('#edit_nama_program_display').val(programNama); // <-- ID Edit
        $('#edit_program_id').val(programId);             // <-- ID Edit

        $('#programSelectionModal').modal('hide');
    });
    // --- Akhir Script Modal Program ---
});
</script>
@endpush