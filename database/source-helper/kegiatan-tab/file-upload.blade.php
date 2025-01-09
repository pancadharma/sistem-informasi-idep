{{-- Document Uploads --}}
<div class="card-body pt-0">
    <div class="row">
        <div class="col-lg-12">
            <label for="dokumen_pendukung" class="control-label small mb-0">
                <strong>
                    {{ __('cruds.kegiatan.file.upload') }}
                </strong>
                <span class="text-red">
                    {{-- ONLY FOR DOCUMENT FILES ONLY --}}
                    ( {{ __('allowed file: .pdf, .doc, .docx, .xls, .xlsx, .pptx | max: 4MB') }} )
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
            <label for="media_pendukung" class="control-label small mb-0">
                <strong>
                    {{ __('cruds.kegiatan.file.upload') }}
                </strong>
                <span class="text-red">
                    {{-- ONLY FOR MEDIA FILES ONLY --}}
                    ( {{ __('allowed file: .jpg, .png, .jpeg, .mp4, .mkv | max: 10 MB') }} )
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
                allowedFileExtensions: allowedExtensions,
                overwriteInitial: false,
                initialPreview: [], // Initialize with empty preview
                initialPreviewConfig: []
            }).on('fileloaded', function(event, file, previewId, index, reader) {
                var uniqueId = fileInputId + '-' + (new Date().getTime());
                fileCaptions[uniqueId] = file.name; // Track the file with its unique ID
                $('#' + captionContainerId).append(
                    `<div class="form-group" id="caption-group-${uniqueId}">
                        <label for="caption-${uniqueId}"> {{ __('cruds.program.ket_file') }} ${file.name}</label>
                        <input type="text" class="form-control" name="keterangan[]" id="keterangan-${uniqueId}">
                    </div>`
                );
                // Store the unique identifier in the file preview element
                $(`#${previewId}`).attr('data-unique-id', uniqueId);
            }).on('fileremoved', function(event, id) {
                // Remove the corresponding caption input
                var uniqueId = $(`#${id}`).attr('data-unique-id');
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

        handleFileInput('dokumen_pendukung', 'captions-container-docs', docFileCaptions, ['docx', 'doc', 'ppt', 'pptx', 'xls', 'xlsx', 'pdf'], 4096);
        handleFileInput('media_pendukung', 'captions-container-media', mediaFileCaptions, ['jpg', 'png', 'jpeg', 'mp4', 'mkv'], 10240);
    });
</script>
@endpush



















































{{-- Document Uploads --}}
<div class="card-body pt-0">
    <div class="row">
        <div class="col-lg-12">
            <label for="dokumen_pendukung" class="control-label small mb-0">
                <strong>
                    {{ __('cruds.kegiatan.file.upload') }}
                </strong>
                <span class="text-red">
                    {{-- ONLY FOR DOCUMENT FILES ONLY --}}
                    ( {{ __('allowed file: .pdf, .doc, .docx, .xls, .xlsx, .pptx | max: 4MB') }} )
                </span>
            </label>
            <div class="form-group file-loading">
                <input id="dokumen_pendukung" name="dokumen_pendukung[]" type="file" class="form-control" multiple data-show-upload="false" data-show-caption="true">
            </div>
            <div id="dokumen_captions-container"></div>
        </div>
    </div>
</div>
{{-- Media Photo/Video Uploads --}}
<div class="card-body pt-0">
    <div class="row">
        <div class="col-lg-12">
            <label for="media_pendukung" class="control-label small mb-0">
                <strong>
                    {{ __('cruds.kegiatan.file.upload') }}
                </strong>
                <span class="text-red">
                    {{-- ONLY FOR DOCUMENT FILES ONLY --}}
                    ( {{ __('allowed file: .jpg, .png, .jpeg .mp4, .mkv | max: 10 MB') }} )
                </span>
            </label>
            <div class="form-group file-loading">
                <input id="media_pendukung" name="media_pendukung[]" type="file" class="form-control" multiple data-show-upload="false" data-show-caption="true">
            </div>
            <div id="media_captions-container"></div>
        </div>
    </div>
</div>


