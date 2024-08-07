<script>
    // call datatable for kecamatan
    $(document).ready(function() {
        $('.select2').select2();

        $('#provinsi_id').change(function(){
            var provinsi_id = $(this).val();
            console.log(provinsi_id);
            if(provinsi_id){
                $.ajax({
                    type: 'GET',
                    url:  '{{ route('kab.data', ':id') }}'.replace(':id', provinsi_id),
                    method: 'GET',
                    dataType: 'json',
                    success: function(hasil) {
                        $("#kabupaten_id").empty();
                        $('#kabupaten_id').html(`<option value="0">{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}</option>`);
                        if(hasil){
                            $.each(hasil, function(index, item) {
                                $("#kabupaten_id").append('<option value="' + item.id + '" data-id="'+item.kode+'">' + item.text + '</option>');
                            });
                        }
                        else{
                            $("#kabupaten_id").empty();
                        }
                    }
                });
            }            
            $('#kabupaten_id').select2({
                placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            });
        });
        
        $('#kabupaten_id').change(function(){
            var selected = $(this).find('option:selected');
            var kode_kab = selected.data('id');
            $('#kode').val(kode_kab+'.');
            
        });

        $('#kecamatan_list').DataTable({
            responsive: true,
            ajax: "{{ route('data.kecamatan') }}",
            processing: true,
            serverSide: true,
            // stateSave: true,
            columns: [
                {
                    data: "kode",
                    width: "5%",
                    className: "text-center"
                },
                {
                    data: "nama",
                    width: "5%",
                    className: "text-left"
                },
                {
                    data: 'kabupaten.nama',
                    width: "15%",
                    className: "text-left"
                },
                {
                    data: "aktif",
                    width: "5%",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data === 1) {
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" alt="☑️" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' +
                                row.id + '"></label></div>';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_" data-aktif-id="aktif_' + row.id +
                                '" class="icheck-primary" title="{{ __("cruds.status.tidak_aktif") }}" type="checkbox" ><label for="aktif_' +
                                row.id + '"></label></div>';
                        }
                    }
                },
                {
                    data: "action",
                    width: "8%",
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
                                columns: [0, 1, 2]
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [0, 1, 2]
                            }
                        },{
                            extend: 'pdf', 
                            exportOptions: {
                                columns: [0, 1, 2]
                            }    
                        },{
                            extend: 'copy',
                            exportOptions: {
                                columns: [0, 1, 2]
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
                [2, 'asc']
            ],
            lengthMenu: [5, 25, 50, 100, 500],
        });
    });
    
    // submit button add kecamatan
    $(document).ready(function(){
        $('#provinsi_add').select2({
            
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('#kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('.btn-add-kecamatan').on('click', function(e){
            e.preventDefault();
            // alert('submit');

        });
    });

    // $(document).ready(function() {
    //     $.ajax({
    //         url:  '{{ route('kecamatan.create') }}',
    //         method: 'GET',
    //         dataType: 'json',
    //         success: function(response){
    //             let data = response.map(function(item) {
    //                 return {
    //                     id: item.id,
    //                     text: item.id+' - '+ item.nama,
    //                 };
    //             });
                
    //             $('#provinsi_add').select2({
    //                 placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
    //                 allowClear: true,
    //                 delay: 250,
    //                 data : data,
    //             });
    //             $(document).on('select2:open', function() {
    //                 setTimeout(function() {
    //                     document.querySelector('.select2-search__field').focus();
    //                 }, 100);
    //             });
    //         },error: function(jqXHR, textStatus, errorThrown) {
    //             const errorData = JSON.parse(jqXHR.responseText);
    //             const errors = errorData.errors; // Access the error object
    //             let errorMessage = "";
    //             for (const field in errors) {
    //                 errors[field].forEach(error => {
    //                     errorMessage +=
    //                     `* ${error}\n`; // Build a formatted error message string
    //                 });
    //             }
    //             Swal.fire({
    //                 title: jqXHR.statusText,
    //                 text: errorMessage,
    //                 icon: 'error'
    //             });
    //         }
    //     });
    // });
</script>