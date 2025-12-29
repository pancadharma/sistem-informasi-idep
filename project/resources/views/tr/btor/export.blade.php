@extends('layouts.app')

@section('title', 'Export BTOR')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Export BTOR Reports</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('btor.export.excel') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Activity Type</label>
                            <select name="jeniskegiatan_id" class="form-control">
                                <option value="">All Types</option>
                                @foreach($jenisKegiatanList as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Program</label>
                            <select name="program_id" class="form-control">
                                <option value="">All Programs</option>
                                @foreach($programList as $program)
                                    <option value="{{ $program->id }}">{{ $program->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Export Format</label>
                            <select name="format" class="form-control">
                                <option value="xlsx">Excel (.xlsx)</option>
                                <option value="csv">CSV (.csv)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download"></i> Export to Excel
                    </button>
                    <a href="{{ route('btor.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
