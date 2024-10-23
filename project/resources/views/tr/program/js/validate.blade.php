@push('css')
    <link href="{{ asset('/vendor/formvalidation/validation.css') }}" rel="stylesheet" />
@endpush
<script src="{{ asset('/vendor/formvalidation/validation.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#createProgram')
            .formValidation({
                framework: 'bootstrap',
                // Only disabled elements are excluded
                // The invisible elements belonging to inactive tabs must be validated
                excluded: [':disabled'],
                icon: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    nama: {
                        validators: {
                            notEmpty: {
                                message: 'Program Name is required'
                            }
                        }
                    },
                    kode: {
                        validators: {
                            notEmpty: {
                                message: 'Program Code is required'
                            }
                        }
                    },
                    tanggalmulai: {
                        validators: {
                            notEmpty: {
                                message: 'Start date is required'
                            }
                        }
                    },
                    tanggalselesai: {
                        validators: {
                            notEmpty: {
                                message: 'Finish date is required'
                            }
                        }
                    },
                    totalnilai: {
                        validators: {
                            notEmpty: {
                                message: 'Total nilai program is required'
                            }
                        }
                    },
                    donor: {
                        validators: {
                            notEmpty: {
                                message: 'Total nilai program is required'
                            }
                        }
                    }
                }
            })
            .on('err.field.fv', function(e, data) {
                // data.fv --> The FormValidation instance

                // Get the first invalid field
                var $invalidFields = data.fv.getInvalidFields().eq(0);

                // Get the tab that contains the first invalid field
                var $tabPane = $invalidFields.parents('.tab-pane'),
                    invalidTabId = $tabPane.attr('id');

                // If the tab is not active
                if (!$tabPane.hasClass('active')) {
                    // Then activate it
                    $tabPane.parents('.tab-content')
                        .find('.tab-pane')
                        .each(function(index, tab) {
                            var tabId = $(tab).attr('id'),
                                $li = $('a[href="#' + tabId + '"][data-toggle="pill"]').parent();

                            if (tabId === invalidTabId) {
                                // activate the tab pane
                                $(tab).addClass('active');
                                // and the associated <li> element
                                $li.addClass('active');
                            } else {
                                $(tab).removeClass('active');
                                $li.removeClass('active');
                            }
                        });

                    // Focus on the field
                    $invalidFields.focus();
                }
            });
    });
</script>
