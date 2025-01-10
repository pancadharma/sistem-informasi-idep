@extends('layouts.app') <!-- Pastikan layout utama sesuai -->

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Activity Logs</h3>
    </div>
    <div class="card-body">
        <table id="logsTable" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Description</th>
                    <th>User</th>
                    <th>Model</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                    <th>Time</th>
                    <th style="width: 10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $key => $log)
                <tr>
                    <td>{{ $key + 1 }}</td> <!-- Auto-increment No -->
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->causer ? $log->causer->nama : 'System' }}</td>
                    <td>{{ class_basename($log->subject_type) }}</td>
                    <td>
                        @if ($log->description === 'created')
                            @php
                                $newValues = collect($log->properties['attributes'] ?? [])->except(['created_at', 'updated_at'])->take(4);
                            @endphp
                            @forelse ($newValues as $key => $value)
                                <div>{{ ucfirst($key) }}: {{ $value }}</div>
                            @empty
                                <div class="text-muted">No new values</div>
                            @endforelse
                        @else
                            @php
                                $newValues = collect($log->properties['attributes'] ?? []);
                                $oldValues = collect($log->properties['old'] ?? []);
                                $changedValues = $newValues->except(['created_at', 'updated_at'])->filter(function ($value, $key) use ($oldValues) {
                                    return $oldValues->has($key) && $oldValues[$key] !== $value;
                                });
                            @endphp
                            @forelse ($changedValues as $key => $value)
                                <div>{{ ucfirst($key) }}: {{ $value }}</div>
                            @empty
                                <div class="text-muted">No changes</div>
                            @endforelse
                        @endif
                    </td>
                    <td>
                        @if ($log->description === 'created')
                            <div class="text-muted">Not applicable</div>
                        @else
                            @php
                                $newValues = collect($log->properties['attributes'] ?? []);
                                $oldValues = collect($log->properties['old'] ?? []);
                                $changedValues = $oldValues->except(['created_at', 'updated_at'])->filter(function ($value, $key) use ($newValues) {
                                    return $newValues->has($key) && $newValues[$key] !== $value;
                                });
                            @endphp
                            @forelse ($changedValues as $key => $value)
                                <div>{{ ucfirst($key) }}: {{ $value }}</div>
                            @empty
                                <div class="text-muted">No changes</div>
                            @endforelse
                        @endif
                    </td>
                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        <a href="{{ route('logs.show', $log->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<!-- Tambahkan CSS tambahan -->
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> --}}
@endpush

@push('scripts')
<!-- Tambahkan JS untuk DataTables -->
{{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script> --}}
<script>
    $(document).ready(function() {
        $('#logsTable').DataTable({
            responsive: true,
            autoWidth: false,
            order: [[0, 'asc']], // Default sorting by "No"
        });
    });
</script>
@endpush