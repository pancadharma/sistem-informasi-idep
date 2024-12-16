<!-- Modal -->
<div class="modal fade" id="ModalTambahPeserta" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ModalTambahPesertaTitle" theme="success">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="ModalTambahPesertaTitle">
                    <i class="bi bi-person-plus"></i>
                    {{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="pesertaForm" class="row">
                    <!-- identitas -->
                    <div class="form-group row">
                       <label for="identitas" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.identitas') }}</label>
                       <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                           <input type="text"
                               class="form-control"
                               id="identitas"
                               name="identitas"
                               pattern="[0-9]+"
                               maxlength="16"
                               placeholder="{{ __('cruds.kegiatan.peserta.placeholder_no_identitas') }}"
                               required
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"
                           >
                           {{-- <small class="form-text text-muted">{{ __('cruds.kegiatan.peserta.helper_no_identitas') }}</small> --}}
                       </div>
                   </div>
                   <!-- identitas -->
                    <div class="form-group row">
                       <label for="no_kk" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.no_kk') }}</label>
                       <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                           <input type="text"
                               class="form-control"
                               id="no_kk"
                               name="no_kk"
                               pattern="[0-9]+"
                               maxlength="16"
                               placeholder="{{ __('cruds.kegiatan.peserta.placeholder_no_kk') }}"
                               required
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)"
                           >
                           {{-- <small class="form-text text-muted">{{ __('cruds.kegiatan.peserta.helper_no_kk') }}</small> --}}
                       </div>
                   </div>
                   <!-- nama kepala keluarga -->
                    <div class="form-group row">
                        <label for="nama_kk" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.nama_kk') }}</label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <input type="text" class="form-control" id="nama_kk" name="nama_kk">
                        </div>
                    </div>
                    <!-- nama peserta / penerima manfaat-->
                    <div class="form-group row">
                        <label for="nama" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.nama') }}</label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                    </div>
                    <!-- jenis kelamin -->
                    <div class="form-group row">
                        <label for="jenis_kelamin" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.jenis_kelamin') }}</label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="pria">{{ __('cruds.kegiatan.peserta.pria') }}</option>
                                <option value="wanita">{{ __('cruds.kegiatan.peserta.wanita') }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- tanggal lahir -->
                    <div class="form-group row">
                        <label for="tanggal_lahir" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.tanggal_lahir') }}</label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="disabilitas" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.disabilitas') }}</label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <select class="form-control" id="disabilitas" name="disabilitas">
                                <option value="0">{{ __('global.no') }}</option>
                                <option value="1">{{ __('global.yes') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="hamil" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.hamil') }}</label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <select class="form-control" id="hamil" name="hamil">
                                <option value="0">{{ __('global.no') }}</option>
                                <option value="1">{{ __('global.yes') }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- status kawin -->
                    <div class="form-group row">
                        <label for="status_kawin" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.status_kawin') }}</label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <select class="form-control" id="status_kawin" name="status_kawin" required>
                                <option value="">{{ __('cruds.kegiatan.peserta.select_status_kawin') }}</option>
                                <option value="belum_menikah">{{ __('cruds.kegiatan.peserta.belum_menikah') }}</option>
                                <option value="menikah">{{ __('cruds.kegiatan.peserta.menikah') }}</option>
                                <option value="cerai">{{ __('cruds.kegiatan.peserta.cerai') }}</option>
                                <option value="cerai_mati">{{ __('cruds.kegiatan.peserta.cerai_mati') }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- jenis peserta (should be a select dropdown ?) -->
                    <div class="form-group row">
                        <label for="jenis_peserta" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">{{ __('cruds.kegiatan.peserta.jenis_peserta') }}</label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <input type="text" class="form-control" id="jenis_peserta" name="jenis_peserta">
                            <!--
                                please suggest a better input for field jenis peserta ( participant type)
                             -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
                <button type="button" class="btn btn-primary" id="saveModalData">{{ __('global.save') }}</button>
            </div>
        </div>
    </div>
</div>

