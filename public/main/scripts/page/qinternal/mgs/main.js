$("body").ready(function(){
    var magnificPopup = $.magnificPopup.instance;
    $('#form_close').click(function(){
        magnificPopup.close();
        if(needRefresh)
        {
            location.reload();
        }
    });
    var _class_success = 'span12 success';
    var _class_error = 'span12 error';

    function _clean_input_background_(form_object){
        $(form_object).each(function(){
            $(this).attr('class','span12');
        });
    }

    $(".btn_new").magnificPopup({
        type:'ajax',
        closeOnBgClick:false,
        callbacks: {
            beforeClose: function() {
//                console.log("need Refresh = " + needRefresh);
                if(needRefresh)
                {
                    location.reload();
                }
            }
        }
    });
    
    $("body").on("submit", ".form-event", function(e){
        var form            = $(this);
        var btn             = form.find("button[type='submit']");
        var btn_cancel      = $(".in-closePopup");
        var form_loading    = $('#loading-form');
        btn.attr("disabled", true);
        btn_cancel.hide();
        form_loading.html('<img src="../main/images/ajax-loader-small.gif"> Sending..');

        var canSubmit = true;
        if($("#start_date").length > 0 && $("#end_date").length > 0)
        {
                var start   = new Date($("#start_date").val());
                var end     = new Date($("#end_date").val());
                    if(end < start)
                    {
                        canSubmit = false;
                        alert("End Date harus lebih dari Start Date.");
                    }
        }
        
        if(canSubmit){
            form.ajaxSubmit(function(res){
                var obj = $.parseJSON(res);
                if(obj.result.status != false)
                {
                    form_loading.html('<b style="color:green;">' + obj.result.message + '</b>');
                    location.reload();
                } else {
                    btn.attr("disabled", false);
                    btn_cancel.show();
                    form_loading.html('<b style="color:red;">' + obj.result.message + '</b>');
                }
            });            
        }else{
            form_loading.html('');
            btn.attr("disabled", false);
            btn_cancel.show();   
        }
        e.preventDefault();
        e.returnValue = false;
    }); 
    
    $("body").on("submit", ".form-teamversus", function(e){
        var form            = $(this);
        var btn             = form.find("button[type='submit']");
        var btn_cancel      = $(".in-closePopup");
        var form_loading    = $('#loading-form');
        btn.attr("disabled", true);
        btn_cancel.hide();
        form_loading.html('<img src="../main/images/ajax-loader-small.gif"> Sending..');

        var canSubmit = true;
        if($("#start_date").length > 0 && $("#end_date").length > 0)
        {
                var start   = new Date($("#start_date").val());
                var end     = new Date($("#end_date").val());
                    if(end < start)
                    {
                        canSubmit = false;
                        alert("End Date harus lebih dari Start Date.");                      
                    }
        }
        
        if($("#team_id").length > 0 && $("#opponent_id").length > 0)
        {
                var team_id                 = $("#team_id").val();
                var opponent_id             = $("#opponent_id").val();
                var team_event_id           = $("#team_id").find(':selected').data('event');
                var opponent_event_id       = $("#opponent_id").find(':selected').data('event');
                    
                    if(team_id == opponent_id)
                    {
                        canSubmit = false;
                        alert("Nama team dengan team lawan tidak boleh sama.");
                    }
                    
                    if(team_event_id != opponent_event_id)
                    {
                        canSubmit = false;
                        alert("Team harus berada pada event yang sama.");
                    }
        }
        
        if(canSubmit){
            form.ajaxSubmit(function(res){
                var obj = $.parseJSON(res);
                if(obj.result.status != false)
                {
                    form_loading.html('<b style="color:green;">' + obj.result.message + '</b>');
                    location.reload();
                } else {
                    btn.attr("disabled", false);
                    btn_cancel.show();
                    form_loading.html('<b style="color:red;">' + obj.result.message + '</b>');
                }
            });            
        }else{
            form_loading.html('');
            btn.attr("disabled", false);
            btn_cancel.show();            
        }
        e.preventDefault();
        e.returnValue = false;
    });  
    
    /* Dismiss Popup */
    $("body").on("click", ".in-closePopup", function(e){
        $.magnificPopup.close();
        e.preventDefault();
        return false;
    });
    
    //TEAM, WINNER
    $("body").on("submit", ".form-team, .form-winner", function(e){
        var form            = $(this);
        var btn             = form.find("button[type='submit']");
        var btn_cancel      = $(".in-closePopup");
        var form_loading    = $('#loading-form');
        btn.attr("disabled", true);
        btn_cancel.hide();
        form_loading.html('<img src="../main/images/ajax-loader-small.gif"> Sending..');
        
        form.ajaxSubmit(function(res){
            var obj = $.parseJSON(res);
            if(obj.result.status != false)
            {
                form_loading.html('<b style="color:green;">' + obj.result.message + '</b>');
                location.reload();
            } else {
                btn.attr("disabled", false);
                btn_cancel.show();
                form_loading.html('<b style="color:red;">' + obj.result.message + '</b>');
            }
        }); 
        
        e.preventDefault();
        e.returnValue = false;
    });  

    //END TEAM, WINNER
           
});

function setMagnificEdit()
{
    if( $(".btn-edit-event").length > 0){
        $(".btn-edit-event").magnificPopup({
            type: 'ajax',
            closeOnBgClick:false,
            tError: 'Something went wrong :(',
            enableEscapeKey : false,
            callbacks: {
                beforeOpen: function() {

                },
                beforeClose: function() {
//                    console.log("need Refresh = " + needRefresh);
                    if(needRefresh)
                    {
                        location.reload();
                    }
                }
            }
        });        
    }
}

function setDatePicker(){
    if($(".start_date").length > 0)
    {
            $(".start_date").datepicker({
                    "format"	: "yyyy-mm-dd",
                    "autoclose"	: true
            });
    }
    
    if($(".end_date").length > 0)
    {
            $(".end_date").datepicker({
                    "format"	: "yyyy-mm-dd",
                    "autoclose"	: true
            });
    }
}
