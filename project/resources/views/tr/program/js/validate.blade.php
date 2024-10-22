{{-- <script src="https://old.formvalidation.io/bundles/40aab700698a291c5ce712a44ec8bc34.js"></script> --}}

@push('css')
    {{-- https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/css/formValidation.min.css --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/css/formValidation.min.css"
        rel="stylesheet" />
@endpush
<script src="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/0.6.2-dev/js/formValidation.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/formvalidation/1.9.0/js/plugins/Tachyons.min.js"></script>
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
                                message: 'The full name is required'
                            }
                        }
                    },
                    kode: {
                        validators: {
                            notEmpty: {
                                message: 'The company name is required'
                            }
                        }
                    },
                    tanggalmulai: {
                        validators: {
                            notEmpty: {
                                message: 'The address is required'
                            }
                        }
                    },
                    tanggalselesai: {
                        validators: {
                            notEmpty: {
                                message: 'The address is required'
                            }
                        }
                    },
                    city: {
                        validators: {
                            notEmpty: {
                                message: 'The city is required'
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
                                $li = $('a[href="#' + tabId + '"][data-toggle="tab"]').parent();

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
