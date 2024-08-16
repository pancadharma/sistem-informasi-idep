<script>
    $(document).ready(function() {
        //load data desa into datatables
        $('#desa_list').DataTable({
            responsive: true,
            ajax: "{{ route('data.desa') }}",
            type: "GET",
            processing: true,
            serverSide: true,
            deferRender: true,
            stateSave: true, 
            columns: [
                {
                    data: "kode",
                    width: "5%",
                    className: "text-center",
                    orderable:false
                },
                {
                    data: "nama",
                    width: "5%",
                    className: "text-left"
                },
                { 
                    data: "nama",
                    width: "15%", 
                    className: "text-left",
                    "render": function(data, type, row) {
                        return row.kecamatan.nama;
                    }
                },
                {
                    data: "aktif",
                    width: "5%",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (data === 1) {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' +row.id+ '" data-aktif-id="' + row.id +
                                '" class="icheck-primary" alt="☑️" title="{{ __("cruds.status.aktif") }}" type="checkbox" checked><label for="aktif_' +row.id+ '"></label></div>';
                        } else {
                            return '<div class="icheck-primary d-inline"><input id="aktif_' +row.id+ '" data-aktif-id="' + row.id +
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

        //trigger provinsi when it changes
        $('#provinsi_id').change(function(){
            var provinsi_id = $(this).val();
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
        //autofill desa_kode with kabupatan_kode
        $('#kabupaten_id, #provinsi_id').change(function(){
            var selected = $(this).find('option:selected');
            var kode_kab = selected.data('id');
            if(kode_kab !== undefined ){
                $('#kode').val(kode_kab+'.');
                $('#kode').prop("title", "add two more of kode kecamatan");
            }else{
                $('#kode').val('');
                $('#kode').prop("placeholder", "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}");
            }
        });

        $('#provinsi_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('#edit_provinsi_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.provinsi.title')}}",
            allowClear: true,
            delay: 250,
            dropdownParent: $('#editKecamatanModal')
        });
        $('#kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            allowClear: true,
            delay: 250,
        });
        $('#edit_kabupaten_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kabupaten.title')}}",
            allowClear: true,
            delay: 250,
            dropdownParent: $('#editKecamatanModal')
        });
        $('#kecamatan_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') }} {{ trans('cruds.kecamatan.title')}}",
            allowClear: true,
            delay: 250,
            dropdownParent: $('#editKecamatanModal')
        });
        $('#edit_kecamatan_id').select2({
            placeholder: "{{ trans('global.pleaseSelect') .' '. trans('cruds.kecamatan.title')}}",
            allowClear: true,
            delay: 250,
            dropdownParent: $('#editKecamatanModal')
        });
    });















</script>