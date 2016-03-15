$("body").ready(function()
{
	$(".form_warnet").submit(function() {
		var form = $(this);
		var ser = form.serialize();
		$("button[id='qproccess_warnet']").attr("disabled", true);
		$("#form_close").attr("disabled", true);
		form.ajaxSubmit(function(res){
            var obj = $.parseJSON(res);
            show_message(obj.result.data, obj.result.status);
            $("button[id='qproccess_warnet']").attr("disabled", false);
            $("#form_close").attr("disabled", false);
            if(obj.result.status == true)
            {
            	needRefresh = true;
            	console.log("success");
            	form[0].reset();
            	$("textarea[name='image']").html("");
                $(".myImage").removeAttr("src");
                $("._status_image").empty();

                $("button[id='qproccess_warnet']").remove();
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