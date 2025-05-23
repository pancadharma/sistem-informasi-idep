{{-- <div class="modal fade" id="ModalDaftarProgram" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="TitleModalDaftarProgram" theme="info">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="TitleModalDaftarProgram">
                    <i class="bi bi-person-plus"></i>
                    {{ __('global.list') .' '. __('cruds.kegiatan.basic.program_nama') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Input Pencarian -->
                <div class="form-group mb-3">
                    <input type="text" id="searchProgram" class="form-control" placeholder="{{ __('global.search') }} {{ __('cruds.kegiatan.basic.program_nama') }}">
                </div>
                <!-- Tabel Program -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="list_program_kegiatan">
                        <thead>
                            <tr>
                                <th>{{ __('cruds.kegiatan.basic.program_kode') }}</th>
                                <th>{{ __('cruds.kegiatan.basic.program_nama') }}</th>
                                <th>{{ __('global.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($program as $program)
                            <tr data-program-id="{{ $program->id }}" data-program-kode="{{ $program->kode }}" data-program-nama="{{ $program->nama }}" class="align-middle select-program">
                                <td>{{ $program->kode }}</td>
                                <td>{{ $program->nama }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info" data-action="select" data-toggle="tooltip" data-placement="top" title="{{ __('global.select') }}">
                                        <i class="bi bi-plus-lg"></i>
                                        <span class="d-none d-sm-inline"></span>
                                        <span class="d-sm-none">{{ __('global.select') }}</span>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menyaring data tabel
    document.getElementById('searchProgram').addEventListener('input', function () {
        let keyword = this.value.toLowerCase();
        let rows = document.querySelectorAll('#list_program_kegiatan tbody tr');

        rows.forEach(row => {
            let programName = row.querySelector('td:nth-child(2)').innerText.toLowerCase(); // Kolom nama program
            if (programName.includes(keyword)) {
                row.style.display = ''; // Tampilkan baris jika cocok
            } else {
                row.style.display = 'none'; // Sembunyikan baris jika tidak cocok
            }
        });
    });
</script>

 --}}

<!-- Modal -->
<div class="modal fade" id="ModalDaftarProgram" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="TitleModalDaftarProgram" theme="danger">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="TitleModalDaftarProgram">
                    {{ __('global.list') .' '. __('cruds.program.title') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-sm table-bordered table-striped" id="list_program_kegiatan">
                            <thead>
                                <tr>
                                    <th>{{ __('cruds.kegiatan.basic.program_kode') }}</th>
                                    <th>{{ __('cruds.kegiatan.basic.program_nama') }}</th>
                                    <th>{{ __('cruds.beneficiary.activity_code') }}</th>
                                    <th>{{ __('global.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="list_program_kegiatan">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
            </div>
        </div>
    </div>
</div>

@push('basic_tab_js')

<script>
    $(document).ready(function() {
        let programTable;
        $('#kode_program').on('click', function(e) {
            e.preventDefault();
            if ($("#dataTable tbody tr").length > 0) {
                showConfirmationModal();
                return false;
            } else {
                setTimeout(() => {
                    if (!programTable) {
                        programTable = $('#list_program_kegiatan').DataTable({
                            processing: true,
                            serverSide: true,
                            width: '100%',
                            ajax: {
                                url: "{{ route('api.data.program.kegiatan') }}",
                                type: "GET"
                            },
                            columns: [
                                { data: 'kode', name: 'kode', className: "align-self text-left", width: "20%", },
                                { data: 'nama', name: 'nama', className: "align-self text-left", width: "50%" },
                                { data: 'activities', name: 'activities', className: "align-self text-left", width: "30%" },
                                { data: 'action', name: 'action', width: "10%", className: "align-self text-center", orderable: false, searchable: false }
                            ],
                            responsive: true,
                            language: {
                                processing: " Memuat..."
                            },
                            lengthMenu: [5, 10, 25, 50, 100],
                            bDestroy: true // Important to re-initialize datatable
                        });
                    }
                }, 500);
                $('#ModalDaftarProgram').removeAttr('inert');
                $('#ModalDaftarProgram').modal('show');
            }
        });

        $('#ModalDaftarProgram').on('hidden.bs.modal', function (e) {
            if (programTable) {
                programTable.destroy();
                programTable = null;
            }
            $(this).attr('inert', '');
        });


        $(document).on('click', '.select-program', function() {
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const url = "{{ route('api.program.kegiatan', ':id') }}".replace(':id', id);

            $('#program_id').val(id);
            $('#kode_program').val(kode);
            $('#nama_program').val(nama).prop('disabled', true);
            $('#ModalDaftarProgram').modal('hide');
        });

    });


</script>
@endpush