@push('basic_tab_js')
<script>
    $(document).ready(function () {
           // Document File
        $("#dokumen_pendukung").fileinput({
            theme: "fa5",
            showBrowse: false,
            showUpload: false,
            showRemove: false,
             showCaption: true,
            showDrag: true,
            uploadAsync: false,
            browseOnZoneClick: true,
            maxFileSize: 4096,
            allowedFileExtensions: ['docx', 'doc', 'ppt', 'pptx', 'xls','xlsx','pdf',
            ],
            overwriteInitial: false,
        }).on('filebatchselected', function(event, files) {
            // Clear all caption inputs when new files are selected
             $('#dokumen_captions-container').empty();
              let fileIndex = 0
            // Iterate over each selected file and trigger the fileloaded event manually
             for (let i = 0; i < files.length; i++) {
                var file = files[i];
                fileIndex++;
                 const uniqueId = `doc-${fileIndex}`
                  $('#dokumen_captions-container').append(
                        `<div class="form-group" id="caption-group-${uniqueId}">
                            <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                            <input type="text" class="form-control" name="dokumen_keterangan[${uniqueId}]" id="caption-${uniqueId}">
                             <button type="button" class="btn btn-sm btn-danger remove-file-button" data-file-id="${uniqueId}">
                                <i class="bi bi-x-lg"></i> {{ __('global.remove') }}
                            </button>
                        </div>`
                  );
            }
         }).on('fileremoved', function(event, id){
                 // Remove the corresponding caption input
            var uniqueId = $(`#${id}`).closest('.file-caption-main').data('uniqueid');
            $(`#caption-group-${uniqueId}`).remove();
        }).on('fileclear', function(event) {
            // Clear all caption inputs when files are cleared
            $('#dokumen_captions-container').empty();
        });

        //media file upload
          $("#media_pendukung").fileinput({
            theme: "fa5",
            showBrowse: false,
            showUpload: false,
             showRemove: false,
            showCaption: true,
            showDrag: true,
             uploadAsync: false,
            browseOnZoneClick: true,
            maxFileSize: 10240,
              allowedFileExtensions: ['jpg', 'png', 'jpeg' , 'mp4' , 'mkv'],
             overwriteInitial: false,
         }).on('filebatchselected', function(event, files) {
                // Clear all caption inputs when new files are selected
              $('#media_captions-container').empty();
                let fileIndex = 0;
            // Iterate over each selected file and trigger the fileloaded event manually
                for (let i = 0; i < files.length; i++) {
                var file = files[i];
                fileIndex++;
                const uniqueId = `media-${fileIndex}`
                 $('#media_captions-container').append(
                        `<div class="form-group" id="caption-group-${uniqueId}">
                            <label class="control-label mb-0 small mt-2" for="caption-${uniqueId}">{{ __('cruds.program.ket_file') }} : <span class="text-red">${file.name}</span></label>
                             <input type="text" class="form-control" name="media_keterangan[${uniqueId}]" id="caption-${uniqueId}">
                             <button type="button" class="btn btn-sm btn-danger remove-file-button" data-file-id="${uniqueId}">
                                <i class="bi bi-x-lg"></i> {{ __('global.remove') }}
                            </button>
                        </div>`
                  );
            }
        }).on('fileremoved', function(event, id){
           // Remove the corresponding caption input
            var uniqueId = $(`#${id}`).closest('.file-caption-main').data('uniqueid');
            $(`#caption-group-${uniqueId}`).remove();
        }).on('fileclear', function(event) {
            // Clear all caption inputs when files are cleared
              $('#media_captions-container').empty();
        });

            //Remove caption if file is removed
            $('#dokumen_captions-container').on('click', '.remove-file-button', function(event){
                const fileId =  $(this).data('file-id');
                $(`#caption-group-${fileId}`).remove();
                 // Remove file input
                const id = $(this).closest('.file-caption-main').data('id');
                  if(id) $('#dokumen_pendukung').fileinput('removeFile', id);

            });
             //Remove caption if file is removed
            $('#media_captions-container').on('click', '.remove-file-button', function(event){
                const fileId =  $(this).data('file-id');
                 $(`#caption-group-${fileId}`).remove();
                 // Remove file input
                const id = $(this).closest('.file-caption-main').data('id');
                if(id) $('#media_pendukung').fileinput('removeFile', id);

            });

    });
</script>
@endpush


public function store(Request $request)
{
    //1. Create trkegiatan record
    $kegiatan = Trkegiatan::create($request->except(['_token', 'dokumen_pendukung', 'media_pendukung', 'dokumen_keterangan', 'media_keterangan']));

    //2. Process the file upload
    if ($request->hasFile('dokumen_pendukung')) {
        foreach ($request->file('dokumen_pendukung') as $key => $file) {
            $media = $kegiatan->addMedia($file)
                 ->toMediaCollection('dokumen');
              $trkegiatan_dokumen = Trkegiatandokumen::create([
                    'id_kegiatan' => $kegiatan->id,
                    'filepath' => $media->getPath(),
                    'nama' => $media->file_name,
                    'keterangan' => $request->input("dokumen_keterangan." . $key)
               ]);
        }
    }

    //2. Process the media file upload
     if ($request->hasFile('media_pendukung')) {
        foreach ($request->file('media_pendukung') as $key => $file) {
              $media = $kegiatan->addMedia($file)
                    ->toMediaCollection('media');
              $trkegiatan_dokumen = Trkegiatandokumen::create([
                     'id_kegiatan' => $kegiatan->id,
                     'filepath' => $media->getPath(),
                     'nama' => $media->file_name,
                     'keterangan' => $request->input("media_keterangan." . $key)
              ]);
           }
      }
      return response()->json(['message' => 'Data saved successfully!'], 201);
}