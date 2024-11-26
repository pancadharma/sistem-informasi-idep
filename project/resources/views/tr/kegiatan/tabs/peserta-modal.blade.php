<!-- Modal -->
<div class="modal fade" id="ModalTambahPeserta" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ModalTambahPesertaTitle" aria-hidden="true" theme="success">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="ModalTambahPesertaTitle">
                    <i class="bi bi-person-plus"></i>
                    {{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
                <button type="button" class="btn btn-primary">{{ __('global.save') }}</button>
            </div>
        </div>
    </div>
</div>


