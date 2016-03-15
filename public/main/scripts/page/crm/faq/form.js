$("body").ready(function()
{
	$(".form_faq").submit(function() {
		var form = $(this);
		var ser = form.serialize();
		$("button[id='qsubmit_faq']").attr("disabled", true);
		$("#form_close").attr("disabled", true);
		$("#_txtAnswer").val(CKEDITOR.instances.txtDescription.getData());
		form.ajaxSubmit(function(res){
            var obj = $.parseJSON(res);
            show_message(obj.message, obj.status);
            $("button[id='qproccess_warnet']").attr("disabled", false);
            $("#form_close").attr("disabled", false);
            if(obj.status == true)
            {
            	needRefresh = true;
            	console.log("success");
            	CKEDITOR.instances.txtDescription.setData("");
            	$("#__is_public").attr("checked", false);
            	form[0].reset();
                $("button[id='qsubmit_faq']").remove();
                $("#form_close").html("close");
            }
        });

		return false;
	});

	function show_message(msg, status)
	{
		var error_template;

		if(status == true)
		{
			error_template = $("#tmp_success_msg").html();
		} else {
			error_template = $("#tmp_error_msg").html();
		}

		$("._error_container").html(error_template);
		$("#error_message").empty().append(msg);
	}

	$("body").on("click", "#qproccess_warnet", function(){
		var status_id = $(this).data("statusid");
		var type = status_id == 2 ? "Approve" : "Reject";
		if(confirm("Apakah Anda yakin ingin " + type + " Warnet ini?"))
		{
			$("input[name='status_id']").val(status_id);
			$(this).parents("form").submit();
		}
	});
});