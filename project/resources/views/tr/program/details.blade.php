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
                        <button type="button" class="nav-link btn text-left btn-block btn-list-outcome" data-index="{{ $index + 1 }}" data-outcome-id="{{ $outcome->id }}" data-action="load">
                            {{ __('cruds.program.outcome.out_program') }} {{ $index + 1 }}
                            <!-- <i class="far fa-envelope float-right align-middle mt-2"></i> -->
                            <i class="bi bi-box-arrow-in-right text-danger float-right align-middle mt-2" title="{{ __('global.details') }} {{ __('cruds.program.outcome.out_program') }} {{ $index + 1 }}"></i>
                        </button>
                    </li>
                    @empty
                    <div class="nav flex-column nav-tabs h-100">
                        <button type="button" class="btn btn-block"></i>No Outcome</button>
                    </div>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">{{ __('cruds.program.title_singular') }}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                            class="fas fa-minus"></i></button>
                </div>
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
            {{-- <div class="card-body pd-0"> --}}
                {{-- <div class="callout callout-danger">
                    <h5>{{ __('cruds.program.objective.label') }}</h5>
                </div>
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
                </div> --}}

                {{-- ALTERNATIVE --}}

                {{--
                <div class="row">
                    <div class="input-field col s12">
                        <input id="goal" type="text" class="validate" readonly autofocus value="{{ old('goal', $program->objektif->target ?? '') }}">
                        <label for="goal">{{ __('cruds.program.goals.label') }}</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="objektif" type="text" class="validate" readonly value="{{ old('objective', $program->objektif->deskripsi ?? '') }}">
                        <label for="objektif">{{ __('cruds.program.objective.label') }}</label>
                    </div>
                </div>
                --}}
            {{-- </div> --}}
        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-primary card-outline hide" id="outcomeData">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title pt-2">{{ __('global.details') . ' ' . __('cruds.program.outcome.out_program') }} <span id="outcome-title"></span></h3>
                    </div>
                </div>
            </div>
            {{-- showing outcome details after clicking --}}
            <div class="card-body hide" id="detail_outcome">
                <div class="row">
                    <div class="input-field col">
                        <input id="deskripsi" name="deskripsi" type="text" class="validate" readonly placeholder="{{ __('cruds.program.outcome.desc') }}" data-toggle="tooltip" data-placement="top" data-position="top" data-tooltip="{{ __('cruds.program.outcome.desc') }}">
                        <label for="deskripsi">{{ __('cruds.program.outcome.desc') }}</label>
                    </div>
                    <div class="input-field col">
                        <input id="indikator" name="indikator" type="text" class="validate" readonly placeholder="{{ __('cruds.program.outcome.indicator') }}" data-toggle="tooltip" data-placement="top" data-position="top" data-tooltip="{{ __('cruds.program.outcome.indicator') }}">
                        <label for="indikator">{{ __('cruds.program.outcome.indicator') }}</label>
                    </div>
                    <div class="input-field col">
                        <input id="target" name="target" type="text" class="validate" readonly placeholder="{{ __('cruds.program.outcome.target') }}" data-toggle="tooltip" data-placement="top" data-position="top" data-tooltip="{{ __('cruds.program.outcome.target') }}">
                        <label for="target">{{ __('cruds.program.outcome.target') }}</label>
                    </div>
                </div>
            </div>
        </div>
        {{-- hidden detail output --}}
        <div class="card card-outline card-primary hide" id="list_output">
            <div class="card-header pr-0 pl-1">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title pt-2">{{ __('cruds.program.output.list') }} {{ __('cruds.program.outcome.of_outcome') }} <span id="outcome-number"></span></h3>
                    </div>
                    <div class="col">
                        <button type="button" data-target="modalAddOutput" class="btn btn-sm modal-trigger float-right btn-success" data-toggle="tooltip" data-position="top" data-tooltip=" {{ __('global.add'). ' ' . __('cruds.program.output.label') }}"><i class="bi bi-plus"></i>
                            {{ __('global.add'). ' ' . __('cruds.program.output.label') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body pr-1 pl-1 pt-0">
                <table id="outcome_output_list" class="highlight striped" style="width:100%">
                    <thead class="">
                        <tr>
                            <th width="30%">{{ __('Output Description') }}</th>
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

{{-- UNUSED CODE HERE --}}
{{-- <div class="row">
    <div class="col-lg-3">
        <div class="card card-primary card-outline">
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
            <button type="button" class="nav-link btn btn-block text-left btn-list-outcome" data-index="{{ $index + 1 }}" data-outcome-id="{{ $outcome->id }}" data-action="load">
                {{ __('cruds.program.outcome.out_program') }} {{ $index + 1 }}
            </button>
        </li>
        @empty
        <div class="nav flex-column nav-tabs h-100">
            <button type="button" class="btn btn-block"></i>No Outcome</button>
        </div>
        @endforelse
    </ul>
</div>
</div>
</div>
<div class="col-9">
    <div class="card card-outline card-primary hide" id="list_output">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    <h3 class="card-title pt-2">{{ __('cruds.program.output.list') }} {{ __('cruds.program.outcome.of_outcome') }} <span id="outcome-number"></span></h3>
                </div>
                <div class="col">
                    <button type="button" data-target="modalAddOutput" class="btn modal-trigger float-right btn-success" data-toggle="tooltip" data-position="top" data-tooltip=" {{ __('global.add'). ' ' . __('cruds.program.output.label') }}"><i class="bi bi-plus-lg"></i>
                        {{ __('global.add'). ' ' . __('cruds.program.output.label') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="outcome_output_list" class="highlight striped" style="width:100%">
                <thead class="">
                    <tr>
                        <th width="30%">{{ __('Output Description') }}</th>
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

</div> --}}



@stop

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/materialize.css') }}">

@endpush

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)

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
            message = formatErrorMessages(response.errors) || 'An unexpected error occurred. Please try again later.';
        } catch (e) {
            message = 'An unexpected error occurred. Please try again later.';
        }
        return message;
    }

    $(document).ready(function() {

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

                            $('#deskripsi').val(response.data.deskripsi ?? '').trigger('input');
                            $('#indikator').val(response.data.indikator ?? '').trigger('input');
                            $('#target').val(response.data.target ?? '').trigger('input');
                            $('#goal').focus().trigger('input');
                            $('#objektif').trigger('input');

                            var $this = $(this); // Cache the current element
                            var $outputId = $this.data('output-id');
                            var $outputIndex = $this.data('index');
                            var $outputApi = "{{ route('api.program.output', ':id') }}".replace(':id', outcomeId); // Get the API URL for the current output

                            $.ajax({
                                url: $outputApi,
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
                                            // if (response.data.length === 0) {
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
                                                            <td>${output.deskripsi ?? ''}</td>
                                                            <td>${output.indikator ?? ''}</td>
                                                            <td>${output.target ?? ''}</td>
                                                            <td><div class="button-container">
                                                                    <button data-target="EditOutput" class="btn btn-sm modal-trigger float-right btn-success" data-output-id="${output.id}" data-index="${$outputIndex}">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    `);
                                                });
                                            }
                                            $('#output-title').text($outputIndex);
                                            $('#output-action').text('Add Output');
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
                                },
                            });
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
                },
            });
        });
    });
</script>

@endpush
