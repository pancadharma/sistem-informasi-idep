<div id="add_peran" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.peran.title') }}
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('peran.store') }}" method="POST" class="resettable-form" id="peranForm" autocomplete="off">
            @csrf
            <div class="form-group">
                <label for="nama">{{ trans('cruds.peran.nama') }}</label>
                <input type="text" id="nama" name="nama" class="form-control" required maxlength="200">
            </div>
            <div class="form-group">
                <strong>{{ trans('cruds.status.title') }}</strong>
                <input type="hidden" name="aktif" value="0">
                <div class="icheck-primary">
                    <input type="checkbox" name="aktif" id="aktif" {{ old('aktif',1) == 1 ? 'checked' : '' }} value="1">
                    <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
                </div>
            </div>
            <button type="submit" class="btn btn-success float-right btn-add-peran">
                <i class="fas fa-save"></i> {{ trans('global.submit') }}
            </button>
        </form>
    </div>
</div>