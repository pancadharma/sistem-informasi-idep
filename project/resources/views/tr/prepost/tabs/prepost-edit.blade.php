<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_program" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
        <input type="hidden" id="user_id" value="{{ auth()->id() }}">
        <!-- id program -->
        <input type="hidden" name="program_id" id="program_id">
        <!-- kode program -->
        <input type="text" class="form-control" id="kode_program" name="kode_program" value="{{ $program->kode }}" disabled>
    </div>
    <!-- nama program-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_program" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.program_nama') }}
        </label>
        <input type="text" class="form-control" id="nama_program" name="nama_program" value="{{ $program->nama }}" readonly>
    </div>
</div>

<div class="form-group row">
    <!-- kode kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.kode') }}
        </label>
        <input type="hidden" class="form-control" id="programoutcomeoutputactivity_id" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="programoutcomeoutputactivity_id" value="{{ $prepost->programActivity->id ?? '' }}" disabled>
        <input type="text" class="form-control" id="kode_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.kode') }}" name="kode_kegiatan"
        data-toggle="modal" data-target="#ModalDaftarProgramActivity" value="{{ $prepost->programActivity->kode ?? '' }}" disabled>
    </div>
    <!-- nama kegiatan-->
    <div class="col-sm-12 col-md-12 col-lg-9 self-center order-2 order-md-2">
        <label for="nama_kegiatan" class="input-group col-form-label">
            {{ __('cruds.kegiatan.basic.nama') }}
        </label>
        <input type="text" class="form-control" id="nama_kegiatan" placeholder="{{ __('cruds.kegiatan.basic.nama') }}" name="nama_kegiatan"value="{{ $prepost->programActivity->nama ?? '' }}" readonly>
    </div>
</div> 

{{-- Nama Pelatihan --}}
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">{{ __('cruds.prepost.nama_pelatihan') }}</label>
        <input type="text" class="form-control" id="nama_pelatihan" placeholder="{{ __('cruds.prepost.nama_pelatihan') }}" name="nama_pelatihan" value="{{ $prepost->trainingname }}">
    </div>
</div>

{{-- Tanggal Pelatihan --}}
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">{{ __('cruds.prepost.start') }}</label>
        <input type="date" class="form-control" id="start_date" placeholder="{{ __('cruds.prepost.start') }}" name="start_date"
        value="{{ $prepost->tanggalmulai ? $prepost->tanggalmulai->format('Y-m-d') : '' }}">
    </div>
    <div class="col-sm-12 col-md-12 col-lg-3 self-center order-1 order-md-1">
        <label for="kode_kegiatan" class="input-group col-form-label">{{ __('cruds.prepost.end') }}</label>
        <input type="date" class="form-control" id="end_date" placeholder="{{ __('cruds.prepost.end') }}" name="end_date"
        value="{{ $prepost->tanggalselesai ? $prepost->tanggalselesai->format('Y-m-d') : '' }}">
    </div>
</div>

<!-- List -->
<div class="row tambah_komodel" id="tambah_komodel">
    <div class="col mb-1 mt-2">
        <button type="button" class="btn btn-warning" id="addDataBtn" data-toggle="modal" data-target="#ModalTambah" title="{{ __('global.add') }}">
            {{ __('global.add') .' '. '' }}
            {{-- <i class="bi bi-person-plus"></i> --}}
        </button>
        <button type="button" class="btn btn-success" id="submitDataBtn">{{ __('global.update') }} <i class="bi bi-save"></i></button>
    </div>
