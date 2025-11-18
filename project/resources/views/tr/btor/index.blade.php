{{-- @extends('tr.btor.layouts.master')

@section('title', 'BTOR Report List') --}}
@extends('layouts.app')

@section('subtitle', __('cruds.kegiatan.list'))
@section('content_header_title')
    @can('kegiatan_access')
        <a class="btn-success btn" href="{{ route('kegiatan.create') }}" title="{{ __('cruds.kegiatan.add') }}">
            {{ __('global.create') .' '.__('cruds.kegiatan.label') }}
        </a>
    @endcan
@endsection
@section('sub_breadcumb', __('cruds.kegiatan.list'))

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2>BTOR - Back to Office Report</h2>
            <p class="text-muted">Manage and export activity reports</p>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('btor.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Activity Type</label>
                            <select name="jeniskegiatan_id" class="form-control">
                                <option value="">All Types</option>
                                @foreach($jenisKegiatanList as $jenis)
                                    <option value="{{ $jenis->id }}" {{ ($filters['jeniskegiatan_id'] ?? '') == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $filters['start_date'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $filters['end_date'] ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="draft" {{ ($filters['status'] ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="ongoing" {{ ($filters['status'] ?? '') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('btor.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Actions --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('btor.export.bulk') }}" id="bulkForm">
                @csrf
                <input type="hidden" name="ids" id="selectedIds">
                <button type="submit" class="btn btn-danger" id="bulkExportBtn" disabled>
                    <i class="fas fa-file-pdf"></i> Export Selected to PDF
                </button>
                <a href="{{ route('btor.export.config') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </a>
                <span id="selectedCount" class="ml-3 text-muted">0 items selected</span>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="40px">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th width="60px">ID</th>
                            <th>Activity Name</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th width="200px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kegiatanList as $kegiatan)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="select-item" value="{{ $kegiatan->id }}">
                                </td>
                                <td>{{ $kegiatan->id }}</td>
                                <td>
                                    <strong>{{ $kegiatan->programOutcomeOutputActivity?->nama ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $kegiatan->programOutcomeOutputActivity?->kode ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $kegiatan->jenisKegiatan?->nama ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($kegiatan->tanggalmulai)
                                        {{ \Carbon\Carbon::parse($kegiatan->tanggalmulai)->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $kegiatan->getDurationInDays() }} days</td>
                                <td>
                                    <span class="badge badge-{{ $kegiatan->status === 'completed' ? 'success' : ($kegiatan->status === 'ongoing' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($kegiatan->status ?? 'draft') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('btor.show', $kegiatan->id) }}" class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('btor.print', $kegiatan->id) }}" class="btn btn-sm btn-secondary" target="_blank" title="Print Preview">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                    <a href="{{ route('btor.export.pdf', $kegiatan->id) }}" class="btn btn-sm btn-danger" title="Download PDF">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle"></i> No reports available
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select All Checkbox
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.select-item');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    // Individual Checkboxes
    document.querySelectorAll('.select-item').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Update Bulk Actions
    function updateBulkActions() {
        const selected = Array.from(document.querySelectorAll('.select-item:checked')).map(cb => cb.value);
        const count = selected.length;

        document.getElementById('selectedCount').textContent = count + ' item(s) selected';
        document.getElementById('bulkExportBtn').disabled = count === 0;
        document.getElementById('selectedIds').value = selected.join(',');
    }

    // Bulk Form Submit
    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        const count = document.querySelectorAll('.select-item:checked').length;
        if (count === 0) {
            e.preventDefault();
            alert('Please select at least one report to export');
        }
    });
</script>
@endpush
@endsection
