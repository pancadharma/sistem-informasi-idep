<!-- Modal -->
{{-- <div class="modal fade" id="ModalTambahPeserta" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ModalTambahPesertaTitle" aria-hidden="true" theme="success">
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

 --}}

<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="ModalTambahPeserta" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ModalTambahPesertaTitle" aria-hidden="true" theme="success">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
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
                <form id="pesertaForm">
                    <div class="form-group">
                        <label for="identitas">Identitas</label>
                        <input type="text" class="form-control" id="identitas" name="identitas" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <input type="text" class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                    </div>
                    <div class="form-group">
                        <label for="disabilitas">Disabilitas</label>
                        <input type="text" class="form-control" id="disabilitas" name="disabilitas" required>
                    </div>
                    <div class="form-group">
                        <label for="hamil">Hamil</label>
                        <input type="text" class="form-control" id="hamil" name="hamil" required>
                    </div>
                    <div class="form-group">
                        <label for="status_kawin">Status Kawin</label>
                        <input type="text" class="form-control" id="status_kawin" name="status_kawin" required>
                    </div>
                    <div class="form-group">
                        <label for="no_kk">No. KK</label>
                        <input type="text" class="form-control" id="no_kk" name="no_kk" required>
                    </div>
                    <div class="form-group">
                        <label for="jenis_peserta">Jenis Peserta</label>
                        <input type="text" class="form-control" id="jenis_peserta" name="jenis_peserta" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_kk">Nama KK</label>
                        <input type="text" class="form-control" id="nama_kk" name="nama_kk" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
                <button type="button" class="btn btn-primary" id="saveModalData">{{ __('global.save') }}</button>
            </div>
        </div>
    </div>
</div>

