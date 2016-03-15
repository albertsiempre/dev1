@section('search_result')
<?php
	//print_r($params);
	if(isset($result) && $result != null)
	{
		if(isset($result['result']) && !empty($result['result']))
		{
			foreach($result['result'] as $data)
			{
				?>
					<tr>
						<td>{{ isset($data["id"]) ? $data["id"] : '' }}</td>
						<td>{{ isset($data["title"]) ? $data["title"] : '' }}</td>
						<td>{{ date("d-m-Y h:i", strtotime($data["start_date"])) . "<br/>" . date("d-m-Y h:i", strtotime($data['end_date'])) }}</td>
						<td>
							<?php
								if(isset($data['categories']) && !empty($data['categories']))
								{
									?>
										<ul style="margin-left: 0px;">
											<?php
												foreach($data['categories'] as $category)
												{
													?>
														<li>{{ isset($category['category_name']) ? $category['category_name'] : '' }}</li>
													<?php
												}
											?>
										</ul>
									<?php
								}
							?>
						</td>
						<td>{{ isset($data['type']['name']) ? $data['type']['name'] : '' }}</td>
						<td style="text-align: center;">{{ $data['survey_id'] }}</td>
						<td>{{ $data['description'] }}</td>
						<td>{{ $data['label'] }}</td>
						<td><a href="{{ $data['link'] }}">{{ $data["link"] }}</a></td>
						<td style="text-align: center;">{{ $data['priority_level'] }}</td>
						<td>
							<a href="{{ URL::route(GROUP_INTERNAL . '.formAdd') . '/' . $data['id'] }}" title="Edit" class="edit-popup-data" role="button">
								<i class="icon-pencil"></i> Edit
							</a><br/>
							<a href="javascript:void(0);" data-status="0" style="margin-left: 5px;" data-url="{{ URL::route(GROUP_INTERNAL . '.del_widget') . '/' . $data['id'] }}" title="Delete" class="_btn_delete_widget" role="button">
                                <i class="icon-trash"></i> Delete
                            </a>
						</td>
					</tr>
				<?php
			}
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

		$("._btn_delete_widget").click(function(){
			var url = $(this).data('url');
			var btn = $(this);
			var tr = btn.parents("tr");
			var status = btn.data("status");
			if(status == 0)
			{
				if(confirm("Apakah Anda yakin ingin menghapus data ini?"))
				{
					tr.css("opacity", "0.5");
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
						tr.css("opacity", "1");
					});
				}
			}
		});
	});
</script>

@stop