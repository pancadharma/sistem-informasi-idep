<div class="signature-section mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="signature-box">
                <p><strong>{{ __('btor.prepared_by') }}:</strong></p>
                <br><br><br>
                <p>
                    @if($kegiatan->datapenulis?->first())
                        <strong>{{ $kegiatan->datapenulis->first()->nama }}</strong><br>
                        <em>{{ $kegiatan->datapenulis->first()->kegiatanPeran?->nama ?? 'Staff' }}</em>
                    @else
                        <strong>_____________________</strong><br>
                        <em>{{ __('btor.penulis_laporan') }}</em>
                    @endif
                </p>
                <p>Date: {{ now()->format('d F Y') }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="signature-box">
                <p><strong>{{ __('btor.approve_by') }}:</strong></p>
                <br><br><br>
                <p>
                    @if($kegiatan->user)
                        <strong>{{ $kegiatan->user->nama }}</strong><br>
                        <em>{{ $kegiatan->user->jabatan?->nama ?? __('btor.penanggung_jawab_jabatan') }}</em>
                    @else
                        <strong>_____________________</strong><br>
                        <em>{{ __('btor.penanggung_jawab_jabatan') }}</em>
                    @endif
                </p>
                <p>Date: _________________</p>
            </div>
        </div>
    </div>
</div>

<div class="report-footer mt-4 pt-3 border-top">
    <small class="text-muted">
        <strong>Note:</strong> This is an official report of IDEP Foundation.
        For inquiries, please contact the Program Department.
    </small>
</div>
