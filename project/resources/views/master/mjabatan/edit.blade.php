{{-- <x-adminlte-modal id="editMjabatanModal" title="Update Jabatan" size="lg" theme="info" icon="fas fa-pencil-alt" v-centered static-backdrop scrollable>
    <div class="modal-body">
        <form action="#" @submit.prevent="handleSubmit" method="PATCH" class="resettable-form" id="editMjabatanForm" autocomplete="off" novalidate>
            @csrf
            @method('PATCH')
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="editnama">{{trans('cruds.mjabatan.nama')}}</label>
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
</x-adminlte-modal> --}}

<x-adminlte-modal id="editMjabatanModal"
                  title="Update Jabatan"
                  size="lg"
                  theme="info"
                  icon="fas fa-pencil-alt"
                  v-centered
                  static-backdrop
                  scrollable>

    <div class="modal-body">
        <form action="#"
              method="POST"
              @submit.prevent="handleSubmit"
              class="resettable-form"
              id="editMjabatanForm"
              autocomplete="off"
              novalidate>

            @csrf
            @method('PATCH')

            <input type="hidden" name="id" id="edit-id">

            {{-- Nama Jabatan --}}
            <div class="form-group">
                <label for="edit-nama">
                    {{ trans('cruds.mjabatan.nama') }}
                </label>
                <input type="text"
                       id="edit-nama"
                       name="nama"
                       class="form-control"
                       required
                       maxlength="200">
            </div>

            {{-- Divisi --}}
            <div class="form-group">
                <label for="edit-divisi-id">
                    {{ trans('cruds.mdivisi.title') }}
                </label>
                <select name="divisi_id"
                        id="edit-divisi-id"
                        class="form-control select2"
                        required>
                    <option value="">-- Pilih Divisi --</option>
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}">
                            {{ $divisi->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Manager --}}
            <div class="form-group">
                <strong>Status Jabatan</strong>
                <input type="hidden" name="is_manager" id="edit-is-manager" value="0">
                <div class="icheck-primary">
                    <input type="checkbox"
                           id="edit-is-manager-checkbox">
                    <label for="edit-is-manager-checkbox">
                        Manager / Approver
                    </label>
                </div>
            </div>

            {{-- Aktif --}}
            <div class="form-group">
                <strong>{{ trans('cruds.status.title') }}</strong>
                <input type="hidden" name="aktif" id="edit-aktif" value="0">
                <div class="icheck-primary">
                    <input type="checkbox"
                           id="edit-aktif-checkbox">
                    <label for="edit-aktif-checkbox">
                        {{ trans('cruds.status.aktif') }}
                    </label>
                </div>
            </div>

            <button type="submit"
                    class="btn btn-success float-right">
                <i class="fas fa-save"></i>
                {{ trans('global.update') }}
            </button>
        </form>
    </div>

</x-adminlte-modal>