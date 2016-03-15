$("body").ready(function(){
	$(".btn-add-subservice").magnificPopup({
        type:'ajax',
        closeOnBgClick:false,
        callbacks: {
        	ajaxContentAdded: function() {
				var el = this.st.el;
				$(".career-notif").parent("div").remove();
				$("input[name='id']").remove();
        	},
			beforeClose: function() {
				if(needRefresh)
				{
					location.reload();
				}
			}
        }
    });

    $("._btn_edit_subservice").magnificPopup({
    	type: 'ajax',
    	closeOnBgClick: false,
    	callbacks: {
    		ajaxContentAdded:function(){
    			var el = this.st.el;
    			var id = el.data("id");
    			var tr = el.parents("tr");
    			var name = tr.find("td:eq(1)").html();
    			$("input[name='id']").val(id);
    			$("input[name='name']").val(name);
    			$(".career-notif").parent("div").remove();
    			$(".block-heading").html("Edit SubService");
    		},
			beforeClose: function() {
				if(needRefresh)
				{
					location.reload();
				}
			}
    	}
    });

    $("._btn_delete_subservice").click(function(){
    	var id = $(this).data("id");
    	var status = $(this).data("status");
    	var url = $(this).data("url");
    	var tr = $(this).parents("tr");
    	var btn = $(this);
    	if(status == 0)
    	{
    		if(confirm("Apakah Anda yakin ingin menghapus data ini?"))
    		{
    			btn.data("status", 1);
	    		$.get(url + "/" + id, function(ret){
	    			var obj = $.parseJSON(ret);
	    			if(obj.status == true)
	    			{
	    				tr.remove();
	    			} else {
	    				btn.data("status", 0);
	    			}

	    			alert(obj.message);
	    		});
    		}
    	} 
    });
});