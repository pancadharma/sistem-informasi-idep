{{-- delete the form tag --}}
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label for="deskripsi" class="control-label small mb-0">{{ __('cruds.program.outcome.desc') }}</label>
            <textarea type="textarea" id="deskripsi" name="deskripsi" class="form-control"
                placeholder="{{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="500"></textarea>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="indikator" class="control-label small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
            <textarea id="indikator" name="indikator" cols="30"
                class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}"
                placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="500" rows="1"></textarea>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="target" class="control-label small mb-0">{{ __('cruds.program.outcome.target') }}</label>
            <textarea id="target" name="target" cols="30"
                class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}"
                placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="500" rows="1"></textarea>
        </div>
    </div>
</div>
<div class="col-md-12" id="pendonor-container">

</div>

{{-- WANT TO PUT THE JS HERE INSTEAD at js/donor.blade.php --}}
{{-- Use @push('js') close by @endpush --}}
