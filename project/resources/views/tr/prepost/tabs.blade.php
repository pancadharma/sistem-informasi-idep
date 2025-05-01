<div class="col-12 col-sm-12">
    <div class="card card-primary card-tabs">
        <div class="card-header border-bottom-0 card-header p-0 pt-1 navigasi">
            {{-- <button type="button" class="btn btn-danger float-right" id="SimpanFormMeals">{{ __('global.save') }}</button> --}}
            <ul class="nav nav-tabs" id="details-kegiatan-tab" role="tablist">
                <button type="button" class="btn btn-tool btn-small" data-card-widget="collapse" title="Minimize">
                    <i class="bi bi-arrows-collapse"></i>
                </button>
                <li class="nav-item">
                    <a class="nav-link active" id="prepost-tab" data-toggle="pill" role="tab" aria-selected="true">
                        {{ __('cruds.prepost.label') }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="details-kegiatan-tabContent">
                <div class="tab-pane fade show active" id="tab-prepost" role="tabpanel" aria-labelledby="beneficiaries-tab">
                    @include('tr.prepost.tabs.main')
                </div>
            </div>
        </div>
        {{-- <div class="card-footer">
            @stack('next-button')
        </div> --}}

    </div>
</div>




