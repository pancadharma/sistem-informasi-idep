<x-adminlte-modal id="ModalTambahKomponen" title="{{ __('Tambah Komponen') }}" theme="teal" icon="bi bi-plus-circle" size='lg' static-backdrop>
    <form id="formTambahKomponen">
        <div class="row mb-3">
            <div class="col-sm-12">
                <label class="form-label mb-0">{{ __('Nama Komponen') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nama" id="namaKomponen" required>
            </div>
        </div>
    </form>
    <x-slot name="footerSlot">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
        <button type="submit" class="btn btn-primary" id="saveKomponenBtn">{{ __('global.save') }}</button>
    </x-slot>
</x-adminlte-modal>