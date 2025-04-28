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
             {{-- Bisa tambahkan tombol kembali di header jika mau --}}
             {{-- <div class="card-tools">
                 <a href="{{ route('feedback.index') }}" class="btn btn-sm btn-secondary">
                     <i class="fas fa-arrow-left"></i> {{ __('Kembali') }}
                 </a>
             </div> --}}
        </div>
        {{-- Form mengarah ke route 'store' --}}
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

                {{-- ISI FORM DIPINDAH DARI _add_modal.blade.php --}}
                <div class="row g-3">
                    {{-- Kolom Kiri --}}
                    <div class="col-md-6">
                    <div class="mb-3">
                <label for="add_kode_program_display" class="form-label">Kode Program <span class="text-info">(Klik untuk memilih)</span></label>
                <input type="text"
                    class="form-control"
                    id="add_kode_program_display"
                    placeholder="Pilih Kode Program..."
                    readonly
                    data-bs-toggle="modal"
                    data-bs-target="#programSelectionModal"
                    style="cursor: pointer;">
                {{-- Input Hidden untuk Menyimpan ID Program ke DB (HANYA SATU!) --}}
                <input type="hidden" id="add_program_id" name="program_id" value="{{ old('program_id') }}">
                {{-- Tampilkan error validasi untuk program_id di sini --}}
                @error('program_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            {{-- Input untuk Nama Program (Hanya Display) --}}
            <div class="mb-3">
                <label for="add_nama_program_display" class="form-label">Nama Program</label>
                <input type="text"
                    class="form-control"
                    id="add_nama_program_display"
                    placeholder="Nama program akan terisi otomatis"
                    readonly>
                {{-- Error tidak perlu ditampilkan lagi di sini --}}
            </div>
                        <div class="mb-3">
                            <label for="add_tanggal_registrasi" class="form-label">Tanggal Registrasi <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_registrasi') is-invalid @enderror" id="add_tanggal_registrasi" name="tanggal_registrasi" value="{{ old('tanggal_registrasi') }}" required>
                            @error('tanggal_registrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="add_umur" class="form-label">Umur</label>
                            <input type="number" class="form-control @error('umur') is-invalid @enderror" id="add_umur" name="umur" value="{{ old('umur') }}">
                             @error('umur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <div class="mb-3">
                              <label for="add_penerima" class="form-label">Penerima</label>
                              <input type="text" class="form-control @error('penerima') is-invalid @enderror" id="add_penerima" name="penerima" value="{{ old('penerima') }}">
                               @error('penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         </div>
                         <div class="mb-3">
                            <label for="add_sort_of_complaint" class="form-label">Jenis Keluhan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sort_of_complaint') is-invalid @enderror" id="add_sort_of_complaint" name="sort_of_complaint" value="{{ old('sort_of_complaint') }}" required>
                            @error('sort_of_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                             <label for="add_age_group" class="form-label">Kelompok Usia</label>
                             <input type="text" class="form-control @error('age_group') is-invalid @enderror" id="add_age_group" name="age_group" value="{{ old('age_group') }}">
                              @error('age_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         </div>
                         <div class="mb-3">
                              <label for="add_position" class="form-label">Posisi Penerima</label>
                              <input type="text" class="form-control @error('position') is-invalid @enderror" id="add_position" name="position" value="{{ old('position') }}">
                               @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         </div>
                         <div class="mb-3">
                              <label for="add_tanggal_selesai" class="form-label">Tanggal Selesai</label>
                              <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="add_tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
                               @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                         </div>
                         <div class="mb-3">
                               <label for="add_sex" class="form-label">Jenis Kelamin</label>
                               <select class="form-select @error('sex') is-invalid @enderror" id="add_sex" name="sex">
                                   <option value="" selected disabled>-- Pilih --</option>
                                   <option value="Laki-laki" {{ old('sex') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                   <option value="Perempuan" {{ old('sex') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                   <option value="Lainnya" {{ old('sex') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                               </select>
                                @error('sex') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                             <label for="add_phone_number" class="form-label">Phone Number (Pelapor)</label>
                             <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="add_phone_number" name="phone_number" value="{{ old('phone_number') }}">
                              @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                             <label for="add_kategori_komplain" class="form-label">Kategori Komplain</label>
                             <input type="text" class="form-control @error('kategori_komplain') is-invalid @enderror" id="add_kategori_komplain" name="kategori_komplain" value="{{ old('kategori_komplain') }}">
                             @error('kategori_komplain') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
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
                            <label for="add_deskripsi" class="form-label">Deskripsi Keluhan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="add_deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="add_status_complaint" class="form-label">Status Awal</label>
                            <select class="form-select @error('status_complaint') is-invalid @enderror" id="add_status_complaint" name="status_complaint">
                                <option value="Baru" {{ old('status_complaint', 'Baru') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                {{-- Status lain tidak relevan saat tambah baru --}}
                            </select>
                            @error('status_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                {{-- AKHIR DARI ISI FORM --}}
            </div>
            <div class="card-footer">
                {{-- Tombol Simpan dan Batal (link kembali ke index) --}}
                <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>
                <a href="{{ route('feedback.index') }}" class="btn btn-secondary">{{ __('Batal') }}</a>
            </div>
        </form>
        {{-- =========================================== --}}
{{-- MODAL UNTUK PEMILIHAN PROGRAM --}}
{{-- =========================================== --}}
<div class="modal fade" id="programSelectionModal" tabindex="-1" aria-labelledby="programSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable "> {{-- Sesuaikan ukuran jika perlu (modal-xl, modal-lg, modal-sm) --}}
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="programSelectionModalLabel">Daftar Program</h5>
                <button type="button" class="btn  bg-danger" data-dismiss="modal" title="{{ __('global.close') }}">
    <i class="fas fa-times text-dark"></i> {{-- Ikon 'X' dari Font Awesome --}}
</button>
            </div>
            <div class="modal-body">
                {{-- Tabel target untuk DataTables --}}
                <table class="table table-hover table-bordered table-striped" id="programListTable" style="width:100%;">
                    <thead>
                        <tr>
                            {{-- Sesuaikan header dengan data program --}}
                            <th>Kode Program</th> {{-- Sesuaikan --}}
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
    </div>
@endsection

{{-- Jika ada CSS/JS spesifik untuk form ini, tambahkan di push --}}
{{-- @push('styles') --}}
    {{-- Hapus style modal dari _add_modal.blade.php --}}
{{-- @endpush --}}

{{-- @push('js') --}}
    {{-- Contoh: Script untuk datepicker jika pakai library --}}
    @push('js')
<script>
$(document).ready(function() {
    let programDataTable = null;

    // Trigger modal dari input Kode Program
    $('#add_kode_program_display').on('click', function(e) {
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
                        // Sesuaikan 'data:' & 'name:' dengan response API tim
                        { data: 'kode', name: 'kode', width: '20%' }, // Contoh: Butuh KODE
                        { data: 'nama', name: 'nama' }, // Contoh: Butuh NAMA
                        {
                            data: 'action', name: 'action', orderable: false,
                            searchable: false, width: '15%', className: 'text-center'
                        }
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

    // Modifikasi Handler Tombol Pilih
    $('#programListTable tbody').on('click', '.select-program', function() { // <-- Pastikan class '.select-program' sesuai
        // ==================================================
        // VERIFIKASI: Ambil ID, KODE, dan NAMA dari tombol API tim
        // ==================================================
        const programId   = $(this).data('id');   // Contoh: Jika tombol punya data-id="..."
        const programKode = $(this).data('kode'); // Contoh: Jika tombol punya data-kode="..."
        const programNama = $(this).data('nama'); // Contoh: Jika tombol punya data-nama="..."

        // ==================================================
        // Isi ketiga input di form utama
        // ==================================================
        $('#add_kode_program_display').val(programKode); // Isi display Kode
        $('#add_nama_program_display').val(programNama); // Isi display Nama
        $('#add_program_id').val(programId);             // Isi hidden ID (YANG DISIMPAN)

        // Tutup modal
        $('#programSelectionModal').modal('hide');
    });
});
</script>
@endpush
{{-- @endpush --}}