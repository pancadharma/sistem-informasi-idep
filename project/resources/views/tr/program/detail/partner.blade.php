{{-- delete the form tag --}}
<div class="col-md-12" id="partnerContainer">
        <div class="col-md-6">
            <div class="form-group">
                <label for="partner" class="small control-label">
                    <strong>
                        {{ __('cruds.program.partner.label') }}
                    </strong>
                </label>
                <div class="select2-orange">
                    <select class="form-control select2" name="partner[]" id="partner" multiple="multiple">
                    </select>
                </div>
            </div>
        </div>
</div>