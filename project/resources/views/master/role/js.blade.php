<script>
$(document).ready(function(){
    $('#role_list').DataTable({
        responsive: true,
        ajax: {
            url : "{{ route('roles.api') }}",
            type: "GET",
            dataType: 'JSON',
        },
        processing: true,serverSide: true,deferRender: true,stateSave: true,
        columns: [
            {data: "nama",orderable: false},
            // {data: "permissions", name: "permissions.nama", width: "15%", className: "text-left", searchable: true, orderable: false,},
            {data: "status",width: "5%", className: "text-center", orderable: false, searchable: false, width: "5%"},
            {data: "action",className: "text-center",orderable: false,width: "15%",}
        ],
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1] // Ensure these indices match your visible columns
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1]
                        }
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0, 1]
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
            [1, 'asc'] // Ensure this matches the index of the `dusun` column
        ],
        lengthMenu: [5, 10 ,25, 50, 100, 200],
    });

    $('#role_list tbody').on('click', '.edit-role-btn, .view-role-btn', function (e) {
        e.preventDefault();
        let action    = $(this).data('action');
        let id_users  = $(this).data('user-id');
        let url_show  = '{{ route('roles.show', ':id') }}'.replace(':id',id_users);
        let url_edit  = '{{ route('roles.edit', ':id') }}'.replace(':id',id_users);

        if(action == 'edit') {
            // alert('edit roles data');




        } else if(action == 'view') {
            // alert('show roles data');
            $.ajax({
                url: url_show,
                method: 'GET',
                dataType: 'json',
                beforeSend: function(){
                    Toast.fire({icon: "info",title: "Processing...",timer: 300,timerProgressBar: true,});
                },
                success: function(data) {
                    $('#view_nama').text(data.nama);
                    $('#view_username').text(data.username);
                    $('#view_email').text(data.email);
                    // $('#view_jabatan').text(data.jabatan.nama); //use to display jabatan if jabatan modul finish
                    $('#view_roles').empty();
                    data.roles.forEach(role => {
                        $('#view_roles').append(`<span class="btn-xs bg-warning">${role.nama}</span> `);
                    });
                    if (data.aktif === 1) {
                        $('#aktif_show').val(data.aktif);
                        $("#aktif_show").prop("checked",true);
                    } else {
                        $('#aktif_show').val(0);
                        $("#aktif_show").prop("checked",false);
                    }
                    $('#status').text(data.aktif === 1? "{{ __('cruds.status.aktif') }}" : "{{ __('cruds.status.tidak_aktif') }}");
                    $('#showUsersModal .modal-title').text(data.nama);
                    $('#showUsersModal').modal('show');
                }
            });


        }
    });






});
</script>