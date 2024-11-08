<div class="col-md-12" id="goalContainer">
    <div class="row">
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="goals_deskripsi" class="input-group small mb-0">{{ __('cruds.program.objective.desc') }}</label>
                <textarea type="textarea" id="goals_deskripsi" name="goals_deskripsi"
                    class="form-control {{ $errors->has('goals_deskripsi') ? 'is-invalid' : '' }}"
                    placeholder=" {{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000">{{ old('goals_deskripsi', $program->goal->deskripsi ?? '') }}
                </textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="goals_indikator" class="input-group small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
                <textarea id="indikator" name="goals_indikator" cols="30"
                    class="form-control {{ $errors->has('goals_indikator') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1">{{ old('goals_indikator', $program->goal->indikator ?? '') }}</textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="goals_target" class="input-group small mb-0">{{ __('cruds.program.outcome.target') }}</label>
                <textarea id="goals_target" name="goals_target" cols="30"
                    class="form-control {{ $errors->has('goals_target') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1">{{ old('goals_target', $program->goal->target ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>
