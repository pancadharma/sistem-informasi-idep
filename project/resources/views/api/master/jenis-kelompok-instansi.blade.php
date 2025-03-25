<form id="submit_dusun" action="" method="POST" autocomplete="off" novalidate>
    @csrf
    @method('POST')

    <div class="form-group">
        <label for="jenis_kelompok_add">{{ __('') }}</label>
        <input type="text" id="jenis_kelompok_add" name="jenis_kelompok_add" class="form-control" required maxlength="200" pattern="^[A-Za-z][A-Za-z0-9 .]*$" title="Must start with a letter.">
    </div>

    <div class="form-group">
        <strong>{{ __('cruds.status.title') }}</strong>
        <div class="icheck-primary">
            <input type="checkbox" name="aktif" id="aktif" {{ old('aktif', 1) == 1 ? 'checked' : '' }} value="1">
            <label for="aktif">{{ __('cruds.status.aktif') }}</label>
        </div>
    </div>

    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close')}}</button>
    <button class="btn btn-success float-right btn-add-jenis-kelompok" type="submit"><i class="fas fa-save"></i> {{ __('global.save') }}</button>
</form>
