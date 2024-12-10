<!-- Modal -->
<div class="modal fade" id="ModalDaftarProgram" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="TitleModalDaftarProgram" theme="info">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="TitleModalDaftarProgram">
                    <i class="bi bi-person-plus"></i>
                    {{ __('global.list') .' '. __('cruds.kegiatan.basic.program_nama') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('global.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="list_program_kegiatan">
                        <thead>
                            <tr>
                                <th>{{ __('cruds.kegiatan.basic.program_kode') }}</th>
                                <th>{{ __('cruds.kegiatan.basic.program_nama') }}</th>
                                <th>{{ __('') }}</th>
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
                {{-- <button type="button" class="btn btn-primary" id="saveModalData">{{ __('global.save') }}</button> --}}
            </div>
        </div>
    </div>
</div>

