{{-- delete the form tag --}}
<div class="col-md-12">
    <div class="form-group">
        <label for="donor" class="small control-label">
            <strong>
                {{ __('cruds.program.donor.label') }}
            </strong>
        </label>
        <div class="select2-orange">
            <select class="form-control select2" name="donor[]" id="donor" multiple required></select>
        </div>
    </div>
</div>
<div class="col-md-12" id="pendonor-container">
    {{-- data donor select2 will appear here --}}
</div>

{{-- WANT TO PUT THE JS HERE INSTEAD at js/donor.blade.php --}}
{{-- Use @push('js') close by @endpush --}}