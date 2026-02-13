{{-- <div id="add_mjabatan" class="card card-primary collapsed-card">
    <div class="card-header">
    {{ trans('global.create')}} {{trans('cruds.mjabatan.title')}}
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('mjabatan.store') }}" method="POST" class="resettable-form" id="mjabatanForm" autocomplete="off">
            @csrf
            <div class="form-group">
                <label for="nama">{{trans('cruds.mjabatan.nama')}}</label>
                <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
            </div>
            <div class="form-group">
                <strong>{{ trans('cruds.status.title') .' '. trans('cruds.mjabatan.title') }}</strong>
                <input type="hidden" name="aktif" value="0">
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                </div>
            </div>
            <button type="submit" class="btn btn-success float-right btn-add-mjabatan"><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
    </div>
</div> --}}

<div id="add_mjabatan" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.mjabatan.title') }}
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('mjabatan.store') }}"
              method="POST"
              class="resettable-form"
              id="mjabatanForm"
              autocomplete="off">
            @csrf

            {{-- Nama Jabatan --}}
            <div class="form-group">
                <label for="nama">{{ trans('cruds.mjabatan.nama') }}</label>
                <input type="text"
                       id="nama"
                       name="nama"
                       class="form-control"
                       required
                       maxlength="200">
            </div>

            {{-- Divisi --}}
            <div class="form-group">
                <label for="divisi_id">
                    {{ trans('cruds.mdivisi.title') }}
                </label>
                <select name="divisi_id"
                        id="divisi_id"
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
                <input type="hidden" name="is_manager" value="0">
                <div class="icheck-primary">
                    <input type="checkbox"
                           name="is_manager"
                           id="is_manager"
                           value="1">
                    <label for="is_manager">
                        Manager / Approver
                    </label>
                </div>
            </div>

            {{-- Aktif --}}
            <div class="form-group">
                <strong>
                    {{ trans('cruds.status.title') . ' ' . trans('cruds.mjabatan.title') }}
                </strong>
                <input type="hidden" name="aktif" value="0">
                <div class="icheck-primary">
                    <input type="checkbox"
                           name="aktif"
                           id="aktif"
                           value="1"
                           checked>
                    <label for="aktif">
                        {{ trans('cruds.status.aktif') }}
                    </label>
                </div>
            </div>

            <button type="submit"
                    class="btn btn-success float-right btn-add-mjabatan">
                <i class="fas fa-save"></i> {{ trans('global.submit') }}
            </button>
        </form>
    </div>
</div>