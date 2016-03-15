@section('search_result')

<?php
	if(isset($result) && $result != null)
	{
		foreach($result as $data)
		{
			?>
				<tr>
					<?php
						if(isset($isSales) && $isSales)
						{
							?>
								<td>{{ $data["name"] }}</td>
								<td>{{ $data["address"] }}</td>
								<td>{{ $data["city"]['name'] }}</td>
								<td>{{ $data["city"]['province']['name'] }}</td>
								<td>
									<a href="{{ $url_edit . '/' . $data['id'] }}" title="Edit" class="edit-popup-data" role="button">
										<i class="icon-pencil"></i> Edit
									</a>
								</td>
							<?php
						} else {
							?>
								<td>{{ $data["id"] }}</td>
								<td>{{ $data["name"] }}</td>
								<td>{{ $data["email"] }}</td>
								<td>{{ $data["phone"] }}</td>
								<td>{{ $data["address"] }}</td>
								<td>{{ $data["city"]['name'] }}</td>
								<td>{{ $data["city"]['province']['name'] }}</td>
								<td>{{ date("d M Y", strtotime($data['start_time'])) }}</td>
								<td>
									<a href="{{ $url_edit . '/' . $data['id'] }}" title="Edit" class="edit-popup-data" role="button">
										<i class="icon-pencil"></i> Edit
									</a>
									<a href="javascript:void(0);" style="margin-left: 5px;" data-url="{{ $url_del . '/' . $data['id'] }}" data-status="0" title="Delete" class="_btn_delete_warnet" role="button">
                                        <i class="icon-trash"></i> Delete
                                    </a>
								</td>
							<?php
						}
					?>
				</tr>
			<?php
		}
	}
?>

<script type="text/javascript">
	$("body").ready(function(){
		$(".edit-popup-data").magnificPopup({
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

		$("._btn_delete_warnet").click(function(){
			var url = $(this).data('url');
			var btn = $(this);
			var status = btn.data("status");
			if(status == 0)
			{
				if(confirm("Apakah Anda yakin ingin menghapus data ini?"))
				{
					btn.data("status", 1);
					$.get(url, function(ret){
						var obj = $.parseJSON(ret);
						if(obj.status == true)
						{
							btn.parents("tr").remove();
						} else {
							btn.data("status", 0);
						}

						alert(obj.message);
					});
				}
			}
		});
	});
</script>

@stop