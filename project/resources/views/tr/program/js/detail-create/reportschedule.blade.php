
@push('js')

<script>  
    $(document).ready(function(){  
    var i=1;  
    $('#add').click(function(){  
        i++;  
        $('#reportschedule').append(
    
    
        `<div class="row" id=${i}>
            <div class="col-lg-3 form-group">
                <div class="input-group">
                    <label for="pendonor_id" class="input-group small mb-0">tanggal</label>
                    <input type="date" id="tanggal" name="tanggallaporan[]" class="form-control">
                </div>
            </div>
            
            
            <div class="col-lg-3 form-group">
                <div class="input-group">
                    <label for="detail" class="input-group small mb-0">Keterangan</label>
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