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

    $("#update_note_form").submit(function(){
        var form = $(this);
        var form_button = form.find("button[type='submit']");
        var form_loading = $('#loading-form');
        var popupClose = $(".mfp-close");
        $('#loading-form').html('<img src="../main/images/ajax-loader-small.gif"> Sending..');
        form_button.attr('disabled',true);
        popupClose.attr("disabled", true);
        form.ajaxSubmit(function(res){
            var obj = $.parseJSON(res);

            if(obj.status = true)
            {
                needRefresh = true;
                form_loading.html('<b style="color:green;">' + obj.message + '</b>');
                form_button.attr('disabled', false);
                popupClose.attr("disabled", false);
            } else {
                form_loading.html('<b style="color:red;">' + obj.message + '</b>');
                form_button.attr('disabled', false);
                popupClose.attr("disabled", false);
            }
        });
        return false;
    });

    $("#_send_dvd_request").submit(function(){
        var form = $(this);
        var submit_btn = form.find("button[type='submit']");
        var form_loading = $("#loading-send-form");
        var data = form.serialize();
        var popupClose = $(".mfp-close");
        if(confirm("Apakah Anda yakin ingin melakukan proses ini?"))
        {
            popupClose.attr("disabled", true);
            submit_btn.attr("disabled", true);
            form_loading.html('<img src="../main/images/ajax-loader-small.gif"> Sending..');
            form.ajaxSubmit(function(result){
                if(result != null && result != ''){
                    console.log(result);
                    var obj = $.parseJSON(result);
                    console.log(obj);
                    if(obj.status = true)
                    {
                        needRefresh = true;
                        form_loading.html('<b style="color:green;">Sukses.</b>');
                        submit_btn.attr('disabled', false);
                        submit_btn.remove();
                        popupClose.attr("disabled", false);
                    } else {
                        form_loading.html('<b style="color:red;">An error occurred. Please try again later. (Code : 0x1)</b>');
                        submit_btn.attr('disabled', false);
                        popupClose.attr("disabled", false);
                    }
                } else {
                    form_loading.html('<b style="color:red;">An error occurred. Please try again later. (Code : 0x0)</b>');
                    submit_btn.attr('disabled', false);
                    popupClose.attr("disabled", false);
                }
            });
        }
        return false;
    });

    $(".btn_checkout").magnificPopup({
        type:'ajax',
        closeOnBgClick:false,
        callbacks: {
            beforeClose: function() {
                console.log("need Refresh = " + needRefresh);
                if(needRefresh)
                {
                    location.reload();
                }
            }
        }
    });

    // $("._btn_print").click(function(){
    //     var url = $(this).data("url");
    //     window.location.href = url;
    // });

    $("._btn_finish").click(function(){
		if(confirm("Apakah Anda yakin ingin checkout?"))
		{
			var form_loading = $('#loading-form');
			var btn = $(this);
			var popupClose = $(".mfp-close");
			form_loading.html('<img src="../main/images/ajax-loader-small.gif"> Sending..');
			$(".__my_footer").find("button").attr("disabled", true);
			popupClose.attr("disabled", true);
			var url = $(this).data("url");
			$.get(url, function(ret){
				var obj = $.parseJSON(ret);
				if(obj.status == true)
				{
					needRefresh = true;
					form_loading.html('<b style="color:green;">' + obj.message + '</b>');
					btn.remove();
					popupClose.attr("disabled", false);
					$(".__my_footer").find("button").attr("disabled", false);
				} else {
					form_loading.html('<b style="color:red;">' + obj.message + '</b>');
					popupClose.attr("disabled", false);
					$(".__my_footer").find("button").attr("disabled", false);
				}
			});
		} else {
			return false;
		}
    });

    $("body").on("click", "#_show_address", function(){
        var address = $(this).data("address");
        var td = $(this).parents("td");
        td.empty().append(address);
    });

    // $('.check label').click(function(){
    //     if($(this).parent().find("input:checked").length == 0) {
    //         $('input',this).attr('placeholder','No. Resi / Keterangan');
    //     }
    //     else {
    //         $('input',this).attr('placeholder','No. Resi / Keterangan');
    //     }
    // })
});