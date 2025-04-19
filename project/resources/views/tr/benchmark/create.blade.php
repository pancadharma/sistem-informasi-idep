@extends("layouts.app")

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">{{ __('global.loading') }}...</h4>
@endsection

@section('subtitle', 'Tambah Benchmark')

@section('content_header_title', 'Tambah Benchmark')

@section('content_body')
<div class="card card-outline card-primary">
    <div class="card-body">
        <form id="benchmarkForm">
            @csrf
            <div class="row">
                {{-- Kiri --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Program</label>
                        <select id="program_id" name="program_id" class="form-control" required>
                            <option value="">-- Pilih Program --</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis_kegiatan_id">Jenis Kegiatan</label>
                        <select id="jenis_kegiatan_id" name="jenis_kegiatan_id" class="form-control" required>
                            <option value="">-- Pilih Jenis Kegiatan --</option>
                            @foreach ($kegiatan as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="outcome_activity_id">Kegiatan</label>
                        <select id="outcome_activity_id" name="outcome_activity_id" class="form-control" required>
                            <option value="">-- Pilih Kegiatan --</option>
                            @foreach ($outcomes as $outcome)
                                <option value="{{ $outcome->id }}">{{ $outcome->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Provinsi</label>
                        <input type="text" class="form-control" name="provinsi">
                    </div>
                    <div class="form-group">
                        <label>Kabupaten</label>
                        <input type="text" class="form-control" name="kabupaten">
                    </div>
                    <div class="form-group">
                        <label>Kecamatan</label>
                        <input type="text" class="form-control" name="kecamatan">
                    </div>
                    <div class="form-group">
                        <label for="dusun_id">Desa</label>
                        <select id="dusun_id" name="dusun_id" class="form-control" required>
                            <option value="">-- Pilih Desa --</option>
                            @foreach ($dusun as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal_implementasi">Tanggal Implementasi</label>
                        <input type="date" id="tanggal_implementasi" name="tanggal_implementasi" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="user_handler_id">Handler</label>
                        <select id="user_handler_id" name="user_handler_id" class="form-control" required>
                            <option value="">-- Pilih Handler --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_compiler_id">Compiler</label>
                        <select id="user_compiler_id" name="user_compiler_id" class="form-control" required>
                            <option value="">-- Pilih Compiler --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Score</label>
                        <input type="number" step="0.01" class="form-control" name="score">
                    </div>
                    <div class="form-group">
                        <label for="catatan_evaluasi">Catatan Evaluasi</label>
                        <textarea id="catatan_evaluasi" name="catatan_evaluasi" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Area Peningkatan</label>
                        <textarea class="form-control" name="area_peningkatan" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="text-right mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
@section('plugins.Sweetalert2', true)
@section('plugins.DatatablesNew', true)
@section('plugins.Select2', true)
@section('plugins.Toastr', true)
@section('plugins.Validation', true)
<script src="{{ asset('tr/benchmark/js/create.blade.js') }}"></script>
@endpush
