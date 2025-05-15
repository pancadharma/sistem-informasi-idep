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


{{-- Modal tambah --}}
<x-adminlte-modal id="ModalTambah" title="{{ __('global.add') }}" theme="teal" icon="bi bi-person-plus" size='lg' static-backdrop >
    <form id="dataForm" class="big">

        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.nama') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" id="nama" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.no_telp') }}</label>
                <input type="text" class="form-control" id="no_telp" name="no_telp" pattern="^0[0-9]*$" placeholder="081XXXXXXXX" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15" >
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.gender') }} <span class="text-danger">*</span></label>
                <select class="form-control select2 flex-grow-1" name="gender" id="gender" requ ired>
                    {{-- <option value="">-- Pilih --</option> --}}
                    <option value="laki">{{ __('cruds.beneficiary.penerima.laki') }}</option>
                    <option value="perempuan">{{ __('cruds.beneficiary.penerima.perempuan') }}</option>
                    <option value="lainnya">{{ __('cruds.beneficiary.penerima.lainnya') }}</option>
                </select>
            </div>
        </div>
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
                    <button type="button" class="form-control btn btn-success btn-sm ml-1" id="addDusunBaru" data-toggle="modal" data-target="#ModalDusunBaru">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        {{-- pre --}}
        <div class="row mb-3">
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.prepost.pre_score') }} <span class="text-danger"></span></label>
                <input type="number" class="form-control" name="pretest" id="pretest" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
            </div>
        {{-- filledby --}}
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-4 order-md-4">
                <label class="form-label mb-0">{{ __('cruds.prepost.filledby') }}  <span class="text-danger"></span></label>
                <select class="form-control select2 flex-grow-1" name="prefill" id="prefill">
                    {{-- <option value="">-- Pilih --</option> --}}
                    <option value="1">{{ __('cruds.prepost.ya') }} </option>
                    <option value="0">{{ __('cruds.prepost.tidak') }} </option>
                </select>
            </div>
        </div>
        {{-- post --}}
        <div class="row mb-3">
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.prepost.post_score') }}  <span class="text-danger"></span></label>
                <input type="number" class="form-control" name="posttest" id="posttest" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
            </div>
        {{-- filledby --}}
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-4 order-md-4">
                <label class="form-label mb-0">{{ __('cruds.prepost.filledby') }} <span class="text-danger"></span></label>
                <select class="form-control select2 flex-grow-1" name="postfill" id="postfill">
                    {{-- <option value="">-- Pilih --</option> --}}
                    <option value="1">{{ __('cruds.prepost.ya') }} </option>
                    <option value="0">{{ __('cruds.prepost.tidak') }} </option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.prepost.keterangan') }} <span class="text-danger">*</span></label>
                <textarea class="form-control" name="notes" id="notes" cols="30" rows="5"></textarea>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
        <button type="submit" class="btn btn-primary" id="saveDataBtn">{{ __('global.save') }}</button>
    </x-slot>
</x-adminlte-modal>

<!-- Modal Edit -->


