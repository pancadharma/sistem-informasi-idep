<div class="modal fade" id="addProvinsi" aria-modal="true" role="dialog" size="lg" theme="success" icon="fa fa-plus">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-success"><i class="fa fa-plus mr-2"></i>
				<h4 class="modal-title">{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>

			<div class="modal-body">
				<div style="height:40%;">
					<form @submit.prevent="handleSubmit" id="provinsiForm" action="{{ route('provinsi.store')}}" method="POST">
						@method('POST')
						@csrf
						<div class="form-group">
						  <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.provinsi.title') }}</label>
						  <input type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required pattern="[0-9]+" maxlength="10">
						</div>
						<div class="form-group">
						  <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.provinsi.title') }}</label>
						  <input type="text" id="nama" name="nama" class="form-control" v-model="form.nama" required maxlength="200">
						</div>
						<div class="form-group">
						  <label for="aktif">{{ trans('cruds.status.title') }} {{ trans('cruds.provinsi.title') }}</label>
						  <input type="checkbox" name="aktif" id="aktif" checked value="1">
						</div>
						<button type="submit" class="btn btn-success float-right" @disabled($errors->isNotEmpty())><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
					</form>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
			</div>
		</div>
	</div>
</div>
{{-- 

<x-adminlte-modal id="addProvinsi" title="{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}" 
    size="lg" theme="success" icon="fa fa-plus" v-centered static-backdrop scrollable>
    <div style="height:40%;">
        <div class="modal-body">
        <form @submit.prevent="handleSubmit" id="provinsiForm" action="{{ route('provinsi.store')}}" method="POST">
            @method('POST')
            @csrf
            <div class="form-group">
              <label for="kode">{{ trans('cruds.form.kode') }} {{ trans('cruds.provinsi.title') }}</label>
              <input type="text" id="kode" name="kode" class="form-control" v-model="form.kode" required pattern="[0-9]+" maxlength="10">
            </div>
            <div class="form-group">
              <label for="nama">{{ trans('cruds.form.nama') }} {{ trans('cruds.provinsi.title') }}</label>
              <input type="text" id="nama" name="nama" class="form-control" v-model="form.nama" required maxlength="200">
            </div>
            <div class="form-group">
              <label for="aktif">{{ trans('cruds.status.title') }} {{ trans('cruds.provinsi.title') }}</label>
              <input type="checkbox" name="aktif" id="aktif" checked value="1">
            </div>
            <button type="submit" class="btn btn-success float-right" @disabled($errors->isNotEmpty())><i class="fas fa-save"></i> {{ trans('global.submit') }}</button>
        </form>
        </div>
    </div>
    <x-slot name="footerSlot">
        {{-- <x-adminlte-button class="mr-auto" theme="success" label="Accept"/>
        <x-adminlte-button theme="danger" label="Dismiss" data-dismiss="modal"/> 
    </x-slot>
</x-adminlte-modal> --}}
