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
                    showDrag: true,
                    uploadAsync: false,
                    browseOnZoneClick: true,
                    maxFileSize: maxSize,
                    maxFilePreviewSize: 10240,
                    maxFileCount: maxCount,
                    allowedFileExtensions: allowedExtensions,
                    overwriteInitial: false,
                    initialPreview: [], // Initialize with empty preview
                    initialPreviewConfig: [],

                    // ✅ CUSTOM MESSAGES
                    msgTooManyFiles: `You can upload up to ${maxCount} files only!`,
                    msgSizeTooLarge: 'File "{name}" (<b>{size}</b>) exceeds the maximum allowed size of <b>{maxSize} KB</b>.',
                    msgInvalidFileExtension: 'File "{name}" has an invalid extension. Allowed extensions are: <b>{extensions}</b>.',
                    msgFilesTooMany: 'Number of files selected ({n}) exceeds the maximum allowed limit of {m}.',

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
                })
                .on('fileclear', function(event) {
                    // Clear all caption inputs when files are cleared
                    fileCaptions = {}; // Reset the tracking object
                    $('#' + captionContainerId).empty();
                })
                // .on('filebatchselected', function(event, files) {
                //     let existingFiles = Object.values(fileCaptions);
                //     $('#' + captionContainerId).empty();
                //     files.forEach(file => {
                //         if (!existingFiles.includes(file.name)) {
                //             var uniqueId = fileInputId + '-' + (new Date().getTime());
                //             fileCaptions[uniqueId] = file.name;
                //             $('#' + captionContainerId).append(
                //                 `<div class="form-group" id="caption-group-${uniqueId}">
                //                     <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                //                     <input type="text" class="form-control" name="keterangan[]" id="caption-${uniqueId}">
                //                 </div>`
                //             );
                //         }
                //     });
                // });
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
                });
        }

        handleFileInput('dokumen_pendukung', 'captions-container-docs', docFileCaptions, ['docx', 'doc', 'ppt',
            'pptx', 'xls', 'xlsx', 'pdf'
        ], 55240, 25);
        handleFileInput('media_pendukung', 'captions-container-media', mediaFileCaptions, ['jpg', 'png',
            'jpeg'
        ], 55240, 25);
    });
</script>
