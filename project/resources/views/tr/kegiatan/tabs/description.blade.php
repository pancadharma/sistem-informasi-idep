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


{{-- <div class="card-body table-responsive p-0">
    <table id="peserta_kegiatan" class="table table-sm table-borderless table-hover mb-0">
        <thead style="background-color: #bf28ec !important">
            <tr class="align-middle text-center text-nowrap  col-6">
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.peserta') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.pria') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.total') }}</th>
            </tr>
        </thead>
        <tbody id="daftar_peserta_kegiatan">
            <tr>
                <td colspan="1" width="55%" class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                </td>
                <td colspan="1" width="15%" class="pl-1">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansiaperempuan" name="penerimamanfaatlansiaperempuan">
                </td>
                <td colspan="1" width="15%" class="pl-1">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansialakilaki" name="penerimamanfaatlansialakilaki">
                </td>
                <td colspan="1" width="15%" class="pl-1 pr-1">
                    <input type="number" class="form-control form-control-sm" id="penerimamanfaatlansiatotal" name="penerimamanfaatlansiatotal">
                </td>
            </tr>
            <tr>
            </tr>
        </tbody>
    </table>
</div> --}}
<p><button id="submit_peserta" type="submit">Submit form</button></p>
<!-- jumlah peserta kegiatan -->
<div class="card-body table-responsive p-0">
    <table id="peserta_kegiatan_summary" class="table table-sm table-borderless table-info mb-0 table-custom" width="100%">
        <thead style="background-color: #11bd7e !important">
            <tr class="align-middle text-center text-nowrap">
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.peserta') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.pria') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.total') }}</th>
            </tr>
        </thead>
        <tbody>
            <!--dewasa row-->
            <tr>
                <td class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.dewasa') }}</label>
                </td>
                <td class="pl-1">
                    <input type="number" id="penerimamanfaatdewasaperempuan" name="penerimamanfaatdewasaperempuan" class="calculate form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td class="pl-1">
                    <input type="number" id="penerimamanfaatdewasalakilaki" name="penerimamanfaatdewasalakilaki" class="calculate form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td class="pl-1 pr-1">
                    <input type="number" readonly id="penerimamanfaatdewasatotal" name="penerimamanfaatdewasatotal" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
            </tr>
            <!--lansia row-->
            <tr>
                <td class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                </td>
                <td class="pl-1">
                    <input type="number" id="penerimamanfaatlansiaperempuan" name="penerimamanfaatlansiaperempuan" class="calculate form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td class="pl-1">
                    <input type="number" id="penerimamanfaatlansialakilaki" name="penerimamanfaatlansialakilaki" class="calculate form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td class="pl-1 pr-1">
                    <input type="number" readonly id="penerimamanfaatlansiatotal" name="penerimamanfaatlansiatotal" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
            </tr>
            <!--remaja row-->
            <tr>
                <td class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.remaja') }}</label>
                </td>
                <td class="pl-1">
                    <input type="number" id="penerimamanfaatremajaperempuan" name="penerimamanfaatremajaperempuan" class="calculate form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td class="pl-1">
                    <input type="number" id="penerimamanfaatremajalakilaki" name="penerimamanfaatremajalakilaki" class="calculate form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td class="pl-1 pr-1">
                    <input type="number" readonly id="penerimamanfaatremajatotal" name="penerimamanfaatremajatotal" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
            </tr>
            <!--anak-anak row-->
            <tr>
                <td class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.anak') }}</label>
                </td>
                <td class="pl-1">
                    <input type="number" id="penerimamanfaatanakperempuan" name="penerimamanfaatanakperempuan" class="calculate form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td class="pl-1">
                    <input type="number" id="penerimamanfaatanaklakilaki" name="penerimamanfaatanaklakilaki" class="calculate form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td class="pl-1 pr-1">
                    <input type="number" readonly id="penerimamanfaatanaktotal" name="penerimamanfaatanaktotal" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
            </tr>
        </tbody>
        <tfoot class="pl-1 pr-1">
            <tr class="align-middle text-center text-nowrap">
                <th class="align-middle fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.total') }}</th>
                <th class="align-middle fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                    <input type="number" readonly id="penerimamanfaatperempuantotal" name="penerimamanfaatperempuantotal" class="form-control-border border-width-2 form-control form-control-sm">
                </th>
                <th class="align-middle fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                    <input type="number" readonly id="penerimamanfaatlakilakitotal" name="penerimamanfaatlakilakitotal" class="form-control-border border-width-2 form-control form-control-sm">
                </th>
                <th class="align-middle fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">
                    <input type="number" readonly id="penerimamanfaattotal" name="penerimamanfaattotal" class="form-control-border border-width-2 form-control form-control-sm">
                </th>
            </tr>
        </tfoot>
    </table>
