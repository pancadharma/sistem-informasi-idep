<div class="modal fade" id="ModalDusunBaru" tabindex="-1" role="dialog" aria-labelledby="ModalDusunBaruLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="ModalDusunBaruLabel">Tambah Dusun Baru</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @include('api.master.dusun')
        <div class="modal-footer">
            {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
            {{-- <button type="button" class="btn btn-primary" id="saveDusun">Simpan</button> --}}
        </div>
        </div>
    </div>
</div>
