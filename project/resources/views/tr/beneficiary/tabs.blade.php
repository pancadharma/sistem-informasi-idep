<div class="col-12 col-sm-12">
    <div class="card card-primary card-tabs">
        <div class="card-header border-bottom-0 card-header p-0 pt-1 navigasi">
            {{-- <button type="button" class="btn btn-danger float-right" id="SimpanFormMeals">{{ __('global.save') }}</button> --}}
            <ul class="nav nav-tabs" id="details-kegiatan-tab" role="tablist">
                <button type="button" class="btn btn-tool btn-small" data-card-widget="collapse" title="Minimize">
                    <i class="bi bi-arrows-collapse"></i>
                </button>
                <li class="nav-item">
                    <a class="nav-link active" id="beneficiaries-tab" data-toggle="pill" href="#tab-beneficiaries" role="tab" aria-controls="tab-beneficiaries" aria-selected="true">
                        {{ __('cruds.beneficiary.penerima.label') }}
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" id="description-nav-tab" data-toggle="pill" href="#description-tab" role="tab" aria-controls="description-tab" aria-selected="false">
                        {{ __('cruds.kegiatan.tabs.description') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="hasil-nav-tab" data-toggle="pill" href="#tab-hasil" role="tab" aria-controls="tab-hasil" aria-selected="false">
                        {{ __('cruds.kegiatan.tabs.hasil') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-files" data-toggle="pill" href="#tab-file" role="tab" aria-controls="tab-file" aria-selected="false">
                        {{ __('cruds.kegiatan.tabs.file') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-report-tab" data-toggle="pill" href="#tab-penulis" role="tab" aria-controls="tab-penulis" aria-selected="false">
                        {{ __('cruds.kegiatan.tabs.penulis') }}
                    </a>
                </li> --}}
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="details-kegiatan-tabContent">
                <div class="tab-pane fade show active" id="tab-beneficiaries" role="tabpanel" aria-labelledby="beneficiaries-tab">
                    @include('tr.beneficiary.tabs.beneficiaries')
                </div>
                <div class="tab-pane fade" id="description-tab" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                    {{-- @include('tr.kegiatan.tabs.description') --}}
                </div>
                <div class="tab-pane fade" id="tab-hasil" role="tabpanel" aria-labelledby="tab-hasil">
                    {{-- @include('tr.kegiatan.tabs.hasil') --}}
                </div>

                <div class="tab-pane fade" id="tab-file" role="tabpanel" aria-labelledby="tab-file">
                    {{-- @include('tr.kegiatan.tabs.file-uploads') --}}
                </div>
                <div class="tab-pane fade" id="tab-penulis" role="tabpanel" aria-labelledby="tab-penulis">
                    {{-- @include('tr.kegiatan.tabs.penulis') --}}
                </div>
            </div>
        </div>
        <div class="card-footer">
            @stack('next-button')
        </div>

    </div>
</div>




