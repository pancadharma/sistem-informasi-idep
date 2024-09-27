<x-adminlte-modal id="editPeranModal" title="Update Peran" size="lg" theme="info" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div class="modal-body">
        <form action="#" @submit.prevent="handleSubmit" method="PATCH" class="resettable-form" id="editPeranForm" autocomplete="off" novalidate>
            @csrf
            @method('PATCH')
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="editnama">{{ trans('cruds.peran.nama') }}</label>
                <input type="text" id="editnama" name="nama" class="form-control" required maxlength="200">
            </div>
            <div class="form-group">
                <strong>{{ trans('cruds.status.title') }}</strong>
                <div class="icheck-primary">
                    <input type="checkbox" id="editaktif">
                    <input type="hidden" name="aktif" id="edit-aktif" value="0">
                    <label for="editaktif"></label>
                </div>
            </div>
            <button type="submit" class="btn btn-success float-right"><i class="fas fa-save"></i> {{ trans('global.update') }}</button>
        </form>
    </div>
</x-adminlte-modal>