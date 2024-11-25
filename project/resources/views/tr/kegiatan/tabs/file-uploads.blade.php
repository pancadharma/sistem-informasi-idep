{{-- File Uploads --}}
<div class="card-body pt-0">
    <div class="row">
        <div class="col-lg-12">
            <label for="file_pendukung" class="control-label small mb-0">
                <strong>
                    {{ __('cruds.program.upload') }}
                </strong>
                <span class="text-red">
                    ( {{ __('allowed file: .jpg .png .pdf .docx | max: 4MB') }} )
                </span>
            </label>
            <div class="form-group file-loading">
                <input id="file_pendukung" name="file_pendukung[]" type="file" class="form-control" multiple data-show-upload="false" data-show-caption="true">
            </div>
            <div id="captions-container"></div>
        </div>
    </div>
</div>