<x-adminlte-modal id="editDataModal" title="{{ __('global.edit') .' '. __('cruds.kegiatan.peserta.label') }}" theme="info" icon="bi bi-person-plus" size='lg' static-backdrop scrollable>
    <form id="editDataForm" class="big" autocomplete="off">
        <input type="hidden" id="editRowId" name="id">
        @method('PUT')
        @csrf

        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.nama') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" id="editnama" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-1 order-md-1">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.no_telp') }}</label>
                <input type="text" class="form-control" id="editno_telp" name="notelp" pattern="^0[0-9]*$" placeholder="081XXXXXXXX" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15" required>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2">
                <label class="form-label mb-0">{{ __('cruds.beneficiary.penerima.gender') }} <span class="text-danger">*</span></label>
                <select class="form-control select2 flex-grow-1" name="jeniskelamin" id="editgender" requ ired>
                   
                    <option value="laki">{{ __('cruds.beneficiary.penerima.laki') }}</option>
                    <option value="perempuan">{{ __('cruds.beneficiary.penerima.perempuan') }}</option>
                    <option value="lainnya">{{ __('cruds.beneficiary.penerima.lainnya') }}</option>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            {{-- Prov --}}
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-1 order-md-1" id="PilihDataDesa">
                <div class="form-input">
                    <label class="form-label mb-0"><strong>{{ __('cruds.provinsi.title') }}</strong> <span class="text-danger">*</span></label>
                    <select class="form-control select2" name="provinsi_id" id="editprovinsi_id" required>
                    </select>
                </div>
            </div>
            {{-- Kab/kota --}}
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2 d-flex align-items-center">
                <div class="col-11">
                    <div class="row">
                        <label class="form-label mb-0">{{ __('cruds.kabupaten.title') }} <span class="text-danger">*</span></label>
                        <div class="col-12 pl-0">
                            <select class="form-control select2 flex-grow-1" name="kabupaten_id" id="editkabupaten_id" required></select>
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
                    <select class="form-control select2" name="kecamatan_id" id="editkecamatan_id" required>
                    </select>
                </div>
            </div>
            {{-- Desa --}}
            <div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2 d-flex align-items-center">
                <div class="col-11">
                    <div class="row">
                        <label class="form-label mb-0">{{ __('cruds.desa.title') }} <span class="text-danger">*</span></label>
                        <div class="col-12 pl-0">
                            <select class="form-control select2 flex-grow-1" name="desa_id" id="editdesa_id" required></select>
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
                            <select class="form-control select2 flex-grow-1" name="dusun_id" id="editdusun_id" required></select>
                        </div>
                    </div>
                </div>
                <div class="form-input">
                    <label class="form-label mb-0">&nbsp;</label>
                    <button type="button" class="form-control btn btn-success btn-sm ml-1" id="addDusunBaru" data-toggle="modal" data-target="#ModalDusunBaru">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        
        {{-- pre --}}
        <div class="row mb-3">
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.prepost.pre_score') }} <span class="text-danger"></span></label>
                <input type="number" class="form-control" name="prescore" id="editpretest" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
            </div>
        {{-- filledby --}}
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-4 order-md-4">
                <label class="form-label mb-0">{{ __('cruds.prepost.filledby') }}  <span class="text-danger"></span></label>
                <select class="form-control select2 flex-grow-1" name="filedbytraineepre" id="editprefill">
                    
                    <option value="1">{{ __('cruds.prepost.ya') }} </option>
                    <option value="0">{{ __('cruds.prepost.tidak') }} </option>
                </select>
            </div>
        </div>
        {{-- post --}}
        <div class="row mb-3">
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.prepost.post_score') }}  <span class="text-danger"></span></label>
                <input type="number" class="form-control" name="postscore" id="editposttest" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')" >
            </div>
        {{-- filledby --}}
            <div class="col-sm-6 col-md-6 col-lg-6 self-center order-4 order-md-4">
                <label class="form-label mb-0">{{ __('cruds.prepost.filledby') }} <span class="text-danger"></span></label>
                <select class="form-control select2 flex-grow-1" name="filedbytraineepost" id="editpostfill">
                    
                    <option value="1">{{ __('cruds.prepost.ya') }} </option>
                    <option value="0">{{ __('cruds.prepost.tidak') }} </option>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-12 col-md-12 col-lg-12 self-center order-3 order-md-3">
                <label class="form-label mb-0">{{ __('cruds.prepost.keterangan') }} <span class="text-danger">*</span></label>
                <textarea class="form-control" name="keterangan" id="editnotes" cols="30" rows="5"></textarea>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <button	type='button' class='btn btn-secondary' data-dismiss='modal'>{{ __('global.close') }}</button>
        <button	type='submit' class='btn btn-primary' id='updateDataBtn'>{{ __('global.update') }}</button>
    </x-slot>
</x-adminlte-modal>