</div>



{{-- <div class="card-body table-responsive p-0">
    <table id="example" class="table table-sm table-borderless table-info mb-0">
        <thead style="background-color: #11bd7e !important">
            <tr class="align-middle text-center text-nowrap">
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.peserta') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.wanita') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.pria') }}</th>
                <th class="align-middle text-white fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.total') }}</th>
            </tr>
        </thead>
        <tbody>
            <!--dewasa row-->
            <tr>
                <td colspan="1" width="30%" class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.dewasa') }}</label>
                </td>
                <td colspan="1" width="10%" class="pl-1">
                    <input type="number" id="penerimamanfaatdewasaperempuan" name="penerimamanfaatdewasaperempuan" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td colspan="1" width="10%" class="pl-1">
                    <input type="text" id="penerimamanfaatdewasalakilaki" name="penerimamanfaatdewasalakilaki" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td colspan="1" width="10%" class="pl-1 pr-1">
                    <input type="text" id="penerimamanfaatdewasatotal" name="penerimamanfaatdewasatotal" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
            </tr>
            <!--lansia row-->
            <tr>
                <td colspan="1" width="30%" class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.lansia') }}</label>
                </td>
                <td colspan="1" width="10%" class="pl-1">
                    <input type="number" id="penerimamanfaatlansiaperempuan" name="penerimamanfaatlansiaperempuan" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td colspan="1" width="10%" class="pl-1">
                    <input type="text" id="penerimamanfaatlansialakilaki" name="penerimamanfaatlansialakilaki" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td colspan="1" width="10%" class="pl-1 pr-1">
                    <input type="text" id="penerimamanfaatlansiatotal" name="penerimamanfaatlansiatotal" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
            </tr>
            <!--remaja row-->
            <tr>
                <td colspan="1" width="30%" class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.remaja') }}</label>
                </td>
                <td colspan="1" width="10%" class="pl-1">
                    <input type="number" id="penerimamanfaatremajaperempuan" name="penerimamanfaatremajaperempuan" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td colspan="1" width="10%" class="pl-1">
                    <input type="text" id="penerimamanfaatremajalakilaki" name="penerimamanfaatremajalakilaki" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td colspan="1" width="10%" class="pl-1 pr-1">
                    <input type="text" id="penerimamanfaatremajatotal" name="penerimamanfaatremajatotal" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
            </tr>
            <!--anak-anak row-->
            <tr>
                <td colspan="1" width="30%" class="pl-1">
                    <label class="text-sm">{{ __('cruds.kegiatan.peserta.anak') }}</label>
                </td>
                <td colspan="1" width="10%" class="pl-1">
                    <input type="number" id="penerimamanfaatanakperempuan" name="penerimamanfaatanakperempuan" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td colspan="1" width="10%" class="pl-1">
                    <input type="text" id="penerimamanfaatanaklakilaki" name="penerimamanfaatanaklakilaki" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
                <td colspan="1" width="10%" class="pl-1 pr-1">
                    <input type="text" id="penerimamanfaatanaktotal" name="penerimamanfaatanaktotal" class="form-control-border border-width-2 form-control form-control-sm">
                </td>
            </tr>

        </tbody>
        <tfoot class="pl-1 pr-1">
            <tr class="align-middle text-center text-nowrap">
                <th class="col-6 align-middle fw-normal text-sm px-2 py-1 py-2 border-start border-secondary">{{ __('cruds.kegiatan.peserta.total') }}</th>
                <th class="col-6 align-middle fw-normal text-sm px-2 py-1 py-2 border-start border-secondary"><span id="penerimamanfaatperempuantotal"></span></th>
                <th class="col-6 align-middle fw-normal text-sm px-2 py-1 py-2 border-start border-secondary"><span id="penerimamanfaatlakilakitotal"></span></th>
                <th class="col-6 align-middle fw-normal text-sm px-2 py-1 py-2 border-start border-secondary"><span id="penerimamanfaattotal"></span></th>
            </tr>
        </tfoot>
    </table>
</div> --}}

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
    .table-custom th:nth-child(2), .table-custom td:nth-child(2),
    .table-custom th:nth-child(3), .table-custom td:nth-child(3),
    .table-custom th:nth-child(4), .table-custom td:nth-child(4) {
        width: 20%;
    }

    .table-custom th:first-child, .table-custom td:first-child {
        width: 40%;
    }
</style>
@endpush

@push('basic_tab_js')
@section('plugins.Summernote', true)

@endpush
