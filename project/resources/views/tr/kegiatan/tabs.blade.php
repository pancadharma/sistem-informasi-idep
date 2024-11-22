<div class="col-12 col-sm-12">
    <div class="card card-primary card-tabs">
        <div class="card-header border-bottom-0 p-0">
            <ul class="nav nav-tabs" id="details-kegiatan-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="basic-nav-tab" data-toggle="pill"
                       href="#basic-tabs" role="tab" aria-controls="basic-tabs"
                       aria-selected="true">{{ __('cruds.program.lokasi.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="description-nav-tab" data-toggle="pill"
                       href="#description-tab" role="tab" aria-controls="description-tab"
                       aria-selected="false">{{ __('cruds.program.donor.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="hasil-nav-tab" data-toggle="pill"
                       href="#hasil-tab" role="tab" aria-controls="hasil-tab"
                       aria-selected="false">{{ __('cruds.program.staff.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-outcome-tab" data-toggle="pill"
                       href="#custom-tabs-four-outcome" role="tab" aria-controls="custom-tabs-four-outcome"
                       aria-selected="false">{{ __('cruds.program.outcome.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-partner-tab" data-toggle="pill"
                       href="#custom-tabs-four-partner" role="tab" aria-controls="custom-tabs-four-partner"
                       aria-selected="false">{{ __('cruds.program.partner.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-report-tab" data-toggle="pill"
                       href="#custom-tabs-four-report" role="tab" aria-controls="custom-tabs-four-report"
                       aria-selected="false">{{ __('cruds.program.report.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-objective-tab" data-toggle="pill"
                       href="#custom-tabs-four-objective" role="tab" aria-controls="custom-tabs-four-objective"
                       aria-selected="false">{{ __('cruds.program.objective.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-goals-tab" data-toggle="pill"
                       href="#custom-tabs-four-goals" role="tab" aria-controls="custom-tabs-four-goals"
                       aria-selected="false">{{ __('cruds.program.goals.label') }}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="details-kegiatan-tabContent">
                <div class="tab-pane fade show active" id="basic-tabs" role="tabpanel"
                     aria-labelledby="basic-tabs">
                     q
                   {{-- @include('tr.program.detail.lokasi') --}}

                </div>
                <div class="tab-pane fade" id="description-tab" role="tabpanel"
                     aria-labelledby="custom-tabs-four-profile-tab">
                   2
                     {{-- @include('tr.program.detail.donor') --}}
                </div>
                <div class="tab-pane fade" id="hasil-tab" role="tabpanel"
                     aria-labelledby="hasil-tab">
                   3
                     {{-- @include('tr.program.detail.staff') --}}
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-outcome" role="tabpanel"
                     aria-labelledby="custom-tabs-four-outcome-tab">
                   4
                     {{-- @include('tr.program.detail.outcome') --}}
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-partner" role="tabpanel"
                     aria-labelledby="custom-tabs-four-partner-tab">
                   5
                     {{-- @include('tr.program.detail.partner') --}}
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-report" role="tabpanel"
                     aria-labelledby="custom-tabs-four-report-tab">
                   6
                     {{-- @include('tr.program.detail.reposrtschedule') --}}
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-objective" role="tabpanel"
                     aria-labelledby="custom-tabs-four-objective-tab">
                   7
                     {{-- @include('tr.program.detail.objective') --}}
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-goals" role="tabpanel"
                     aria-labelledby="custom-tabs-four-goals-tab">
                   8
                     {{-- @include('tr.program.detail.goals') --}}
                </div>

            </div>
        </div>

    </div>
</div>

