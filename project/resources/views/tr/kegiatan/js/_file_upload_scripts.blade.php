{{-- <script>
    $(document).ready(function() {
    /**
     * Initializes a Bootstrap FileInput instance with caption generation.
     * @param {string} fileInputId - The ID of the file input element.
     * @param {string} captionContainerId - The ID of the container for caption fields.
     * @param {string} captionInputName - The base name for the caption input fields (e.g., 'keterangan_dokumen').
     * @param {string[]} allowedExtensions - Array of allowed file extensions.
     * @param {number} maxSize - Maximum file size in KB.
     * @param {number} maxCount - Maximum number of files.
     * @param {any[]} initialPreview - Array of initial preview content (URLs).
     * @param {object[]} initialPreviewConfig - Array of configuration objects for initial previews.
     */
    function handleFileInput(
        fileInputId,
        captionContainerId,
        captionInputName,
        allowedExtensions,
        maxSize,
        maxCount,
        initialPreview,
        initialPreviewConfig
    ) {
        const captionContainer = $('#' + captionContainerId);

        // 1. Create caption fields for existing files passed from the server.
        initialPreviewConfig.forEach(config => {
            const fileId = config.key;
            const fileName = config.caption;
            // Assumes the controller adds 'keterangan' to the 'extra' object in the config
            const captionValue = config.extra?.keterangan || '';
            const captionText = config.caption || fileName; // Fallback to fileName if caption is not set
            const name = `keterangan_existing[${fileId}]`; // Specific name for existing file captions to link them by ID

            captionContainer.append(
                `<div class="form-group" id="caption-group-${fileId}">
                    <label class="control-label mb-0 small mt-2" for="keterangan-${fileId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${fileName}</span></label>
                    <input type="text" class="form-control" name="${captionText}" id="keterangan-${fileId}" value="${captionValue}">
                </div>`
            );
        });

        $("#" + fileInputId).fileinput({
            theme: "fa5",
            showBrowse: false,
            showUpload: false,
            showRemove: true, // Set to true to allow removing files
            showCaption: true,
            browseOnZoneClick: true,
            uploadAsync: false,
            overwriteInitial: false,
            maxFileSize: maxSize,
            maxFileCount: maxCount,
            allowedFileExtensions: allowedExtensions,
            initialPreviewAsData: true,
            initialPreview: initialPreview,
            initialPreviewConfig: initialPreviewConfig,
            previewFileIconSettings: {
                'doc': '<i class="fas fa-file-word text-primary"></i>',
                'docx': '<i class="fas fa-file-word text-primary"></i>',
                'xls': '<i class="fas fa-file-excel text-success"></i>',
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pptx': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                'htm': '<i class="fas fa-file-code text-info"></i>',
                'txt': '<i class="fas fa-file-alt text-info"></i>',
            },
            // Custom error messages
            msgTooManyFiles: `You can upload up to ${maxCount} files only!`,
            msgFilesTooMany: 'Number of files selected ({n}) exceeds the maximum allowed limit of {m}.',

        }).on('fileloaded', function(event, file, previewId, index, reader) {
            // 2. This event fires for each NEW file selected by the user.
            // Create a unique ID for the new file's caption group.
            const uniqueId = 'new-' + file.lastModified + '-' + file.size;

            // Add the caption input field for the new file.
            captionContainer.append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                    <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">Caption for: <span class="text-primary">${file.name}</span></label>
                    <input type="text" class="form-control" name="${captionInputName}_new[]" id="caption-${uniqueId}">
                </div>`
            );
            // Store this unique ID on the preview element so we can find it on removal.
            $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);

        }).on('fileremoved', function(event, id) {
            // 3. This event fires when a NEW file (not an initial one) is removed from the preview.
            const uniqueId = $(`#${$.escapeSelector(id)}`).data('unique-id');
            if (uniqueId) {
                $(`#caption-group-${uniqueId}`).remove();
            }
        }).on('fileclear', function(event) {
            // 4. When the "clear" button is hit, remove all NEW file captions.
            captionContainer.find('div[id^="caption-group-new-"]').remove();

        }).on('filepredelete', function(event, key, jqXHR, data) {
            // 5. This event fires just before an INITIAL file is deleted via the server URL.
            // Remove the corresponding caption input for the file being deleted.
            $(`#caption-group-${key}`).remove();
        });
    }

    // Initialize Document Uploader
    handleFileInput(
        'dokumen_pendukung',
        'captions-container-docs',
        'keterangan_dokumen',
        ['docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx', 'pdf'],
        55240,
        25,
        {!! json_encode($dokumen_initialPreview ?? []) !!},
        {!! json_encode($dokumen_initialPreviewConfig ?? []) !!}
    );

    // Initialize Media Uploader
    handleFileInput(
        'media_pendukung',
        'captions-container-media',
        'keterangan_media',
        ['jpg', 'png', 'jpeg'],
        55240,
        25,
        {!! json_encode($media_initialPreview ?? []) !!},
        {!! json_encode($media_initialPreviewConfig ?? []) !!}
    );
});
</script> --}}


