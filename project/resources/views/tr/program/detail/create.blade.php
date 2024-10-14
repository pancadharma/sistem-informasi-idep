<div class="col-12 col-sm-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header border-bottom-0 p-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-lokasi-tab" data-toggle="pill"
                        href="#custom-tabs-four-lokasi" role="tab" aria-controls="custom-tabs-four-lokasi"
                        aria-selected="true">{{ __('cruds.program.lokasi.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-donor-tab" data-toggle="pill"
                        href="#custom-tabs-four-donor" role="tab" aria-controls="custom-tabs-four-donor"
                        aria-selected="false">{{ __('cruds.program.donor.label') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-staff-tab" data-toggle="pill"
                        href="#custom-tabs-four-staff" role="tab" aria-controls="custom-tabs-four-staff"
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
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-four-lokasi" role="tabpanel"
                    aria-labelledby="custom-tabs-four-lokasi-tab">
                    @include('tr.program.detail.lokasi')
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-donor" role="tabpanel"
                    aria-labelledby="custom-tabs-four-profile-tab">
                    @include('tr.program.detail.donor')
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-staff" role="tabpanel"
                    aria-labelledby="custom-tabs-four-staff-tab">
                    @include('tr.program.detail.staff')
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-outcome" role="tabpanel"
                    aria-labelledby="custom-tabs-four-outcome-tab">
                    @include('tr.program.detail.outcome')
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-partner" role="tabpanel"
                    aria-labelledby="custom-tabs-four-partner-tab">
                    @include('tr.program.detail.partner')
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-report" role="tabpanel"
                    aria-labelledby="custom-tabs-four-report-tab">
                    Report
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-objective" role="tabpanel"
                    aria-labelledby="custom-tabs-four-objective-tab">
                    Objective
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-goals" role="tabpanel"
                    aria-labelledby="custom-tabs-four-goals-tab">
                    @include('tr.program.detail.goals')
                </div>
            </div>
        </div>

    </div>
</div>
