<!-- deskripsi kegiatan -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="deskripsilatarbelakang" class="input-group">
            {{ __('cruds.kegiatan.description.latar_belakang') }}
            <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.description.latar_belakang_helper') }}"></i>
        </label>
        <textarea name="deskripsilatarbelakang" id="deskripsilatarbelakang" placeholder=" {{ __('cruds.kegiatan.description.latar_belakang_helper') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
<!-- tujuan kegiatan -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="deskripsitujuan" class="mb-0 input-group">
            {{ __('cruds.kegiatan.description.tujuan') }}
            <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.description.tujuan_helper') }}"></i>
        </label>
        <textarea name="deskripsitujuan" id="deskripsitujuan" placeholder=" {{ __('cruds.kegiatan.description.tujuan_helper') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
<!-- siapa deskripsi keluaran kegiatan -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="deskripsikeluaran" class="mb-0 input-group">
            {{ __('cruds.kegiatan.description.deskripsikeluaran') }}
            <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.description.keluaran_helper') }}"></i>
        </label>

        <textarea name="deskripsikeluaran" id="deskripsikeluaran" placeholder=" {{ __('cruds.kegiatan.description.keluaran_helper') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
<!-- Peserta yang terlibat -->
<div class="form-group row mb-0">
    <div class="col-sm col-md col-lg self-center">
        <label class="mb-0 self-center input-group">
            {{ __('cruds.kegiatan.peserta.label') }}
            <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="{{ __('cruds.kegiatan.peserta.helper') }}"></i>
        </label>
    </div>
</div>

