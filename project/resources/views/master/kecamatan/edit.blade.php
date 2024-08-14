<x-adminlte-modal id="editKecamatanModal" title=" {{ trans('global.update') .' '.trans('cruds.kecamatan.title')}}" size="lg" theme="info" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        {{-- <div class="modal-title">
            {{ trans('global.create')}} {{trans('cruds.kecamatan.title')}}
            <div class="card-tools">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div> --}}
        <div class="card-body">
            <form action="#" @submit.prevent="handleSubmit" method="PATCH" class="resettable-form" id="editKecamatanForm" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <input type="hidden" name="id" id="id">
                    <label class="required" for="edit_provinsi_id">{{ trans('cruds.provinsi.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                    <select class="form-control select2" name="provinsi_id" id="edit_provinsi_id" required style="width: 100%">
                        @foreach($provinsi as $data => $entry)
                            <option value="{{ $data }}" {{ old('provinsi_id') == $data ? 'selected' : '' }}>{{ $data }} - {{ $entry }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_kabupaten_id">{{ trans('cruds.kabupaten.nama') }} {{ trans('cruds.kabupaten.title') }}</label>
                    <div class="form-group">
                        <select id="edit_kabupaten_id" name="kabupaten_id" class="form-control select2 kabupaten-data" style="width: 100%" required>
                            <option>{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}</option>
                        </select>
                        <div id="edit_kabupaten_id-error" class="invalid-feedback"></div>
                        <div id="edit_kabupaten-error" class="invalid-feedback"></div>
                        <span id="edit_kabupaten_error" class="invalid-feedback">{{ trans('cruds.kecamatan.kode_validation') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="editkode">{{ trans('cruds.kecamatan.kode') .' '. trans('cruds.kecamatan.title') }}</label>
                    <input placeholder="" type="text" id="editkode" name="kode" class="form-control" required data-placement="left" title="Update {{ trans('cruds.kecamatan.kode') .' '. trans('cruds.kecamatan.title') }}" data-toggle="tooltip" data-placement="top" maxlength="8">

                    <div id="editkode-error" class="invalid-feedback"></div>
                    <span id="editkode_error" class="invalid-feedback">{{ trans('cruds.kecamatan.kode_validation') }}</span>
                </div>
                <div class="form-group">
                    <label for="editnama">{{ trans('cruds.kecamatan.nama') }}</label>
                    <input type="text" id="editnama" name="nama" class="form-control" required maxlength="200">

                    <div id="editnama-error" class="invalid-feedback"></div>
                    <span id="editnama_error" class="invalid-feedback">{{ trans('cruds.kecamatan.nama_validation') }}</span>
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
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>