{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />   --}}
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>   --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  


<div class="table-responsive">  
    <table class="table table-bordered" id="dynamic_field">  
        
                  <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                  
    </table>  
   
</div> 

<script>  
$(document).ready(function(){  
var i=1;  
$('#add').click(function(){  
    i++;  
    $('#dynamic_field').append(


    `<div class="row" id=${i}>
        <div class="col-lg-3 form-group">
            <div class="input-group">
                <label for="pendonor_id" class="input-group small mb-0">tanggal</label>
                <input type="date" id="tanggal" name="tanggal[]" class="form-control">
            </div>
        </div>
        
        
        <div class="col-lg-3 form-group">
            <div class="input-group">
                <label for="detail" class="input-group small mb-0">Keterangan</label>
                <textarea name="keterangan[]" class="form-control" placeholder="keterangan"></textarea>
                <span class="input-group-append">
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
