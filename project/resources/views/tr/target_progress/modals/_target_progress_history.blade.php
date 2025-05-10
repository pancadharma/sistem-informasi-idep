<!-- Modal -->
<div class="modal fade" id="target_progress_history_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="title_target_progress_history_modal" theme="primary">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="title_target_progress_history_modal">
                    {{ __('global.list') .' '. __('cruds.history.title') }}
                </h5>
                <button type="button" class="close text-dark" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Target & Progress Table --}}
                <div class="row">
                    <div class="col-12">
                        <table id="target_progress_history_table" class="table w-100 responsive-table table-bordered datatable-target_progress">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

