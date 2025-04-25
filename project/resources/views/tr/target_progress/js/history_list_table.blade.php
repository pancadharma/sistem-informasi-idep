<script>
    $doc.ready(function() {
        // Initialize Functions & Variables
        let $modal			    = $('#ModalHistoryTargetProgress'),
            $history_table      = $modal.find("#history_table"),
            historiesDataTable   = undefined;

        $modal.on('shown.bs.modal', function (e) {
            historiesDataTable = $history_table.DataTable({
                processing: true,
                serverSide: true,
                width: '100%',
                ajax: {
                    url: "{{ route('api.target_progress.histories', ':id') }}".replace(':id', $('#program_id').val() || 0),
                    type: "GET"
                },
                columns: [
                    { data: 'kode', name: 'kode', className: "align-self text-left" },
                    { data: 'nama', name: 'nama', className: "align-self text-left" },
                    { data: 'target', name: 'target', className: "align-self text-left" },
                    { data: 'created_at', name: 'created_at', className: "align-self text-left" },
                    { data: 'updated_at', name: 'updated_at', className: "align-self text-left" },
                    { data: 'action', name: 'action', width: "10%", className: "align-self text-center", orderable: false, searchable: false }
                ],
                responsive: true,
                language: {
                    processing: " Memuat..."
                },
                lengthMenu: [5, 10, 25, 50, 100],
                bDestroy: true // Important to re-initialize datatable
            });
            $modal.removeAttr('inert');
        });
        
        $modal.on('hidden.bs.modal', function (e) {
            if (historiesDataTable) {
                historiesDataTable.destroy();
                historiesDataTable = undefined;
                $(this).find("#history_table tbody tr").remove();
            }
            $(this).attr('inert', '');
        });
    });
</script>
