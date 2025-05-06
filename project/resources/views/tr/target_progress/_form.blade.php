{{-- FORM - Target & Progress --}}
<form id="create_target_progress" method="POST" action="{{ route('target_progress.store') }}" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
	@csrf
	@method('POST')
	
	{{-- Fields --}}
	<div class="container-fluid">
		<input name="target_progress[id]" id="target_progress_id" type="hidden">
		{{-- Select Program --}}
		<div class="row">
			{{-- Kode & ID --}}
			<div class="col-sm-12 col-md-12 col-lg-6 self-center order-1 order-md-1">
				<label for="kode_program" class="input-group col-form-label">
					{{ __('cruds.target_progress.basic.program_select') }}
				</label>
				<input
					name="target_progress[program_id]"
					id="program_id"
					type="hidden"
					value="{{ old('program_id', $targetProgress->program_id) }}"
					{{ !!old('id', !!$targetProgress->id) ? 'readonly inert' : "" }}
				>
				<input
					id="kode_program"
					type="text"
					name="target_progress[kode_program]"
					class="form-control"
					placeholder="{{ __('cruds.target_progress.basic.program_select') }}"
					value="{{ old('kode_program', $targetProgress->program->kode) }}"
					{{ !!old('id', !!$targetProgress->id) ? 'readonly inert' : "" }}
				>
			</div>
			
			{{-- Kode & ID --}}
			<div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2">
				<label for="target_progress_tanggal" class="input-group col-form-label">
					{{ __('cruds.target_progress.basic.tanggal') }}
				</label>
				<input
					id="target_progress_tanggal"
					type="text"
					name="target_progress[tanggal]"
					class="form-control"
					inputmode="none"
					autocomplete="off"
					placeholder="{{ __('cruds.target_progress.basic.tanggal') }}"
					value="{{ old('tanggal', $targetProgress->tanggal?->format("d/m/Y")) }}"
				>
			</div>
		</div>
		
		{{-- Target & Progress Table --}}
		<div class="row responsive list_peserta mt-3">
			<div class="col-12">
				<table id="target_progress_table" class="table responsive-table table-bordered datatable-target_progress" width="100%">
				</table>
			</div>
		</div>
	</div>
	
	<hr>
	
	<!-- Bottom Action -->
	<div class="container-fluid">
		<div class="row tambah_target_progress" id="tambah_target_progress">
			<div class="col mb-1 mt-2 d-flex justify-content-end">
				<button type="submit" class="btn btn-success" id="submitDataBtn">{{ __('global.save') }} <i class="bi bi-save"></i></button>
			</div>
		</div> 
	</div>
</form>
