@section('search_result')
	<?php
		if(isset($result) && $result != null)
		{
			foreach($result as $res)
			{
				?>
					<tr>
						<td>{{ $res['id'] }}</td>
						<td {{ $res['is_created_by_admin'] == true ? "class='__isAdmin'" : null; }}>{{ $res['user_name'] }}</td>
						<td>{{ $res['email'] }}</td>
						<td>{{ $res['service']['name'] }}</td>
						<td>
							<div class='__ellipsis'>
								{{ strip_tags($res['description']) }}
							</div>
							<button class='__readMoreAnswer btn'>More</button>
						</td>
						<td>
							{{ date("d-m-Y", strtotime($res['created_date'])) }}
							<br/>
							{{ date("H:i:s", strtotime($res['created_date'])) }}
						</td>
						<td style="text-align: center; vertical-align: middle;">
							<?php
								//echo $res['statusticket']['id'] . ' ' . $res['statusticket']['name'];
								switch($res['statusticket']['id'])
								{
									case 12:
										?>
											<span class="badge badge-warning"><?= $res['statusticket']['name']; ?></span>
										<?php
										break;
									case 2:
										?>
											<span class="badge badge-success"><?= $res['statusticket']['name']; ?></span>
										<?php
										break;
									case 11:
										//solution suggested
										?>
											<span class="badge badge-info"><?= $res['statusticket']['name']; ?></span>
										<?php
										break;
									case 14:
										//need more info
										?>
											<span class="badge badge-info"><?= $res['statusticket']['name']; ?></span>
										<?php
										break;
									case 10:
										//feedback received
										?>
											<span class="badge badge-info" style="background: #e74c3c"><?= $res['statusticket']['name']; ?></span>
										<?php
										break;
									case 1:
										?>
											<span class="badge badge-inverted"><?= $res['statusticket']['name']; ?></span>
										<?php
										break;

								}
							?>
						</td>
						<td>
							<a href="{{ $url_edit . '/' . $res['id'] }}"
								title="Edit"
								class="edit-ticket-data"
								role="button"
								data-tid="{{ $res['id'] }}">
								<i class="icon-inbox"></i> Answer
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
			vertical-align: middle !important;
		}

		.__isAdmin {
			font-weight: bold !important;
			color: #3399FF;
		}
	</style>

	<script type="text/javascript">
		$(".__readMoreAnswer").click(function(){
			var td = $(this).parent("td");
			var textBox = td.find(".__ellipsis");
			textBox.removeClass("__ellipsis");
			$(this).remove();
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

		$('.edit-ticket-data').magnificPopup({
			type: 'ajax',
			closeOnBgClick: false,
			callbacks: {
				ajaxContentAdded: function() {
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
					$("._conv_container").animate({ scrollTop: $("._conv_container")[0].scrollHeight }, 1000);
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
	</script>
@stop