@extends('layouts.app')

{{-- Customize layout sections --}}

@section('subtitle', 'Outcome Test') {{-- Ganti Site Title Pada Tab Browser --}}
@section('content_header_title', 'Outcome') {{-- Ditampilkan pada halaman sesuai Menu yang dipilih --}}
@section('sub_breadcumb', '') {{-- Menjadi Bradcumb Setelah Menu di Atas --}}

{{-- Content body: main page content --}}

@section('content_body')
    <form id="programForm">
        @csrf
        @method('POST')
        <!-- Program Fields -->
        <input type="hidden" name="program_id" value="{{ $program_id }}">
        <!-- More program fields here -->

        <!-- Outcome Fields -->
        <div class="col-md-12" id="outcomeContainer">
            <div class="row">
                <div class="col-lg-4 form-group">
                    <div class="input-group">
                        <label for="program_name" class="input-group small mb-0">Program Name</label>
                        <input type="text" name="program_name" placeholder="Program Name" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 form-group">
                    <div class="input-group">
                        <label for="deskripsi" class="input-group small mb-0">{{ __('cruds.program.outcome.desc') }}</label>
                        <textarea type="textarea" id="deskripsi" name="deskripsi[]"
                            class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                            placeholder=" {{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000"></textarea>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="input-group">
                        <label for="indikator"
                            class="input-group small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
                        <textarea id="indikator" name="indikator[]" cols="30"
                            class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}"
                            placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1"></textarea>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="input-group">
                        <label for="target"
                            class="input-group small mb-0">{{ __('cruds.program.outcome.target') }}</label>
                        <textarea id="target" name="target[]" cols="30"
                            class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}"
                            placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1"></textarea>
                        <span class="input-group-append">
                            <button type="button" class="ml-2 btn btn-success form-control addOutcome btn-flat"
                                data-target="outcome-container_1"><i class="bi bi-plus"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-danger form-control">Submit</button>
    </form>

    <div class="row hehe d-none" id="outcomeTemplate">
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="deskripsi" class="input-group small mb-0">{{ __('cruds.program.outcome.desc') }}</label>
                <textarea type="textarea" name="deskripsi[]" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000"></textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="indikator" class="input-group small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
                <textarea name="indikator[]" class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1"></textarea>
            </div>
        </div>
        <div class="col-lg-4 form-group">
            <div class="input-group">
                <label for="target" class="input-group small mb-0">{{ __('cruds.program.outcome.target') }}</label>
                <textarea name="target[]" class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}"
                    placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1"></textarea>
                <span class="input-group-append">
                    <button type="button" class="ml-2 btn btn-danger form-control removeButton btn-flat">
                        <i class="bi bi-trash"></i>
                    </button>
                </span>
            </div>
        </div>
    </div>

    {{-- <div class="col-md-12 d-none" id="outcomeTemplate">
        <div class="row">
            <div class="col-lg-4 form-group">
                <div class="input-group">
                    <label for="deskripsi" class="input-group small mb-0">{{ __('cruds.program.outcome.desc') }}</label>
                    <textarea type="textarea" name="deskripsi[]" class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}"
                        placeholder="{{ __('cruds.program.outcome.desc') }}" rows="1" maxlength="1000"></textarea>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="input-group">
                    <label for="indikator"
                        class="input-group small mb-0">{{ __('cruds.program.outcome.indicator') }}</label>
                    <textarea name="indikator[]" class="form-control {{ $errors->has('indikator') ? 'is-invalid' : '' }}"
                        placeholder="{{ __('cruds.program.outcome.indicator') }}" maxlength="1000" rows="1"></textarea>
                </div>
            </div>
            <div class="col-lg-4 form-group">
                <div class="input-group">
                    <label for="target" class="input-group small mb-0">{{ __('cruds.program.outcome.target') }}</label>
                    <textarea name="target[]" class="form-control {{ $errors->has('target') ? 'is-invalid' : '' }}"
                        placeholder="{{ __('cruds.program.outcome.target') }}" maxlength="1000" rows="1"></textarea>
                    <span class="input-group-append">
                        <button type="button" class="ml-2 btn btn-danger form-control removeButton btn-flat">
                            <i class="bi bi-trash"></i>
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div> --}}


@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
    <script>
        $(document).ready(function() {
            let outcomeIndex = 0;

            // Add Outcome button click handler
            $("#outcomeContainer").on("click", ".addOutcome", function() {
                outcomeIndex++;
                const $clone = $("#outcomeTemplate").clone().removeClass("d-none").removeAttr("id").attr(
                    "data-outcome-index", outcomeIndex);
                $("#outcomeContainer").append($clone);
                $clone.find("textarea").each(function() {
                    const $input = $(this);
                    const name = $input.attr("name").replace("[]", "[" + outcomeIndex + "]");
                    $input.attr("name", name);
                });
                $clone.find("textarea").val("");
            });

            // Remove button click handler
            $(document).on("click", ".removeButton", function() {
                $(this).closest(".row").remove();
            });

            // Handle form submission
            $('#programForm').on('submit', function(e) {
                e.preventDefault();
                const formData = $(this).serializeArray();

                console.log(formData);
                $.ajax({
                    url: "{{ route('trprogram.outcome.submit') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        Toast.fire({
                            title: "{{ __('global.success') }}",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            timerProgressBar: true,
                        });
                    },
                    error: function(xhr, jqXHR, textStatus, errorThrown) {
                        $('#updatePasswordBtn').removeAttr('disabled');
                        var errorMessage = 'An unexpected error occurred.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON
                                .message; // Get the message from the response
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    },
                });
            });
        });

        function formatErrorMessages(errors) {
            let message = '<ul>';
            for (const field in errors) {
                errors[field].forEach(function(error) {
                    message += `<li>${error}</li>`;
                });
            }
            message += '</ul>';
            return message;
        }
    </script>
@endpush
