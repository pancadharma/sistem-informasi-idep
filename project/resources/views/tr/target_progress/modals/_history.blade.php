<!-- Modal -->
<div class="modal fade" id="ModalHistoryTargetProgress" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="TitleModalHistoryTargetProgress" theme="primary">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="TitleModalHistoryTargetProgress">
                    {{ __('global.list') .' '. __('cruds.history.title') }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-striped w-100" id="history_table">
                            <thead>
                                <tr>
                                    <th>raw_tanggal</th>
                                    <th>{{ __('cruds.target_progress.basic.tanggal') }}</th>
                                    <th class="text-center">
                                        <i class="fa fa-history" aria-hidden="true"></i>
                                        {{ __('cruds.target_progress.basic.history') }}
                                    </th>
                                    <th class="text-nowrap">{{ __('cruds.target_progress.basic.kode_program') }}</th>
                                    <th class="text-nowrap">{{ __('cruds.target_progress.basic.nama_program') }}</th>
                                    <th class="text-nowrap">{{ __('cruds.target_progress.basic.jumlah_target') }}</th>
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