<div class="card-body table-responsive p-0 col-12">
    <table id="list_peserta_kegiatan" class="table table-sm table-borderless table-hover mb-0">
        <thead style="background-color: #149387 !important">
            <tr class="align-middle text-center">
                <th class="col-6 align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.peserta') }}</th>
                <th class="col-2 align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                <th class="col-2 align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.pria') }}</th>
                <th class="col-2 align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.total') }}</th>
                {{-- <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary rounded-end-3">{{ __('global.action') }}</th> --}}
            </tr>
        </thead>
        <tbody id="tbody_peserta">
            <tr>
                <td class="col-6" colspan="1" width="1%">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.dewasa') }}</label>
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatdewasaperempuan" name="penerimamanfaatdewasaperempuan">
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatdewasalakilaki" name="penerimamanfaatdewasalakilaki">
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatdewasatotal" name="penerimamanfaatdewasatotal">
                </td>
            </tr>
            <tr>
                <td class="col-6">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansiaperempuan" name="penerimamanfaatlansiaperempuan">
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansialakilaki" name="penerimamanfaatlansialakilaki">
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansiatotal" name="penerimamanfaatlansiatotal">
                </td>
            </tr>
            <tr>
                <td class="col-6">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.remaja') }}</label>
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatremajaperempuan" name="penerimamanfaatremajaperempuan">
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatremajalakilaki" name="penerimamanfaatremajalakilaki">
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatremajatotal" name="penerimamanfaatremajatotal">
                </td>
            </tr>
            <tr>
                <td class="col-6">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.anak') }}</label>
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatanakperempuan" name="penerimamanfaatanakperempuan">
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatanaklakilaki" name="penerimamanfaatanaklakilaki">
                </td>
                <td class="col-2">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatanaktotal" name="penerimamanfaatanaktotal">
                </td>
            </tr>

        </tbody>
    </table>
</div>

<div class="card-body table-responsive">
    <table id="peserta_disable" class="table table-sm table-borderless table-hover mb-0">
        <thead style="background-color: #bf28ec !important">
            <tr class="align-middle text-center text-nowrap  col-6">
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.peserta') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.pria') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.total') }}</th>
            </tr>
        </thead>
        <tbody id="tbody_peserta_disable">
            {{-- <tr>
                <td colspan="1" width="25%">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.dewasa') }}</label>
                </td>
                <td colspan="1" width="25%">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatdewasaperempuan" name="penerimamanfaatdewasaperempuan">
                </td>
                <td colspan="1" width="25%">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatdewasalakilaki" name="penerimamanfaatdewasalakilaki">
                </td>
                <td colspan="1" width="25%">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatdewasatotal" name="penerimamanfaatdewasatotal">
                </td>
            </tr> --}}
            <tr>
                <td colspan="1" width="60%">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                </td>
                <td colspan="1" width="10%">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansiaperempuan" name="penerimamanfaatlansiaperempuan">
                </td>
                <td colspan="1" width="10%">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansialakilaki" name="penerimamanfaatlansialakilaki">
                </td>
                <td colspan="1" width="10%">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansiatotal" name="penerimamanfaatlansiatotal">
                </td>
            </tr>
            <tr>

            </tr>
        </tbody>
    </table>
</div>



{{-- <!-- siapa pelatihnya dan darimana -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="pelatih_asal" class="mb-0 self-center input-group">{{ __('cruds.kegiatan.description.pelatih_asal') }}</label>

        <textarea name="pelatih_asal" id="pelatih_asal" placeholder=" {{ __('cruds.kegiatan.description.asal_pelatihan') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
<!-- Apa Saja yang Dilakukan Dalam Kegiatan Tersebut -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="kegiatan" class="mb-0 self-center input-group">{{ __('cruds.kegiatan.description.kegiatan') }}</label>
        <textarea name="kegiatan" id="kegiatan" placeholder=" {{ __('cruds.kegiatan.description.kegiatan') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
<!-- Informasi Lain yang Terkait -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="informasi_lain" class="mb-0 self-center input-group">
            {{ __('cruds.kegiatan.description.informasi_lain') }}
        </label>

        <textarea name="informasi_lain" id="informasi_lain" placeholder=" {{ __('cruds.kegiatan.description.informasi_lain') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>

<!-- Berapa Luas Lahan yang Diintervensi (Ha) -->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center">
        <label for="luas_lahan" class="mb-0 input-group">{{ __('cruds.kegiatan.description.luas_lahan') }}</label>
        <input type="text" class="form-control" id="luas_lahan" placeholder=" {{ __('cruds.kegiatan.description.luas_lahan') }}" name="luas_lahan">
    </div>
</div>

<!-- Bila Kegiatan Berkaitan dengan Intervensi Makhluk Hidup, Barang, dan Hal Lain yang Bisa Dikuantifikasi, Sebutkan -->
<div class="form-group row">
    <div class="col-sm-12 col-md-12 col-lg-10 self-center col-form-label">
        <div class="input-group">
            <label for="barang" class="mb-0 input-group">{{ __('cruds.kegiatan.description.barang') }}</label>
            <input type="text" class="form-control" id="barang" placeholder=" {{ __('cruds.kegiatan.description.barang') }}" name="barang">
        </div>
    </div>
    <!-- Satuan -->
    <div class="col-sm-12 col-md-12 col-lg-2 col-form-label">
        <div class="input-group">
            <label for="satuan" class="mb-0 self-center input-group">{{ __('cruds.kegiatan.description.satuan') }}</label>
            <select class="form-control select2" data-api-url="{{ route('api.kegiatan.satuan') }}" id="satuan" placeholder=" {{ __('global.pleaseSelect') .' '.__('cruds.kegiatan.description.satuan') }}" name="satuan">
            </select>
        </div>
    </div>
</div>
<!--others-->
<div class="form-group row">
    <div class="col-sm col-md col-lg self-center input-group">
        <label for="barang" class="mb-0 self-center input-group">{{ __('cruds.kegiatan.description.others') }}</label>
        <textarea name="others" id="others" placeholder=" {{ __('cruds.kegiatan.description.others') }}" class="form-control summernote" rows="2"></textarea>
    </div>
</div>
 --}}



@push('css')
<style>
    .fixed {position:fixed; bottom:0; left:0; z-index:2;width: 100% !important;}
    .content-header h1 {
        font-size: 1.1rem!important;
        margin: 0;
    }
    .note-toolbar {
        background:#00000000 !important;
    }

    .note-editor.note-frame .note-statusbar,
    .note-editor.note-airframe .note-statusbar {
        background-color: #007bff17 !important;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        border-top: 1px solid #00000000;
    }
</style>
@endpush

@push('basic_tab_js')
@section('plugins.Summernote', true)

@endpush
