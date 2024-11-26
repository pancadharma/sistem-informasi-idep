{{--
<!---->
<!-- Dewasa -->
<div class="form-group row">
    <label for="pria" class="col-sm-2 col-md-2 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.peserta.pria') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-2 order-md-2 self-center">
        <input type="text" class="form-control" id="pria" placeholder="0" name="pria">
   </div>
    <label for="wanita" class="col-sm-2 col-md-2 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-right text-lg-right self-center">{{ __('cruds.kegiatan.peserta.wanita') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="wanita" placeholder="0" name="wanita">
   </div>
</div>
<!-- Anak-anak -->
<div class="form-group row">
    <label for="laki" class="col-sm-2 col-md-2 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.peserta.laki') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-2 order-md-2 self-center">
        <input type="text" class="form-control" id="laki" placeholder="0" name="laki">
   </div>
    <label for="perempuan" class="col-sm-2 col-md-2 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-right text-lg-right self-center">{{ __('cruds.kegiatan.peserta.perempuan') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-4 order-md-4 self-center">
        <input type="text" class="form-control" id="perempuan" placeholder="0" name="perempuan">
   </div>
</div>
<!-- Anak-anak -->
<div class="form-group row">
    <label for="total" class="col-sm-2 col-md-2 col-lg-2 order-1 order-md-1 col-form-label self-center">{{ __('cruds.kegiatan.peserta.total') }}</label>
    <div class="col-sm-4 col-md-4 col-lg-4 order-2 order-md-2 self-center">
        <input type="text" class="form-control" id="total" placeholder="0" name="total">
   </div>
</div>
--}}


<!-- Penulis Laporan Kegiatan-->
<div class="form-group row tambah_peserta" id="tambah_peserta">
    <button type="button" class="btn btn-success float-right">{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}</button>
</div>
<div class="form-group row list_peserta">
    <table id="list_peserta_kegiatan" class="table table-bordered responsive datatable-kegiatan"
    style="width:100%">
        <thead>
            <tr>
                <th>{{ __('cruds.kegiatan.peserta.identitas') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.nama') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.jenis_kelamin') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.tanggal_lahir') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.disabilitas') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.hamil') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.status_kawin') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.no_kk') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.jenis_peserta') }}</th>
                <th>{{ __('cruds.kegiatan.peserta.nama_kk') }}</th>
            </tr>
    </table>
</div>
