{{-- resources/views/tr/feedback/create.blade.php --}}

@extends('layouts.app')

{{-- Sesuaikan Judul dan Breadcrumb untuk halaman create --}}
@section('subtitle', __('Tambah Feedback & Response Baru'))
@section('content_header_title', __('Add New Feedback & Response'))
@section('sub_breadcumb')
    {{-- Link kembali ke halaman index --}}
    <a href="{{ route('feedback.index') }}">{{ __('Feedback & Response') }}</a>
    <li class="breadcrumb-item active">{{ __('Tambah Baru') }}</li>
@endsection

@section('content_body')
<div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('Formulir Tambah Feedback') }}</h3>
        </div>
        <form action="{{ route('feedback.store') }}" method="POST">
            @csrf
            <div class="card-body">
                {{-- Tampilkan error validasi global jika ada --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Terdapat kesalahan pada input Anda.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-3">
                    {{-- ====================================== --}}
                    {{-- KOLOM KIRI --}}
                    {{-- ====================================== --}}
                    <div class="col-md-6">

                        {{-- Grup: Informasi Program & Registrasi --}}
                        <fieldset class="mb-4 border p-3 rounded">
                            <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Program & Registrasi</legend>

                            <div class="mb-3">
                                <label for="add_kode_program_display" class="form-label">Kode Program <span class="text-info">(Klik untuk memilih)</span></label>
                                <input type="text" class="form-control @error('program_id') is-invalid @enderror" id="add_kode_program_display" placeholder="Pilih Kode Program..." readonly data-bs-toggle="modal" data-bs-target="#programSelectionModal" style="cursor: pointer;" value="{{ old('kode_program_display') }}">
                                <input type="hidden" id="add_program_id" name="program_id" value="{{ old('program_id') }}">
                                @error('program_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="add_nama_program_display" class="form-label">Nama Program</label>
                                <input type="text" class="form-control" id="add_nama_program_display" placeholder="Nama program akan terisi otomatis" readonly value="{{ old('nama_program_display') }}">
                            </div>
                            <div class="mb-3">
                                <label for="add_tanggal_registrasi" class="form-label">Tanggal Registrasi <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_registrasi') is-invalid @enderror" id="add_tanggal_registrasi" name="tanggal_registrasi" value="{{ old('tanggal_registrasi', date('Y-m-d')) }}" required>
                                @error('tanggal_registrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </fieldset>

                        {{-- Grup: Informasi Penerima Manfaat --}}
                        <fieldset class="mb-4 border p-3 rounded">
                            <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Penerima Manfaat</legend>

                            <div class="mb-3">
                                <label for="add_penerima" class="form-label">Nama Penerima</label>
                                <input type="text" class="form-control @error('penerima') is-invalid @enderror" id="add_penerima" name="penerima" value="{{ old('penerima') }}">
                                @error('penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="add_umur" class="form-label">Umur</label>
                                        <input type="number" class="form-control @error('umur') is-invalid @enderror" id="add_umur" name="umur" value="{{ old('umur') }}" min="0">
                                        @error('umur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="add_age_group" class="form-label">Kelompok Usia</label>
                                        <input type="text" class="form-control @error('age_group') is-invalid @enderror" id="add_age_group" name="age_group" value="{{ old('age_group') }}" readonly>
                                        @error('age_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                             </div>
                             <div class="mb-3">
                                <label for="add_sex" class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('sex') is-invalid @enderror" id="add_sex" name="sex">
                                    <option value="" {{ old('sex') == '' ? 'selected' : '' }}>-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('sex') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('sex') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    <option value="Lainnya" {{ old('sex') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('sex') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="mb-3">
                                <label for="add_position" class="form-label">Posisi Penerima</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" id="add_position" name="position" value="{{ old('position') }}">
                                @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_kontak_penerima" class="form-label">Kontak Penerima</label>
                                <input type="text" class="form-control @error('kontak_penerima') is-invalid @enderror" id="add_kontak_penerima" name="kontak_penerima" value="{{ old('kontak_penerima') }}">
                                @error('kontak_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_address" class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="add_address" name="address" rows="3">{{ old('address') }}</textarea>
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
                                <input type="text" class="form-control @error('nama_pelapor') is-invalid @enderror" id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor') }}">
                                @error('nama_pelapor') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="add_phone_number" class="form-label">Phone Number (Pelapor)</label>
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="add_phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </fieldset>

                        {{-- Grup: Detail Keluhan / Feedback --}}
                        <fieldset class="mb-4 border p-3 rounded">
                            <legend class="w-auto px-2 h6 fw-bold text-primary">Detail Keluhan / Feedback</legend>

                             <div class="mb-3">
                                <label for="add_sort_of_complaint" class="form-label">Jenis Keluhan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sort_of_complaint') is-invalid @enderror" id="add_sort_of_complaint" name="sort_of_complaint" value="{{ old('sort_of_complaint') }}" required>
                                @error('sort_of_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_kategori_komplain" class="form-label">Kategori Komplain</label>
                                <input type="text" class="form-control @error('kategori_komplain') is-invalid @enderror" id="add_kategori_komplain" name="kategori_komplain" value="{{ old('kategori_komplain') }}">
                                @error('kategori_komplain') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_channels" class="form-label">Channel Pengaduan</label>
                                <input type="text" class="form-control @error('channels') is-invalid @enderror" id="add_channels" name="channels" value="{{ old('channels') }}">
                                @error('channels') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="add_other_channel" class="form-label">Channel Lain</label>
                                <input type="text" class="form-control @error('other_channel') is-invalid @enderror" id="add_other_channel" name="other_channel" value="{{ old('other_channel') }}">
                                @error('other_channel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_deskripsi" class="form-label">Deskripsi Keluhan <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="add_deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_status_complaint" class="form-label">Status Awal</label>
                                <select class="form-select @error('status_complaint') is-invalid @enderror" id="add_status_complaint" name="status_complaint" >
                                    <option value="Baru" selected>Baru</option>
                                </select>
                                @error('status_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                        </fieldset>

                         {{-- Grup: Informasi Penanganan (Handler) --}}
                        <fieldset class="mb-4 border p-3 rounded">
                             <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Penanganan (Handler)</legend>

                             <div class="mb-3">
                                <label for="add_handler" class="form-label">Handler (Petugas)</label>
                                <input type="text" class="form-control @error('handler') is-invalid @enderror" id="add_handler" name="handler" value="{{ old('handler') }}">
                                @error('handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_position_handler" class="form-label">Posisi Handler</label>
                                <input type="text" class="form-control @error('position_handler') is-invalid @enderror" id="add_position_handler" name="position_handler" value="{{ old('position_handler') }}">
                                @error('position_handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_kontak_handler" class="form-label">Kontak Handler</label>
                                <input type="text" class="form-control @error('kontak_handler') is-invalid @enderror" id="add_kontak_handler" name="kontak_handler" value="{{ old('kontak_handler') }}">
                                @error('kontak_handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="mb-3">
                                <label for="add_tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="add_tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
                                @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                        </fieldset>

                    </div> {{-- End Kolom Kanan --}}
                </div> {{-- End row g-3 --}}
            </div> {{-- End card-body --}}
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                <a href="{{ route('feedback.index') }}" class="btn btn-secondary">{{ __('Batal') }}</a>
            </div>
        </form>

        {{-- =========================================== --}}
        {{-- MODAL UNTUK PEMILIHAN PROGRAM (Tetap sama) --}}
        {{-- =========================================== --}}
        <div class="modal fade" id="programSelectionModal" tabindex="-1" aria-labelledby="programSelectionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable ">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="programSelectionModalLabel">Daftar Program</h5>
                        <button type="button" class="btn bg-danger" data-bs-dismiss="modal" aria-label="Close" title="{{ __('global.close') }}">
                             <i class="fas fa-times text-dark"></i>
                        </button>
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
    </div> {{-- End card --}}
@endsection

@push('js')
<script>
$(document).ready(function() {
    // --- Script untuk Modal Program (sudah ada) ---
    let programDataTable = null;

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
            return ''; // Default jika tidak masuk kategori (seharusnya tidak terjadi jika umur >= 0)
        }
    }

    // Ambil elemen input umur dan kelompok usia
    const umurInput = $('#add_umur');
    const ageGroupInput = $('#add_age_group');

    // Event listener saat input umur berubah
    umurInput.on('input', function() {
        const umur = parseInt($(this).val(), 10); // Ambil nilai umur sebagai integer
        const kelompokUsia = calculateAgeGroup(umur); // Hitung kelompok usia
        ageGroupInput.val(kelompokUsia); // Set nilai input kelompok usia
    });

    // Panggil sekali saat halaman load untuk menghitung dari old value (jika ada)
    const initialUmur = parseInt(umurInput.val(), 10);
    if (!isNaN(initialUmur)) {
        ageGroupInput.val(calculateAgeGroup(initialUmur));
    }


    // --- Logika Modal Program ---
    $('#add_kode_program_display').on('click', function(e) {
        e.preventDefault();
        openProgramModalAndInitDataTable();
    });

    function openProgramModalAndInitDataTable() {
        // Cek apakah DataTable sudah diinisialisasi dalam modal ini
        // Tambahkan pengecekan agar tidak re-init jika modal hanya di-hide/show
        if (!$.fn.DataTable.isDataTable('#programListTable')) {
             programDataTable = $('#programListTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('api.beneficiary.program') }}", // Pastikan route ini benar
                    type: "GET"
                },
                columns: [
                    // Sesuaikan 'data:' & 'name:' dengan response API Anda
                    { data: 'kode', name: 'kode', width: '20%' },
                    { data: 'nama', name: 'nama' },
                    {
                        data: 'action', name: 'action', orderable: false,
                        searchable: false, width: '15%', className: 'text-center'
                    }
                ],
                language: {
                    // Opsi bahasa Indonesia bisa ditambahkan di sini
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
                // bDestroy: true // Hapus bDestroy karena kita cek dengan isDataTable
            });
        }
        // Tampilkan modal setelah DataTable siap (atau sudah ada)
        $('#programSelectionModal').modal('show');
    }

    // Optional: Hancurkan DataTable saat modal benar-benar ditutup (jika diperlukan untuk memory)
    // $('#programSelectionModal').on('hidden.bs.modal', function (e) {
    //     if (programDataTable) {
    //         programDataTable.destroy();
    //         $('#programListTable tbody').empty(); // Kosongkan tbody juga
    //         programDataTable = null;
    //     }
    // });

    // Modifikasi Handler Tombol Pilih
    $('#programListTable tbody').on('click', '.select-program', function() {
        // Pastikan data attributes (id, kode, nama) ada di tombol .select-program yang di-generate oleh API
        const programId   = $(this).data('id');
        const programKode = $(this).data('kode');
        const programNama = $(this).data('nama');

        // Isi input di form utama
        $('#add_kode_program_display').val(programKode); // Isi display Kode
        $('#add_nama_program_display').val(programNama); // Isi display Nama
        $('#add_program_id').val(programId);             // Isi hidden ID (YANG DISIMPAN)

        // Tutup modal
        $('#programSelectionModal').modal('hide');
    });

    // --- Akhir Logika Modal Program ---

    // Isi display program jika ada old value untuk program_id
    const oldProgramId = $('#add_program_id').val();
    const oldKodeDisplay = $('#add_kode_program_display').val(); // Ambil dari old value display jika ada
    const oldNamaDisplay = $('#add_nama_program_display').val();

    if (oldProgramId && (!oldKodeDisplay || !oldNamaDisplay)) {
        // Jika ada old program_id tapi display kosong (misal error validasi hanya pada field lain)
        // Kita perlu fetch data program berdasarkan oldProgramId untuk mengisi display
        // Ini memerlukan endpoint API baru atau modifikasi yang ada
        // Contoh (membutuhkan endpoint '/api/program/{id}'):
        /*
        $.ajax({
            url: '/api/program/' + oldProgramId, // Ganti dengan endpoint Anda
            type: 'GET',
            success: function(program) {
                if (program) {
                    $('#add_kode_program_display').val(program.kode);
                    $('#add_nama_program_display').val(program.nama);
                }
            },
            error: function() {
                console.error('Gagal mengambil detail program untuk old value.');
            }
        });
        */
       // Jika Anda sudah menyimpan old value untuk display, baris di atas tidak perlu
       // Pastikan input display di atas memiliki value="{{ old('kode_program_display') }}"
       // dan value="{{ old('nama_program_display') }}" dan controller mengembalikannya saat validasi gagal.
    }


});
</script>
@endpush