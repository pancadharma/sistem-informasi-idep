<x-adminlte-modal id="editMdivisiModal"
    title="Update Divisi"
    theme="info"
    icon="fas fa-pencil-alt">

    <form id="editMdivisiForm">
        @csrf
        @method('PUT')

        <input type="hidden" id="edit_id">

        <div class="form-group">
            <label>Nama Divisi</label>
            <input type="text" id="edit_nama" name="nama" class="form-control" required>
        </div>

        <input type="hidden" name="aktif" id="edit-aktif" value="0">
        <div class="icheck-primary">
            <input type="checkbox" id="editaktif">
            <label for="editaktif">Aktif</label>
        </div>

        <button class="btn btn-success mt-3">
            <i class="fas fa-save"></i> Update
        </button>
    </form>
</x-adminlte-modal>