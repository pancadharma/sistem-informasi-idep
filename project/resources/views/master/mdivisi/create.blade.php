<div class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.mdivisi.title') }}
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    <div class="card-body">
        <form id="mdivisiForm">
            @csrf

            <div class="form-group">
                <label>{{ trans('cruds.mdivisi.nama') }}</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="form-group">
                <input type="hidden" name="aktif" value="0">
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" value="1" checked>
                    <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-add-mdivisi">
                <i class="fas fa-save"></i> {{ trans('global.submit') }}
            </button>
        </form>
    </div>
</div>