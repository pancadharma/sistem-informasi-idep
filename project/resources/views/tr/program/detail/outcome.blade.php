<div class="col-md-12" id="outcomeContainer">
    <div class="row">
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="deskripsi" class="input-group small mb-0">{{ __('cruds.program.outcome.desc') }}</label>
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
                <span class="input-group-append">
                    <button type="button" class="ml-2 btn btn-success form-control addOutcome btn-flat"
                        data-target="outcome-container_1"><i class="bi bi-plus"></i></button>
                </span>
            </div>
        </div>
    </div>
</div>


<div class="row hehe d-none" id="outcomeTemplate">
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <label for="deskripsi" class="input-group small mb-0">{{ __('cruds.program.outcome.desc') }}</label>
            <textarea type="textarea" name="deskripsi[]" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                placeholder="{{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000"></textarea>
        </div>
    </div>
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <label for="indikator" class="input-group small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
            <textarea name="indikator[]" class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}"
                placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1"></textarea>
        </div>
    </div>
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <label for="target" class="input-group small mb-0">{{ __('cruds.program.outcome.target') }}</label>
            <textarea name="target[]" class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}"
                placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1"></textarea>
            <span class="input-group-append">
                <button type="button" class="ml-2 btn btn-danger form-control removeButton btn-flat">
                    <i class="bi bi-trash"></i>
                </button>
            </span>
        </div>
    </div>
</div>
