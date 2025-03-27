 <div class="modal fade" id="ModalAddJenisKelompok" tabindex="-1" role="dialog" aria-labelledby="ModalAddJenisKelompokLabel"
    aria-hidden="true" >
    <div class="modal-dialog modal-dialog-scrollable ">
        <div class="modal-content">
            <div class="modal-header bg-red">
                <h5 class="modal-title" id="ModalAddJenisKelompokLabel">
                    {{ __('global.create') . ' ' . __('cruds.beneficiary.penerima.jenis_kelompok') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="add_jenis_kelompok" class="add_jenis_kelompok">
                    <form id="tambah_jenis_kelompok" action="" method="POST" autocomplete="off" novalidate>
                        @csrf
                        @method('POST')
                    
                        <div class="form-group">
                            <label for="jenis_kelompok_add">{{ __('cruds.beneficiary.penerima.jenis_kelompok') }}</label>
                            <input type="text" id="jenis_kelompok_add" name="jenis_kelompok_add" class="form-control" required maxlength="200" pattern="^[A-Za-z][A-Za-z0-9 .]*$" title="Must start with a letter.">
                        </div>
                        <div class="form-group">
                            <strong>{{ __('cruds.status.title') }}</strong>
                            <div class="icheck-primary">
                                <input type="checkbox" name="aktif" id="aktif" {{ old('aktif', 1) == 1 ? 'checked' : '' }} value="1">
                                <label for="aktif">{{ __('cruds.status.aktif') }}</label>
                            </div>
                        </div>
                        <button class="btn btn-secondary float-left" type="button" data-dismiss="modal">{{ __('global.close')}}</button>
                        <button class="btn btn-success float-right btn-add-jenis-kelompok" type="button"><i class="fas fa-save"></i> {{ __('global.save') }}</button>
                    </form>                    
                </div>
                <!--
                <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
                     <button type="submit" class="btn btn-primary" id="save_jenis_kelompok">{{ __('global.save') }}</button>
                    </div>
                -->
            </div>
        </div>
    </div>
 </div>