<x-adminlte-modal id="createProgramModal" title="{{ __('global.create') }} {{ __('cruds.program.title') }}" size="xl" theme="primary" icon="fas fa-plus">
    <form id="createProgramForm" method="POST" action="{{ route('program.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="kode">{{ __('cruds.program.kode') }}</label>
                    <input type="text" class="form-control" id="kode" name="kode" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama">{{ __('cruds.program.nama') }}</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggalmulai">{{ __('cruds.program.tanggalmulai') }}</label>
                    <input type="date" class="form-control" id="tanggalmulai" name="tanggalmulai" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggalselesai">{{ __('cruds.program.tanggalselesai') }}</label>
                    <input type="date" class="form-control" id="tanggalselesai" name="tanggalselesai" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="deskripsiprojek">{{ __('cruds.program.deskripsiprojek') }}</label>
                    <textarea class="form-control" id="deskripsiprojek" name="deskripsiprojek" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="analisamasalah">{{ __('cruds.program.analisamasalah') }}</label>
                    <textarea class="form-control" id="analisamasalah" name="analisamasalah" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ekspektasipenerimamanfaat">{{ __('cruds.program.ekspektasipenerimamanfaat') }}</label>
                    <input type="number" class="form-control" id="ekspektasipenerimamanfaat" name="ekspektasipenerimamanfaat">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="totalnilai">{{ __('cruds.program.totalnilai') }}</label>
                    <input type="number" class="form-control" id="totalnilai" name="totalnilai">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="status">{{ __('cruds.status.title') }}</label>
                    <select class="form-control" id="status" name="status">
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="running">Running</option>
                        <option value="submit">Submit</option>
                    </select>
                </div>
            </div>
        </div>
    </form>
    
    <x-slot name="footerSlot">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('global.close') }}</button>
        <button type="submit" form="createProgramForm" class="btn btn-primary">{{ __('global.save') }}</button>
    </x-slot>
</x-adminlte-modal>