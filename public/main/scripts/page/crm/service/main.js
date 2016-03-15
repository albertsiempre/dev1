$("body").ready(function(){
	$(".btn-add-category").magnificPopup({
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

    $("._btn_edit_service").magnificPopup({
    	type: 'ajax',
    	closeOnBgClick: false,
    	callbacks: {
    		ajaxContentAdded:function(){
    			var el = this.st.el;
    			var id = el.data("id");
                var gid = el.data("groupid");
                var cid = el.data("gameid");
    			var tr = el.parents("tr");
    			var name = tr.find("td:eq(1)").html();
    			$("input[name='id']").val(id);
    			$("input[name='name']").val(name);
                $("select[name='group_id']").val(gid);
                if(cid != null) $("select[name='category_id']").val(cid);
    			$(".career-notif").parent("div").remove();
    			$(".block-heading").html("Edit Service");
    		},
			beforeClose: function() {
				if(needRefresh)
				{
					location.reload();
				}
			}
    	}
    });

    $("._btn_delete_service").click(function(){
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