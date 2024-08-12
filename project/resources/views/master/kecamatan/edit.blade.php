<div class="modal fade" id="editKecamatanModal" tabindex="-1" role="dialog" aria-labelledby="editKecamatanModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    {{ trans('global.create')}} {{trans('cruds.kecamatan.title')}}
                    <div class="card-tools">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="card card-primary">
                    <div class="card-body">
                        <form @submit.prevent="handleSubmit" method="PATCH" class="resettable-form" id="editKecamatanForm" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <input type="hidden" name="id" id="id">
                                <select id="provinsi_id" name="provinsi_id" class="form-control select2 provinsi-data" style="width: 100%"></select>
                            </div>
                            <div class="form-group">
                                <label for="kabupaten_nama">{{ trans('cruds.kabupaten.nama') }} {{ trans('cruds.kabupaten.title') }}</label>
                                <div class="form-group">
                                    <select id="kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" style="width: 100%" required>
                                        <option>{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="kode">{{ trans('cruds.kecamatan.kode') .' '. trans('cruds.kecamatan.title') }}</label>
                                <input placeholder="" type="text" id="editkode" name="kode" class="form-control" required data-placement="left" title="Update {{ trans('cruds.kecamatan.kode') .' '. trans('cruds.kecamatan.title') }}" data-toggle="tooltip" data-placement="top" maxlength="8">
                            </div>
                            <div class="form-group">
                                <label for="nama">{{ trans('cruds.kecamatan.nama') }}</label>
                                <input type="text" id="editnama" name="nama" class="form-control" required maxlength="200">
                            </div>
                
                            <div class="form-group">
                                <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.kecamatan.title') }}</strong>
                                <div class="icheck-primary">
                                    <input type="checkbox" id="editaktif" {{ old('aktif') == 1 ? 'checked' : '' }}>
                                    <input type="hidden" name="aktif" id="edit-aktif" value="0">
                                    <label for="editaktif">{{ trans('cruds.status.aktif', [], 'en') ?: trans('cruds.status.tidak_aktif', [], 'en') }}</label>
                                </div>
                            </div>
                            <button type="submit" id="editKecamatan" class="btn btn-success float-right" ><i class="fas fa-update"></i> {{ trans('global.update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
