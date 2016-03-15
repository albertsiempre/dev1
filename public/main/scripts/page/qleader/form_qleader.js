var _class_success = 'span12 success';
var _class_error = 'span12 error';
$(function(){
    $('#city').chosen();
    $('#staff_username').autocomplete({
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
                $('#qleader_form input#staff_username').attr('class',_class_error);
            }
            else{
                if(typeof(response.suggestions[0].data)!='undefined'){
                    $('#qleader_form input#staff_username').attr('class',_class_success);
                    $('#qleader_form input[name="staff_id"]').val(response.suggestions[0].data);
                }
            }
        }
    });


    $('#qleader_form').submit(function(){
        submit_form($(this));
        return false;
    });

    $("#qi-upload-pp").submit(function() {
        $(this).addClass('hidden');
        $("#qi-uploading").removeClass('hidden');
    });
    $("#qi-upload-pp").ajaxForm(function(data) {
        $('#qi-upload-pp').removeClass('hidden');
        $('#qi-upload-pp').parents('.modal').addClass('hidden');
        $("#qi-uploading").addClass('hidden');
        $('#qi-iresult-container').removeClass().addClass('hidden');
        try {
            jr = $.parseJSON(data);
            if (jr.ok) {
                var img_href = jr.puri ? jr.puri : "https://accounts.qeon.co.id/assets/img/avatar_big_default.png";
                var obj_jcrop = $("img#pp_jcrop").data("Jcrop");
                var jcrop_ratio = jr.r;
                if (obj_jcrop && obj_jcrop.destroy) { obj_jcrop.destroy();}else{}
                $("div#qi-img-placer").find("img").remove();
                $("<img />").attr("src", img_href).attr("id", "pp_jcrop").appendTo("div#qi-img-placer");
                bindJcrop(jcrop_ratio);
                $('#qi-iresult-container').removeClass('hidden').addClass('alert alert-success').html("Upload success");
                return true;
            } else {
                //$("#qi-uploading").show(0);
                if (jr.invalidate) document.location.href = window.my_addr; /// if session is invalidated, redirect to / ...
                //console.log(jr);
                var error = "Upload gagal" + (jr.err ? (": " + jr.err) : ".");
                //triggerMsg($("span#qi-iresult-container"), false, error);
                $('#qi-iresult-container').removeClass('hidden').addClass('alert alert-error').html(error);
                return false;
            } // if (jr.ok)
        } catch (e) {
            //console.log(e);
            /// show error message...
            $('#qi-iresult-container').removeClass('hidden').addClass('alert alert-error').html('Upload gagal: Kesalahan fatal.');
            //triggerMsg($("span#qi-iresult-container"), false, "Upload gagal: Kesalahan fatal.");
            return false;
        } // try - catch (e)
    });
});


function _clean_input_background_(form_object){
    $(form_object).each(function(){
        $(this).attr('class','span12');
    });
}

function clean_form(){
    $('#qleader_form').clearForm();

    //clear additional form
    $('#city').val(1).trigger('liszt:updated');
    $("div#qi-img-placer").find("img").remove();
    $("img#pp_jcrop").remove();
    $("input.dims_hidden").remove();
}
function bindJcrop(ratio) {
    if (window.jQuery.Jcrop && $("#pp_jcrop").length > 0) {
        ratio = parseFloat(ratio)
        $("#qleader_form").unbind('submit').submit(function() {
            $("input.dims_hidden").remove();
            $("<input>").attr("type", "hidden").attr("name", "crop_x").attr("class", "dims_hidden").val(dims.x).appendTo(this);
            $("<input>").attr("type", "hidden").attr("name", "crop_y").attr("class", "dims_hidden").val(dims.y).appendTo(this);
            $("<input>").attr("type", "hidden").attr("name", "from_x").attr("class", "dims_hidden").val(dims.x2).appendTo(this);
            $("<input>").attr("type", "hidden").attr("name", "from_y").attr("class", "dims_hidden").val(dims.y2).appendTo(this);
            $("<input>").attr("type", "hidden").attr("name", "crop_w").attr("class", "dims_hidden").val(dims.w).appendTo(this);
            $("<input>").attr("type", "hidden").attr("name", "crop_h").attr("class", "dims_hidden").val(dims.h).appendTo(this);
            $("<input>").attr("type", "hidden").attr("name", "crop_r").attr("class", "dims_hidden").val(ratio).appendTo(this);
            submit_form($(this));
            return false;
        });
        return $("#pp_jcrop").Jcrop({
            'onSelect':function(coords) { dims = coords; },
            'aspectRatio':1,
            'minSize':[Math.ceil(200 * ratio), Math.ceil(200*ratio)],
            'setSelect':[0, 0, Math.ceil(200 * ratio), Math.ceil(200*ratio)]
        });
    }
    return false;
}



function submit_form($form){

    var stat = true;
    var $username = $form.find('input[name="username"]');
    var $phone = $form.find('input[name="phone"]');
    var $password = $form.find('input[name="password"]');
    var $fullname = $form.find('input[name="fullname"]');
    var $email = $form.find('input[name="email"]');
    var $staffname = $form.find('input#staff_username');
    var $staffid = $form.find('input[name="staff_id"]');
    var $error_message = $('#error_message');
    _clean_input_background_([$username,$phone,$email,$password,$fullname,$staffname]);

    if($username.val()==='') {
        $username.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Username harus diisi.');
        stat = false;
    }
    else if($password.val() === '') {
        $password.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Password Anda harus diisi.');
        stat = false;
    }
    else if($fullname.val() === '') {
        $fullname.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Nama lengkap Anda harus diisi.');
        stat = false;
    }
    else if($email.val() === '') {
        $email.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Email Anda harus diisi.');
        stat = false;
    }
    else if($phone.val() === '') {
        $phone.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Nomor HP harus diisi.');
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
    else if($staffid.val()==='' || $staffid.val()===0){
        $staffname.attr('class',_class_error).focus();
        $error_message.addClass('alert alert-error').html('Nama staff harus diisi.');
        stat = false;
    }

    if(stat){
        $error_message.attr('class','').html('');
        $('button[type="submit"]').attr('disabled',true);
        $form.ajaxSubmit(function(result){
            if(result!=null && result!=''){
                result = JSON.parse(result);
                if(result.Success){
                    $error_message.addClass('alert alert-success');
                    //kalau form edit, ga ada usernamenya, ga usah kosongin
                    location.reload();
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