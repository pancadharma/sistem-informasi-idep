<!-- deskripsi kegiatan -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="deskripsi_kegiatan" class="input-group">{{ __('cruds.kegiatan.description.label') }}</label>
        <textarea name="deskripsi_kegiatan" id="deskripsi_kegiatan" placeholder=" {{ __('cruds.kegiatan.description.label') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
<!-- tujuan kegiatan -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="tujuan_kegiatan" class="mb-0 input-group">{{ __('cruds.kegiatan.description.tujuan') }}</label>
        <textarea name="tujuan_kegiatan" id="tujuan_kegiatan" placeholder=" {{ __('cruds.kegiatan.description.tujuan') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
<!-- siapa yang_terlibat kegiatan -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="yang_terlibat" class="mb-0 input-group">{{ __('cruds.kegiatan.description.involved') }}</label>

        <textarea name="yang_terlibat" id="yang_terlibat" placeholder=" {{ __('cruds.kegiatan.description.involved') }}" class="form-control summernote" rows="1"></textarea>
    </div>
</div>
<!-- siapa pelatihnya dan darimana -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="pelatih_asal" class="mb-0 self-center input-group">{{ __('cruds.kegiatan.description.pelatih_asal') }}</label>

        <textarea name="pelatih_asal" id="pelatih_asal" placeholder=" {{ __('cruds.kegiatan.description.asal_pelatihan') }}" class="form-control summermnote" rows="1"></textarea>
    </div>
</div>
<!-- Apa Saja yang Dilakukan Dalam Kegiatan Tersebut -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="kegiatan" class="mb-0 self-center input-group">{{ __('cruds.kegiatan.description.kegiatan') }}</label>
        <textarea name="kegiatan" id="kegiatan" placeholder=" {{ __('cruds.kegiatan.description.kegiatan') }}" class="form-control summermnote" rows="2"></textarea>
    </div>
</div>
<!-- Informasi Lain yang Terkait -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="informasi_lain" class="mb-0 self-center input-group">
            {{ __('cruds.kegiatan.description.informasi_lain') }}
        </label>

        <textarea name="informasi_lain" id="informasi_lain" placeholder=" {{ __('cruds.kegiatan.description.informasi_lain') }}" class="form-control summermnote" rows="2"></textarea>
    </div>
</div>

<!-- Berapa Luas Lahan yang Diintervensi (Ha) -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="luas_lahan" class="mb-0 input-group">{{ __('cruds.kegiatan.description.luas_lahan') }}</label>
        <input type="text" class="form-control" id="luas_lahan" placeholder=" {{ __('cruds.kegiatan.description.luas_lahan') }}" name="luas_lahan">
    </div>
</div>

<!-- Bila Kegiatan Berkaitan dengan Intervensi Makhluk Hidup, Barang, dan Hal Lain yang Bisa Dikuantifikasi, Sebutkan -->
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-10 self-center col-form-label">
        <div class="input-group">
            <label for="barang" class="mb-0 input-group">{{ __('cruds.kegiatan.description.barang') }}</label>
            <input type="text" class="form-control" id="barang" placeholder=" {{ __('cruds.kegiatan.description.barang') }}" name="barang">
        </div>
    </div>
    <!-- Satuan -->
    <div class="col-sm-12 col-md-12 col-lg-2 col-form-label">
        <div class="input-group">
            <label for="satuan" class="mb-0 self-center input-group">{{ __('cruds.kegiatan.description.satuan') }}</label>
            <input type="text" class="form-control" id="satuan" placeholder=" {{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.description.satuan') }}" name="satuan">
        </div>
    </div>
</div>
<!--others-->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center input-group">
        <label for="barang" class="mb-0 self-center input-group">{{ __('cruds.kegiatan.description.others') }}</label>
        <textarea name="others" id="others" placeholder=" {{ __('cruds.kegiatan.description.others') }}" class="form-control summermnote" rows="2"></textarea>
    </div>
</div>




@push('css')
<style>
    .content-header h1 {
        font-size: 1.1rem!important;
        margin: 0;
    }
    .note-toolbar {
        background:#00000000 !important;
    }

    .note-editor.note-frame .note-statusbar,
    .note-editor.note-airframe .note-statusbar {
        background-color: #007bff17 !important;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        border-top: 1px solid #00000000;
    }
</style>
@endpush

@push('basic_tab_js')
@section('plugins.Summernote', true)

<script defer>
    $(`.summernote textarea`).each(function() {
        if (!$(this).data('initialized')) {
            $(this).summernote({
                height: 100,
                width: '100%',
                toolbar: [
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['paragraph', ['paragraph']],
                    ['view', ['fullscreen', 'codeview']],
                ],
                inheritPlaceholder: true,
            });
            $(this).data('initialized', true); // Mark this textarea as initialized
        }
    });
</script>
@endpush
