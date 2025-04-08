<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog    ">
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
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
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

<!-- Modal Edit & Tambah Peserta Penerima Manfaat-->
<x-adminlte-modal id="editDataModal" title="{{ __('global.edit') .' '. __('cruds.kegiatan.peserta.label') }}" theme="info" icon="bi bi-person-plus" size='lg' static-backdrop scrollable>
    <form id="editDataForm" class="big" autocomplete="off">
        <input type="hidden" id="editRowId">

        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.nama') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editNama" name="nama" required>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-1 order-md-1 mb-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.no_telp') }}</label>
                <input type="text" class="form-control" id="editNoTelp" name="no_telp" pattern="^0[0-9]*$" placeholder="081XXXXXXXX" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15">
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-3 order-md-3 mb-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.age') }}<span class="text-danger">*</span></label>
                <input type="number" class="form-control usia-input" id="editUsia" name="usia" required>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-2 order-md-2 mb-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.gender') }} <span class="text-danger">*</span></label>
                <select class="form-control" id="editGender" name="gender" required>
                    <option value="laki">{{ __('cruds.beneficiary.penerima.laki') }}</option>
                    <option value="perempuan">{{ __('cruds.beneficiary.penerima.perempuan') }}</option>
                    <option value="lainnya">{{ __('cruds.beneficiary.penerima.lainnya') }}</option>
                </select>
            </div>
        </div>
        {{-- Edit Marjinal --}}
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.marjinal') }}</label>
                <div class="select2-info">
                    <select class="form-select select2-multiple select2" id="editKelompokRentan" name="kelompok_rentan" multiple>
                    </select>
                </div>
            </div>
        </div>
        {{-- Edit Jenis Kelompok --}}
        <div class='row mb-3'>
            <div class='col'>
                <label class='form-label'>{{ __('cruds.beneficiary.penerima.jenis_kelompok') }}</label>
                <div class="select2-info">
                    <select class="form-control select2-multiple select2" name="jenis_kelompok" id="editJenisKelompok" multiple>
                    </select>
                </div>
            </div>
        </div>
        {{-- Edit Data Provinsi, Kabupaten, Kecamatan, Desa, Dusun --}}
        <div class="row">
            {{-- edit provinsi --}}
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1 mb-3" id="PilihDataProvinsiEdit">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.provinsi.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="provinsi_id" id="provinsi_id_edit" required>
                        <option value="">{{ __('global.select') .' '. __('cruds.provinsi.title') }}</option>
                    </select>
                </div>
            </div>
            {{-- edit kabupaten  --}}
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2 mb-3" id="PilihDataKabupatenEdit">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.kabupaten.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="kabupaten_id" id="kabupaten_id_edit" required>
                        <option value="">{{ __('global.select') .' '. __('cruds.kabupaten.title') }}</option>
                    </select>
                </div>
            </div>
            {{-- edit kecamatan  --}}
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-3 order-md-3 mb-3" id="PilihDataKecamatanEdit">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.kecamatan.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="kecamatan_id" id="kecamatan_id_edit" required>
                        <option value="">{{ __('global.select') .' '. __('cruds.kecamatan.title') }}</option>
                    </select>
                </div>
            </div>
            {{-- edit desa  --}}
            <div class="col-sm-6 col-md-6 col-lg-12 self-center order-4 order-md-4 mb-3">
                <label class="form-label mb-0">{{ __('cruds.desa.title') }} <span class="text-danger">*</span></label>
                <div class="select2-info">
                    <select class="form-control select2" id="desa_id_edit" name="desa_id" required>
                        <option value="">{{ __('global.select') .' '. __('cruds.desa.title') }}</option>
                    </select>
                </div>
            </div>
            {{-- edit dusun  --}}
            <div class="col-sm-6 col-md-6 col-lg-12 self-center order-5 order-md-5 mb-3">
                <label class="form-label mb-0">{{ __('cruds.dusun.title') }} <span class="text-danger">*</span></label>
                <div class="select2-info">
                    <select class="form-control select2" id="dusun_id_edit" name="dusun_id">
                        <option value="">{{ __('global.select') .' '. __('cruds.dusun.title') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- Edit RT and RW fields -->
        <div class='row'>
            <div class='col-sm-6 col-md-6 col-lg-6 self-center order-1 order-md-1 mb-3'>
                <label class='form-label mb-0'>{{ __('cruds.beneficiary.penerima.rw') }}<span	class='text-danger'>*</span></label>
                <input type='text' 	class='form-control' id='editRwBanjar' name='rw' required />
            </div>
            <div class='col-sm-6 col-md-6 col-lg-6 self-center order-2 order-md-2 mb-3'>
                <label class='form-label mb-0'>{{ __('cruds.beneficiary.penerima.rt') }}<span class='text-danger'>*</span></label>
                <input type='text' class='form-control' id='editRt' name='rt' required />
            </div>
        </div>
        {{--Edit Non-AC Kode --}}
        <div class="mb-2 row ml-0">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center icheck-info d-inline">
                <input class="form-check-input" type="checkbox" id="edit_is_non_activity" name="edit_is_non_activity">
                <label class="form-label" for="edit_is_non_activity">{{ __('Non-AC Kode') }}</label>
            </div>
        </div>
        <!-- Edit Activity Select -->
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1" id="pilihActivityEdit">
                <div class="select2-info">
                    <label class="form-label mb-0"><strong>{{ __('cruds.beneficiary.select_activity') }}</strong> <span class="text-danger"></span></label>
                    <select class="form-select select2" name="activitySelectEdit" id="activitySelectEdit" multiple>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
        </div>
        {{-- edit keterangan peserta --}}
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                <div class="select2-teal">
                    <label class="form-label mb-0"><strong>{{ __('cruds.beneficiary.penerima.ket') }}</strong> <span class="text-danger"></span></label>
                    <textarea name="keterangan" id="keterangan_edit" rows="5" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <button	type='button' class='btn btn-secondary' data-dismiss='modal'>{{ __('global.close') }}</button>
        <button	type='button' class='btn btn-primary' id='updateDataBtn'>{{ __('global.update') }}</button>
    </x-slot>
</x-adminlte-modal>


<x-adminlte-modal id="ModalTambahPeserta" title="{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}" theme="teal" icon="bi bi-person-plus" size='lg' static-backdrop scrollable>
    <form id="dataForm" class="big" autocomplete="off">
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.nama') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" required id="nama_beneficiary">
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-1 order-md-1 mb-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.no_telp') }}</label>
                <input type="text" class="form-control" id="no_telp" name="no_telp" pattern="^0[0-9]*$" placeholder="081XXXXXXXX" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15">
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-3 order-md-3 mb-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.age') }} <span class="text-danger">*</span></label>
                <input type="number" class="form-control usia-input" name="usia" required id="umur">
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-2 order-md-2 mb-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.gender') }} <span class="text-danger">*</span></label>
                <select class="form-control" name="gender" required id="jenis_kelamin">
                    <option value="laki">{{ __('cruds.beneficiary.penerima.laki') }}</option>
                    <option value="perempuan">{{ __('cruds.beneficiary.penerima.perempuan') }}</option>
                    <option value="lainnya">{{ __('cruds.beneficiary.penerima.lainnya') }}</option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.marjinal') }}</label>
                <div class="select2-teal">
                    <select class="form-control select2-multiple select2" name="kelompok_rentan" multiple id="kelompok_rentan">
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center d-flex align-items-center mb-3">
                <div class="col-11">
                    <div class="row">
                        <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.jenis_kelompok') }}</label>
                        <div class="col-12 pl-0">
                            <div class="select2-teal">
                                <select class="form-control select2-multiple select2 flex-grow-1" name="jenis_kelompok" multiple id="jenis_kelompok">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-input">
                    <label class="form-label mb-0">&nbsp;</label>
                    <button type="button" class="form-control btn bg-teal btn-sm mr-1" id="addJenisKelompok" data-toggle="modal" data-target="#ModalAddJenisKelompok">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        {{-- Select Data Start From Provinsi until Dusun and RT RW--}}
        <div class="row">
            {{-- provinsi --}}
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1 mb-3" id="PilihDataProvinsi">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.provinsi.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="provinsi_id" id="provinsi_id_tambah" required>
                        <option value="">{{ __('global.select') .' '. __('cruds.provinsi.title') }}</option>
                    </select>
                </div>
            </div>
            {{-- kabupaten --}}
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2 mb-3" id="PilihDataKabupaten">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.kabupaten.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="kabupaten_id" id="kabupaten_id_tambah" required>
                        <option value="">{{ __('global.select') .' '. __('cruds.kabupaten.title') }}</option>
                    </select>
                </div>
            </div>
            {{-- kecamatan --}}
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-3 order-md-3 mb-3" id="PilihDataKecamatan">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.kecamatan.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="kecamatan_id" id="kecamatan_id_tambah" required>
                        <option value="">{{ __('global.select') .' '. __('cruds.kecamatan.title') }}</option>
                    </select>
                </div>
            </div>
            {{-- desa --}}
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-4 order-md-4 mb-3" id="PilihDataDesa">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.desa.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="desa_id" id="desa_id_tambah" required>
                        <option value="">{{ __('global.select') .' '. __('cruds.desa.title') }}</option>
                    </select>
                </div>
            </div>
            {{-- dusun --}}
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-5 order-md-5 d-flex align-items-center mb-3">
                <div class="col-11">
                    <div class="row">
                        <label class="form-label mb-0">{{ __('cruds.dusun.title') }} <span class="text-danger">*</span></label>
                        <div class="col-12 pl-0">
                            <select class="form-control select2 flex-grow-1" name="dusun_id" id="dusun_id_tambah" required>
                                <option value="">{{ __('global.select') .' '. __('cruds.dusun.title') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-input">
                    <label class="form-label mb-0">&nbsp;</label>
                    <button type="button" class="form-control btn bg-teal btn-sm mr-1" id="addDusunBaru" data-toggle="modal" data-target="#ModalDusunBaru">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        {{-- RT RW --}}
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-3 order-md-3 mb-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.rw') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="rw" id="rw">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-4 order-md-4 mb-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.rt') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="rt" id="rt">
            </div>
        </div>
        {{-- Non-AC Kode --}}
        <div class="mb-2 row ml-0">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center icheck-teal d-inline">
                <input class="form-check-input" type="checkbox" id="is_non_activity" name="is_non_activity">
                <label class="form-label" for="is_non_activity">{{ __('Non-AC Kode') }}</label>
            </div>
        </div>
        {{-- select activity --}}
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1" id="pilihActivity">
                <div class="select2-teal">
                    <label class="form-label mb-0"><strong>{{ __('cruds.beneficiary.select_activity') }}</strong> <span class="text-danger"></span></label>
                    <select class="form-select select2 select2-multiple" name="activitySelect" multiple id="activitySelect" >
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
        </div>
        {{-- keterangan peserta --}}
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                <div class="select2-teal">
                    <label class="form-label mb-0"><strong>{{ __('cruds.beneficiary.penerima.ket') }}</strong> <span class="text-danger"></span></label>
                    <textarea name="keterangan" id="keterangan" rows="5" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
        <button type="submit" class="btn btn-primary" id="saveDataBtn">{{ __('global.save') }}</button>
    </x-slot>
</x-adminlte-modal>




