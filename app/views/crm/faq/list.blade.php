@section('search_result')
	<?php
		if(isset($result) && $result != null)
		{
			foreach($result as $res)
			{
				?>
					<tr>
						<td>{{ $res['service']['name'] }}</td>
						<td>{{ $res['subservice']['name'] }}</td>
						<td>{{ $res['question'] }}</td>
						<td>
							<div class='__ellipsis'>
								{{ strip_tags($res['answer']) }}
							</div>
							<button class='__readMoreAnswer btn'>More</button>
						</td>
						<td>
							<div class="_wrap_btn _up">
						        <div class="_left _centerContent _boxes">
						            {{ $res['score']['positive'] }}
						        </div>
						        <div class="_right">
						           <div data-url="{{ isset($url_score) ? $url_score . '/' . $res['id'] . '/' . '1' : '' }}" class="_likeBtn _btnUp <?= !$res['is_can_vote'] ? '_disabled' : ''; ?>">
						               <i class="icon-thumbs-up"></i>
						            </div>
						        </div>
						    </div>
						    
						    <div class="_wrap_btn _down">
						        <div class="_left _centerContent _boxes">
						            {{ $res['score']['negative'] }}
						        </div>
						        <div class="_right">
						           <div data-url="{{ isset($url_score) ? $url_score . '/' . $res['id'] . '/' . '0' : '' }}" class="_likeBtn _btnDown <?= !$res['is_can_vote'] ? '_disabled' : ''; ?>">
						               <i class="icon-thumbs-down"></i>
						            </div>
						        </div>
						    </div>
						</td>
						<td>{{ $res['is_public'] == true ? 'Ya' : 'Tidak' }}</td>
						<td>
							<a href="{{ $url_add }}"
								title="Edit"
								class="edit-faq-data"
								role="button"
								data-qt="{{ $res['question'] }}"
								data-ctid="{{ $res['service']['id'] }}"
								data-sctid="{{ $res['subservice']['id'] }}"
								data-isp="{{ $res['is_public'] }}"
								data-order="{{ $res['order'] }}"
								data-asw="{{ htmlentities($res['answer']) }}"
								data-fid="{{ $res['id'] }}">
								<i class="icon-pencil"></i> Edit
							</a>
							<br/>
							<a href="javascript:void(0);"
								title="Edit"
								class="del-faq-data"
								role="button"
								data-fid="{{ $res['id'] }}"
								data-status="0"
								data-url="{{ isset($url_del) ? $url_del : '' }}">
								<i class="icon-trash"></i> Delete
							</a>
						</td>
					</tr>
				<?php
			}
		}
	?>

	<style>
		.__ellipsis {
			width: 100%;
			white-space: nowrap;
			text-overflow: ellipsis;
			display: block;
			overflow: hidden;
			margin-bottom: 10px;
		}

		td {
			word-break: normal !important;
		}

		._wrap_btn {
		    display: block;
		    position: relative;
		    width: 100%;
		    overflow: hidden;
		    min-height: 1px;
		    height: auto;
		    margin-bottom: 5px;
		    border-radius: 3px;
		    -webkit-border-radius: 3px;
		    color: #4e4e4e;
		}

		._disabled {
			opacity: 0.5;
			-webkit-opacity
		}

		._left {
		    float: left;
		}

		._right {
		    float: right;
		}

		._centerContent {
		    text-align: center;
		}

		._boxes {
		    padding: 5px;
		    text-align: center;
		    font-weight: bold;
		    font-size: 14px;
		    width: 55%;
			text-overflow: ellipsis;
			white-space: nowrap;
			overflow: hidden;
		}

		._likeBtn {
		    position: relative;
		    padding: 5px;
		    cursor: pointer;
		    color: #fefefe;
		    font-size: 12px;
		}

		._up {
			border: 1px solid #5A8C28;
		}

		._down {
			border: 1px solid #AA3333;
		}

		._btnUp {
			background: #6CA536;
			border-left: 1px solid #5A8C28;
		}

		._btnDown {
			background: #C94444;
		}
	</style>

	<script type="text/javascript">
		$(".__readMoreAnswer").click(function(){
			var td = $(this).parent("td");
			var textBox = td.find(".__ellipsis");
			textBox.removeClass("__ellipsis");
			$(this).remove();
		});

		// $(".edit-faq-data").magnificPopup({
		// 	type:'ajax',
		// 	closeOnBgClick:false,
		// 	callbacks: {
		// 		beforeClose: function() {
		// 			console.log("need Refresh = " + needRefresh);
		// 			if(needRefresh)
		// 			{
		// 				location.reload();
		// 			}
		// 		}
		// 	}
		// });

		$("._btnUp").click(function(){
			var btn = $(this);
			var td = btn.parents("td");
			var up = td.find("._up").find("._boxes");
			var btm = td.find("._down").find("._boxes");
			var btn_down = td.find("._btnDown");

			if(!btn.hasClass("_disabled"))
			{
				up.html(parseInt(up.html()) + 1);
				//btm.html(parseInt(btm.html()) - 1);

				btn.toggleClass("_disabled");
				btn_down.toggleClass("_disabled");

				var url = btn.data("url");
				$.get(url, function(e)
				{
					var obj = $.parseJSON(e);
					if(obj.status == false)
					{
						alert(obj.message);
					}
				});
			}
		});

		$("._btnDown").click(function(){
			var btn = $(this);
			var td = btn.parents("td");
			var up = td.find("._up").find("._boxes");
			var btm = td.find("._down").find("._boxes");
			var btn_up = td.find("._btnUp");

			if(!btn.hasClass("_disabled"))
			{
				//up.html(parseInt(up.html()) - 1);
				btm.html(parseInt(btm.html()) + 1);

				btn.toggleClass("_disabled");
				btn_up.toggleClass("_disabled");

				var url = btn.data("url");
				$.get(url, function(e)
				{
					var obj = $.parseJSON(e);
					if(obj.status == false)
					{
						alert(obj.message);
					}
				});
			}
		});

		$(".del-faq-data").click(function(){
			var id = $(this).data("fid");
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

		var el, tr, faq_id, category_id, subcategory_id, question, is_public, answer, order;
		$('.edit-faq-data').magnificPopup({
			type: 'ajax',
			closeOnBgClick: false,
			callbacks: {
				beforeOpen: function() {
			      	el = this.st.el;

					faq_id = el.data("fid");
					category_id = el.data("ctid");
					subcategory_id = el.data("sctid");
					question = el.data("qt");
					is_public = el.data("isp");
					answer = el.data("asw");
					order = el.data("order");
			    },
				ajaxContentAdded: function() {
					$("#__category_id").val(category_id);
					$("#__subcategory_id").val(subcategory_id);
					$("#__question").val(question);
					if(is_public == "1") $("#__is_public").attr("checked", true);
					$("#txtDescription").val(answer);
					$("#__order").val(order);
					$("input[name='faq_id']").val(faq_id)
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
	</script>
@stop