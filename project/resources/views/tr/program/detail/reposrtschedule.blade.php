@if($program->jadwalreport->isEmpty())
    <div class="table-responsive">  
        <table class="table table-bordered" id="reportschedule">   
            <button type="button" name="add" id="add" class="btn btn-success">+</button>
        </table>   
    </div> 
@else
    <div class="table-responsive">  
        <table class="table table-bordered" id="reportschedule">   
            <button type="button" name="add" id="add" class="btn btn-success">+</button>
        </table>   
    </div> 
    @foreach ($program->jadwalreport as $index => $jdwalreport)
    <div class="row" id={{ $index }}>
        <div class="col-lg-3 form-group">
            <div class="input-group">
                <label for="pendonor_id" class="input-group small mb-2 mt-2">Tanggal</label>
                <input type="date" id="tanggal" name="tanggallaporan[]" class="form-control" value="{{ old('tanggalmulai', \Carbon\Carbon::parse($jdwalreport->tanggal)->format('Y-m-d')) }}">
            </div>
        </div>
        <div class="col-lg-3 form-group">
            <div class="input-group">
                <label for="detail" class="input-group small mb-2 mt-2">Keterangan</label>
                <textarea name="keteranganlaporan[]" class="form-control" placeholder="keterangan" rows="1">{{ $jdwalreport->keterangan ?? '' }}</textarea>
                <span class="input-group-append ml-2">
                    <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="{{ $index }}"><i class="bi bi-trash"></i></button>
                </span>
            </div>
        </div>
    </div>
    @endforeach
@endif

