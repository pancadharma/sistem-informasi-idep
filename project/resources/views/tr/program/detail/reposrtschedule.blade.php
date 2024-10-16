{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />   --}}
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  


<div class="form-group">  
<form name="add_name" id="add_name">  
<div class="table-responsive">  
    <table class="table table-bordered" id="dynamic_field">  
         {{-- <tr> --}}
              {{-- <td> --}}
                  <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                  
              {{-- </td>   --}}
         {{-- </tr>   --}}
    </table>  
    {{-- <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />   --}}
</div>  
</form>  
</div>  

<script>  
$(document).ready(function(){  
var i=1;  
$('#add').click(function(){  
    i++;  
    $('#dynamic_field').append('<tr id="row'+i+'"><td><div class="form-inline"><input type="date" name="date[]" class="form-control" style="margin-right:10px;"><textarea style="width:60%" name="nilai[]" class="form-control nilai_list" placeholder="keterangan"></textarea></div></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
});  
$(document).on('click', '.btn_remove', function(){  
    var button_id = $(this).attr("id");   
    $('#row'+button_id+'').remove();  
}); 
});  
</script>
