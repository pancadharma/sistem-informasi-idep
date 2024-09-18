<script>
    $(document).ready(function(){
        $(document).on('click', '#show-aktif, [id^="aktif_"]', function(e) {
            e.preventDefault(); //not allow user to click the checkbox for aktif checbox
        });

        // LOAD DATA USERS with ROLES into DATATABLES
        $('#users_list').DataTable({
            ajax: {
                    "url": "{{ route('users.api') }}",
                    "type": "GET",
                    "dataType": "JSON",
            },
            responsive: true,lengthChange: false,
            processing: true,autoWidth: false,serverSide: true,deferRender: true, stateSave: true,
            rowCallback: function(row, data, index) {
                if (index % 2 === 0) {
                    $(row).addClass('even');
                } else {
                    $(row).addClass('odd');
                }
            },
            columns: [
                {data: "nama", width: "15%", className: "text-left"},
                {data: "username",width: "15%",className: "text-left"},
                {data: "email",width: "15%",className: "text-left"},
                {data: "roles", name: "roles.nama", width: "15%", className: "text-left", searchable: true, orderable: false,}, //added name:"roles.name" to get eager relation of users to roles
                {data: "status",width: "5%", className: "text-center", orderable: false, searchable: false,}, //change column aktif into status by adding ->addColumn in Controller that send DataTables
                {data: "action", width: "10%", orderable: false, searchable: false}
            ],
            layout: {
                topStart: {
                    buttons: [
                        {extend: 'print',exportOptions: {columns: [0, 1, 2, 3]}},// Ensure these indices match your visible columns
                        {extend: 'excel',exportOptions: {columns: [0, 1, 2, 3]}},
                        {extend: 'pdf',exportOptions: {columns: [0, 1, 2, 3]}},
                        {extend: 'copy',exportOptions: {columns: [0, 1, 2, 3]}},
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
    });
</script>