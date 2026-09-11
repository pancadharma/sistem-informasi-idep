@extends('layouts.app')

@section('content_body')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="mb-0">⚙️ Pengaturan Approver Timesheet</h4>
    </div>

    <div class="alert alert-info">
        <strong>Petunjuk:</strong> klik tombol <strong>Edit</strong> pada tiap baris untuk mengubah approver user tersebut.
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="approvalAssignmentsTable"
                       class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 6%;">No</th>
                            <th style="width: 28%;">Staff</th>
                            <th style="width: 22%;">Jabatan</th>
                            <th style="width: 34%;">Approver yang boleh approve</th>
                            <th style="width: 10%;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->jabatan->nama ?? '-' }}</td>
                                <td>
                                    @php
                                        $selectedNames = [];
                                    @endphp

                                    @foreach(($assignedApprovers[$user->id] ?? []) as $assignedApproverId)
                                        @php
                                            $assignedUser = $users->firstWhere('id', $assignedApproverId);
                                            if ($assignedUser) {
                                                $selectedNames[] = $assignedUser->nama;
                                            }
                                        @endphp
                                    @endforeach

                                    @if(count($selectedNames) > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($selectedNames as $name)
                                                <span class="badge badge-light border">{{ $name }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted">Belum ada approver</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-sm btn-primary edit-assignment"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->nama }}"
                                            data-assigned-approvers='@json($assignedApprovers[$user->id] ?? [])'>
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editAssignmentModal" tabindex="-1" role="dialog" aria-labelledby="editAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('approval.assignments.save') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editAssignmentModalLabel">Edit Approver</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="assignmentUserId">

                    <div class="form-group">
                        <label for="assignmentUserName">Staff</label>
                        <input type="text" id="assignmentUserName" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="assignmentApprovers">Approver yang boleh approve</label>
                        <select id="assignmentApprovers"
                                name="approvers[]"
                                class="form-control select2-multiple"
                                multiple
                                data-placeholder="Pilih approver">
                            @foreach($users as $approver)
                                @if($approver->id !== auth()->id())
                                    <option value="{{ $approver->id }}">{{ $approver->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)

@push('js')
<script>
    $(function () {
        const table = $('#approvalAssignmentsTable').DataTable({
            responsive: true,
            paging: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
            order: [[1, 'asc']],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                zeroRecords: 'Tidak ada data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                paginate: {
                    previous: 'Sebelumnya',
                    next: 'Selanjutnya'
                }
            }
        });

        $('.select2-multiple').select2({
            width: '100%',
            placeholder: 'Pilih approver',
            allowClear: true
        });

        $('.edit-assignment').on('click', function () {
            const userId = $(this).data('userId');
            const userName = $(this).data('userName');
            const assignedApprovers = $(this).data('assignedApprovers') || [];

            $('#assignmentUserId').val(userId);
            $('#assignmentUserName').val(userName);
            $('#editAssignmentModalLabel').text('Edit Approver: ' + userName);
            $('#assignmentApprovers').val(assignedApprovers).trigger('change');
            $('#editAssignmentModal').modal('show');
        });

        const successMessage = @json(session('success'));
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: successMessage,
                timer: 2500,
                showConfirmButton: false
            });
        }
    });
</script>
@endpush
