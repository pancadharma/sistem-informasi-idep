{{-- FORM - Target & Progress --}}
<form id="create_target_progress" method="POST" class="needs-validation" data-toggle="validator" autocomplete="off" enctype="multipart/form-data">
	@csrf
	@method('POST')

	{{-- Fields --}}
	<div class="container-fluid">
		{{-- Select Program --}}
		<div class="row">
			{{-- Kode & ID --}}
			<div class="col-sm-12 col-md-12 col-lg-6 self-center order-1 order-md-1">
				<label for="kode_program" class="input-group col-form-label">
					{{ __('cruds.target_progress.basic.program_select') }}
				</label>
				<input id="program_id" type="hidden" name="program_id">
				<input id="kode_program" type="text" name="kode_program" class="form-control" placeholder="{{ __('cruds.target_progress.basic.program_select') }}">
			</div>

			{{-- Kode & ID --}}
			<div class="col-sm-12 col-md-12 col-lg-6 self-center order-2 order-md-2">
				<label for="tanggal" class="input-group col-form-label">
					{{ __('cruds.target_progress.basic.tanggal') }}
				</label>
				<input type="date" class="form-control" id="tanggal" placeholder="{{ __('cruds.target_progress.basic.tanggal') }}" name="tanggal">
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
				<button type="button" class="btn btn-success" id="submitDataBtn">{{ __('global.save') }} <i class="bi bi-save"></i></button>
			</div>
		</div> 
	</div>
</form>
