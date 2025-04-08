    <!-- Program and Activity Select (Readonly) -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-4 self-center order-1 order-md-1">
            <label for="kode_program" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_kode') }}</label>
            <!-- id program -->
            <input type="hidden" name="program_id" id="program_id" value="{{ $program->id }}">
            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::id() }}">
            <!-- kode program -->
            <input type="text" class="form-control" id="kode_program" name="kode_program" value="{{ $program->kode }}" disabled>
        </div>
        <!-- nama program -->
        <div class="col-sm-12 col-md-12 col-lg-8 self-center order-2 order-md-2">
            <label for="nama_program" class="input-group col-form-label">{{ __('cruds.kegiatan.basic.program_nama') }}</label>
            <input type="text" class="form-control" id="nama_program" name="nama_program" value="{{ $program->nama }}" readonly>
        </div>
    </div>

    <!-- Peserta Kegiatan -->
    <div class="row no-print" id="tambah_peserta">
        <div class="col-sm-12 col-md-12 col-lg mb-2 mt-2">
            <button type="button" class="btn btn-warning" id="addDataBtn" data-toggle="modal" data-target="#ModalTambahPeserta" title="{{ __('global.add') .' '. __('cruds.kegiatan.peserta.label') }}">
                {{ __('global.add') .' '. '' }} <i class="bi bi-person-plus"></i>
            </button>
            <button type="button" class="btn btn-success" id="submitDataBtn">{{ __('global.update') }} <i class="bi bi-save"></i></button>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-2 mb-2 mt-2">
            <div class="input-group ml-auto">
                <input type="text" class="form-control" id="search_peserta" placeholder="Cari..." name="search_peserta">
                <span class="input-group-append">
                    <span type="button" class="btn btn-primary"><i class="fas fa-fw fa-search"></i></span>
                </span>
            </div>
        </div>
    </div>
    <!-- Beneficiary Table -->
    <div class="row responsive list_peserta">
        <div class="col-12 table-responsive">
            <table id="dataTable" class="table table-sm table-bordered table-hover datatable-kegiatan display text-nowrap">
                <thead style="background-color: rgba(255, 255, 255, 0) !important" class="text-sm">
                    <tr class="align-middle text-center display nowrap">
                        <th rowspan="2" class="text-center align-middle d-none">#</th>
                        <th rowspan="2" class="align-middle text-nowrap">{{ __("cruds.beneficiary.penerima.nama") }}</th>
                        <th rowspan="2" class="align-middle text-wrap">{{ __("cruds.beneficiary.penerima.gender") }}</th>
                        <th rowspan="2" class="align-middle text-wrap">{{ __("cruds.beneficiary.penerima.marjinal") }}</th>
                        <th colspan="4" data-dt-order="disable" class="text-center align-middle">{{ __("cruds.beneficiary.penerima.address") }}</th>
                        <th rowspan="2" class="align-middle">{{ __("cruds.beneficiary.penerima.no_telp") }}</th>
                        <th rowspan="2" class="align-middle text-wrap">{{ __("cruds.beneficiary.penerima.jenis_kelompok") }}</th>
                        <th rowspan="2" class="align-middle">{{ __("cruds.beneficiary.penerima.age") }}</th>
                        <th colspan="4" data-dt-order="disable" class="text-center align-middle">{{ __("cruds.beneficiary.penerima.age_group") }}</th>
                        <th colspan="{{ $activities->count() }}" data-dt-order="disable" class="text-center align-middle" id="headerActivityProgram">{{ __('cruds.beneficiary.activity_code') }}</th>
                        <th rowspan="2" data-dt-order="disable" class="text-center align-middle text-nowrap" id="header_is_non_activity">Non-AC</th>
                        <th rowspan="2" data-dt-order="disable" class="text-center align-middle text-nowrap" id="header_keterangan">{{ __('cruds.beneficiary.penerima.ket') }}</th>
                        <th rowspan="2" data-dt-order="disable" class="text-center align-middle">{{ __("global.actions") }}</th>
                    </tr>
                    <tr id="activityHeaders" class="text-sm">
                        <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rt") }}</th>
                        <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.rw") }}</th>
                        <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.dusun") }} <small><i class="fas fa-question-circle" title="{{ __("cruds.beneficiary.penerima.banjar") }}" data-placement="top"></i></small></th>
                        <th colspan="1" class="align-middle text-center">{{ __("cruds.beneficiary.penerima.desa") }}</th>
                        <th colspan="1" class="align-middle text-center bg-cyan" title="{{ __('cruds.kegiatan.peserta.anak') }}">0-17</th>
                        <th colspan="1" class="align-middle text-center bg-teal" title="{{ __('cruds.kegiatan.peserta.remaja') }}">18-24</th>
                        <th colspan="1" class="align-middle text-center bg-yellow" title="{{ __('cruds.kegiatan.peserta.dewasa') }}">25-59</th>
                        <th colspan="1" class="align-middle text-center bg-pink" title="{{ __('cruds.kegiatan.peserta.lansia') }}"> > 60 </th>
                        @foreach ($activities as $activity)
                            <th class="align-middle text-center activity-header" data-activity-id="{{ $activity->id }}">{{ $activity->kode }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="tableBody" class="display nowrap overflow-auto">
                    @foreach ($beneficiaries as $beneficiary)
                        <tr data-id="{{ $beneficiary->id }}">
                            <td class="text-center d-none">{{ $loop->iteration }}</td>
                            <td data-nama="{{ $beneficiary->nama }}">{{ $beneficiary->nama }}</td>
                            <td data-gender="{{ $beneficiary->jenis_kelamin }}">{{ $beneficiary->jenis_kelamin === 'laki' ? __('cruds.beneficiary.penerima.laki') : ($beneficiary->jenis_kelamin === 'perempuan' ? __('cruds.beneficiary.penerima.perempuan') : __('cruds.beneficiary.penerima.lainnya')) }}</td>
                            <td data-kelompok_rentan="{{ $beneficiary->kelompokMarjinal->pluck('id')->implode(',') }}"
                                data-kelompok_rentan_full='{{ json_encode($beneficiary->kelompokMarjinal->map(fn($k) => ['id' => $k->id, 'text' => $k->nama])) }}'>
                                {{ $beneficiary->kelompokMarjinal->pluck('nama')->implode(', ') }}
                            </td>
                            <td data-rt="{{ $beneficiary->rt }}">{{ $beneficiary->rt }}</td>
                            <td data-rw="{{ $beneficiary->rw }}">{{ $beneficiary->rw }}</td>
                            <td data-dusun-id="{{ $beneficiary->dusun_id ?? '' }}">{{ $beneficiary->dusun->nama ?? ''}}</td>
                            <td data-desa-id="{{ $beneficiary->dusun->desa_id ?? '' }}">{{ $beneficiary->dusun->desa->nama ?? '' }}</td>
                            <td data-no_telp="{{ $beneficiary->no_telp }}">{{ $beneficiary->no_telp }}</td>
                            <td data-jenis_kelompok="{{ $beneficiary->jenisKelompok->pluck('id')->implode(',') }}"
                                data-jenis_kelompok_full='{{ json_encode($beneficiary->jenisKelompok->map(fn($j) => ['id' => $j->id, 'text' => $j->nama])) }}'>
                                {{ $beneficiary->jenisKelompok->pluck('nama')->implode(', ') }}
                            </td>
                            <td class="text-center" data-usia="{{ $beneficiary->umur }}">{{ $beneficiary->umur }}</td>
                            <td class="text-center">{{ $beneficiary->umur <= 17 ? '√' : '' }}</td>
                            <td class="text-center">{{ $beneficiary->umur >= 18 && $beneficiary->umur <= 24 ? '√' : '' }}</td>
                            <td class="text-center">{{ $beneficiary->umur >= 25 && $beneficiary->umur <= 59 ? '√' : '' }}</td>
                            <td class="text-center">{{ $beneficiary->umur > 60 ? '√' : '' }}</td>
                            @foreach ($activities as $activity)
                                <td class="text-center" data-program-activity-id="{{ $activity->id }}">{{ $beneficiary->penerimaActivity->contains('id', $activity->id) ? '√' : '' }}</td>
                            @endforeach
                            <td data-is_non_activity="{{ $beneficiary->is_non_activity ? 'true' : 'false' }}">{{ $beneficiary->is_non_activity ? '√' : '' }}</td>
                            <td data-keterangan="{{ $beneficiary->keterangan }}" class="ellipsis-cell">{{ $beneficiary->keterangan }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $beneficiary->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $beneficiary->id }}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
