function sendRequest(url, type, data, enableSweetAlert = false, enablePageReload = false, reloadLocation = null, enableLoader = false, enableConsoleLog = false){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url : url,
        type : type,
        data: data,
        dataType: 'JSON',
        beforeSend: function() {            
            if(enableLoader)
            {
                $('.loading').show();
            }
         },
         complete: function(){
            if(enableLoader)
            {
                $('.loading').hide();
            }
         },
        success: function(data){
            console.log(data);
            if(enableSweetAlert)
            {
                swal.fire({
                    position: 'center',
                    type: 'success',
                    title: 'Record updated successfully.',
                    showConfirmButton: false,
                    timer: 1500
                });
                if(enablePageReload)
                {
                    if(reloadLocation == null)
                    {
                        setTimeout(function(){
                            window.location.reload(); 
                        }, 1500);
                    }
                    else{
                        setTimeout(function(){
                            window.location = reloadLocation; 
                        }, 1500);
                    }
                }
            }
            else{
                if(reloadLocation == null)
                {
                    window.location.reload();
                }
                else{
                    window.location = reloadLocation; 
                }
            }
        },
        error: function(error){
            // console.log(error.responseJSON);
            // window.scrollTo({ top: 0, behavior: 'smooth' });
            if(enableSweetAlert)
            {
                swal.fire({
                    position: 'center',
                    type: 'error',
                    title: 'Unable to update.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    });
}
function sendDeleteRequest(url, enablePageReload = false, reloadLocation = null, ajaxReloadId)
{
    swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes!'
    }).then(function(result) {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url : url,
                type : 'POST',
                data: {
                    '_method' : "DELETE",
                },
                beforeSend: function() {            
                    $('.loading').show();
                 },
                 complete: function(){
                    $('.loading').hide();
                 },
                // dataType: 'JSON',
                success: function(data){
                    swal.fire({
                        position: 'center',
                        type: 'success',
                        title: 'Record deleted successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    if(enablePageReload)
                    {
                        if(reloadLocation == null)
                        {
                            setTimeout(function(){
                                window.location.reload(); 
                            }, 1500);
                        }
                        else{
                            setTimeout(function(){
                                window.location = reloadLocation; 
                            }, 1500);
                        }
                    }
                    else{
                        if(ajaxReloadId != null)
                        {
                            // console.log(ajaxReloadId);
                            ajaxReloadId.ajax.reload(null, false);
                        }
                    }
                },
                error: function(error){
                    swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'Oops...',
                        text: 'Unable to delete.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
}