{{-- <script>
$(document).ready(function() {
    var docFileIndex = 0;
    var mediaFileIndex = 0;
    var docFileCaptions = {};
    var mediaFileCaptions = {};

    function handleFileInput(fileInputId, captionContainerId, fileCaptions, allowedExtensions, maxSize, maxCount, initialPreview, initialPreviewConfig) {
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
            maxFileCount: maxCount,
            allowedFileExtensions: allowedExtensions,
            overwriteInitial: false,
            previewFileIconSettings: {
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'doc': '<i class="fas fa-file-word text-primary"></i>',
                'docx': '<i class="fas fa-file-word text-primary"></i>',
                'xls': '<i class="fas fa-file-excel text-success"></i>',
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pptx': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                'htm': '<i class="fas fa-file-code text-info"></i>',
                'txt': '<i class="fas fa-file-alt text-info"></i>',
            },
            initialPreview: initialPreview || [],
            initialPreviewConfig: initialPreviewConfig || [],
            initialPreviewAsData: true,

            // Custom messages
            msgTooManyFiles: `You can upload up to ${maxCount} files only!`,
            msgSizeTooLarge: 'File "{name}" (<b>{size}</b>) exceeds the maximum allowed size of <b>{maxSize} KB</b>.',
            msgInvalidFileExtension: 'File "{name}" has an invalid extension. Allowed extensions are: <b>{extensions}</b>.',
            msgFilesTooMany: 'Number of files selected ({n}) exceeds the maximum allowed limit of {m}.',
        }).on('filepreloaded', function(event, data, previewId, index) {
            // Handle preloaded files
            var uniqueId = fileInputId + '-' + data.key; // Use media ID for uniqueness
            var caption = data.extra.keterangan || data.caption.replace(/<[^>]+>/g, ''); // Strip HTML from caption
            fileCaptions[uniqueId] = caption;
            $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);
            $('#' + captionContainerId).append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                    <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${caption}</span></label>
                    <input type="text" class="form-control" name="keterangan[]" id="caption-${uniqueId}" value="${caption}">
                </div>`
            );
        }).on('fileloaded', function(event, file, previewId, index, reader) {
            // Handle new files
            var uniqueId = fileInputId + '-' + (new Date().getTime() + index);
            fileCaptions[uniqueId] = file.name;
            $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);
            $('#' + captionContainerId).append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                    <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                    <input type="text" class="form-control" name="keterangan[]" id="caption-${uniqueId}" value="">
                </div>`
            );
        }).on('fileremoved', function(event, id) {
            var uniqueId = $(`#${$.escapeSelector(id)}`).attr('data-unique-id');
            delete fileCaptions[uniqueId];
            $(`#caption-group-${uniqueId}`).remove();
        }).on('fileclear', function(event) {
            fileCaptions = {};
            $('#' + captionContainerId).empty();
        }).on('filebatchselected', function(event, files) {
            // Only append captions for new files, preserve preloaded captions
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var uniqueId = fileInputId + '-' + (new Date().getTime() + i);
                if (!Object.values(fileCaptions).includes(file.name)) { // Avoid duplicates
                    fileCaptions[uniqueId] = file.name;
                    $('#' + captionContainerId).append(
                        `<div class="form-group" id="caption-group-${uniqueId}">
                            <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                            <input type="text" class="form-control" name="keterangan[]" id="caption-${uniqueId}" value="">
                        </div>`
                    );
                }
            }
        });
    }


        // Initialize for media
    handleFileInput(
        'media_pendukung',
        'captions-container-media',
        mediaFileCaptions,
        ['jpg', 'png', 'jpeg'],
        55240,
        25,
        {!! json_encode($media_initialPreview ?? []) !!},
        {!! json_encode($media_initialPreviewConfig ?? []) !!}
    );

    // Initialize for documents
    handleFileInput(
        'dokumen_pendukung',
        'captions-container-docs',
        docFileCaptions,
        ['docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx', 'pdf'],
        55240,
        25,
        {!! json_encode($dokumen_initialPreview ?? []) !!},
        {!! json_encode($dokumen_initialPreviewConfig ?? []) !!}
    );


});
</script> --}}

