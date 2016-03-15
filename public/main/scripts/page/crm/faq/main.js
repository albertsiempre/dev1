$("body").ready(function(){
	$(".btn-add-faq").magnificPopup({
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
				$(".career-notif").parent("div").remove();
        	},
            beforeOpen: function() {
				
			},
			beforeClose: function() {
				if(CKEDITOR.instances.txtDescription)
				{
					CKEDITOR.instances.txtDescription.destroy();
				}

				if(needRefresh)
				{
					location.reload();
				}
			}
        }
    });
});