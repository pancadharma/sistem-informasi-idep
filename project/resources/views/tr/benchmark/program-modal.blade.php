<!-- Modal -->
<div class="modal fade" id="ModalDaftarProgram" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="TitleModalDaftarProgram" theme="danger">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="TitleModalDaftarProgram">
                    {{ __('global.list') .' '. __('cruds.program.title') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-striped" id="list_program_kegiatan">
                            <thead>
                                <tr>
                                    <th>{{ __('cruds.kegiatan.basic.program_kode') }}</th>
                                    <th>{{ __('cruds.kegiatan.basic.program_nama') }}</th>
                                    <th>{{ __('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
            </div>
        </div>
    </div>
</div>