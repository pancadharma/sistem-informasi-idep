<div class="modal fade" id="previewModals" tabindex="-1" aria-labelledby="previewModalsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalsLabel">Preview Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre id="modalData"></pre>
                <form id="previewData"></form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="sendDataBtn">Kirim Data</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre id="modalData"></pre>
                <form id="previewData"></form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="sendDataBtn">Kirim Data</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Add Beneficiaries Data -->
<div class="modal fade" id="ModalTambahPeserta" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="dataModalLabel" theme="success">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="ModalTambahPesertaTitle">
                    <i class="bi bi-person-plus"></i>
                    {{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="dataForm">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender" required>
                                <option value="laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Disabilitas</label>
                            <input type="text" class="form-control" name="disabilitas">
                        </div>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <label class="form-label">Kelompok Rentan</label>
                            <select class="form-select select2-multiple select2" name="kelompok_rentan" multiple>
                                <option value="Anak-anak">Anak-anak</option>
                                <option value="Lansia">Lansia</option>
                                <option value="Ibu Hamil">Ibu Hamil</option>
                                <option value="Penyandang Disabilitas">Penyandang Disabilitas</option>
                                <option value="Minoritas">Minoritas</option>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">RT</label>
                            <input type="text" class="form-control" name="rt" required>
                        </div>
                        <div class="col">
                            <label class="form-label">RW / Banjar</label>
                            <input type="text" class="form-control" name="rw_banjar" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Dusun</label>
                            <input type="text" class="form-control" name="dusun" required>
                        </div>

                        <div class="col">
                            <label class="form-label">Desa</label>
                            <input type="text" class="form-control" name="desa" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">No. Telp</label>
                            <input type="text" class="form-control" name="no_telp">
                        </div>
                        <div class="col">
                            <label class="form-label">Jenis Kelompok/Instansi</label>
                            <input type="text" class="form-control" name="jenis_kelompok">
                        </div>
                        <div class="col">
                            <label class="form-label">Usia</label>
                            <input type="number" class="form-control usia-input" name="usia" required>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
                <button type="submit" class="btn btn-primary" id="saveDataBtn">{{ __('global.save') }}</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Edit Form -->
<div class="modal fade" id="editDataModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editDataModalLabel" theme="info">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="ModalEditPesertaTitle">
                    <span class="material-symbols-outlined"> edit_square </span>
                    {{ __('global.edit') .' '. __('cruds.kegiatan.peserta.label') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editDataForm" class="form-group">
                    <input type="hidden" id="editRowId">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Gender</label>
                            <select class="form-select" id="editGender" name="gender" required>
                                <option value="laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Disabilitas</label>
                            <input type="text" class="form-control" id="editDisabilitas" name="disabilitas">
                        </div>
                        <div class="col">
                            <label class="form-label">Kelompok Rentan</label>
                            <select class="form-select select2-multiple" id="editKelompokRentan"
                                name="kelompok_rentan" multiple>
                                <option value="Anak-anak">Anak-anak</option>
                                <option value="Lansia">Lansia</option>
                                <option value="Ibu Hamil">Ibu Hamil</option>
                                <option value="Penyandang Disabilitas">Penyandang Disabilitas</option>
                                <option value="Minoritas">Minoritas</option>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">RT</label>
                            <input type="text" class="form-control" id="editRt" name="rt" required>
                        </div>
                        <div class="col">
                            <label class="form-label">RW / Banjar</label>
                            <input type="text" class="form-control" id="editRwBanjar" name="rw_banjar" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Dusun</label>
                            <input type="text" class="form-control" id="editDusun" name="dusun" required>
                        </div>

                        <div class="col">
                            <label class="form-label">Desa</label>
                            <input type="text" class="form-control" id="editDesa" name="desa" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">No. Telp</label>
                            <input type="text" class="form-control" id="editNoTelp" name="no_telp">
                        </div>
                        <div class="col">
                            <label class="form-label">Jenis Kelompok/Instansi</label>
                            <input type="text" class="form-control" id="editJenisKelompok" name="jenis_kelompok">
                        </div>
                        <div class="col">
                            <label class="form-label">Usia</label>
                            <input type="number" class="form-control usia-input" id="editUsia" name="usia" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
                <button type="button" class="btn btn-primary" id="updateDataBtn">{{ __('global.update') }}</button>
            </div>
        </div>
    </div>
</div>