<script>
$(document).ready(function() {
    var docFileCaptions = {};
    var mediaFileCaptions = {};

    function handleFileInput(fileInputId, captionContainerId, fileCaptions, allowedExtensions, maxSize, maxCount, initialPreview, initialPreviewConfig, captionPrefix) {
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
            maxFileCount: maxCount,
            allowedFileExtensions: allowedExtensions,
            overwriteInitial: false,
            previewFileIconSettings: {
                'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
                'doc': '<i class="fas fa-file-word text-primary"></i>',
                'docx': '<i class="fas fa-file-word text-primary"></i>',
                'xls': '<i class="fas fa-file-excel text-success"></i>',
                'xlsx': '<i class="fas fa-file-excel text-success"></i>',
                'ppt': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'pptx': '<i class="fas fa-file-powerpoint text-danger"></i>',
                'zip': '<i class="fas fa-file-archive text-muted"></i>',
                'htm': '<i class="fas fa-file-code text-info"></i>',
                'txt': '<i class="fas fa-file-alt text-info"></i>',
            },
            initialPreview: initialPreview || [],
            initialPreviewConfig: initialPreviewConfig || [],
            initialPreviewAsData: true,
            // Custom messages
            msgTooManyFiles: `You can upload up to ${maxCount} files only!`,
            msgSizeTooLarge: 'File "{name}" (<b>{size}</b>) exceeds the maximum allowed size of <b>{maxSize} KB</b>.',
            msgInvalidFileExtension: 'File "{name}" has an invalid extension. Allowed extensions are: <b>{extensions}</b>.',
            msgFilesTooMany: 'Number of files selected ({n}) exceeds the maximum allowed limit of {m}.',
        }).on('filepreloaded', function(event, data, previewId, index) {
            // Handle preloaded (existing) files
            var uniqueId = data.key; // Media ID from initialPreviewConfig
            var caption = data.extra.keterangan || data.caption.replace(/<[^>]+>/g, '');
            fileCaptions[uniqueId] = caption;
            $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);
            $('#' + captionContainerId).append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                    <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${data.caption}</span></label>
                    <input type="text" class="form-control" name="${captionPrefix}[existing][${uniqueId}]" id="caption-${uniqueId}" value="${caption}">
                </div>`
            );
        }).on('fileloaded', function(event, file, previewId, index, reader) {
            // Handle newly uploaded files
            var uniqueId = fileInputId + '-' + (new Date().getTime() + index);
            fileCaptions[uniqueId] = file.name;
            $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);
            $('#' + captionContainerId).append(
                `<div class="form-group" id="caption-group-${uniqueId}">
                    <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                    <input type="text" class="form-control" name="${captionPrefix}[new][]" id="caption-${uniqueId}" value="">
                </div>`
            );
        }).on('fileremoved', function(event, id) {
            // Remove caption when file is removed
            var uniqueId = $(`#${$.escapeSelector(id)}`).attr('data-unique-id');
            delete fileCaptions[uniqueId];
            $(`#caption-group-${uniqueId}`).remove();
        }).on('fileclear', function(event) {
            // Clear all captions when file input is cleared
            fileCaptions = {};
            $('#' + captionContainerId).empty();
        }).on('filebatchselected', function(event, files) {
            // No need to append captions here; handled by fileloaded
        });
    }

    // Initialize for documents
    handleFileInput(
        'dokumen_pendukung',
        'captions-container-docs',
        docFileCaptions,
        ['docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx', 'pdf'],
        55240,
        25,
        {!! json_encode($dokumen_initialPreview ?? []) !!},
        {!! json_encode($dokumen_initialPreviewConfig ?? []) !!},
        'dokumen_keterangan'
    );

    // Initialize for media
    handleFileInput(
        'media_pendukung',
        'captions-container-media',
        mediaFileCaptions,
        ['jpg', 'png', 'jpeg'],
        55240,
        25,
        {!! json_encode($media_initialPreview ?? []) !!},
        {!! json_encode($media_initialPreviewConfig ?? []) !!},
        'media_keterangan'
    );
});
</script>
