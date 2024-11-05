{{-- delete the form tag --}}
<div class="col-md-12" id="staffContainer">
      <div class="row">  
        <div class="col-md-6">
            <div class="form-group">
                <label for="lokasi" class="small control-label">
                    <strong>
                        {{ __('cruds.program.staff.label') }}
                    </strong>
                </label>
                <div class="select2-orange">
                    <select class="form-control select2" name="staff[]" id="staff" multiple="multiple">
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="lokasi" class="small control-label">
                    <strong>
                        {{ __('cruds.program.staff.peran') }}
                    </strong>
                </label>
                <div class="select2-orange">
                    <select class="form-control select2" name="peran[]" id="peran" multiple="multiple">
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>