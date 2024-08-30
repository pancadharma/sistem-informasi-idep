<script>

$(document).ready(function() {
    //Dusun DataTables
    $('#dusun_list').DataTable({
        responsive: true,
        ajax: "{{ route('data.dusun') }}",
        type: "GET",
        processing: true,
        serverSide: true,
        deferRender: true,
        stateSave: true,
        columns: [
            {
                data: "kode",
                // name: 'kelurahan.kode', //needed when using alternative query
                width: "5%",
                className: "text-center",
                orderable: false
            },
            {
                data: "nama",
                // name: 'kelurahan.nama',
                width: "15%",
                className: "text-left"
            },
            {
                data: "desa.nama", // Update to match the server-side column name
                // name: 'dusun.nama',
                width: "15%",
                className: "text-left"
            },
            {
                data: "kode_pos", // 
                // name: 'dusun.nama',
                width: "15%",
                className: "text-left"
            },
            {
                data: "aktif",
                // name: 'kelurahan.aktif',
                width: "5%",
                className: "text-center",
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (data === 1) {
                        return '<div class="icheck-primary d-inline"><input id="aktif_' + row.id + '" data-aktif-id="' + row.id +
                            '" class="icheck-primary" alt="☑️aktif" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' + row.id + '"></label></div>';
                    } else {
                        return '<div class="icheck-primary d-inline"><input id="aktif_' + row.id + '" data-aktif-id="' + row.id +
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
            [2, 'asc'] // Ensure this matches the index of the `dusun` column
        ],
        lengthMenu: [5, 25, 50, 100, 500],
    });




});


































</script>