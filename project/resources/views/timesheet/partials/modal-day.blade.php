<div class="modal fade" id="modalDay" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="height:80vh; display:flex; flex-direction:column;">

            {{-- HEADER --}}
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-day"></i>
                        Input Kegiatan Harian
                    </h5>
                    <button type="button"
                            class="close text-white"
                            data-dismiss="modal">
                        &times;
                    </button>
                </div>
            
            {{-- FORM --}}
            <form id="formDay"
                  method="POST"
                  action="{{ route('timesheet.day.store') }}"
                  autocomplete="off"
                  style="display:flex; flex-direction:column; flex:1; min-height:0;">
                @csrf

                

                {{-- BODY --}}
                <div class="modal-body" style="overflow-y:auto; flex:1; min-height:0;">

                    {{-- HIDDEN --}}
                    <input type="hidden"
                           name="timesheet_id"
                           value="{{ $timesheet->id ?? '' }}">

                    <input type="hidden"
                           name="work_date"
                           id="modal-work-date">

                    <input type="hidden" id="has-existing-activities" value="0">

                    {{-- INFO TANGGAL --}}
                    <div class="mb-3">
                        <strong>Tanggal:</strong>
                        <span id="modal-date-text"></span>
                    </div>

                    {{-- STATUS HARI --}}
                    <div class="form-group">
                        <label>Status Hari</label>
                        <select name="day_status"
                                id="day-status"
                                class="form-control">
                            <option value="kerja">Kerja</option>
                            <option value="libur">Libur</option>
                            <option value="cuti">Cuti</option>
                            <option value="doc">DOC</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                    {{-- CATATAN --}}
                    <div id="noteBox" class="form-group" style="display:none;">
                        <label>Keterangan</label>
                        <textarea name="note"
                                class="form-control"
                                placeholder="Wajib diisi untuk sakit / cuti / doc"></textarea>
                    </div>

                    {{-- TABLE AKTIVITAS --}}
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle"
                               id="activityTable" style="min-width: 900px; width: 100%;">
                            <thead class="thead-light text-center">
                                <tr>
                                    <th style="width: 15%">Area</th>
                                    <th style="width: 15%">Lokasi Kerja</th>
                                    <th style="width: 16%">Waktu</th>
                                    <th style="width: 17%">Fokus Area</th>
                                    <th style="width: 14%">Resource</th>
                                    <th style="width: 18%">Kegiatan</th>
                                    <th style="width: 5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- row via JS --}}

                            </tbody>
                        </table>
                    </div>

                    <button type="button"
                            class="btn btn-outline-primary btn-sm"
                            id="addRow">
                        + Tambah Aktivitas
                    </button>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>