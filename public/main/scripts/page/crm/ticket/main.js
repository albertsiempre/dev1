$("body").ready(function(){
	$(".btn-add-ticket").magnificPopup({
        type:'ajax',
        closeOnBgClick:false,
        callbacks: {
        	ajaxContentAdded: function() {
				$("#txtDescription").val("");
                var el = this.st.el;
                var urlckfinder = $("#urlCKFinder").html();
                var editor = CKEDITOR.replace('txtDescription',{
                    toolbar: [
                        { name: 'insert', items: [ 'Image' ] },
                        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] }
                    ],
                    filebrowserBrowseUrl : urlckfinder + '/ckfinder.html',
                    filebrowserImageBrowseUrl : urlckfinder + '/ckfinder.html?type=Images',
                    filebrowserUploadUrl : urlckfinder + '/core/connector/php/connector.php?command=QuickUpload&type=Files',
                    filebrowserImageUploadUrl : urlckfinder + '/core/connector/php/connector.php?command=QuickUpload&type=Images'
                });
				$("input[name='id']").remove();
        	},
			beforeClose: function() {
				if(CKEDITOR.instances.txtDescription)
                {
                    CKEDITOR.instances.txtDescription.destroy();
                }

                if(needRefresh)
				{
                    $("._doSearch").click();
				}
			}
        }
    });

    $("._btn_edit_ticketsource").magnificPopup({
    	type: 'ajax',
    	closeOnBgClick: false,
    	callbacks: {
    		ajaxContentAdded:function(){
    			var el = this.st.el;
    			var id = el.data("id");
    			var tr = el.parents("tr");
    			var name = tr.find("td:eq(1)").html();
    			$("input[name='id']").val(id);
    			$("input[name='source']").val(name);
    			$(".career-notif").parent("div").remove();
    			$(".block-heading").html("Edit Ticket Source");
    		},
			beforeClose: function() {
				if(needRefresh)
				{
                    $("._doSearch").click();
				}
			}
    	}
    });

    $("._btn_delete_ticketsource").click(function(){
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