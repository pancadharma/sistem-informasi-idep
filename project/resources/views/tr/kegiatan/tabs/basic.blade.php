{{-- Basic Information --}}

<div class="form-group row">
    <label for="nama_kegiatan" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">
        {{ __('cruds.kegiatan.basic.nama') }}
    </label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
      <input type="text" class="form-control form-control-lg" id="nama_kegiatan" placeholder="" name="nama">
    </div>
</div>

<div class="form-group row">
    <label for="kode_kegiatan" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">
        {{ __('cruds.kegiatan.basic.kode') }}
    </label>
    <div class="col-sm-4 col-md-4 col-lg-6 order-2 order-md-2 self-center">
      <input type="text" class="form-control form-control-lg" id="kode_kegiatan" placeholder="" name="nama">
    </div>
</div>

<div class="form-group row">
    <label for="nama_desa" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">
        {{ __('cruds.kegiatan.basic.desa') }}
    </label>
    <div class="col-sm col-md col-lg-6 order-2 order-md-2 self-center">
      <input type="text" class="form-control form-control-lg" id="nama_desa" placeholder="" name="desa">
    </div>
</div>
<div class="form-group row">
    <label for="lokasi" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">
        {{ __('cruds.kegiatan.basic.lokasi') }}
    </label>
    <div class="col-sm col-md col-lg-6 order-2 order-md-2 self-center">
      <input type="text" class="form-control form-control-lg" id="lokasi" placeholder="" name="desa">
    </div>
</div>
<div class="form-group row">
    <!-- tgl mulai-->
    <label for="tanggalmulai" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">
        {{ __('cruds.kegiatan.basic.tanggalmulai') }}
    </label>
    <div class="col-sm-3 col-md-3 col-lg-4 order-2 order-md-2 self-center">
        <input type="date" class="form-control form-control-lg" id="tanggalmulai" placeholder="" name="tanggalmulai">
    </div>
    <!-- tgl selesai-->
    <label for="tanggalselesai" class="col-sm-3 col-md-3 col-lg-2 order-3 order-md-3 col-form-label text-sm-left text-md-left text-lg-right self-center">
        {{ __('cruds.kegiatan.basic.tanggalselesai') }}
    </label>
    <div class="col-sm-3 col-md-3 col-lg-4 order-4 order-md-4 self-center">
        <input type="date" class="form-control form-control-lg" id="tanggalselesai" placeholder="" name="tanggalselesai">
    </div>
</div>
<!-- nama mitra-->
<div class="form-group row">
    <label for="nama_mitra" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label self-center">
        {{ __('cruds.kegiatan.basic.nama_mitra') }}
    </label>
    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
      <input type="text" class="form-control form-control-lg" id="nama_mitra" placeholder="" name="nama_mitra">
    </div>
</div>

@push('next-button')
<button id="next-button" class="btn btn-primary float-right">Next</button>
@endpush

@push('basic_tab_js')
<script>
    document.getElementById('next-button').addEventListener('click', function(e) {
        e.preventDefault();
        var tabs = document.querySelectorAll('#details-kegiatan-tab .nav-link');
        var activeTab = document.querySelector('#details-kegiatan-tab .nav-link.active');
        var nextTabIndex = Array.from(tabs).indexOf(activeTab) + 1;

        if (nextTabIndex < tabs.length) {
            tabs[nextTabIndex].click();
        }
    });
</script>
@endpush
