<x-adminlte-modal id="modalEditOutput" title="{{ __('global.update'). ' ' . __('cruds.program.output.label') }}" size="xl" theme="success" icon="bi bi-pencil-square" static-backdrop scrollable>
    <div class="h-sm-50vh h-md-60vh h-lg-70vh h-xl-70vh">
        <div class="modal-body">
            <form id="formEditOutput" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="programoutcome_id" id="edit_programoutcome_id">
                <input type="hidden" name="program_id" id="edit_program_id">
                <input type="hidden" name="output_id" id="edit_output_id">
                 <div class="row">
                    <div class="input-field col-lg-12">
                        <textarea class="materialize-textarea" id="edit_deskripsi_output" name="deskripsi" data-length="1000" required></textarea>
                        <label class="pl-2" for="edit_deskripsi_output">{{ __('global.edit') .' '. __('cruds.program.output.desc') .' '. __('cruds.program.output.label') }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col-lg-12">
                        <textarea class="materialize-textarea" id="edit_indikator_output" name="indikator" data-length="1000" required></textarea>
                        <label class="pl-2" for="edit_indikator_output">{{ __('global.edit') .' '. __('cruds.program.output.indicator') .' '. __('cruds.program.output.label') }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col-lg-12">
                        <textarea class="materialize-textarea" id="edit_target_output" name="target" data-length="1000" required></textarea>
                        <label class="pl-2" for="edit_target_output">{{ __('global.edit') .' '. __('cruds.program.output.target') .' '. __('cruds.program.output.label') }}</label>
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
                                            {!! __('cruds.activity.no_activity', ['icon' => '<i class="bi bi-plus text-success"></i>']) !!}
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
        <x-adminlte-button theme="danger" label="{{ __('global.close') }}" data-dismiss="modal"/>
    </x-slot>
</x-adminlte-modal>

@push('css')
<style> @media (min-width: 576px) { .h-sm-50vh { height: 50vh; } } @media (min-width: 768px) { .h-md-60vh { height: 60vh; } } @media (min-width: 992px) { .h-lg-70vh { height: 70vh; } } @media (min-width: 1200px) { .h-xl-90vh { height: 90vh; } } </style>
@endpush

@push('edit-output')
<script defer>
    function fetchActivityOutput(api) {
        $.ajax({
            url: api,
            method: 'GET',
            beforeSend: function() {
                Toast.fire({
                    icon: 'info',
                    title: 'Loading...',
                    timer: 300
                });
            },
            success: function(response) {
                setTimeout(() => {
                    if (response.success) {
                        const data = response.data[0]; // Assuming only one data object
                        $('#edit_deskripsi_output').val(data.deskripsi ?? '').focus().trigger('input');
                        $('#edit_indikator_output').val(data.indikator ?? '').focus().trigger('input');
                        $('#edit_target_output').val(data.target ?? '').focus().trigger('input');

                        $('#edit_activity_output_list').empty();
                        if (!data.activities || data.activities.length === 0 || data.activities.every(activity => !activity.deskripsi && !activity.indikator && !activity.target)) {
                            $('#edit_activity_output_list').append(`
                                <tr>
                                    <td colspan="4" class="text-center" id="edit-no-activity">
                                        {!! __('cruds.activity.no_activity', ['icon' => '<i class="bi bi-plus text-danger"></i>']) !!}
                                    </td>
                                </tr>
                            `);
                        } else {
                            data.activities.forEach(function(activity, index) {
                                $('#edit_activity_output_list').append(`
                                    <tbody id="edit-has-activity-${index}" data-edit-body-id="${index}" class="data-activity-edit">
                                        <tr data-activity-id="${index}">
                                            <th width="10%">Deskripsi Kegiatan</th>
                                            <td width="90%">
                                                <textarea type="textarea" id="edit_deskripsi_${index}" name="deskripsi[]" class="form-control" placeholder="Deskripsi Kegiatan" rows="3" maxlength="1000">${activity.deskripsi ?? ''}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="10%">Indikator Kegiatan</th>
                                            <td width="90%">
                                                <textarea type="textarea" id="edit_indikator_${index}" name="indikator[]" class="form-control" placeholder="Indikator Kegiatan" rows="3" maxlength="1000">${activity.indikator ?? ''}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="10%">Target Kegiatan</th>
                                            <td width="90%">
                                                <textarea type="textarea" id="edit_target_${index}" name="target[]" class="form-control" placeholder="Target Kegiatan" rows="3" maxlength="1000">${activity.target ?? ''}</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <td class="align-middle float-right">
                                                <div style="text-align: center">
                                                    <button type="button" class="btn btn-sm btn-danger waves-effect waves-red edit-remove-activity" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                `);
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.message,
                        });
                    }
                }, 250);
            },
            error: function(xhr, textStatus, errorThrown) {
                const errorMessage = getErrorMessage(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage,
                    confirmButtonText: 'Okay'
                });
            },
        });
    }



    $(document).ready(function() {
        $('#edit_activity_output_list').on('click', '.edit-remove-activity', function(e) {
            e.preventDefault();
            var EditActivityId = $(this).closest('tbody').data('edit-body-id');
            $(`#edit-has-activity-${EditActivityId}`).remove();

            // Check if there are no more activity rows and show the no-activity message
            if ($('#edit_activity_output_list tbody.edit-has-activity').length === 0) {
                $('#edit_tbody-no-activity').removeClass('hide').html(`
                    <tr>
                    <td colspan="4" class="text-center" id="edit-no-activity">
                        {{ __('cruds.activity.no_selected') }}
                    </td>
                    </tr>
                `);
            }
        });

        $('#outcome_output_list, tbody').on('click', '.btnEditOutcomeOutput', function(e) {
            let outputId = $(this).data('output-id');
            let outcomeId = $('.btnEditOutcomeOutput').data('outcome-id');
            let OutcomeProgramId = {{ $program->id }};
            $('#edit_programoutcome_id').val(outcomeId);
            $('#edit_program_id').val(OutcomeProgramId);
            $('#edit_output_id').val(outputId);

            let api = "{{ route('api.program.output.activity', ':id') }}".replace(':id', outputId);
            fetchActivityOutput(api);
            $('#modalEditOutput').modal('show');

        });

        // Reset modal content when closed
        $('#modalEditOutput').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#edit_tbody-no-activity').removeClass('hide').html(`
                <tr>
                <td colspan="4" class="text-center" id="edit-no-activity">
                    {{ __('cruds.activity.no_selected') }}
                </td>
                </tr>
            `);
            $('#edit_activity_output_list').find('tbody.data-activity-edit').remove();
        });

        $('#addActvityOutcomeOnEditModal').click(function() {
            let activityIndex = $('#edit_activity_output_list tbody.data-activity-edit').length + 1;
            $('#edit_tbody-no-activity').addClass('hide').empty();

            let newActivityTbody = `
                <tbody id="edit-has-activity-${activityIndex}" data-edit-body-id="${activityIndex}" class="data-activity-edit">
                    <tr data-activity-id="${activityIndex}">
                        <th width="10%">Deskripsi Kegiatan</th>
                        <td width="90%">
                        <textarea type="textarea" id="edit_deskripsi_${activityIndex}" name="deskripsi[]" class="form-control" placeholder="Deskripsi Kegiatan" rows="3" maxlength="1000"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">Indikator Kegiatan</th>
                        <td width="90%">
                        <textarea type="textarea" id=edit_indikator_"${activityIndex}" name="indikator[]" class="form-control" placeholder="Indikator Kegiatan" rows="3" maxlength="1000"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">Target Kegiatan</th>
                        <td width="90%">
                        <textarea type="textarea" id=edit_target_"${activityIndex}" name="target[]" class="form-control" placeholder="Target Kegiatan" rows="3" maxlength="1000"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td class="align-middle float-right">
                        <div style="text-align: center">
                            <button type="button" class="btn btn-sm btn-danger waves-effect waves-red edit-remove-activity" title="Hapus">
                            <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        </td>
                </tr>
                </tbody>`;
            $('#edit_activity_output_list').append(newActivityTbody);

            // Initialize Summernote for the new textareas

            // $(`#edit-has-activity-${activityIndex} textarea`).each(function() {
            //     if (!$(this).data('initialized')) {
            //         $(this).summernote({
            //             height: 100,
            //             width: '100%',
            //             toolbar: [
            //                 ['font', ['bold', 'italic', 'underline', 'clear']],
            //                 ['color', ['color']],
            //                 ['table', ['table']],
            //                 ['paragraph', ['paragraph']],
            //                 ['view', ['fullscreen', 'codeview']],
            //             ],
            //             inheritPlaceholder: true,
            //         });
            //         $(this).data('initialized', true); // Mark this textarea as initialized
            //     }
            // });
        });


    });
</script>
@endpush
