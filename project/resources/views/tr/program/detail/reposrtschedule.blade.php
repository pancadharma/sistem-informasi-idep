
<div class="row">
    <div class="col-lg-3 form-group">
        <div class="input-group">
            <label for="pendonor_id" class="input-group small mb-1 ">Tanggal</label>
        </div>
    </div>
    <div class="col form-group">
        <div class="input-group">
            <label for="detail" class="input-group small mb-2 ">Keterangan</label>
        </div>
    </div>
</div>

@if($program->jadwalreport->isEmpty())
    <div class="table-responsive">  
        <table class="table table-bordered" id="reportschedule">   
            <div class="row">
                <div class="col-lg-3 form-group">
                    <div class="input-group">
                        <input type="date" id="tanggal" name="tanggallaporan[]" class="form-control">
                    </div>
                </div>
                <div class="col form-group">
                    <div class="input-group">
                        <textarea name="keteranganlaporan[]" class="form-control" placeholder="keterangan" rows="1"></textarea>
                        <span class="input-group-append ml-2">
                                <button type="button" name="add" id="add" class="btn form-control btn-success btn-flat"><i class="bi bi-plus"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </table>   
    </div> 
@else
    <div class="table-responsive">  
        <table class="table table-bordered" id="editreportschedule">   
            {{-- <button type="button" name="addedit" id="addedit" class="btn btn-success">+</button> --}}
                @foreach ($program->jadwalreport as $index => $jdwalreport)
                    <div class="row" id={{ $index }}>
                        <div class="col-lg-3 form-group">
                            <div class="input-group">
                                <input type="date" id="tanggal" name="tanggallaporan[]" class="form-control" value="{{ old('tanggalmulai', \Carbon\Carbon::parse($jdwalreport->tanggal)->format('Y-m-d')) }}">
                                <input type="hidden" name="jadwalreport_id[]" value="{{ $jdwalreport->id }}">
                            </div>
                        </div>
                        <div class="col form-group">
                            <div class="input-group">
                                <textarea name="keteranganlaporan[]" class="form-control" placeholder="keterangan" rows="1">{{ $jdwalreport->keterangan ?? '' }}</textarea>
                                <span class="input-group-append ml-2">
                                    @if ($index === 0)
                                        <button type="button" name="addedit" id="addedit" class="btn form-control btn-success btn-flat"><i class="bi bi-plus"></i></button>
                                    @else
                                        <button type="button" class="btn btn-danger form-control remove-pendonor btn-flat" data-target="{{ $index }}"><i class="bi bi-trash"></i></button>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
        </table> 
    </div> 
@endif


@push('js')
<script>  
    $(document).ready(function(){  
    let i = {{ $program->jadwalreport->count() }}
    $('#addedit').click(function(){  
        i++;  
        $('#editreportschedule').append(
    
    
        `<div class="row" id=${i}>
            <div class="col-lg-3 form-group">
                <div class="input-group">
                    <input type="date" id="tanggal" name="tanggallaporan[]" class="form-control">
                    <input type="hidden" name="jadwalreport_id[]">
                </div>
            </div>
            
            
            <div class="col form-group">
                <div class="input-group">
                    <textarea name="keteranganlaporan[]" class="form-control" placeholder="keterangan" rows="1"></textarea>
                    <span class="input-group-append ml-2" >
                        <button type="button" class="btn btn-danger form-control remove-pendonor nilaidonasi btn-flat" data-target="${i}"><i class="bi bi-trash"></i></button>
                    </span>   
                </div>
            </div>
    
        </div>`
        
        
        );  
    });  
    $(document).on('click', '.btn_remove', function(){  
        var button_id = $(this).attr("id");   
        $('#row'+button_id+'').remove();  
    }); 
    });  
</script>
@endpush