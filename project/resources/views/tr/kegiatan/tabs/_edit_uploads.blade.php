    <!-- Document Uploads -->
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-lg-12">
                <label for="dokumenPendukungDropzone" class="control-label mb-0">
                    <strong>{{ __('cruds.kegiatan.file.upload') }}</strong>
                    <span class="text-red">( {{ __('allowed file: .pdf, .doc, .docx, .xls, .xlsx, .pptx | max: 50 MB') }} )</span>
                </label>
                <div class="form-group">
                    <div id="dokumenPendukungDropzone" style="border: 2px dashed #ccc; padding: 20px; min-height: 150px;"></div>
                </div>
                <div id="captions-container-docs"></div>
            </div>
        </div>
    </div>

    <!-- Media Photo/Video Uploads -->
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-lg-12">
                <label for="mediaPendukungDropzone" class="control-label mb-0">
                    <strong>{{ __('cruds.kegiatan.file.upload_media') }}</strong>
                    <span class="text-red">( {{ __('allowed file: .jpg, .png, .jpeg | max: 50 MB') }} )</span>
                </label>
                <div class="form-group">
                    <div id="mediaPendukungDropzone" style="border: 2px dashed #ccc; padding: 20px; min-height: 150px;"></div>
                </div>
                <div id="captions-container-media"></div>
            </div>
        </div>
    </div>
@push('basic_tab_js')
    @include('tr.kegiatan.js._dropzone_script')
@endpush
