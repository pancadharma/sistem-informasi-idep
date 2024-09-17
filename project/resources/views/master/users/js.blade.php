<script>
$(document).ready(function(){
    $('#users_list').DataTable({
        responsive: true,
        ajax: "{{ route('users.api') }}",
        // ajax: "{{ route('api.users') }}",
        type: "GET",
        processing: true,
        serverSide: true,
        deferRender: true,
        stateSave: true,
        columns: [
            {
                data: "nama",
                width: "15%",
                className: "text-left"
            },
            {
                data: "username",
                width: "15%",
                className: "text-left"
            },
            {
                data: "email",
                width: "15%",
                className: "text-left"
            },
            {
                data: "roles",
                // render: function(data, type, row) {
                //     // return data.map(role => role.nama).join(", ");
                //     return data.map(role => `<span class="btn btn-warning btn-xs">${role.nama}</span>`).join(" - ");
                // },
                width: "15%",
                className: "text-left",
                searchable: true,
                orderable: true,
            },
            {
                data: "aktif",
                width: "5%",
                className: "text-center",
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (data === 1) {
                        return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="' + row.id +
                            '" class="icheck-primary" alt="☑️ aktif" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' + row.id + '"></label></div>';
                    } else {
                        return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="' + row.id +
                            '" class="icheck-primary" alt="not-aktif" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox"><label for="aktif_' +
                            row.id + '"></label></div>';
                    }
                }
            },
            {
                data: "action",
                width: "10%",
                className: "text-center",
                orderable: false,
            }
        ],
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3] // Ensure these indices match your visible columns
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0, 1, 2, 3]
                        }
                    },
                    'colvis',
                ],
            },
            bottomStart: {
                pageLength: 5,
            }
        },
        order: [
            [2, 'asc'] // Ensure this matches the index of the `users` column
        ],
        lengthMenu: [5, 10 ,25, 50, 100, 200],
    });
})










</script>