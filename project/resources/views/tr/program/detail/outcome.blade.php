<!-- <div class="row" id="program_outcome">
    <div class="col-lg-4">
        <div class="form-group">
            <label for="deskripsi" class="control-label small mb-0">{{ __('cruds.program.outcome.desc') }}</label>
            <textarea type="textarea" id="deskripsi" name="deskripsi" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                placeholder=" {{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000"></textarea>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="indikator" class="control-label small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
            <textarea id="indikator" name="indikator" cols="30"
                class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}"
                placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1"></textarea>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="target" class="control-label small mb-0">{{ __('cruds.program.outcome.target') }}</label>
            <textarea id="target" name="target" cols="30"
                class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}"
                placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1"></textarea>
        </div>
    </div>
    <div class="col-lg-3 form-group">
        <div class="input-group">
            <label for="tambah_outcome_" class="input-group small mb-0">{{ __('cruds.program.donor.val') }}</label>
            <textarea type="text" id="tambah_outcome_" name="tambah_outcome_[]" class="form-control">
            <span class="input-group-append">
                <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="${containerId}"><i class="bi bi-trash"></i></button>
            </span>
        </div>
    </div>
</div>
<div class="col-md-12" id="outcome-container">

</div> -->

<!-- <div class="col-md-12" id="outcome-container">
  <div class="row" id="outcome-container-1">
    <div class="col-lg-3 form-group">
      <div class="input-group">
        <label for="nama_outcome_1" class="input-group small mb-0">Outcome</label>
        <input type="hidden" name="outcome_id[]" value="1" id="outcome_1">
        <input type="hidden" name="program_id[]" value="1" id="outcome_1">
        <input type="text" id="nama_outcome_1" name="nama_outcome[]" class="form-control" placeholder="" required>
      </div>
    </div>
    <div class="col-lg-3 form-group">
      <div class="input-group">
        <label for="email-1" class="input-group small mb-0">Email</label>
        <input type="text" id="email-1" name="email" class="form-control" value="" required>
      </div>
    </div>
    <div class="col-lg-2 form-group">
      <div class="input-group">
        <label for="phone-1" class="input-group small mb-0">Nomor Telepon</label>
        <input type="text" id="phone-1" name="phone" class="form-control" value="" required>
      </div>
    </div>
    <div class="col-lg-3 form-group">
      <div class="input-group">
        <label for="nilaidonasi-1" class="input-group small mb-0">Nilai Donasi</label>
        <input type="text" id="nilaidonasi-1" name="nilaidonasi[]" class="form-control currency" value="0">
        <span class="input-group-append">
          <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="pendonor-container-1"><i class="bi bi-trash"></i></button>
        </span>
      </div>
    </div>
  </div>
</div> -->

<div class="card border-primary">
    <div class="card-header">
        <h3 class="card-title">Outcome</h3>
    </div>

    <div class="card-body" id="outcome_data">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="col-md-12" id="outcomeContainer">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <label for="descripsi_outcome" class="input-group small mb-0">Descr</label>
                                    <input type="hidden" name="outcome_id[]" id="outcome">
                                    <input type="text" id="descripsi_outcome" name="descripsi_outcome[]" class="form-control" placeholder="deskripsi" required>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <label for="target_outcome" class="input-group small mb-0">Target</label>
                                    <input type="text" id="target_outcome" name="target_outcome[]" class="form-control" placeholder="target" required>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <label for="indikator_outcome" class="input-group small mb-0">Indikator</label>
                                    <input type="text" id="indikator_outcome" name="indikator_outcome[]" class="form-control" placeholder="indikator" required>
                                    <!-- </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="input-group">
                                    <label for="nilaidonasi_1" class="input-group small mb-0">Nilai Outcome</label>
                                    <input type="text" id="nilai_outcome_1" name="nilai_outcome[]" class="form-control currency" value="0"> -->
                                    <span class="input-group-append">
                                        <button type="button" class="ml-2 btn btn-success form-control addOutcome btn-flat" data-target="outcome-container_1"><i class="bi bi-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div class="card-body d-none" id="outcomeTemplate">
        <!--   <div class="card-body" id="outcomeTemplate"> -->
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <label for="descripsi_outcome" class="input-group small mb-0">Descr</label>
                                    <input type="hidden" name="outcome_id[]" value="1" id="outcome_1">
                                    <input type="text" name="descripsi_outcome[]" class="form-control" placeholder="deskripsi" required>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <label for="target_outcome" class="input-group small mb-0">Target</label>
                                    <input type="text" name="target_outcome[]" class="form-control" placeholder="target" required>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group">
                                <div class="input-group">
                                    <label for="indikator_outcome" class="input-group small mb-0">Indikator</label>
                                    <input type="text" name="indikator_outcome[]" class="form-control" placeholder="indikator" required>
                                    <!-- </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="input-group">
                                    <label for="nilaidonasi_outcome" class="input-group small mb-0">Nilai Outcome</label>
                                    <input type="text" id="nilai_outcome" name="nilai_outcome[]" class="form-control currency" value="0"> -->
                                    <span class="input-group-append">
                                        <button type="button" class="ml-2 btn btn-danger form-control removeButton btn-block" data-target="outcome-container_1"><i class="bi bi-trash"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- WANT TO PUT THE JS HERE INSTEAD at js/donor.blade.php --}}
{{-- Use @push('js') close by @endpush --}}
