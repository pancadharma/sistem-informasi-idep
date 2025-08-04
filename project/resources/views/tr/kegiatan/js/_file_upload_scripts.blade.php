<script>

$(document).ready(function() {
    var docFileCaptions = {};
    var mediaFileCaptions = {};

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
            append: true,
            initialPreviewAsData: true,
            initialPreview: initialPreview || [],
            // initialPreview: {!! json_encode($media_initialPreview) !!} || [],
            initialPreviewConfig: initialPreviewConfig || [],
            removeFromPreviewOnError: true,
            // initialPreviewConfig: {!! json_encode($media_initialPreviewConfig) !!} || [],

            previewFileIconSettings: {
                // 'pdf': '<i class="fas fa-file-pdf text-danger"></i>',
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
            previewFileExtSettings: {
                'doc': ext => /(doc|docx)$/i.test(ext),
                'xls': ext => /(xls|xlsx)$/i.test(ext),
                'ppt': ext => /(ppt|pptx)$/i.test(ext),
                'zip': ext => /(zip|rar|tar|gzip|gz|7z)$/i.test(ext),
                'htm': ext => /(htm|html)$/i.test(ext),
                'txt': ext => /(txt|ini|csv|java|php|js|css)$/i.test(ext),
                'mov': ext => /(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i.test(ext),
                'mp3': ext => /(mp3|wav)$/i.test(ext),
            },

            // Custom messages
            msgTooManyFiles: `You can upload up to ${maxCount} files only!`,
            msgSizeTooLarge: 'File "{name}" (<b>{size}</b>) exceeds the maximum allowed size of <b>{maxSize} KB</b>.',
            msgInvalidFileExtension: 'File "{name}" has an invalid extension. Allowed extensions are: <b>{extensions}</b>.',
            msgFilesTooMany: 'Number of files selected ({n}) exceeds the maximum allowed limit of {m}.',
        }).on('filepreloaded', function(event, data, previewId, index) {
            // Handle preloaded (existing) files
            const caption = data.caption || data.name;
            const uid = data.key;
            const label = data.extra.keterangan || data.caption.replace(/<[^>]+>/g, '');
            addCaptionInput(uid, label, false);
            $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uid);

        })
        .on('filebatchselected', function(event, files) {
            $('#' + captionContainerId + ' .new-file').remove();

            Array.from(files).forEach((file, i) => {
                const uid = 'new-' + Date.now() + '-' + i;
                addCaptionInput(uid, file.name, true);
                fileCaptions[uid] = file.name;
            });
        })

        .on('fileloaded', function(event, file, previewId, index, reader) {


            const uidKeys = Object.keys(fileCaptions);
            const uid = uidKeys[index];
            $('#' + previewId).attr('data-unique-id', uid);

        }).on('fileremoved', function(event, id) {
            const uid = $('#' + previewId).data('unique-id');
            $('#caption-group-' + uid).remove();


        }).on('fileclear', function(event) {
            $('#' + captionContainerId).empty();
            fileCaptions = {};
        });

        function addCaptionInput(uid, label, isNew) {
            $('#' + captionContainerId).append(`
                    <div class="form-group ${isNew ? 'new-file' : ''}" id="caption-group-${uid}">
                        <label class="control-label mb-0 small mt-2" for="keterangan-${uid}">
                            {{ __('cruds.program.ket_file') }}: <span class="text-red">${label}</span>
                        </label>
                        <input type="text" class="form-control" name="${captionPrefix}[]" id="${captionPrefix}-${uid}">
                    </div>
            `);
        }

    }


});

</script>
