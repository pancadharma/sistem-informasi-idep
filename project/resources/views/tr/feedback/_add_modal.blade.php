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
                <h5 class="modal-title" id="addFeedbackModalLabel">{{ __('cruds.feedback.add_new') }}</h5>
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
                                <label for="add_program" class="form-label">{{ __('cruds.feedback.fields.program') }}</label>
                                <input type="text" class="form-control @error('program') is-invalid @enderror" id="add_program" name="program" value="{{ old('program') }}">
                                @error('program') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="add_tanggal_registrasi" class="form-label">{{ __('cruds.feedback.fields.tanggal_registrasi') }} <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('tanggal_registrasi') is-invalid @enderror" id="add_tanggal_registrasi" name="tanggal_registrasi" value="{{ old('tanggal_registrasi') }}" required>
                                @error('tanggal_registrasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="add_umur" class="form-label">{{ __('cruds.feedback.fields.umur') }}</label>
                                <input type="number" class="form-control @error('umur') is-invalid @enderror" id="add_umur" name="umur" value="{{ old('umur') }}">
                                 @error('umur') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="mb-3">
                                 <label for="add_penerima" class="form-label">{{ __('cruds.feedback.fields.penerima') }}</label>
                                 <input type="text" class="form-control @error('penerima') is-invalid @enderror" id="add_penerima" name="penerima" value="{{ old('penerima') }}">
                                 @error('penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_sort_of_complaint" class="form-label">{{ __('cruds.feedback.fields.sort_of_complaint') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sort_of_complaint') is-invalid @enderror" id="add_sort_of_complaint" name="sort_of_complaint" value="{{ old('sort_of_complaint') }}" required>
                                @error('sort_of_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                 <label for="add_age_group" class="form-label">{{ __('cruds.feedback.fields.age_group') }}</label>
                                 <input type="text" class="form-control @error('age_group') is-invalid @enderror" id="add_age_group" name="age_group" value="{{ old('age_group') }}">
                                 @error('age_group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                 <label for="add_position" class="form-label">{{ __('cruds.feedback.fields.position') }}</label>
                                 <input type="text" class="form-control @error('position') is-invalid @enderror" id="add_position" name="position" value="{{ old('position') }}">
                                  @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                 <label for="add_tanggal_selesai" class="form-label">{{ __('cruds.feedback.fields.tanggal_selesai') }}</label>
                                 <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="add_tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
                                  @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                   <label for="add_sex" class="form-label">{{ __('cruds.feedback.fields.sex') }}</label>
                                  <select class="form-select @error('sex') is-invalid @enderror" id="add_sex" name="sex">
                                       <option value="" selected disabled>{{ __('cruds.feedback.placeholders.pilih') }}</option>
                                       <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>{{ __('cruds.beneficiary.penerima.laki') }}</option>
                                       <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>{{ __('cruds.beneficiary.penerima.perempuan') }}</option>
                                       <option value="Unspecified" {{ old('sex') == 'Unspecified' ? 'selected' : '' }}>{{ __('cruds.beneficiary.penerima.lainnya') }}</option>
                                  </select>
                                   @error('sex') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                            <div class="mb-3">
                                 <label for="add_kontak_penerima" class="form-label">{{ __('cruds.feedback.fields.kontak_penerima') }}</label>
                                 <input type="text" class="form-control @error('kontak_penerima') is-invalid @enderror" id="add_kontak_penerima" name="kontak_penerima" value="{{ old('kontak_penerima') }}">
                                  @error('kontak_penerima') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                            <div class="mb-3">
                                   <label for="add_address" class="form-label">{{ __('cruds.feedback.fields.address') }}</label>
                                  <textarea class="form-control @error('address') is-invalid @enderror" id="add_address" name="address" rows="3">{{ old('address') }}</textarea>
                                  @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        </div>

                        {{-- Kolom Kanan --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                 <label for="add_phone_number" class="form-label">{{ __('cruds.feedback.fields.phone_number') }}</label>
                                 <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="add_phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                  @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                 <label for="add_channels" class="form-label">{{ __('cruds.feedback.fields.channels') }}</label>
                                 <input type="text" class="form-control @error('channels') is-invalid @enderror" id="add_channels" name="channels" value="{{ old('channels') }}">
                                  @error('channels') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                 <label for="add_other_channel" class="form-label">{{ __('cruds.feedback.fields.other_channel') }}</label>
                                 <input type="text" class="form-control @error('other_channel') is-invalid @enderror" id="add_other_channel" name="other_channel" value="{{ old('other_channel') }}">
                                 @error('other_channel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                 <label for="add_kategori_komplain" class="form-label">{{ __('cruds.feedback.fields.kategori_komplain') }}</label>
                                 <input type="text" class="form-control @error('kategori_komplain') is-invalid @enderror" id="add_kategori_komplain" name="kategori_komplain" value="{{ old('kategori_komplain') }}">
                                 @error('kategori_komplain') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                 <label for="add_handler" class="form-label">{{ __('cruds.feedback.fields.handler') }}</label>
                                 <input type="text" class="form-control @error('handler') is-invalid @enderror" id="add_handler" name="handler" value="{{ old('handler') }}">
                                  @error('handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                 <label for="add_position_handler" class="form-label">{{ __('cruds.feedback.fields.position_handler') }}</label>
                                 <input type="text" class="form-control @error('position_handler') is-invalid @enderror" id="add_position_handler" name="position_handler" value="{{ old('position_handler') }}">
                                 @error('position_handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                            <div class="mb-3">
                                 <label for="add_kontak_handler" class="form-label">{{ __('cruds.feedback.fields.kontak_handler') }}</label>
                                 <input type="text" class="form-control @error('kontak_handler') is-invalid @enderror" id="add_kontak_handler" name="kontak_handler" value="{{ old('kontak_handler') }}">
                                 @error('kontak_handler') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                             <div class="mb-3">
                                <label for="add_deskripsi" class="form-label">{{ __('cruds.feedback.fields.deskripsi') }} <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="add_deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            {{-- Status bisa diset otomatis atau manual --}}
                            <div class="mb-3">
                                <label for="add_status_complaint" class="form-label">{{ __('cruds.feedback.fields.status_complaint') }}</label>
                                <select class="form-select @error('status_complaint') is-invalid @enderror" id="add_status_complaint" name="status_complaint">
                                     <option value="" {{ old('status_complaint', 'Baru') == 'Baru' ? 'selected' : '' }}>{{ __('Baru') }}</option>
                                     {{-- Opsi lain jika diperlukan saat tambah --}}
                                 </select>
                                 @error('status_complaint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                             </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>