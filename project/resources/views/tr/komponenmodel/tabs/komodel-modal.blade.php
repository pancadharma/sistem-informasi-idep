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

<!-- Modal Edit -->
<x-adminlte-modal id="editDataModal" title="{{ __('global.edit') .' '. __('cruds.kegiatan.peserta.label') }}" theme="teal" icon="bi bi-person-plus" size='lg' static-backdrop>
    <form id="editDataForm" class="big">
        <input type="hidden" id="editRowId">

        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.nama') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editNama" name="nama" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.no_telp') }}</label>
                <input type="text" class="form-control" id="editNoTelp" name="no_telp" pattern="^0[0-9]*$" placeholder="081XXXXXXXX" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15">
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.age') }}<span class="text-danger">*</span></label>
                <input type="number" class="form-control usia-input" id="editUsia" name="usia" required>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 self-center order-2 order-md-2">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.gender') }} <span class="text-danger">*</span></label>
                <select class="form-control" id="editGender" name="gender" required>
                    <option value="laki">{{ __('cruds.beneficiary.penerima.laki') }}</option>
                    <option value="perempuan">{{ __('cruds.beneficiary.penerima.perempuan') }}</option>
                    <option value="lainnya">{{ __('cruds.beneficiary.penerima.lainnya') }}</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-2 order-md-2">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.disability') }}</label>
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
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.marjinal') }}</label>
                <div class="select2-info">
                    <select class="form-select select2-multiple select2" id="editKelompokRentan" name="kelompok_rentan" multiple>
                    </select>
                </div>
            </div>
        </div>
        <div class='row mb-3'>
            <div class='col'>
                <label class='form-label'>{{ __('cruds.beneficiary.penerima.jenis_kelompok') }}</label>
                {{-- <input type='text' class='form-control' id='editJenisKelompok' name='jenis_kelompok'/> --}}
                <div class="select2-cyan">
                    <select class="form-control select2-multiple select2" name="jenis_kelompok" id="editJenisKelompok">
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.desa.title') }} <span class="text-danger">*</span></label>
                <div class="select2-info">
                <select class="form-control select2" id="editDesa" name="desa_id" required>
                    <option value="">Select Desa</option>
                </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-2 order-md-2">
                <label class="form-label mb-0">{{ __('cruds.dusun.title') }} <span class="text-danger">*</span></label>
                <div class="select2-info">
                    <select class="form-control select2" id="editDusun" name="dusun_id">
                        <option value="">Select Dusun</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- RT and RW fields -->
        <div class='row mb-3'>
            <div class='col-sm-6 col-md-6 col-lg-6 self-center order=1 order-md=1'>
                <label class='form-label mb=0'>{{ __('cruds.beneficiary.penerima.rw') }}<span	class='text-danger'>*</span></label>
                <input type='text' 	class='form-control' id='editRwBanjar' name='rw_banjar' required />
            </div>
            <div class='col-sm-6 col-md-6 col-lg-6 self-center order-2 order-md-2'>
                <label class='form-label mb-0'>{{ __('cruds.beneficiary.penerima.rt') }}<span class='text-danger'>*</span></label>
                <input type='text' class='form-control' id='editRt' name='rt' required />
            </div>
        </div>
        <!-- Activity Select -->
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1" id="pilihActivityEdit">
                <div class="select2-green">
                    <label class="form-label mb-0"><strong>{{ __('Select Activities') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-select select2" name="activitySelectEdit" id="activitySelectEdit" multiple>
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <button	type='button' class='btn btn-secondary' data-dismiss='modal'>{{ __('global.close') }}</button>
        <button	type='button' class='btn btn-primary' id='updateDataBtn'>{{ __('global.update') }}</button>
    </x-slot>
</x-adminlte-modal>

{{-- Modal tambah --}}
<x-adminlte-modal id="ModalTambah" title="{{ __('global.add') }}" theme="teal" icon="bi bi-person-plus" size='lg' static-backdrop >
    <form id="dataForm" class="big">

        <div class="row mb-3">
            {{-- Prov --}}
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-1 order-md-1" id="PilihDataDesa">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.provinsi.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="provinsi_id" id="pilihprovinsi_id" required>
                    </select>
                </div>
            </div>
            {{-- Kab/kota --}}
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2 d-flex align-items-center">
                <div class="col-11">
                    <div class="row">
                        <label class="form-label mb-0">{{ __('cruds.kabupaten.title') }} <span class="text-danger">*</span></label>
                        <div class="col-12 pl-0">
                            <select class="form-control select2 flex-grow-1" name="kabupaten_id" id="pilihkabupaten_id" required></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            {{-- Kecamatan --}}
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-1 order-md-1" id="PilihDataDesa">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.kecamatan.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="kecamatan_id" id="pilihkecamatan_id" required>
                    </select>
                </div>
            </div>
            {{-- Desa --}}
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2 d-flex align-items-center">
                <div class="col-11">
                    <div class="row">
                        <label class="form-label mb-0">{{ __('cruds.desa.title') }} <span class="text-danger">*</span></label>
                        <div class="col-12 pl-0">
                            <select class="form-control select2 flex-grow-1" name="desa_id" id="pilihdesa_id" required></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            {{-- Dusun --}}
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2 d-flex align-items-center">
                <div class="col-11">
                    <div class="row">
                        <label class="form-label mb-0">{{ __('cruds.dusun.title') }} <span class="text-danger">*</span></label>
                        <div class="col-12 pl-0">
                            <select class="form-control select2 flex-grow-1" name="dusun_id" id="pilihdusun_id" required></select>
                        </div>
                    </div>
                </div>
                <div class="form-input">
                    <label class="form-label mb-0">&nbsp;</label>
                    {{-- <button type="button" class="form-control btn btn-success btn-sm ml-1" id="addDusunBaru" data-toggle="modal" data-target="#ModalDusunBaru">
                        <i class="bi bi-plus"></i>
                    </button> --}}
                </div>
            </div>
        </div>
        {{-- longtitude --}}
        <div class="row mb-3">
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.kegiatan.lat') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="lat" pattern="^-?([1-8]?[0-9](\.\d+)?|90(\.0+)?)$" required>
            </div>
        {{-- latitude --}}
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-4 order-md-4">
                <label class="form-label mb-0">{{ __('cruds.kegiatan.long') }}  <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="long" pattern="^-?((1[0-7][0-9]|[1-9]?[0-9])(\.\d+)?|180(\.0+)?)$" required>
            </div>
        </div>
        {{-- Jumlah --}}
        <div class="row mb-3">
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.komponenmodel.jumlah') }}  <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="jumlah" required pattern="[0-9]*" inputmode="numeric">
            </div>
        {{-- Satuan --}}
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-4 order-md-4">
                <label class="form-label mb-0">{{ __('cruds.satuan.title') }} <span class="text-danger">*</span></label>
                <select class="form-control select2 flex-grow-1" name="satuan_id" id="satuan_id" required></select>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
        <button type="submit" class="btn btn-primary" id="saveDataBtn">{{ __('global.save') }}</button>
    </x-slot>
</x-adminlte-modal>




