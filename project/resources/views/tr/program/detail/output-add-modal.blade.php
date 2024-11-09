<x-adminlte-modal id="modalAddOutput" title="{{ __('global.add'). ' ' . __('cruds.program.output.label') }}" size="xl" theme="primary" icon="fas fa-folder-open" static-backdrop scrollable>
    <div style="height:60%;">
        <div class="modal-body">
            <form id="formAddOutput" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="programoutcome_id" id="programoutcome_id">
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
                                        {{ __('cruds.activity.list') }}
                                    </h3>
                                </div>
                                <div class="col">
                                    <button type="button" class="btn btn-sm float-right waves-effect waves-red btn-success" id="addActvityOutcome" data-toggle="tooltip" data-position="top" title=" {{ __('global.add'). ' ' . __('cruds.activity.label') }}">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table" id="activity_output_list" width="100%">
                                    <tbody id="tbody-no-activity">
                                      <tr>
                                        <td colspan="4" class="text-center" id="no-activity">
                                            {!! __('cruds.activity.no_activity', ['icon' => '<i class="bi bi-plus text-danger"></i>']) !!}
                                        </td>
                                      </tr>
                                    </tbody>
                <!-- New tbody elements will be appended here -->
                                  </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button type="submit" class="btn btn-primary waves-effect waves-red btn-block">
                        <i class="bi bi-floppy"></i>
                        {{ __('global.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button theme="danger" label="{{ __('global.close') }}" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>
