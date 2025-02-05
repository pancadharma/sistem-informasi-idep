<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalsLabel">Preview Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre id="modalData2"></pre>
                <form id="previewData2"></form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Kirim Data</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="previewModal2" tabindex="-1" aria-labelledby="previewModalLabel2" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalsLabel2">Preview Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre id="modalDat2a"></pre>
                <form id="previewData2"></form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Kirim Data</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModalsData" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="previewModalsData" >
    <div class="modal-dialog modal-dialog-scrollable modal modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalsDataTitle">Preview Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="modalData" style="white-space: pre-wrap; word-wrap: break-word;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="sendDataBtn">Kirim Data</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Add Beneficiaries Data -->
<div class="modal fade" id="ModalTambahPeserta" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="dataModalLabel" theme="success">
    <div class="modal-dialog modal-dialog-scrollable modal modal-dialog-centered modal-lg" role="document">
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
                <form id="dataForm" class="big">
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                            <label class="form-label mb-0">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-4 col-lg-4 self-center order-1 order-md-1">
                            <label class="form-label mb-0">No. Telp</label>
                            <input type="text" class="form-control" name="no_telp" placeholder="" >
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 self-center order-3 order-md-3">
                            <label class="form-label mb-0">Usia <span class="text-danger">*</span></label>
                            <input type="number" class="form-control usia-input" name="usia" required>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 self-center order-2 order-md-2">
                            <label class="form-label mb-0">Gender <span class="text-danger">*</span></label>
                            <select class="form-control" name="gender" required>
                                <option value="laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2">
                            <label class="form-label mb-0">Disabilitas</label>
                            <div class="select2-red">
                                <select class="form-control" id="disabilitas" name="disabilitas" multiple>
                                    <option value="Fisik">Fisik</option>
                                    <option value="Sensorik">Sensorik</option>
                                    <option value="Intelektual">Intelektual</option>
                                    <option value="Mental">Mental</option>
                                    <option value="Ganda">Ganda</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2">
                            <label class="form-label mb-0">Kelompok Rentan</label>
                            <div class="select2-green">
                                <select class="form-control select2-multiple select2" name="kelompok_rentan" multiple id="kelompok_rentan">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label mb-0">Jenis Kelompok / Instansi</label>
                            <input type="text" class="form-control" name="jenis_kelompok">
                        </div>
                    </div>
                    <div class="row mb-3">
                        {{-- desa --}}
                        <div class="col-sm-12 col-md-12 col-lg-6 self-center order-1 order-md-1" id="PilihDataDesa">
                            <div class="form-input">
                                <label class="form-label mb-0"><strong>{{ __('cruds.desa.title') }}</strong> <span class="text-danger">*</span></label>
                                <select class="form-control select2bs4" name="desa_id" id="desa_id" required>
                                </select>
                            </div>
                        </div>
                        {{-- dusun --}}
                        <div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2 d-flex align-items-center">
                            <div class="col-11">
                                <div class="row">
                                    <label class="form-label mb-0">{{ __('cruds.dusun.title') }} <span class="text-danger">*</span></label>
                                    <div class="col-12 pl-0">
                                        <select class="form-control select2 flex-grow-1" name="dusun_id" id="dusun_id"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-input">
                                <label class="form-label mb-0">&nbsp;</label>
                                <button type="button" class="form-control btn btn-success addDusun btn-sm ml-1">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        {{-- <div class="col-sm-12 col-md-12 col-lg-6 self-center order-7 d-flex align-items-center">
                            <label class="input-group col-form-label"><i class="bi bi-geo" data-toggle="tooltip" title="" data-original-title="Silahkan copy-paste titik koordinat lokasi kegiatan dan jelaskan nama lokasinya (bisa lebih dari satu lokasi)"></i>
                            </label>
                            <input type="text" class="form-control lang-input flex-grow-1 is-invalid" id="long-1738777836209" name="long[]" placeholder="Longitude">
                            <select class="form-control select2" name="dusun_id" id="dusun_id"></select>
                            <button type="button" class="btn btn-danger remove-staff-row btn-sm ml-1">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div> --}}
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-6 col-md-6 col-lg-6 self-center order-3 order-md-3">
                            <label class="form-label mb-0">RW <small> Banjar</small> <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="rw_banjar">
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 self-center order-4 order-md-4">
                            <label class="form-label mb-0">RT <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="rt" required>
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
    <div class="modal-dialog modal-dialog-scrollable modal modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="ModalEditPesertaTitle">
                    <span class="material-symbols-outlined"> edit_square </span>
                    {{ __('global.edit') .' '. __('cruds.kegiatan.peserta.label') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editDataForm" class="big">
                    <input type="hidden" id="editRowId">

                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                            <label class="form-label mb-0">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editNama" name="nama" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-4 col-lg-4 self-center order-1 order-md-1">
                            <label class="form-label mb-0">No. Telp</label>
                            <input type="text" class="form-control" id="editNoTelp" name="no_telp" placeholder="">
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 self-center order-3 order-md-3">
                            <label class="form-label mb-0">Usia <span class="text-danger">*</span></label>
                            <input type="number" class="form-control usia-input" id="editUsia" name="usia" required>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 self-center order-2 order-md-2">
                            <label class="form-label mb-0">Gender <span class="text-danger">*</span></label>
                            <select class="form-control" id="editGender" name="gender" required>
                                <option value="laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2">
                            <label class="form-label mb-0">Disabilitas</label>
                            <div class="select2-red">
                                <select class="form-select" id="editDisabilitas" name="disabilitas" multiple>
                                    <option value="Fisik">Fisik</option>
                                    <option value="Sensorik">Sensorik</option>
                                    <option value="Intelektual">Intelektual</option>
                                    <option value="Mental">Mental</option>
                                    <option value="Ganda">Ganda</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2">
                            <label class="form-label mb-0">Kelompok Rentan</label>
                            <div class="select2-info">
                                <select class="form-select select2-multiple select2" id="editKelompokRentan" name="kelompok_rentan" multiple>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class='row mb-3'>
                        <div class='col'>
                            <label class='form-label'>Jenis Kelompok / Instansi</label>
                            <input type='text' class='form-control' id='editJenisKelompok' name='jenis_kelompok'/>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-6 col-md-6 col-lg-6 self-center order-1 order-md-1">
                            <label class="form-label mb-0">Desa <span class="text-danger">*</span></label>
                            <div class="select2-info">
                            <select class="form-control select2" id="editDesa" name="desa_id" required>
                                <option value="">Select Desa</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 self-center order-2 order-md-2">
                            <label class="form-label mb-0">Dusun <span class="text-danger">*</span></label>
                            <div class="select2-info">
                                <select class="form-control select2" id="editDusun" name="dusun_id" required>
                                    <option value="">Select Dusun</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- RT and RW fields -->
                    <div class='row mb-3'>
                        <div class='col-sm-6 col-md-6 col-lg-6 self-center order=1 order-md=1'>
                            <label class='form-label mb=0'>RW / Banjar<span	class='text-danger'>*</span></label>
                           	<input type='text' 	class='form-control' id='editRwBanjar' name='rw_banjar' required />
                       	</div>
                           <div class='col-sm-6 col-md-6 col-lg-6 self-center order-2 order-md-2'>
                            <label class='form-label mb-0'>RT<span class='text-danger'>*</span></label>
                            <input type='text' class='form-control' id='editRt' name='rt' required />
                        </div>
                  	</div>
                </form>
            </div>
           	<div class='modal-footer'>
               	<button	type='button' class='btn btn-secondary' data-dismiss='modal'>{{ __('global.close') }}</button>
               	<button	type='button' class='btn btn-primary' id='updateDataBtn'>{{ __('global.update') }}</button>
           	</div>
       	</div>
   	</div>
</div>





