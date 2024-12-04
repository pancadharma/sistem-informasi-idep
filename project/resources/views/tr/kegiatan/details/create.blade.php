<!--using this files if layout display like program-->
<div class="col-12 col-sm-12">
    <div class="card card-primary card-outline">

        <!--card header : basic information-->
        <div class="card-header">
            <strong> {{ __('cruds.kegiatan.tabs.basic') }}</strong>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body pb-0">
            <!--include dependency from outcome activity-->
            @include('tr.kegiatan.details.create.foreign')
            <!--include basic information-->
            @include('tr.kegiatan.details.create.basic')

            <!--include lokasi information-->
            @include('tr.kegiatan.details.create.lokasi')

        </div>

    <!-- main card-->
    </div>
</div>
