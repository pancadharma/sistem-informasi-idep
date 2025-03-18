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
                <input id="dokumen_pendukung" name="dokumen_pendukung[]" type="file" class="form-control" multiple data-show-upload="false" data-show-caption="true">
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
                <input id="media_pendukung" name="media_pendukung[]" type="file" class="form-control" multiple data-show-upload="false" data-show-caption="true">
            </div>
            <div id="captions-container-media"></div>
        </div>
    </div>
</div>

@push('basic_tab_js')
<script>
    $(document).ready(function () {
        var docFileIndex = 0;
        var mediaFileIndex = 0;
        var docFileCaptions = {}; // Object to track document files and captions
        var mediaFileCaptions = {}; // Object to track media files and captions

        function handleFileInput(fileInputId, captionContainerId, fileCaptions, allowedExtensions, maxSize) {
            $("#" + fileInputId).fileinput({
                theme: "fa5",
                showBrowse: false,
                showUpload: false,
                showRemove: false,
                showCaption: true,
                showDrag: true,
                uploadAsync: false,
                browseOnZoneClick: true,
                maxFileSize: maxSize,
                maxFilePreviewSize: 10240,
                allowedFileExtensions: allowedExtensions,
                overwriteInitial: false,
                initialPreview: [], // Initialize with empty preview
                initialPreviewConfig: []
            }).on('fileloaded', function(event, file, previewId, index, reader) {
                var uniqueId = fileInputId + '-' + (new Date().getTime());

                fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                // $('#' + captionContainerId).append(
                //     `<div class="form-group" id="caption-group-${uniqueId}">
                //         <label for="caption-${uniqueId}"> {{ __('cruds.program.ket_file') }} ${file.name}</label>
                //         <input type="text" class="form-control" name="keterangan[]" id="keterangan-${uniqueId}">
                //     </div>`
                // );
                // Store the unique identifier in the file preview element
                // $(`#${previewId}`).attr('data-unique-id', uniqueId);
                $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);

            }).on('fileremoved', function(event, id) {
                // Remove the corresponding caption input
                // var uniqueId = $(`#${id}`).attr('data-unique-id');
                var uniqueId = $(`#${$.escapeSelector(id)}`).attr('data-unique-id');
                delete fileCaptions[uniqueId]; // Remove the file from the tracking object
                $(`#caption-group-${uniqueId}`).remove();
            }).on('fileclear', function(event) {
                // Clear all caption inputs when files are cleared
                fileCaptions = {}; // Reset the tracking object
                $('#' + captionContainerId).empty();
            }).on('filebatchselected', function(event, files) {
                // Clear all caption inputs when new files are selected
                fileCaptions = {}; // Reset the tracking object
                $('#' + captionContainerId).empty();
                // Iterate over each selected file and trigger the fileloaded event manually
                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var uniqueId = fileInputId + '-' + (new Date().getTime());
                    fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                    $('#' + captionContainerId).append(
                        `<div class="form-group" id="caption-group-${uniqueId}">
                            <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                            <input type="text" class="form-control" name="keterangan[]" id="caption-${uniqueId}">
                        </div>`
                    );
                }
            });
        }

        handleFileInput('dokumen_pendukung', 'captions-container-docs', docFileCaptions, ['docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx', 'pdf'], 55240);
        handleFileInput('media_pendukung', 'captions-container-media', mediaFileCaptions, ['jpg', 'png', 'jpeg'], 55240);
    });
</script>
@endpush
