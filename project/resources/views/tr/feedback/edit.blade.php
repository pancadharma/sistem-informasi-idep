{{-- resources/views/tr/feedback/edit.blade.php --}}

@extends('layouts.app')

@section('subtitle', __('Edit Feedback & Response'))
@section('content_header_title', __('Edit Feedback'))
@section('sub_breadcumb')
<a href="{{ route('feedback.index') }}">{{ __('Feedback & Response') }}</a>
    <li class="breadcrumb-item active">{{ __('Edit FRM') }}</li>
@endsection

@section('content_body')
<div class="card card-outline card-warning mb-4"> {{-- Card warna warning untuk edit --}}
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit me-1"></i>
            Edit Data Feedback ID: {{ $feedback->id }}
        </h3>
    </div>
    <div class="card-body">
        {{-- Menampilkan error dari session 'error' (untuk exception) --}}
        <!-- @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Peringatan!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Menampilkan error validasi standar --}}
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
        @endif -->

        <form action="{{ route('feedback.update', $feedback->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- ====================================== --}}
                {{-- KOLOM KIRI --}}
                {{-- ====================================== --}}
                <div class="col-md-6">

                    {{-- Grup: Informasi Program & Registrasi --}}
                    <fieldset class="mb-4 border p-3 rounded">
                        <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Program & Registrasi</legend>

                        <div class="mb-3">
                            <label for="edit_kode_program_display" class="form-label">Kode Program</label>
                            <input type="text" class="form-control @error('program_id') is-invalid @enderror" 
                                   id="edit_kode_program_display" name="kode_program_display" {{-- Tambahkan name agar old() bisa bekerja jika diperlukan --}}
                                   placeholder="Kode Program" readonly 
                                   value="{{ old('kode_program_display', $feedback->Program?->kode) }}">
                            {{-- INI BAGIAN PENTING UNTUK VALIDASI "program id diperlukan" --}}
                            <input type="hidden" id="edit_program_id" name="program_id" 
                                value="{{ old('program_id', $feedback->program_id) }}">
                            @error('program_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_program_display" class="form-label">Nama Program</label>
                            <input type="text" class="form-control" 
                                   id="edit_nama_program_display" name="nama_program_display" {{-- Tambahkan name --}}
                                   placeholder="Nama program akan terisi otomatis" readonly 
                                   value="{{ old('nama_program_display', $feedback->Program?->nama) }}">
                        </div>
                        <div class="mb-3">
                            <label for="edit_tanggal_registrasi" class="form-label">Tanggal Registrasi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_registrasi') is-invalid @enderror" 
                                   id="edit_tanggal_registrasi" name="tanggal_registrasi" 
                                   value="{{ old('tanggal_registrasi', optional($feedback->tanggal_registrasi)->format('Y-m-d')) }}" required>
                            @error('tanggal_registrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- FIELD BARU: Field Office --}}
                        <div class="mb-3">
                            <label for="edit_field_office" class="form-label">Field Office</label>
                            <div class="select2-purple">
                                <select class="form-select form-control select2 @error('field_office') is-invalid @enderror" 
                                        id="edit_field_office" name="field_office">
                                    <option value="" {{ old('field_office', $feedback->field_office) == '' ? 'selected' : '' }}>-- Pilih Field Office --</option>
                                    <option value="Bali" {{ old('field_office', $feedback->field_office) == 'Bali' ? 'selected' : '' }}>Bali</option>
                                    <option value="Bangka Belitung" {{ old('field_office', $feedback->field_office) == 'Bangka Belitung' ? 'selected' : '' }}>Bangka Belitung</option>
                                    <option value="Jawa Timur" {{ old('field_office', $feedback->field_office) == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                    <option value="Kalimantan Timur" {{ old('field_office', $feedback->field_office) == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                                    <option value="Kalimantan Utara" {{ old('field_office', $feedback->field_office) == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                                    <option value="NTT" {{ old('field_office', $feedback->field_office) == 'NTT' ? 'selected' : '' }}>NTT</option>
                                    <option value="Papua Barat" {{ old('field_office', $feedback->field_office) == 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                                    <option value="Sulawesi Tengah" {{ old('field_office', $feedback->field_office) == 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                                    <option value="Yogyakarta" {{ old('field_office', $feedback->field_office) == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                                </select>
                            </div>
                            @error('field_office') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        {{-- PENYESUAIAN OPSI SEX --}}
                        <div class="mb-3">
                            <label for="edit_sex" class="form-label">Jenis Kelamin</label>
                            <div class="select2-purple">
                                <select class="form-select form-control select2 @error('sex') is-invalid @enderror" id="edit_sex" name="sex">
                                    <option value="" {{ old('sex', $feedback->sex) == '' ? 'selected' : '' }}>-- Pilih --</option>
                                    <option value="Male" {{ old('sex', $feedback->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', $feedback->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Boy" {{ old('sex', $feedback->sex) == 'Boy' ? 'selected' : '' }}>Boy</option>
                                    <option value="Girl" {{ old('sex', $feedback->sex) == 'Girl' ? 'selected' : '' }}>Girl</option>
                                    <option value="Unspecified" {{ old('sex', $feedback->sex) == 'Unspecified' ? 'selected' : '' }}>Unspecified</option>
                                </select>
                            </div>
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
                            {{-- Mengganti ID agar konsisten dengan prefix 'edit_' jika diperlukan oleh JS, namun jika tidak, ID lama bisa dipertahankan --}}
                            <label for="edit_nama_pelapor" class="form-label">Nama Pelapor</label>
                            <input type="text" class="form-control @error('nama_pelapor') is-invalid @enderror" id="edit_nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor', $feedback->nama_pelapor) }}">
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
                        {{-- PENYESUAIAN CHANNELS MENJADI SELECT --}}
                        <div class="mb-3">
                                <label for="edit_channels" class="form-label">Channel Pengaduan</label>
                                <div class="select2-purple">
                                    <select class="form-select form-control select2 @error('channels') is-invalid @enderror" id="edit_channels" name="channels">
                                        <option value="" {{ old('channels', $feedback->channels) == '' ? 'selected' : '' }}>-- Pilih Channel --</option>
                                        <option value="Complaint Form" {{ old('channels', $feedback->channels) == 'Complaint Form' ? 'selected' : '' }}>Complaint Form</option>
                                        <option value="Complaint Box" {{ old('channels', $feedback->channels) == 'Complaint Box' ? 'selected' : '' }}>Complaint Box</option>
                                        <option value="Face to Face" {{ old('channels', $feedback->channels) == 'Face to Face' ? 'selected' : '' }}>Face to Face</option>
                                        <option value="Hotline" {{ old('channels', $feedback->channels) == 'Hotline' ? 'selected' : '' }}>Hotline</option>
                                        <option value="Help Desk" {{ old('channels', $feedback->channels) == 'Help Desk' ? 'selected' : '' }}>Help Desk</option>
                                        <option value="SMS" {{ old('channels', $feedback->channels) == 'SMS' ? 'selected' : '' }}>SMS</option>
                                        <option value="WhatsApp" {{ old('channels', $feedback->channels) == 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                        <option value="Children Consultation" {{ old('channels', $feedback->channels) == 'Children Consultation' ? 'selected' : '' }}>Children Consultation</option>
                                        <option value="Local Agency" {{ old('channels', $feedback->channels) == 'Local Agency' ? 'selected' : '' }}>Local Agency</option>
                                        <option value="Others" {{ old('channels', $feedback->channels) == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </div>
                                @error('channels') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            {{-- Channel Lain (Awalnya mungkin tersembunyi, dikontrol JS) --}}
                            <div class="mb-3" id="edit_other_channel_group"> {{-- TAMBAHKAN ID DI SINI --}}
                                <label for="edit_other_channel" class="form-label">Channel Lain</label>
                                <input type="text" class="form-control @error('other_channel') is-invalid @enderror" id="edit_other_channel" name="other_channel" value="{{ old('other_channel', $feedback->other_channel) }}">
                                @error('other_channel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi Keluhan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="edit_deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $feedback->deskripsi) }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        {{-- PENYESUAIAN OPSI STATUS COMPLAINT --}}
                        <div class="mb-3">
                            <label for="edit_status_complaint" class="form-label">Status Complaint <span class="text-danger">*</span></label>
                            <div class="select2-purple">
                                <select class="form-select form-control select2 @error('status_complaint') is-invalid @enderror" id="edit_status_complaint" name="status_complaint" required>
                                    <option value="Process" {{ old('status_complaint', $feedback->status_complaint) == 'Process' ? 'selected' : '' }}>Process</option>
                                    <option value="Resolved" {{ old('status_complaint', $feedback->status_complaint) == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>
                            @error('status_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- FIELD BARU: Status Tampilan (Hide/Unhide) --}}
                        <div class="mb-3">
                            <label for="edit_is_hidden" class="form-label">Status Tampilan <span class="text-danger">*</span></label>
                            <div class="select2-purple">
                                <select class="form-select form-control select2 @error('is_hidden') is-invalid @enderror" id="edit_is_hidden" name="is_hidden" required>
                                    <option value="0" {{ old('is_hidden', (int)$feedback->is_hidden) === 0 ? 'selected' : '' }}>Tampilkan (Unhide)</option>
                                    <option value="1" {{ old('is_hidden', (int)$feedback->is_hidden) === 1 ? 'selected' : '' }}>Sembunyikan (Hide)</option>
                                </select>
                            </div>
                            @error('is_hidden') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </fieldset>

                    {{-- Grup: Informasi Penanganan (Handler) --}}
                    <fieldset class="mb-4 border p-3 rounded">
                        <legend class="w-auto px-2 h6 fw-bold text-primary">Informasi Penanganan (Handler)</legend>
                        {{-- ====================================================================== --}}
                        {{-- PENYESUAIAN UNTUK HANDLER DAN POSISI HANDLER --}}
                        {{-- ====================================================================== --}}
                        <div class="mb-3">
                            <label for="edit_handler_id" class="form-label">Handler (Petugas)</label>
                            <div class="select2-purple">
                                <select class="form-select form-control select2 @error('handler_id') is-invalid @enderror" 
                                        id="edit_handler_id" name="handler_id">
                                    <option value="" data-position="">-- Pilih Handler --</option>
                                    @if(isset($handlers) && $handlers->count() > 0)
                                        @foreach($handlers as $user)
                                            <option value="{{ $user->id }}" 
                                                    {{ old('handler_id', $feedback->handler_id) == $user->id ? 'selected' : '' }}
                                                    data-position="{{ $user->jabatan?->nama ?? '' }}"> {{-- Pastikan 'nama' adalah kolom jabatan yg benar --}}
                                                {{ $user->nama }} {{-- Pastikan 'nama' adalah kolom nama user yg benar --}}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>Tidak ada data handler</option>
                                    @endif
                                </select>
                            </div>
                            @error('handler_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_position_handler" class="form-label">Posisi Handler</label>
                            <input type="text" class="form-control @error('position_handler') is-invalid @enderror" 
                                   id="edit_position_handler" name="position_handler" 
                                   value="{{ old('position_handler', $feedback->handlerUser?->jabatan?->nama ?? '') }}" readonly>
                            @error('position_handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        {{-- ====================================================================== --}}
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

                        {{-- FIELD BARU: Handler Description --}}
                        <div class="mb-3">
                            <label for="edit_handler_description" class="form-label">Handler Description</label>
                            <textarea class="form-control @error('handler_description') is-invalid @enderror" 
                                      id="edit_handler_description" name="handler_description" 
                                      rows="3">{{ old('handler_description', $feedback->handler_description) }}</textarea>
                            @error('handler_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </fieldset>
                </div> {{-- End Kolom Kanan --}}
            </div> {{-- End row g-3 --}}

            <div class="card-footer mt-4 bg-light border-top">
                <a href="{{ route('feedback.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-warning float-end"> {{-- Tombol Simpan Perubahan jadi warning --}}
                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div> {{-- End card-body --}}
</div> {{-- End card --}}

{{-- MODAL UNTUK PEMILIHAN PROGRAM (Tetap ada, tapi tidak dipicu dari field Kode Program di edit) --}}
<div class="modal fade" id="programSelectionModal" tabindex="-1" aria-labelledby="programSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger"> {{-- Pastikan Bootstrap versi yang sesuai untuk close button --}}
                <h5 class="modal-title" id="programSelectionModalLabel">Daftar Program</h5>
                {{-- Tombol close disesuaikan dengan Bootstrap 4 (data-dismiss) atau 5 (data-bs-dismiss) --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" title="{{ __('global.close') }}">
                {{-- Jika menggunakan Bootstrap 5, seharusnya:
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
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
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                 {{-- Tombol close disesuaikan dengan Bootstrap 4 (data-dismiss) atau 5 (data-bs-dismiss) --}}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                {{-- Jika BS5: <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Fungsi untuk menghitung kelompok usia
    function calculateAgeGroup(age) {
        if (age === null || age === '' || isNaN(age) || age < 0) return '';
        if (age <= 17) return '0 - 17';
        if (age <= 24) return '18 - 24';
        if (age <= 59) return '25 - 59';
        if (age >= 60) return '>60';
        return '';
    }

    const umurInputEdit = $('#edit_umur'); // Target ID yang benar untuk edit
    const ageGroupInputEdit = $('#edit_age_group'); // Target ID yang benar untuk edit

    umurInputEdit.on('input', function() {
        const umur = parseInt($(this).val(), 10);
        ageGroupInputEdit.val(calculateAgeGroup(umur));
    });

    // Panggil sekali saat halaman load untuk data yang ada di form edit
    if (umurInputEdit.val()) { // Cek jika ada nilai awal
        ageGroupInputEdit.val(calculateAgeGroup(parseInt(umurInputEdit.val(), 10)));
    }

    // --- Script untuk Modal Program (jika digunakan di konteks lain pada halaman edit) ---
    let programDataTableEdit = null; // Gunakan variabel berbeda jika perlu

    // $('#edit_kode_program_display').on('click', function(e) { // Ini sudah dikomentari sesuai permintaan
    //     e.preventDefault();
    //     openProgramModalAndInitDataTableEdit();
    // });

    function openProgramModalAndInitDataTableEdit() { // Ubah nama fungsi jika perlu
        if (!$.fn.DataTable.isDataTable('#programListTable')) { // Tetap target tabel yang sama
            programDataTableEdit = $('#programListTable').DataTable({
                // ... Konfigurasi DataTable sama seperti di create.blade.php ...
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('api.beneficiary.program') }}", // Pastikan route ini benar
                    type: "GET"
                },
                columns: [
                    { data: 'kode', name: 'kode', width: '20%' },
                    { data: 'nama', name: 'nama' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, width: '15%', className: 'text-center'}
                ],
                // ... bahasa, dll ...
            });
        }
        $('#programSelectionModal').modal('show');
    }

    $('#programListTable tbody').on('click', '.select-program', function() {
        const programId   = $(this).data('id');
        const programKode = $(this).data('kode');
        const programNama = $(this).data('nama');

        // Mengisi field di form edit (ini akan bekerja jika modal dipanggil dari edit)
        $('#edit_kode_program_display').val(programKode);
        $('#edit_nama_program_display').val(programNama);
        $('#edit_program_id').val(programId);        

        $('#programSelectionModal').modal('hide');
    });

  // =====================================================
    // --- Script untuk Auto-fill Posisi Handler (EDIT) ---
    // =====================================================
    $('#edit_handler_id').on('change select2:select', function() { // Tambahkan select2:select
        const selectedOption = $(this).find('option:selected');
        const position = selectedOption.data('position');
        
        console.log('[EDIT] Handler selected. Option value:', $(this).val());
        console.log('[EDIT] Selected HTML Option:', selectedOption[0]); 
        console.log('[EDIT] Value of data-position attribute:', position);
        
        $('#edit_position_handler').val(position || ''); 
        console.log('[EDIT] Posisi Handler input set to:', $('#edit_position_handler').val());
    });

    // Trigger 'change' saat halaman dimuat untuk mengisi Posisi Handler awal
    const currentHandlerId = "{{ old('handler_id', $feedback->handler_id ?? '') }}";
    if (currentHandlerId && currentHandlerId !== "") {
        // Penting: Pastikan nilai dropdown #edit_handler_id sudah di-set ke currentHandlerId *sebelum* trigger change
        // Ini penting agar $(this).find('option:selected') di dalam event handler bisa mendapatkan option yang benar.
        $('#edit_handler_id').val(currentHandlerId); 
        
        console.log('[EDIT] Triggering change on load for handler ID:', currentHandlerId);
        // Beri sedikit waktu agar Select2 (jika digunakan) selesai render sebelum trigger.
        // Atau, jika Select2 sudah diinisialisasi, trigger event spesifik Select2.
        setTimeout(function() {
            if ($.fn.select2 && $('#edit_handler_id').data('select2')) {
                 $('#edit_handler_id').trigger('change.select2'); // Untuk memicu update tampilan Select2 dan event change internalnya
            } else {
                $('#edit_handler_id').trigger('change');
            }
        }, 150); // Penyesuaian timeout mungkin diperlukan
    }
    // =====================================================
    // --- Akhir Script Auto-fill Posisi Handler (EDIT) ---
    // =====================================================

 // =====================================================
    // --- Script untuk Show/Hide "Channel Lain" (EDIT) ---
    // =====================================================
    const channelsDropdownEdit = $('#edit_channels');
    const otherChannelGroupEdit = $('#edit_other_channel_group');
    const otherChannelInputEdit = $('#edit_other_channel');

    function toggleOtherChannelFieldEdit() {
        if (channelsDropdownEdit.val() === 'Others') {
            otherChannelGroupEdit.slideDown(); // Tampilkan grup field "Channel Lain"
            otherChannelInputEdit.val('');    // <--- BARIS INI DIUBAH/DITAMBAHKAN: Selalu kosongkan input "Channel Lain"
        } else {
            otherChannelGroupEdit.slideUp();   // Sembunyikan grup field "Channel Lain"
            otherChannelInputEdit.val('');    // <--- BARIS INI JUGA PASTIKAN ADA: Kosongkan input saat disembunyikan
        }
    }

    // Panggil fungsi saat halaman pertama kali dimuat untuk memeriksa nilai awal dari $feedback atau old()
    // Fungsi ini akan langsung mengosongkan field jika channel awal adalah 'Others'
    toggleOtherChannelFieldEdit(); 

    // Tambahkan event listener untuk dropdown "Channel Pengaduan" di halaman edit
    channelsDropdownEdit.on('change select2:select', function() { // Dengarkan juga event Select2 jika digunakan
        toggleOtherChannelFieldEdit();
    });
    // =====================================================
    // --- Akhir Script untuk Show/Hide "Channel Lain" (EDIT) ---
    // =====================================================

    // Inisialisasi Select2 untuk semua elemen select dengan class .select2 di halaman edit
    if ($.fn.select2) {
        $('#edit_sex, #edit_field_office, #edit_channels, #edit_status_complaint, #edit_is_hidden').select2({
            theme: 'bootstrap-5' // Pastikan tema ini sesuai dengan versi Bootstrap Anda
                                  // Ganti ke 'bootstrap4' jika Anda menggunakan AdminLTE default dengan BS4
        });
    } else {
        console.warn('Select2 library not found. Please ensure it is included for edit page.');
    }
});
</script>
@endpush