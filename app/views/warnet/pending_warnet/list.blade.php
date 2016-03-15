@section('search_result')

<?php
	if(isset($result) && $result != null)
	{
		foreach($result as $data)
		{
			?>
				<tr>
					<td>{{ $data["id"] }}</td>
					<td>{{ $data["name"] }}</td>
					<td>{{ $data["email"] }}</td>
					<td>{{ $data["phone"] }}</td>
					<td>{{ $data["address"] }}</td>
					<td>{{ $data["city"]['name'] }}</td>
					<td>{{ $data["city"]['province']['name'] }}</td>
					<td>{{ date("d M Y", strtotime($data['start_time'])) }}</td>
					<td>
						<a href="{{ $url_single . '/' . $data['id'] }}" title="View" class="single-popup-data" role="button">
							<i class="icon-eye-open"></i> Preview
						</a>
					</td>
				</tr>
			<?php
		}
	}
?>

<script type="text/javascript">
	$("body").ready(function(){
		$(".single-popup-data").magnificPopup({
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
</script>

@stop