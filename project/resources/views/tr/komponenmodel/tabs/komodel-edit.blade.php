<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_program" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
        <input type="hidden" id="user_id" value="{{ auth()->id() }}">
        <!-- id program -->
        <input type="hidden" name="program_id" id="program_id">
        <!-- kode program -->
        <input type="text" class="form-control" id="kode_program" name="kode_program" value="{{ $komodel->program->kode }}" disabled>
    </div>
    <!-- nama program-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_program" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.program_nama') }}
        </label>
        <input type="text" class="form-control" id="nama_program" name="nama_program" value="{{ $komodel->program->nama }}" readonly>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_komponenmodel" class="input-group col-form-label">{{ __('cruds.komponenmodel.title') }}</label>
        <div class="select2-purple">
            <select class="form-control select2" name="model_id[]" id="model_id" data-api-url="{{ route('api.komodel.model') }}" required>
                @if(isset($komponen))
                    <option value="{{ $komponen->id }}" selected>{{ $komponen->nama }}</option>
                @endif
            </select>
        </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label class="invisible">Tombol</label> <!-- Label tetap ada tetapi disembunyikan -->
        <div class="input-group-append">
            <button type="button" class="btn btn-primary" id="addKomponenBtn" data-toggle="modal" data-target="#ModalTambahKomponen">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_sektor" class="input-group col-form-label">{{ __('cruds.komponenmodel.label_sektor') }}</label>
        <div class="select2-purple">
            <select class="form-control select2" name="sektor_id[]" id="sektor_id" multiple data-api-url="{{ route('api.komodel.sektor') }}" required>
                @foreach ($komodel->sektors as $sektor)
                    <option value="{{ $sektor->id }}" selected>{{ $sektor->nama }}</option>
                @endforeach
            </select>
        </div>
        
    </div>
</div>

<div class="row tambah_komodel" id="tambah_komodel">
    <div class="col mb-1 mt-2">
        <button type="button" class="btn btn-warning" id="addDataBtn" data-toggle="modal" data-target="#ModalTambah" title="{{ __('global.add') }}">
            {{ __('global.add') .' '. '' }}
            {{-- <i class="bi bi-person-plus"></i> --}}
        </button>
        <button type="button" class="btn btn-success" id="submitDataBtn">{{ __('global.update') }} <i class="bi bi-save"></i></button>
    </div>
    {{-- <div class="col-sm-12 col-md-12 col-lg-2 mb-2 mt-2">
        <div class="input-group ml-auto">
            <input type="text" class="form-control" id="search_peserta" placeholder="Cari..." name="search_peserta">
            <span class="input-group-append">
                <span type="button" class="btn btn-primary"><i class="fas fa-fw fa-search"></i></span>
            </span>
        </div>
    </div> --}}
</div>

<div class="row responsive list_peserta">
    <div class="col-12 table-responsive">
        <table id="dataTable" class="table table-sm table-bordered table-hover datatable-kegiatan display text-nowrap">
            <thead style="background-color: rgba(255, 255, 255, 0) !important" class="text-sm">
                <tr class="align-middle text-center display nowrap">
                    <th rowspan="2" class="text-center align-middle d-none">#</th>
                    <th colspan="7" data-dt-order="disable" class="text-center align-middle">{{ __("cruds.komponenmodel.label_lokasi") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.komponenmodel.jumlah") }}</th>
                    <th rowspan="2" class="align-middle">{{ __("cruds.komponenmodel.satuan") }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __("global.actions") }}</th>
                </tr>
                <tr id="activityHeaders" class="text-sm">
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.provinsi") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.kabupaten") }} </th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.kecamatan") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.desa") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.dusun") }}</th>
                    <th class="align-middle text-center">Long</th>
                    <th class="align-middle text-center">Lat</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="display overflow-auto">
                @foreach ($lokasiData as $index => $lokasi)
                    <tr class="text-sm"
                        data-provinsi-id="{{ $lokasi->provinsi_id }}"
                        data-provinsi-nama="{{ $lokasi->provinsi->nama ?? '' }}"
                        data-kabupaten-id="{{ $lokasi->kabupaten_id }}"
                        data-kabupaten-nama="{{ $lokasi->kabupaten->nama ?? '' }}"
                        data-kecamatan-id="{{ $lokasi->kecamatan_id }}"
                        data-kecamatan-nama="{{ $lokasi->kecamatan->nama ?? '' }}"
                        data-desa-id="{{ $lokasi->desa_id }}"
                        data-desa-nama="{{ $lokasi->desa->nama ?? '' }}"
                        data-dusun-id="{{ $lokasi->dusun_id }}"
                        data-dusun-nama="{{ $lokasi->dusun->nama ?? '' }}"
                        data-long="{{ $lokasi->long }}"
                        data-lat="{{ $lokasi->lat }}"
                        data-jumlah="{{ $lokasi->jumlah }}"
                        data-satuan-id="{{ $lokasi->satuan_id }}"
                    >
                        <td class="text-center d-none">{{ $index + 1 }}</td>
                        <td class="text-center align-middle">{{ $lokasi->provinsi->nama ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $lokasi->kabupaten->nama ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $lokasi->kecamatan->nama ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $lokasi->desa->nama ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $lokasi->dusun->nama ?? '-' }}</td>
                        <td class="text-center align-middle">{{ $lokasi->long }}</td>
                        <td class="text-center align-middle">{{ $lokasi->lat }}</td>
                        <td class="text-center align-middle jumlah-cell">{{ $lokasi->jumlah }}</td>
                        <td class="text-center align-middle">{{ $lokasi->satuan->nama ?? '-' }}</td>
                        <td class="text-center text-nowrap">
                            {{-- <button class="btn btn-sm btn-warning edit-row" title="Edit Lokasi" data-index="{{ $index }}">
                                <i class="fas fa-edit"></i>
                            </button> --}}
                            <button class="btn btn-sm btn-warning edit-btn" title="Edit Lokasi" data-id="{{ $lokasi->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            {{-- <button class="btn btn-sm btn-danger delete-row" title="Hapus Lokasi" data-index="{{ $index }}">
                                <i class="fas fa-trash"></i>
                            </button> --}}
                            <button class="btn btn-danger btn-danger delete-row"  title="Hapus Lokasi" data-id="{{ $lokasi->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>