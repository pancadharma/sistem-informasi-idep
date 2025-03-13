<div class="col-md-12" id="objectiveContainer">
    <div class="row">
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="objektif_deskripsi" class="input-group small mb-0">{{ __('cruds.program.objective.desc') }}</label>
                <textarea type="objektif_deskripsi" id="objektif_deskripsi" name="objektif_deskripsi"
                    class="form-control {{ $errors->has('objektif_deskripsi') ? 'is-invalid' : '' }}"
                    placeholder=" {{ __('cruds.program.outcome.desc') }}" rows="5" maxlength="10000">{{ old('objektif_deskripsi', $program->objektif->deskripsi ?? '') }}</textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="objektif_indikator" class="input-group small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
                <textarea id="objektif_indikator" name="objektif_indikator" cols="30"
                    class="form-control {{ $errors->has('objektif_indikator') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="10000" rows="5">{{ old('objektif_indikator', $program->objektif->indikator ?? '') }}</textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="objektif_target" class="input-group small mb-0">{{ __('cruds.program.outcome.target') }}</label>
                <textarea id="objektif_target" name="objektif_target" cols="30"
                    class="form-control {{ $errors->has('objektif_target') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="10000" rows="5">{{ old('objektif_target', $program->objektif->target ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>
