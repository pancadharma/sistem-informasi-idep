{{-- Document Uploads --}}
<div class="card-body pt-0">
    <div class="row">
        <div class="col-lg-12">
            <label for="dokumen_pendukung" class="control-label mb-0">
                <strong>
                    {{ __('cruds.kegiatan.file.upload') }}
                </strong>
                <span class="text-red">
                    {{-- ONLY FOR DOCUMENT FILES ONLY --}}
                    ( {{ __('allowed file: .pdf, .doc, .docx, .xls, .xlsx, .pptx | max: 50 MB') }} )
                </span>
            </label>
            <div class="form-group file-loading">
                <input id="dokumen_pendukung" name="dokumen_pendukung[]" type="file" class="form-control" multiple
                    data-show-upload="false" data-show-caption="true">
            </div>
            <div id="captions-container-docs"></div>
        </div>
    </div>
</div>

{{-- Media Photo/Video Uploads --}}
<div class="card-body pt-0">
    <div class="row">
        <div class="col-lg-12">
            <label for="media_pendukung" class="control-label mb-0">
                <strong>
                    {{ __('cruds.kegiatan.file.upload_media') }}
                </strong>
                <span class="text-red">
                    {{-- ONLY FOR MEDIA FILES ONLY --}}
                    ( {{ __('allowed file: .jpg, .png, .jpeg | max: 50 MB') }} )
                </span>
            </label>
            <div class="form-group file-loading">
                <input id="media_pendukung" name="media_pendukung[]" type="file" class="form-control" multiple
                    data-show-upload="false" data-show-caption="true">
            </div>
            <div id="captions-container-media"></div>
        </div>
    </div>
</div>

@push('basic_tab_js')
    @include('tr.kegiatan.js._uploads')
@endpush
