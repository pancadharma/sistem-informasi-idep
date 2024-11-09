<x-adminlte-modal id="modalEditOutput" title="{{ __('global.update'). ' ' . __('cruds.program.output.label') }}" size="xl" theme="info" icon="bi bi-pencil-square" static-backdrop scrollable>
    <div style="height:60%;">
        <div class="modal-body">
            <form id="formEditOutput" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="programoutcome_id" id="edit_programoutcome_id">
                 <div class="row">
                    <div class="input-field col-lg-12">
                        <textarea class="materialize-textarea" id="deskripsi_output" name="deskripsi" data-length="1000" required></textarea>
                        <label class="pl-2" for="deskripsi_output">{{ __('cruds.program.output.desc') }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col-lg-12">
                        <textarea class="materialize-textarea" id="indikator_output" name="indikator" data-length="1000" required></textarea>
                        <label class="pl-2" for="indikator_output">{{ __('cruds.program.output.indicator') }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col-lg-12">
                        <textarea class="materialize-textarea" id="target_output" name="target" data-length="1000" required></textarea>
                        <label class="pl-2" for="target_output">{{ __('cruds.program.output.target') }}</label>
                    </div>
                </div>
                {{-- data table output activity --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header pl-0 pr-0">
                                <div class="col">
                                    <h3 class="card-title pt-2">
                                        <i class="bi bi-activity"></i>
                                        {{ __('cruds.activity.add') }}
                                    </h3>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-sm float-right waves-effect waves-red btn-success" id="addActvityOutcomeOnEditModal" data-toggle="tooltip" data-position="top" title=" {{ __('global.add'). ' ' . __('cruds.activity.label') }}">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table" id="edit_activity_output_list" width="100%">
                                    <tbody id="edit_tbody-no-activity">
                                      <tr>
                                        <td colspan="4" class="text-center" id="edit-no-activity">
                                          {{ __('cruds.activity.no_selected') }}
                                        </td>
                                      </tr>
                                    </tbody>
                                    {{-- Show a existing output here as a tbody table --}}
                                  </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button type="submit" id="btnUpdateOutput" class="btn btn-success waves-effect waves-red btn-block">
                        <i class="bi bi-floppy"></i>
                        {{ __('global.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <x-slot name="footerSlot">

    </x-slot>
</x-adminlte-modal>
