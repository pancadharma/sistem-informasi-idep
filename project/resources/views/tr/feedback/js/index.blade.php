{{-- resources/views/tr/feedback/js/index.blade.php --}}
<script>
    $(document).ready(function () {
        const table = $('#feedbackTable').DataTable({
            serverSide: true, // Wajib!
            processing: true, // Tampilkan indikator loading
            ajax: {
                 url: '{{ route("api.feedback.datatable") }}', // Route API yang dibuat
                 data: function (d) {
                     // (Opsional) Tambahkan parameter filter program jika tombol filter masih dipakai
                     // d.program = $('#filterForm input[name="program"]').val();
                 }
            },
            columns: [
                // Sesuaikan 'data' & 'name' dengan response JSON dari API Controller Anda
                { data: 'DT_RowIndex', name: 'DT_RowIndex', title: '{{ __("No") }}', orderable: false, searchable: false, className: 'text-center', width: '5%' },
                { data: 'program', name: 'program', title: '{{ __("Program") }}' },
                { data: 'tanggal_registrasi', name: 'tanggal_registrasi', title: '{{ __("Tgl Registrasi") }}' },
                { data: 'sort_of_complaint', name: 'sort_of_complaint', title: '{{ __("Jenis Keluhan") }}' },
                { data: 'status_badge', name: 'status_complaint', title: '{{ __("Status") }}', orderable: false, searchable: false }, // Kolom berisi HTML badge
                { data: 'action', name: 'action', title: '{{ __("Aksi") }}', orderable: false, searchable: false, className: 'text-center' } // Kolom berisi HTML tombol
            ],
            // KONFIGURASI LAYOUT & BUTTONS (Hanya Ikon + Tooltip)
            layout: {
                topStart: {
                    buttons: [{
                            text: '<i class="fas fa-print"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-secondary',
                            extend: 'print',
                            exportOptions: {
                                columns: [0, 1, 2, 3] // Ensure these indices match your visible columns
                            }
                        },
                        {
                            text: '<i class="fas fa-file-excel"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-success',
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            text: '<i class="fas fa-file-pdf"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-danger',
                            extend: 'pdf',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'copy',
                            text: '<i class="fas fa-copy"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-info',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="fas fa-eye"></i> <span class="d-none d-md-inline"></span>',
                            className: 'btn btn-warning',
                            exportOptions: {
                                columns: [0, 1, 2, 3]
                            }
                        },
                    ],
                },
                 bottomStart: {
                    pageLength: 10,
                }
            },
            order: [1, 'asc'],
            lengthMenu: [10,25,50,100],

            drawCallback: function( settings ) {
                 $('#feedbackTable .delete-btn').off('click').on('click', function(e){
                     e.preventDefault();
                     let button = $(this);
                     let deleteUrl = button.data('route');

                     Swal.fire({
                         title: '{{ __("Apakah Anda yakin?") }}',
                         text: '{{ __("Data yang dihapus tidak dapat dikembalikan!") }}',
                         icon: 'warning',
                         showCancelButton: true,
                         confirmButtonColor: '#3085d6',
                         cancelButtonColor: '#d33',
                         confirmButtonText: '{{ __("Ya, hapus!") }}',
                         cancelButtonText: '{{ __("Batal") }}'
                     }).then((result) => {
                         if (result.isConfirmed) {
                             $.ajax({
                                 url: deleteUrl,
                                 type: 'POST',
                                 data: {
                                     _token: '{{ csrf_token() }}',
                                     _method: 'DELETE'
                                 },
                                 success: function(response) {
                                     table.ajax.reload(null, false);
                                     Swal.fire('Dihapus!','Data feedback berhasil dihapus.','success');
                                 },
                                 error: function(xhr) {
                                     Swal.fire('Error!','Gagal menghapus data.','error');
                                     console.error('Delete error:', xhr.responseText);
                                 }
                             });
                         }
                     });
                 });
             } // End drawCallback
        }); // End DataTable init

        // (Opsional) Jika Anda mempertahankan tombol/form filter program
        /*
        $('#filterForm').on('submit', function(e){
             e.preventDefault();
             table.draw();
        });
        $('#resetFilterButton').on('click', function(e){
             e.preventDefault();
             $('#filterForm input[name="program"]').val('');
             table.draw();
        });
        */

    }); // End document ready
</script>