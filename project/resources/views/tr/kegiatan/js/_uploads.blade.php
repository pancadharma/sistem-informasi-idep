{{-- sistem-informasi-idep\project\resources\views\tr\kegiatan\js\_uploads.blade.php --}}
<script>
    $(document).ready(function() {
        var docFileIndex = 0;
        var mediaFileIndex = 0;
        var docFileCaptions = {}; // Object to track document files and captions
        var mediaFileCaptions = {}; // Object to track media files and captions

        function handleFileInput(fileInputId, captionContainerId, fileCaptions, allowedExtensions, maxSize,
            maxCount) {
            $("#" + fileInputId).fileinput({
                    theme: "fa5",
                    showBrowse: false,
                    showUpload: false,
                    showRemove: false,
                    showCaption: true,
                    showDrag: false,
                    uploadAsync: false,
                    browseOnZoneClick: true,
                    maxFileSize: maxSize,
                    maxFilePreviewSize: 2048,
                    maxFileCount: maxCount,
                    allowedFileExtensions: allowedExtensions,
                    overwriteInitial: false,
                    initialPreview: [], // Initialize with empty preview
                    initialPreviewConfig: [],
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
                        'doc': function(ext) {
                            return ext.match(/(doc|docx)$/i);
                        },
                        'xls': function(ext) {
                            return ext.match(/(xls|xlsx)$/i);
                        },
                        'ppt': function(ext) {
                            return ext.match(/(ppt|pptx)$/i);
                        },
                        'zip': function(ext) {
                            return ext.match(/(zip|rar|tar|gzip|gz|7z)$/i);
                        },
                        'htm': function(ext) {
                            return ext.match(/(htm|html)$/i);
                        },
                        'txt': function(ext) {
                            return ext.match(/(txt|ini|csv|java|php|js|css)$/i);
                        },
                        'mov': function(ext) {
                            return ext.match(/(avi|mpg|mkv|mov|mp4|3gp|webm|wmv)$/i);
                        },
                        'mp3': function(ext) {
                            return ext.match(/(mp3|wav)$/i);
                        }
                    },

                    // ✅ CUSTOM MESSAGES
                    msgTooManyFiles: `You can upload up to ${maxCount} files only!`,
                    msgSizeTooLarge: 'File "{name}" (<b>{size}</b>) exceeds the maximum allowed size of <b>{maxSize} KB</b>.',
                    msgInvalidFileExtension: 'File "{name}" has an invalid extension. Allowed extensions are: <b>{extensions}</b>.',
                    msgFilesTooMany: 'Number of files selected ({n}) exceeds the maximum allowed limit of {m}.',

                })
                .on('filebatchselected', function(event, files) {
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
                })
                .on('fileloaded', function(event, file, previewId, index, reader) {
                    var uniqueId = fileInputId + '-' + (new Date().getTime());

                    fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                    $(`#${$.escapeSelector(previewId)}`).attr('data-unique-id', uniqueId);

                }).on('fileremoved', function(event, id) {
                    // Remove the corresponding caption input
                    // var uniqueId = $(`#${id}`).attr('data-unique-id');
                    var uniqueId = $(`#${$.escapeSelector(id)}`).attr('data-unique-id');
                    delete fileCaptions[uniqueId]; // Remove the file from the tracking object
                    $(`#caption-group-${uniqueId}`).remove();
                })
                .on('fileclear', function(event) {
                    // Clear all caption inputs when files are cleared
                    fileCaptions = {}; // Reset the tracking object
                    $('#' + captionContainerId).empty();
                })
        }

        handleFileInput('dokumen_pendukung', 'captions-container-docs', docFileCaptions, ['docx', 'doc', 'ppt',
            'pptx', 'xls', 'xlsx', 'pdf'
        ], 40960, 50);
        handleFileInput('media_pendukung', 'captions-container-media', mediaFileCaptions, ['jpg', 'png',
            'jpeg'
        ], 40960, 50);
    });
</script>
