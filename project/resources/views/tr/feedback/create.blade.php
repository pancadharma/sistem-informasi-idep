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
        <form action="{{ route('feedback.store') }}" method="POST" id="createFeedbackForm">
            @csrf
            <div class="card-body">
                @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Peringatan!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
{{-- Blok untuk error validasi standar tetap ada --}}
<!-- @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Peringatan!</strong> Terdapat satu atau lebih kesalahan pada input Anda:
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif -->
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
                            <div class="mb-3">
                                <label for="field_office" class="form-label">Field Office</label>
                                <div class="select2-purple"> {{-- Jika menggunakan Select2 --}}
                                    <select class="form-select form-control select2 @error('field_office') is-invalid @enderror" id="field_office" name="field_office">
                                        <option value="" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == '' ? 'selected' : '' }}>-- Pilih Field Office --</option>
                                        <option value="Bali" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'Bali' ? 'selected' : '' }}>Bali</option>
                                        <option value="Bangka Belitung" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'Bangka Belitung' ? 'selected' : '' }}>Bangka Belitung</option>
                                        <option value="Jawa Timur" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                        <option value="Kalimantan Timur" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                                        <option value="Kalimantan Utara" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                                        <option value="NTT" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'NTT' ? 'selected' : '' }}>NTT</option>
                                        <option value="Papua Barat" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                                        <option value="Sulawesi Tengah" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                                        <option value="Yogyakarta" {{ old('field_office', isset($feedback) ? $feedback->field_office : '') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                                        {{-- Tambahkan opsi lain atau muat dari database --}}
                                    </select>
                                </div>
                                @error('field_office') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                                <div class="select2-purple">
                                    <select class="form-select form-control select2 @error('sex') is-invalid @enderror" id="add_sex" name="sex">
                                        <option value="" {{ old('sex') == '' ? 'selected' : '' }}>-- Pilih --</option>
                                        <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Boy" {{ old('sex') == 'Boy' ? 'selected' : '' }}>Boy</option>
                                        <option value="Girl" {{ old('sex') == 'Girl' ? 'selected' : '' }}>Girl</option>
                                        <option value="Unspecified" {{ old('sex') == 'Unspecified' ? 'selected' : '' }}>Unspecified</option>
                                    </select>
                                </div>
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
                             {{-- Channel Pengaduan --}}
                        <div class="mb-3">
                            <label for="add_channels" class="form-label">Channel Pengaduan</label>
                            <div class="select2-purple">
                                <select class="form-select form-control select2 @error('channels') is-invalid @enderror" id="add_channels" name="channels">
                                    <option value="" {{ old('channels') == '' ? 'selected' : '' }}>-- Pilih Channel --</option>
                                    <option value="Complaint Form" {{ old('channels') == 'Complaint Form' ? 'selected' : '' }}>Complaint Form</option>
                                    <option value="Complaint Box" {{ old('channels') == 'Complaint Box' ? 'selected' : '' }}>Complaint Box</option>
                                    <option value="Face to Face" {{ old('channels') == 'Face to Face' ? 'selected' : '' }}>Face to Face</option>
                                    <option value="Hotline" {{ old('channels') == 'Hotline' ? 'selected' : '' }}>Hotline</option>
                                    <option value="Help Desk" {{ old('channels') == 'Help Desk' ? 'selected' : '' }}>Help Desk</option>
                                    <option value="SMS" {{ old('channels') == 'SMS' ? 'selected' : '' }}>SMS</option>
                                    <option value="WhatsApp" {{ old('channels') == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                    <option value="Children Consultation" {{ old('channels') == 'Children Consultation' ? 'selected' : '' }}>Children Consultation</option>
                                    <option value="Local Agency" {{ old('channels') == 'Local Agency' ? 'selected' : '' }}>Local Agency</option>
                                    <option value="Others" {{ old('channels') == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                            @error('channels') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Channel Lain (Awalnya mungkin tersembunyi) --}}
                        <div class="mb-3" id="other_channel_group" style="display: none;"> {{-- Tambahkan ID dan style display none --}}
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
                                <label for="add_status_complaint" class="form-label">Status Complaint</label>
                                <div class="select2-purple">
                                    <select class="form-select form-control select2 @error('status_complaint') is-invalid @enderror" id="add_status_complaint" name="status_complaint">
                                        <option value="Process" {{ old('status_complaint') == 'Process' ? 'selected' : '' }}>Process</option>
                                        <option value="Resolved" {{ old('status_complaint') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                        {{-- Jika Anda ingin ada opsi "Pilih" sebagai default --}}
                                        {{-- <option value="" {{ old('status_complaint') == '' ? 'selected' : '' }}>-- Pilih Status --</option> --}}
                                    </select>
                                </div>
                                @error('status_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                            <label for="is_hidden" class="form-label">Status Tampilan</label>
                            <div class="select2-purple">
                                <select class="form-select form-control select2 @error('is_hidden') is-invalid @enderror" id="is_hidden" name="is_hidden">
                                    <option value="0" {{ old('is_hidden', isset($feedback) ? (int)$feedback->is_hidden : 0) == 0 ? 'selected' : '' }}>Tampilkan (Unhide)</option>
                                    <option value="1" {{ old('is_hidden', isset($feedback) ? (int)$feedback->is_hidden : 0) == 1 ? 'selected' : '' }}>Sembunyikan (Hide)</option>
                                </select>
                            </div>
                            @error('is_hidden') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        </fieldset>

                         {{-- Grup: Informasi Penanganan (Handler) --}}
                        <fieldset class="mb-4 border p-3 rounded">
                             <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Penanganan (Handler)</legend>

                               <div class="mb-3">
                                <label for="add_handler_id" class="form-label">Handler (Petugas)</label>
                                <div class="select2-purple">
                                    {{-- Menggunakan name="handler_id" --}}
                                    <select class="form-select form-control select2 @error('handler_id') is-invalid @enderror" 
                                            id="add_handler_id" name="handler_id"> 
                                        <option value="" data-position="">-- Pilih Handler --</option>
                                        @if(isset($handlers))
                                            @foreach($handlers as $user)
                                                <option value="{{ $user->id }}" 
                                                        {{ old('handler_id') == $user->id ? 'selected' : '' }}
                                                        data-position="{{ $user->jabatan?->nama ?? '' }}"> {{-- PASTIKAN 'nama' adalah nama kolom yang benar di tabel jabatan Anda --}}
                                                    {{ $user->nama }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('handler_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                              <div class="mb-3">
                        <label for="add_position_handler" class="form-label">Posisi Handler</label>
                        {{-- Dijadikan readonly karena akan diisi otomatis --}}
                        <input type="text" class="form-control @error('position_handler') is-invalid @enderror" 
                               id="add_position_handler" name="position_handler" 
                               value="{{ old('position_handler') }}" readonly> 
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
                             <div class="mb-3">
                            <label for="handler_description" class="form-label">Handler Description</label>
                            <textarea class="form-control @error('handler_description') is-invalid @enderror" id="handler_description" name="handler_description" rows="3">{{ old('handler_description', isset($feedback) ? $feedback->handler_description : '') }}</textarea>
                            @error('handler_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="{{ __('global.close') }}">
                            <span aria-hidden="true">&times;</span>
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
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
            return ''; 
        }
    }

    // Ambil elemen input umur dan kelompok usia
    const umurInput = $('#add_umur');
    const ageGroupInput = $('#add_age_group');

    // Event listener saat input umur berubah
    umurInput.on('input', function() {
        const umur = parseInt($(this).val(), 10); 
        const kelompokUsia = calculateAgeGroup(umur); 
        ageGroupInput.val(kelompokUsia); 
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
        if (!$.fn.DataTable.isDataTable('#programListTable')) {
            programDataTable = $('#programListTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('api.beneficiary.program') }}", 
                    type: "GET"
                },
                columns: [
                    { data: 'kode', name: 'kode', width: '20%' },
                    { data: 'nama', name: 'nama' },
                    {
                        data: 'action', name: 'action', orderable: false,
                        searchable: false, width: '15%', className: 'text-center'
                    }
                ],
                language: {
                    "sEmptyTable":     "Tidak ada data yang tersedia pada tabel ini",
                    "sProcessing":   "Sedang memproses...",
                    // ... (terjemahan DataTables lainnya) ...
                    "sSearch":       "Cari:",
                    "oPaginate": {
                        "sFirst":    "Pertama",
                        "sPrevious": "Sebelumnya",
                        "sNext":     "Selanjutnya",
                        "sLast":     "Terakhir"
                    }
                },
                lengthMenu: [5, 10, 25, 50, 100],
                pagingType: 'full_numbers',
            });
        }
        $('#programSelectionModal').modal('show');
    }

    $('#programListTable tbody').on('click', '.select-program', function() {
        const programId   = $(this).data('id');
        const programKode = $(this).data('kode');
        const programNama = $(this).data('nama');

        $('#add_kode_program_display').val(programKode);
        $('#add_nama_program_display').val(programNama);
        $('#add_program_id').val(programId);        

        $('#programSelectionModal').modal('hide');
    });
    // --- Akhir Logika Modal Program ---

    // Isi display program jika ada old value untuk program_id (saat validasi gagal)
    // Ini penting jika 'kode_program_display' dan 'nama_program_display' tidak memiliki atribut name
    // atau jika old() untuknya tidak terisi karena alasan lain.
    const oldProgramIdVal = "{{ old('program_id') }}"; // Ambil dari old() helper
    const oldKodeDisplayVal = "{{ old('kode_program_display') }}";
    const oldNamaDisplayVal = "{{ old('nama_program_display') }}";

    if (oldKodeDisplayVal) { // Jika old value untuk display kode ada, gunakan itu
        $('#add_kode_program_display').val(oldKodeDisplayVal);
    }
    if (oldNamaDisplayVal) { // Jika old value untuk display nama ada, gunakan itu
        $('#add_nama_program_display').val(oldNamaDisplayVal);
    }

    // Fallback jika display kosong tapi program_id ada (misalnya jika display field tidak di-submit/flash)
    if (oldProgramIdVal && oldProgramIdVal !== "" && (!oldKodeDisplayVal || !oldNamaDisplayVal)) {
        // Anda bisa mengaktifkan AJAX call di sini jika diperlukan
        // Untuk saat ini, pastikan field 'kode_program_display' dan 'nama_program_display'
        // memiliki atribut 'name' di HTML agar old() helpernya bekerja optimal.
        console.log('Old program_id exists (' + oldProgramIdVal + '), but display fields might be empty. Consider AJAX fallback if needed.');
        /*
        $.ajax({
            url: '/api/program/' + oldProgramIdVal, // Ganti dengan endpoint Anda
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
    }

    // =====================================================
    // --- Script untuk Auto-fill Posisi Handler ---
    // =====================================================
    $('#add_handler_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const position = selectedOption.data('position');
        
        // console.log untuk debugging (bisa Anda hapus jika sudah bekerja)
        console.log('Handler selected. Option value:', $(this).val());
        console.log('Selected HTML Option:', selectedOption[0]); 
        console.log('Value of data-position attribute:', position);
        
        $('#add_position_handler').val(position || ''); // Isi field posisi handler
        console.log('Posisi Handler input set to:', $('#add_position_handler').val());
    });

    // Trigger 'change' sekali saat halaman dimuat jika ada old value untuk handler_id
    // Ini akan mengisi Posisi Handler jika halaman kembali setelah validasi gagal
    // dan handler_id sebelumnya sudah terpilih.
    const oldHandlerId = "{{ old('handler_id') }}"; // Ambil dari old() helper
    if (oldHandlerId && oldHandlerId !== "") { 
        // Kita set dulu valuenya agar event change bisa membaca data-position dari option yang terpilih
        $('#add_handler_id').val(oldHandlerId); 
        console.log('Triggering change on load for handler ID:', oldHandlerId);
        $('#add_handler_id').trigger('change');
    }
    // =====================================================
    // --- Akhir Script Auto-fill Posisi Handler ---
    // =====================================================

     // =====================================================
    // --- Script untuk Show/Hide "Channel Lain" ---
    // =====================================================
    const channelsDropdown = $('#add_channels');
    const otherChannelGroup = $('#other_channel_group'); // Target div berdasarkan ID yang baru ditambahkan
    const otherChannelInput = $('#add_other_channel');

    function toggleOtherChannelField() {
        if (channelsDropdown.val() === 'Others') {
            otherChannelGroup.slideDown(); // Tampilkan dengan animasi
        } else {
            otherChannelGroup.slideUp();   // Sembunyikan dengan animasi
            otherChannelInput.val('');     // Kosongkan nilai input Channel Lain
        }
    }

    // Panggil fungsi saat halaman pertama kali dimuat untuk memeriksa nilai old() atau default
    toggleOtherChannelField(); 

    // Tambahkan event listener untuk dropdown "Channel Pengaduan"
    channelsDropdown.on('change select2:select', function() { // Dengarkan juga event Select2
        toggleOtherChannelField();
    });
    // =====================================================
    // --- Akhir Script untuk Show/Hide "Channel Lain" ---
    // =====================================================

    // ================================================================================
    // --- Script untuk auto-fill "Channel Lain" dengan '-' saat submit (CREATE) ---
    // ================================================================================
    // Pastikan form memiliki id="createFeedbackForm" (sudah ditambahkan di HTML)
    $('#createFeedbackForm').on('submit', function() {
        // Gunakan variabel channelsDropdown dan otherChannelInput yang sudah didefinisikan di atas
        if (channelsDropdown.val() === 'Others') {
            if (otherChannelInput.val().trim() === '') {
                otherChannelInput.val('-'); // Isi dengan '-' jika kosong
            }
        }
        // Jika channel bukan 'Others', otherChannelInput sudah dikosongkan oleh toggleOtherChannelField()
        // dan akan dikirim sebagai string kosong.
        // Form akan melanjutkan proses submit setelah ini.
    });
    // ================================================================================
    // --- Akhir Script untuk auto-fill "Channel Lain" ---
    // ================================================================================



    // =====================================================
    // --- Inisialisasi Select2 ---
    // =====================================================
    // Inisialisasi untuk semua elemen select dengan class .select2
    if ($.fn.select2) {
        // Daftar ID dari semua <select> yang menggunakan class 'select2' di create.blade.php Anda
        const select2Elements = '#add_sex, #add_channels, #add_status_complaint, #field_office, #is_hidden, #add_handler_id';
        
        $(select2Elements).select2({
            theme: 'bootstrap-5' // Ganti ke 'bootstrap4' jika environment Anda menggunakan AdminLTE default dengan Bootstrap 4
                                  // atau hapus 'theme' untuk menggunakan tema default Select2.
                                  // Pastikan CSS untuk tema yang dipilih sudah dimuat.
        });
    } else {
        console.warn('Select2 library not found. Please ensure it is included.');
    }
    // =====================================================
    // --- Akhir Inisialisasi Select2 ---
    // =====================================================

});
</script>
@endpush