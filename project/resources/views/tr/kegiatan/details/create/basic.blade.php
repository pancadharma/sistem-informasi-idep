<div class="row">
    <div class="col-lg-3">
        <div class="form-group">
            <label for="nama_program" class="control-label small mb-0">{{ __('cruds.program.nama') }}</label>
            <input type="text" id="nama_program" name="nama_program" class="form-control" required>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="nama_kegiatan" class="control-label small mb-0">{{ __('cruds.kegiatan.basic.nama') }}</label>
            <input type="text" id="nama_kegiatan" name="nama_kegiatan" class="form-control" required>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="kode_kegiatan" class="control-label small mb-0">{{ __('cruds.kegiatan.basic.kode') }}</label>
            <input type="text" id="kode_kegiatan" name="kode_kegiatan" class="form-control currency" minlength="0" step=".01" required>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="tanggalmulai" class="control-label small mb-0">{{ __('cruds.kegiatan.basic.tanggalmulai') }}</label>
            <input type="date" id="tanggalmulai" name="tanggalmulai" class="form-control" required>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="tanggalselesai" class="control-label small mb-0">{{ __('cruds.kegiatan.basic.tanggalselesai') }}</label>
            <input type="date" id="tanggalselesai" name="tanggalselesai" class="form-control" required>
        </div>
    </div>
</div>
