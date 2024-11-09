@extends('layouts.app')

@section('subtitle', __('global.details') . ' ' . __('cruds.program.title_singular'). ' '. $program->nama ?? '')
@section('content_header_title', __('cruds.program.outcome.list_program').' '. $program->nama ?? '')
@section('sub_breadcumb', __('cruds.program.title_singular'))

@section('content_body')
<div class="row">
    <div class="col-md-3">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('cruds.program.outcome.label') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                    @forelse ($outcomes as $index => $outcome)
                    <li class="nav-item">
                        <button type="button" class="nav-link btn text-left btn-block btn-list-outcome waves-effect waves-teal" data-index="{{ $index + 1 }}" data-outcome-id="{{ $outcome->id }}" data-action="load">
                            {{ __('cruds.program.outcome.out_program') }} {{ $index + 1 }}
                            <i class="bi bi-box-arrow-in-right text-danger float-right align-middle mt-2" title="{{ __('global.details') }} {{ __('cruds.program.outcome.out_program') }} {{ $index + 1 }}"></i>
                        </button>
                    </li>
                    @empty
                    <div class="nav flex-column nav-tabs h-100">
                        <span class="card-body">
                            {!! __('cruds.program.outcome.no_outcome', ['icon' => '<i class="bi bi-pencil-square"></i>']) !!}
                        </span>
                        <button type="button" class="btn text-right btn-sm waves-effect waves-teal" onclick="navigateToEditPage('{{ route('program.edit', $program->id) }}', 'outcome')">
                            <i class="bi bi-pencil-square float-right align-middle p-2"></i>
                        </button>
                    </div>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">{{ __('cruds.program.title_singular') }}</h3>
            </div>
            {{-- show objective and goals --}}
            <div class="accordion" id="programObjectiveGoals">
                <div class="card mb-0">
                    <div class="card-header" id="cardObjective">
                        <button class="btn-link btn text-left btn-block pt-0 pb-0 pr-0 pl-0" type="button" data-toggle="collapse" data-target="#objectiveData" aria-expanded="true" aria-controls="objectiveData">
                            <h5 class="card-title">{{ __('cruds.program.objective.label') }}</h5>
                        </button>
                    </div>

                    <div id="objectiveData" class="collapse show" aria-labelledby="cardObjective"
                        data-parent="#programObjectiveGoals">
                        <div class="card-body">
                            <div class="text-wrap">
                                <div class="label font-weight-bold">{{ __('cruds.program.objective.desc') }}</div>
                                <p class="text-break">
                                    {{ old('goal', $program->objektif->deskripsi ?? '') }}
                                </p>
                            </div>
                            <div class="text-wrap">
                                <div class="label font-weight-bold">{{ __('cruds.program.objective.indicator') }}</div>
                                <p class="text-break">
                                    {{ old('goal', $program->objektif->indikator ?? '') }}
                                </p>
                            </div>
                            <div class="text-wrap">
                                <div class="label font-weight-bold">{{ __('cruds.program.objective.target') }}</div>
                                <p class="text-break">
                                    {{ old('goal', $program->objektif->target ?? '') }}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card mb-0">
                    <div class="card-header" id="cardGoals">
                        <button class="btn-link btn text-left btn-block pt-0 pb-0 pr-0 pl-0" type="button" data-toggle="collapse" data-target="#goalsData" aria-expanded="false" aria-controls="goalsData">
                            <h5 class="card-title">{{ __('cruds.program.goals.label') }}</h5>
                        </button>
                    </div>
                    <div id="goalsData" class="collapse" aria-labelledby="cardGoals" data-parent="#programObjectiveGoals">
                        <div class="card-body">
                            <div class="text-wrap">
                                <div class="label font-weight-bold">{{ __('cruds.program.objective.desc') }}</div>
                                <p class="text-break">
                                    {{ old('goal', $program->goal->deskripsi ?? '') }}
                                </p>
                            </div>
                            <div class="text-wrap">
                                <div class="label font-weight-bold">{{ __('cruds.program.objective.indicator') }}</div>
                                <p class="text-break">
                                    {{ old('goal', $program->goal->indikator ?? '') }}
                                </p>
                            </div>
                            <div class="text-wrap">
                                <div class="label font-weight-bold">{{ __('cruds.program.objective.target') }}</div>
                                <p class="text-break">
                                    {{ old('goal', $program->goal->target ?? '') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-primary card-outline hide" id="outcomeData">
            <div class="card-header">
                <div class="row">
                    <div class="col pl-2">
                        <h3 class="card-title pt-2">
                            {{ __('global.details') . ' ' . __('cruds.program.outcome.out_program') }} <span id="outcome-title"></span>
                        </h3>
                    </div>
                </div>
            </div>
            {{-- showing outcome details after clicking --}}
            <div class="card-body hide" id="detail_outcome">
                <div class="row">
                    <div class="input-field col-lg-4">
                        {{-- <input id="deskripsi" name="deskripsi" type="text" class="validate" readonly placeholder="{{ __('cruds.program.outcome.desc') }}" data-toggle="tooltip" data-placement="top" data-position="top" data-tooltip="{{ __('cruds.program.outcome.desc') }}"> --}}
                        {{-- <label for="deskripsi">{{ __('cruds.program.outcome.desc') }}</label> --}}
                        <div class="text-wrap">
                            <div class="label font-weight-bold">{{ __('cruds.program.outcome.desc') }}</div>
                            <p class="text-break">
                                <span id="deskripsi" name="deskripsi"></span>
                            </p>
                        </div>

                    </div>
                    <div class="input-field col-lg-4">
                        {{-- <input id="indikator" name="indikator" type="text" class="validate" readonly placeholder="{{ __('cruds.program.outcome.indicator') }}" data-toggle="tooltip" data-placement="top" data-position="top" data-tooltip="{{ __('cruds.program.outcome.indicator') }}">
                        <label for="indikator">{{ __('cruds.program.outcome.indicator') }}</label> --}}
                        <div class="text-wrap">
                            <div class="label font-weight-bold">{{ __('cruds.program.outcome.indicator') }}</div>
                            <p class="text-break">
                                <span id="indikator" name="indikator"></span>
                            </p>
                        </div>
                    </div>
                    <div class="input-field col-lg-4">
                        {{-- <input id="target" name="target" type="text" class="validate" readonly placeholder="{{ __('cruds.program.outcome.target') }}" data-toggle="tooltip" data-placement="top" data-position="top" data-tooltip="{{ __('cruds.program.outcome.target') }}">
                        <label for="target">{{ __('cruds.program.outcome.target') }}</label> --}}
                        <div class="text-wrap">
                            <div class="label font-weight-bold">{{ __('cruds.program.outcome.target') }}</div>
                            <p class="text-break">
                                <span id="target" name="target"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- hidden detail output --}}
        <div class="card card-outline card-primary hide" id="list_output">
            <div class="card-header pr-0 pl-3">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title pt-2"><i class="fas fa-list-ul"></i> {{ __('cruds.program.output.list') }} {{ __('cruds.program.outcome.of_outcome') }} <span id="outcome-number"></span></h3>
                    </div>
                    <div class="col">
                        <button type="button" data-target="modalAddOutput" class="btn btn-sm modal-trigger float-right waves-effect waves-teal btn-success" id="addOutputBtn" data-toggle="tooltip" data-position="top" data-tooltip=" {{ __('global.add'). ' ' . __('cruds.program.output.label') }}"><i class="bi bi-plus"></i>
                            {{ __('global.add'). ' ' . __('cruds.program.output.label') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pr-0 pl-0 pt-0">
                <table id="outcome_output_list" class="highlight striped" style="width:100%">
                    <thead class="">
                        <tr>
                            <th class="pl-3" width="30%">{{ __('Output Description') }}</th>
                            <th width="30%">{{ __('Output Indicator') }}</th>
                            <th width="30%">{{ __('Output Target') }}</th>
                            <th width="10%" class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody id="row-output">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('tr.program.detail.output-add-modal')
@include('tr.program.detail.output') {{-- show edit output --}}
@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/materialize.css') }}">

@endpush

@push('js')

<script src="{{ asset('vendor/adminlte/dist/js/materialize.js') }}"></script>
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
@section('plugins.Summernote', true)

<script src="{{ asset('/vendor/inputmask/jquery.maskMoney.js') }}"></script>
<script src="{{ asset('/vendor/inputmask/AutoNumeric.js') }}"></script>

<script>
    function handleErrors(response) {
        let errorMessage = response.message;
        if (response.status === 400) {
            try {
                const errors = response.errors;
                errorMessage = formatErrorMessages(errors);
            } catch (error) {
                errorMessage = "<p>An unexpected error occurred. Please try again later.</p>";
            }
        }
        Swal.fire({
            title: "Error!",
            html: errorMessage,
            icon: "error"
        });
    }

    function formatErrorMessages(errors) {
        let message = '<br><ul style="text-align:left!important">';
        for (const field in errors) {
            errors[field].forEach(function(error) {
                message += `<li>${error}</li>`;
            });
        }
        message += '</ul>';
        return message;
    }

    function getErrorMessage(xhr) {
        let message;
        try {
            const response = JSON.parse(xhr.responseText);
            message = response.message || 'An unexpected error occurred. Please try again later.';
        } catch (e) {
            message = 'An unexpected error occurred. Please try again later.';
        }
        return message;
    }

    function fetchOutputs(outputApi) {
        $.ajax({
            url: outputApi,
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
                        $('#row-output').empty();
                        if (response.data.length === 0 || response.data.every(row => !row.deskripsi && !row.indikator && !row.target)) {
                            $('#row-output').append(`
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        `);
                        } else {
                            response.data.forEach(function(output) {
                                $('#row-output').append(`
                                <tr id="row-output-${output.id}" data-id="${output.id}" class="data-output">
                                    <td class="pl-3">${output.deskripsi ?? ''}</td>
                                    <td>${output.indikator ?? ''}</td>
                                    <td>${output.target ?? ''}</td>
                                    <td><div class="button-container">
                                            <button data-target="EditOutput" class="btn btn-sm modal-trigger float-right btn-success btnEditOutcomeOutput" data-action="edit" data-output-id="${output.id}" data-index="${output.id}" data-outcome-id="${output.programoutcome_id}">
                                            <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
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
                }, 100);
            },
            error: function(xhr, textStatus, errorThrown) {
                const errorMessage = getErrorMessage(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    html: errorMessage,
                    confirmButtonText: 'Okay'
                });
            }
        });
    }


    // load data outcome when
    $(document).ready(function() {
        $('#addOutputBtn').click(function() {
            $('#modalAddOutput').modal('show');
        });

        $('.nav').on('click', '.btn-list-outcome', function(e) {
            e.preventDefault(); // Prevent default button behavior
            var outcomeId = $(this).data('outcome-id');
            var outcomeIndex = $(this).data('index');
            var outcomeApi = "{{ route('api.program.outcome', ':id') }}".replace(':id', outcomeId);
            var outputApi = "{{ route('api.program.output', ':id') }}".replace(':id', outcomeId);

            // Fetch outcome data using the outcome ID
            $.ajax({
                url: outcomeApi,
                method: 'GET',
                beforeSend: function() {
                    $('#detail_outcome, #list_output, #outcomeData').addClass('hide');
                    $('#loading').removeClass('hide');
                },
                success: function(response) {
                    setTimeout(() => {
                        if (response.success) {
                            $('#detail_outcome, #list_output, #outcomeData').removeClass('hide');
                            $('#outcome-title').text(outcomeIndex);
                            $('#outcome-number').text(outcomeIndex);

                            // $('#deskripsi').val(response.data.deskripsi ?? '').trigger('input');
                            $('#deskripsi').html(response.data.deskripsi ?? '')
                            // $('#indikator').val(response.data.indikator ?? '').trigger('input');
                            $('#indikator').html(response.data.indikator ?? '');
                            // $('#target').val(response.data.target ?? '').trigger('input');
                            $('#target').html(response.data.target ?? '');
                            $('#goal').focus().trigger('input');
                            $('#objektif').trigger('input');
                            $('#programoutcome_id').val(response.data.id);

                            var $this = $(this); // Cache the current element
                            var $outputId = $this.data('output-id');
                            var $outputIndex = $this.data('index');
                            var $outputApi = "{{ route('api.program.output', ':id') }}".replace(':id', outcomeId); // Get the API URL for the current output

                            fetchOutputs(outputApi);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message,
                            });
                        }
                    }, 300);
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
        });
    // });

    // // add data activity on modal add output
    // $(document).ready(function() {
        $('#addActvityOutcome').click(function() {
            let activityIndex = $('#activity_output_list, tbody.data-activity').length + 1;
            $('#tbody-no-activity').addClass('hide').empty();

            let newActivityTbody = `
                <tbody id="has-activity-${activityIndex}" data-body-id="${activityIndex}" class="data-activity">
                    <tr data-activity-id="${activityIndex}">
                        <th width="10%">Deskripsi Kegiatan</th>
                        <td width="90%">
                        <textarea type="textarea" id="deskripsi_${activityIndex}" name="deskripsi[]" class="form-control" placeholder="Deskripsi Kegiatan" rows="1" maxlength="1000"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">Indikator Kegiatan</th>
                        <td width="90%">
                        <textarea type="textarea" id=indikator_"${activityIndex}" name="indikator[]" class="form-control" placeholder="Indikator Kegiatan" rows="1" maxlength="1000"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th width="10%">Target Kegiatan</th>
                        <td width="90%">
                        <textarea type="textarea" id=target_"${activityIndex}" name="target[]" class="form-control" placeholder="Target Kegiatan" rows="1" maxlength="1000"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td class="align-middle float-right">
                        <div style="text-align: center">
                            <button type="button" class="btn btn-sm btn-danger waves-effect waves-red remove-activity" title="Hapus">
                            <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        </td>
                </tr>
                </tbody>`;
            $('#activity_output_list').append(newActivityTbody);

            // Initialize Summernote for the new textareas

            // $(`#has-activity-${activityIndex} textarea`).each(function() {
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

        // Event delegation to handle removing activity rows
        $('#activity_output_list').on('click', '.remove-activity', function(e) {
            e.preventDefault();
            var activityId = $(this).closest('tbody').data('body-id');
            $(`#has-activity-${activityId}`).remove();

            // Check if there are no more activity rows and show the no-activity message
            if ($('#activity_output_list tbody.data-activity').length === 0) {
                $('#tbody-no-activity').removeClass('hide').html(`
                    <tr>
                    <td colspan="4" class="text-center" id="no-activity">
                        {{ __('cruds.activity.no_selected') }}
                    </td>
                    </tr>
                `);
            }
        });


        // Reset modal content when closed
        $('#modalAddOutput').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#tbody-no-activity').removeClass('hide').html(`
                <tr>
                <td colspan="4" class="text-center" id="no-activity">
                    {{ __('cruds.activity.no_selected') }}
                </td>
                </tr>
            `);
            $('#activity_output_list').find('tbody.data-activity').remove();
        });
    // });

    let url_simpan_output_activity = "{{ route('program.details.output.activity.store') }}";
    let output_outcome_id = "{{ $program->id }}";

    // $(document).ready(function() {
        $('#formAddOutput').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
            $('#formAddOutput').find('button[type="submit"]').attr('disabled'); //disable submit button to prevent multiple submission

            let outcome_id = $('#programoutcome_id').val();
            let outputApi = "{{ route('api.program.output', ':id') }}".replace(':id', outcome_id);

            // Collect the main form data
            let formData = {
                _token: $('input[name="_token"]').val(),
                _method: 'POST',
                programoutcome_id: $('#programoutcome_id').val(),
                deskripsi: $('#deskripsi_output').val(),
                indikator: $('#indikator_output').val(),
                target: $('#target_output').val(),
                activities: []
            };

            // Collect output activity data
            $('#activity_output_list tbody.data-activity').each(function() {
                let activity = {
                    deskripsi: $(this).find('textarea[name="deskripsi[]"]').val(),
                    indikator: $(this).find('textarea[name="indikator[]"]').val(),
                    target: $(this).find('textarea[name="target[]"]').val()
                };
                formData.activities.push(activity);
            });
            // Send the data via AJAX
            $.ajax({
                type: 'POST',
                url: url_simpan_output_activity, // Replace with your server endpoint
                data: JSON.stringify(formData),
                dataType: 'json',
                contentType: 'application/json',
                beforeSend: function() {
                    Toast.fire({
                        icon: 'info',
                        title: 'Loading...',
                        timer: 300
                    });
                },
                success: function(response) {
                    $('#formAddOutput')[0].reset();
                    $('#tbody-no-activity').removeClass('hide').html(`
                        <tr>
                        <td colspan="4" class="text-center" id="no-activity">
                            {{ __('cruds.activity.no_selected') }}
                        </td>
                        </tr>
                    `);
                    $('#activity_output_list').find('tbody.data-activity').remove();
                    $('#modalAddOutput').modal('hide');
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Output has been added!',
                            confirmButtonText: 'Okay'
                        });
                        fetchOutputs(outputApi);
                    }, 300);
                },
                error: function(xhr, textStatus, errorThrown) {
                    $('#formAddOutput').find('button[type="submit"]').removeAttr('disabled'); //enable submit button
                    const errorMessage = getErrorMessage(xhr);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        html: errorMessage,
                        confirmButtonText: 'Okay'
                    });
                },
            });
        });

    });
</script>
@stack('edit-output')
<script>
    function navigateToEditPage(url, tab) {
        window.location.href = `${url}?tab=${tab}`;
    }
</script>
@endpush
