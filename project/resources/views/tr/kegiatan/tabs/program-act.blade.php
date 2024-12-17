<!-- Modal Daftar Kegiatan Based on Selected Program-->
<div class="modal fade" id="ModalDaftarProgramActivity" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="TitleModalDaftarProgramActivity" theme="info">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="TitleModalDaftarProgramActivity">
                    <i class="bi bi-person-plus"></i>
                    {{ __('cruds.kegiatan.list') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="list_program_out_activity">
                        <thead>
                            <tr>
                                {{--
                                <th>{{ __('cruds.kegiatan.kode') }}</th>
                                <th>{{ __('cruds.kegiatan.nama') }}</th>

                                --}}
                                <!--should be changed based on what data is showing kode & nama kegiatan commented above-->
                                <th>{{ __('cruds.activity.deskripsi') }}</th>
                                <th>{{ __('cruds.activity.indicator') }}</th>
                                <th>{{ __('cruds.activity.target') }}</th>
                                <th>{{ __('global.action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="data-program-kegiatan">
                            {{-- autofill by js based on selected program --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
            </div>
        </div>
    </div>
</div>