</div>
<div class="row responsive list_peserta">
    <div class="col-12 table-responsive">
        <table id="dataTable" class="table table-sm table-bordered table-hover datatable-kegiatan display text-nowrap">
            <thead style="background-color: rgba(255, 255, 255, 0) !important" class="text-sm">
                <tr class="align-middle text-center display nowrap">
                    <th rowspan="2" class="text-center align-middle d-none">#</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.nama_peserta') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.jenis_kelamin') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.kontak') }}</th>
                    <th colspan="5" class="text-center align-middle">{{ __("cruds.prepost.alamat") }}</th>
                    {{-- <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.start') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.end') }}</th> --}}
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.pre_score') }}</th>
                    <th rowspan="2" class="text-center align-middle" title="{{ __('cruds.prepost.filledby') }}">
                        <i class="fas fa-user-check"></i>
                    </th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.post_score') }}</th>
                    <th rowspan="2" class="text-center align-middle" title="{{ __('cruds.prepost.filledby') }}">
                        <i class="fas fa-user-check"></i>
                    </th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.perubahan') }}</th>
                    <th rowspan="2" class="text-center align-middle">{{ __('cruds.prepost.keterangan') }}</th> 
                    <th rowspan="2" class="text-center align-middle">{{ __("global.actions") }}</th>
                </tr>
                <tr id="activityHeaders" class="text-sm">
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.provinsi") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.kabupaten") }} </th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.kecamatan") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.desa") }}</th>
                    <th class="align-middle text-center">{{ __("cruds.komponenmodel.dusun") }}</th>
                </tr>
            </thead>
            <tbody id="tableBody" class="display overflow-auto">
                @foreach ($pesertaList as $index => $peserta)
                    <tr class="text-sm"
                        data-nama="{{ $peserta->nama }}"
                        data-jeniskelamin="{{ $peserta->jeniskelamin }}"
                        data-notelp="{{ $peserta->notelp }}"
                        data-provinsi-id="{{ $peserta->dusun->desa->kecamatan->kabupaten->provinsi->id ?? '' }}"
                        data-provinsi-nama="{{ $peserta->dusun->desa->kecamatan->kabupaten->provinsi->nama ?? '' }}"
                        data-kabupaten-id="{{ $peserta->dusun->desa->kecamatan->kabupaten->id ?? '' }}"
                        data-kabupaten-nama="{{ $peserta->dusun->desa->kecamatan->kabupaten->nama ?? '' }}"
                        data-kecamatan-id="{{ $peserta->dusun->desa->kecamatan->id ?? '' }}"
                        data-kecamatan-nama="{{ $peserta->dusun->desa->kecamatan->nama ?? '' }}"
                        data-desa-id="{{ $peserta->dusun->desa->id ?? '' }}"
                        data-desa-nama="{{ $peserta->dusun->desa->nama ?? '' }}"
                        data-dusun-id="{{ $peserta->dusun->id ?? '' }}"
                        data-dusun-nama="{{ $peserta->dusun->nama ?? '' }}"
                        data-prescore="{{ $peserta->prescore }}"
                        data-filedbytraineepre="{{ $peserta->filedbytraineepre }}"
                        data-postscore="{{ $peserta->postscore }}"
                        data-filedbytraineepost="{{ $peserta->filedbytraineepost }}"
                        data-valuechange="{{ $peserta->valuechange }}"
                        data-keterangan="{{ $peserta->keterangan }}"
                    >
                        <td class="d-none">{{ $index + 1 }}</td>
                        <td class="align-middle">{{ $peserta->nama }}</td>
                        <td class="align-middle text-center">{{ $peserta->jeniskelamin }}</td>
                        <td class="align-middle">{{ $peserta->notelp }}</td>
                        <td class="align-middle">{{ $peserta->dusun->desa->kecamatan->kabupaten->provinsi->nama ?? '-' }}</td>
                        <td class="align-middle">{{ $peserta->dusun->desa->kecamatan->kabupaten->nama ?? '-' }}</td>
                        <td class="align-middle">{{ $peserta->dusun->desa->kecamatan->nama ?? '-' }}</td>
                        <td class="align-middle">{{ $peserta->dusun->desa->nama ?? '-' }}</td>
                        <td class="align-middle">{{ $peserta->dusun->nama ?? '-' }}</td>
                        <td class="text-center">{{ $peserta->prescore }}</td>
                        <td class="text-center"> {{ $peserta->filedbytraineepre ? 'Ya' : 'Tidak' }}</td>
                        <td class="text-center">{{ $peserta->postscore }}</td>
                        <td class="text-center"> {{ $peserta->filedbytraineepost ? 'Ya' : 'Tidak' }}</td>
                        <td class="align-middle">{{ $peserta->valuechange }}</td>
                        <td class="align-middle">{{ $peserta->keterangan }}</td>
                        <td class="text-center text-nowrap">
                            <button class="btn btn-sm btn-warning edit-btn" title="Edit Peserta" data-id="{{ $peserta->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-row" title="Hapus Peserta" data-id="{{ $peserta->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>