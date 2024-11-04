<div class="col-md-12" id="objectiveContainer">
    <div class="row">
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="deskripsi" class="input-group small mb-0">{{ __('cruds.program.objective.desc') }}</label>
                <textarea type="textarea" id="deskripsi" name="deskripsi[]"
                    class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                    placeholder=" {{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000"></textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="indikator"
                    class="input-group small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
                <textarea id="indikator" name="indikator[]" cols="30"
                    class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1"></textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="target" class="input-group small mb-0">{{ __('cruds.program.outcome.target') }}</label>
                <textarea id="target" name="target[]" cols="30"
                    class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1"></textarea>
            </div>
        </div>
    </div>
</div>