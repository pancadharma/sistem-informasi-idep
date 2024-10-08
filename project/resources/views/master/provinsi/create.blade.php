<div id="add_provinsi" class="card card-primary collapsed-card">
    <div class="card-header">
        {{ trans('global.create')}} {{trans('cruds.provinsi.title')}}
        <div class="card-tools">
            {{-- limit which user can view this button to open the form create satuan --}}
            @can('provinsi_create')
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-plus"></i>
            </button>
            @endcan
        </div>
    </div>
    <div class="card-body responsive">
        <form @submit.prevent="handleSubmit" id="provinsiForm" action="{{ route('provinsi.store')}}" method="POST" class="resettable-form">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.provinsi.title') }}</label>
                <input type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required pattern="[0-9]+" maxlength="2">
            </div>
            <div class="form-group">
                <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.provinsi.title') }}</label>
                <input type="text" id="nama" name="nama" class="form-control" pattern="^[A-Za-z][A-Za-z0-9]{1,}$" required maxlength="200">
            </div>
            <div class="form-group">
            <strong>{{ trans('cruds.status.title') }} {{ trans('cruds.provinsi.title') }}</strong>
            <div class="icheck-primary">
                <input type="checkbox" name="aktif" id="aktif" {{ old('aktif', 1) == 1 ? 'checked' : '' }} value="1">
                <label for="aktif">{{ trans('cruds.status.aktif') }}</label>
            </div>
            </div>
            <button type="submit" id="addProvinsiBtn" class="btn btn-success float-right" @disabled($errors->isNotEmpty())><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
    </div>
</div>
