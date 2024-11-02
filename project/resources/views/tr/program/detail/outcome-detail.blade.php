{{-- <div class="modal fade" id="modalAddOutput" tabindex="-1" role="dialog" aria-labelledby="modalAddOutputLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddOutputLabel">{{ __('global.add'). ' ' . __('cruds.program.output.label') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAddOutput">
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
                    </div>
                    <div class="form-group">
                        <label for="indikator">Indikator</label>
                        <input type="text" class="form-control" id="indikator" name="indikator" required>
                    </div>
                    <div class="form-group">
                        <label for="target">Target</label>
                        <input type="text" class="form-control" id="target" name="target" required>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitOutput">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}


<x-adminlte-modal id="modalAddOutput"  title="{{ __('global.add'). ' ' . __('cruds.program.output.label') }}"
size="xl" theme="info" icon="fas fa-folder-open" v-centered static-backdrop scrollable>
    <div style="height:50%;">
        <div class="modal-body">
            <!-- Your form goes here -->
            <form id="formAddOutput">
                <div class="form-group">
                    <label for="deskripsi">{{ __('cruds.program.output.desc') }}</label>
                    <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
                </div>
                <div class="form-group">
                    <label for="indikator">{{ __('cruds.program.output.indicator') }}</label>
                    <input type="text" class="form-control" id="indikator" name="indikator" required>
                </div>
                <div class="form-group">
                    <label for="target">{{ __('cruds.program.output.target') }}</label>
                    <input type="text" class="form-control" id="target" name="target" required>
                </div>
                <button type="submit" class="btn btn-primary" id="submitOutput">Submit</button>
            </form>
        </div>
    </div>
    <x-slot name="footerSlot">
    </x-slot>
</x-adminlte-modal>
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAddOutputLabel">Tambah Output</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Your form goes here -->
          <form id="formAddOutput">
            <div class="form-group">
              <label for="deskripsi">Deskripsi</label>
              <input type="text" class="form-control" id="deskripsi" name="deskripsi" required>
            </div>
            <div class="form-group">
              <label for="indikator">Indikator</label>
              <input type="text" class="form-control" id="indikator" name="indikator" required>
            </div>
            <div class="form-group">
              <label for="target">Target</label>
              <input type="text" class="form-control" id="target" name="target" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
