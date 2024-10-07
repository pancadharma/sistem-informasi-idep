<script>
    (function ($) {
        var Defaults = $.fn.select2.amd.require('select2/defaults');

        $.extend(Defaults.defaults, {
            dropdownPosition: 'auto'
        });

        var AttachBody = $.fn.select2.amd.require('select2/dropdown/attachBody');

        var _positionDropdown = AttachBody.prototype._positionDropdown;

        AttachBody.prototype._positionDropdown = function () {

            var $window = $(window);

            var isCurrentlyAbove = this.$dropdown.hasClass('select2-dropdown--above');
            var isCurrentlyBelow = this.$dropdown.hasClass('select2-dropdown--below');

            var newDirection = null;

            var offset = this.$container.offset();

            offset.bottom = offset.top + this.$container.outerHeight(false);

            var container = {
                height: this.$container.outerHeight(false)
            };

            container.top = offset.top;
            container.bottom = offset.top + container.height;

            var dropdown = {
                height: this.$dropdown.outerHeight(false)
            };

            var viewport = {
                top: $window.scrollTop(),
                bottom: $window.scrollTop() + $window.height()
            };

            var enoughRoomAbove = viewport.top < (offset.top - dropdown.height);
            var enoughRoomBelow = viewport.bottom > (offset.bottom + dropdown.height);

            var css = {
                left: offset.left,
                top: container.bottom
            };

            // Determine what the parent element is to use for calciulating the offset
            var $offsetParent = this.$dropdownParent;

            // For statically positoned elements, we need to get the element
            // that is determining the offset
            if ($offsetParent.css('position') === 'static') {
                $offsetParent = $offsetParent.offsetParent();
            }

            var parentOffset = $offsetParent.offset();

            css.top -= parentOffset.top
            css.left -= parentOffset.left;

            var dropdownPositionOption = this.options.get('dropdownPosition');

            if (dropdownPositionOption === 'above' || dropdownPositionOption === 'below') {

                newDirection = dropdownPositionOption;

            } else {

                if (!isCurrentlyAbove && !isCurrentlyBelow) {
                    newDirection = 'below';
                }

                if (!enoughRoomBelow && enoughRoomAbove && !isCurrentlyAbove) {
                    newDirection = 'above';
                } else if (!enoughRoomAbove && enoughRoomBelow && isCurrentlyAbove) {
                    newDirection = 'below';
                }

            }

            if (newDirection == 'above' ||
                (isCurrentlyAbove && newDirection !== 'below')) {
                css.top = container.top - parentOffset.top - dropdown.height;
            }

            if (newDirection != null) {
                this.$dropdown
                    .removeClass('select2-dropdown--below select2-dropdown--above')
                    .addClass('select2-dropdown--' + newDirection);
                this.$container
                    .removeClass('select2-container--below select2-container--above')
                    .addClass('select2-container--' + newDirection);
            }

            this.$dropdownContainer.css(css);

        };

    })(window.jQuery);
</script>

<script>

// Data Table
$(document).ready(function() {
        $('#program-list').DataTable({
            responsive: true,
            // scrollX: true,
            ajax: "{{ route('data.program') }}",  // Update the route for kaitan_sdg data
            processing: true,
            serverSide: true,
            columns: [
                {data: 'DT_RowIndex', width: "5%", name: 'No.', className: "text-center"},
                {data: "kode", orderable: true, searchable: true},
                {data: "nama", orderable: true, searchable: true},
                {data: "tanggalmulai", orderable: true, searchable: true},
                {data: "tanggalselesai", orderable: true, searchable: true},
                {data: "totalnilai", orderable: true, searchable: true, visible: false},
                {data: "ekspektasipenerimamanfaat", orderable: true, searchable: true, visible: false},
                {data: "ekspektasipenerimamanfaatwoman", orderable: true, searchable: true, visible: false},
                {data: "ekspektasipenerimamanfaatman", orderable: true, searchable: true, visible: false},
                {data: "ekspektasipenerimamanfaatgirl", orderable: true, searchable: true, visible: false},
                {data: "ekspektasipenerimamanfaatboy", orderable: true, searchable: true, visible: false},
                {data: "ekspektasipenerimamanfaattidaklangsung", orderable: true, searchable: true, visible: false},
                {data: "deskripsiprojek", orderable: true, searchable: true, visible: false},
                {data: "analisamasalah", orderable: true, searchable: true, visible: false},
                {
                    data: "status",
                    orderable: true,
                    searchable: true, width: "10%",
                    className: "text-center",
                    render: function (data, type, row) {
                        if (data === 'draft') {
                            return '<span class="badge badge-secondary">Draft</span>';
                        } else if (data === 'pending') {
                            return '<span class="badge badge-warning">Pending</span>';
                        } else if (data === 'submit') {
                            return '<span class="badge badge-success">Submit</span>';
                        }
                        return data;  // Return data if none of the conditions are met
                    }
                },

                { data: "action", className: "text-center", orderable: false }
            ],
            layout: {
                topStart: {
                    buttons: [
                        {
                            text: '<i class="fas fa-print"></i> <span class="d-none d-md-inline">Print</span>',
                            className: 'btn btn-secondary',
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2,3,14] // Ensure these indices match your visible columns
                            }
                        },
                        {
                            text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline">Excel</span>',
                            className: 'btn btn-success',
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline">PDF</span>',
                            className: 'btn btn-danger',
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> <span class="d-none d-md-inline">Copy</span>',
                            className: 'btn btn-info',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline">Column visibility</span>',
                            className: 'btn btn-warning',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },
                    ],
                },
                bottomStart: {
                    pageLength: 10,
                }
            },
            order: [[0, 'asc']],
            lengthMenu: [10, 25, 50, 100, 500],
        });

        // $('#editprogramForm').on('submit', function(e) {
        //     e.preventDefault();
        //     let id = $('#id').val();
        //     let url = '{{ route('program.update', ':id') }}'.replace(':id', id);
        //     let formData = $(this).serialize();

        //     $.ajax({
        //         url: url,
        //         method: 'PUT',
        //         data: formData,
        //         success: function(response) {
        //             if (response.success) {
        //                 $('#editprogramModal').modal('hide');
        //                 $('#program-list').DataTable().ajax.reload();
        //             }
        //         },
        //         error: function(xhr) {
        //             console.error(xhr.responseText);
        //         }
        //     });
        // });
    });
</script>
