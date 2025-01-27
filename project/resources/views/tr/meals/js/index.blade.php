<script>
    $(document).ready(function() {
        $('#mealsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('api.meals.datatable') }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nama', name: 'nama'},
                {data: 'description', name: 'description'},
                {data: 'price', name: 'price'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
