
$(function(){
    $('#qleader').chosen();
    $('#username').autocomplete({
        serviceUrl: url_get_user,
        minChars : 3,
        deferRequestBy : 1000,
        noCache : true,
        onSelect: function(suggestion) {
        },
        onSearchStart : function(query){
            $(this).after('<span id="ajax-loader" style="margin-left:15px;"><img src="'+url_loading_ajax+'" alt="loading.."/></span>');
            $(this).attr('disabled','disabled');
        },
        onSearchComplete : function(query,response){
            $('#ajax-loader').remove();
            $(this).removeAttr('disabled');
            if(response.suggestions.length == 0){
                $('#member_form input#username').attr('class',_class_error);
            }
            else{
                if(typeof(response.suggestions[0].data)!='undefined'){
                    $('#member_form input#username').attr('class',_class_success);
                    $('#member_form input[name="member_id"]').val(response.suggestions[0].data);
                }
            }
        }
    });

    $('#member_form').submit(function(){
        submit_form($(this));
        return false;
    });
});



function clean_form(){
    $('#member_form').clearForm();
}

function submit_form($form){
    var stat = true;
    var $username = $form.find('input[name="username"]');
    var $fullname = $form.find('input[name="fullname"]');
    var $phone = $form.find('input[name="phone"]');
    var $error_message = $('#error_message');
    _clean_input_background_([$username,$phone,$fullname]);

    if($username.val()==='') {
        $username.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Username harus diisi.');
        stat = false;
    }
    else if($fullname.val() === '') {
        $fullname.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Nama lengkap Anda harus diisi.');
        stat = false;
    }
    else if($phone.val() === '') {
        $phone.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Nomor telepon harus diisi.');
        stat = false;
    }
    else if($phone.val() !== '') {
        subphone = $phone.val().substr(0, 1);
        if(subphone !== '0' || isNaN($phone.val())) {
            $phone.attr('class',_class_error).focus();
            $error_message.addClass('alert alert-error').html('Format nomor ponsel harus diawali dengan angka 0 dan semua berupa angka.');
            stat = false;
        }
    }

    if(stat){
        $error_message.attr('class','').html('');
        $('button[type="submit"]').attr('disabled',true);
        $form.ajaxSubmit(function(result){
            if(result!=null && result!=''){
                result = JSON.parse(result);
                if(result.Success){
                    $error_message.addClass('alert alert-success');
                    location.reload();
                    //kalau form edit, ga ada usernamenya, ga usah kosongin
                    if($('input[name="username"]').length!=0){
                        clean_form();
                    }
                }else $error_message.addClass('alert alert-error');
                $error_message.html(result.Message);
            }
            $('button[type="submit"]').attr('disabled',false);
        });
    }
}