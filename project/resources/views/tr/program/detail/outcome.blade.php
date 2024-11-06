{{-- FIX CODE FOR SINGLE VIEW OF PROGRAM OUTCOME --}}
<div class="col-md-12" id="outcomeContainer">
    <!-- Labels for the fields -->
    <div class="row">
        <div class="col-lg-4 form-group">
            <label for="deskripsi" class="input-group small mb-0">{{ __('cruds.program.outcome.desc') }}</label>
        </div>
        <div class="col-lg-4 form-group">
            <label for="indikator" class="input-group small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
        </div>
        <div class="col-lg-4 form-group">
            <label for="target" class="input-group small mb-0">{{ __('cruds.program.outcome.target') }}</label>
        </div>
    </div>

    @if($program->outcome->isEmpty())
        <!-- Show a single set of fields if there are no existing outcomes -->
        <div class="row">
            <div class="col-lg-4 form-group">
                <div class="input-group">
                    <textarea id="deskripsi" name="deskripsi[]" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000"></textarea>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="input-group">
                    <textarea id="indikator" name="indikator[]" class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1"></textarea>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="input-group">
                    <textarea id="target" name="target[]" class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1"></textarea>
                    <span class="input-group-append">
                        <button type="button" class="ml-2 btn btn-success form-control addOutcome btn-flat" data-target="outcome-container_1"><i class="bi bi-plus"></i></button>
                    </span>
                </div>
            </div>
        </div>
    @else
        @foreach ($program->outcome as $index => $outcome)
            <div class="row">
                <div class="col-lg-4 form-group">
                    <div class="input-group">
                        <textarea id="deskripsi_{{ $index }}" name="deskripsi[]" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000">{{ old('deskripsi.' . $index, $outcome->deskripsi) }}</textarea>
                        <input type="hidden" name="outcome_id[]" value="{{ $outcome->id }}"> <!-- Hidden input for outcome ID -->
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="input-group">
                        <textarea id="indikator_{{ $index }}" name="indikator[]" class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1">{{ old('indikator.' . $index, $outcome->indikator) }}</textarea>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="input-group">
                        <textarea id="target_{{ $index }}" name="target[]" class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1">{{ old('target.' . $index, $outcome->target) }}</textarea>
                        <span class="input-group-append">
                            @if ($index === 0)
                                <button type="button" class="ml-2 btn btn-success form-control addOutcome btn-flat" data-target="outcome-container_{{ $index + 1 }}"><i class="bi bi-plus"></i></button>
                            @else
                                <button type="button" class="ml-2 btn btn-danger form-control removeButton btn-flat"><i class="bi bi-trash"></i></button>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="row hehe d-none" id="outcomeTemplate">
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <textarea type="textarea" name="deskripsi[]" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000"></textarea>
            <input type="hidden" name="outcome_id[]"> <!-- Leave empty for new outcomes -->
        </div>
    </div>
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <textarea name="indikator[]" class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1"></textarea>
        </div>
    </div>
    <div class="col-lg-4 form-group">
        <div class="input-group">
            <textarea name="target[]" class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}" placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1"></textarea>
            <span class="input-group-append">
                <button type="button" class="ml-2 btn btn-danger form-control removeButton btn-flat">
                    <i class="bi bi-trash"></i>
                </button>
            </span>
        </div>
    </div>
</div>
