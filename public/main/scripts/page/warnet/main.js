$("body").ready(function(){
	$('.ajax-popup').magnificPopup({
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
});