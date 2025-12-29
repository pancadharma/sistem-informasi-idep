@push('styles')
<style>
/* Target spesifik modal body di dalam dialog scrollable */
.modal-dialog-scrollable .modal-body {
    max-height: 65vh; !important; /* Coba atur tinggi eksplisit, sesuaikan nilainya */
    /* overflow-y: auto; sudah ada, tapi bisa ditambahkan lagi untuk memastikan */
     overflow-y: auto;
}

/* Atau coba target ID modal untuk specificity lebih tinggi */
/* #addFeedbackModal .modal-body { */
/* max-height: 65vh !important; /* Pakai !important untuk tes jika perlu */
/* overflow-y: auto !important; */
/* } */
</style>
@endpush

<div class="modal fade" id="addFeedbackModal" tabindex="-1" aria-labelledby="addFeedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> {{-- Ukuran modal besar & bisa di-scroll --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFeedbackModalLabel">Tambah FRM (Feedback & Response)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('feedback.store') }}" method="POST">
                @csrf {{-- Token CSRF untuk keamanan --}}
                <div class="modal-body">
                    {{-- Tampilkan error validasi global --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- Kolom Kiri --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="add_program" class="form-label">Program</label>
                                <input type="text" class="form-control @error('program') is-invalid @enderror" id="add_program" name="program" value="{{ old('program') }}">
                                @error('program') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            {{-- Status bisa diset otomatis atau manual --}}
                            <div class="mb-3">
                                <label for="add_status_complaint" class="form-label">Status Awal</label>
                                <select class="form-select @error('status_complaint') is-invalid @enderror" id="add_status_complaint" name="status_complaint">
                                     <option value="Baru" {{ old('status_complaint', 'Baru') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                     {{-- Opsi lain jika diperlukan saat tambah --}}
                                 </select>
                                 @error('status_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>