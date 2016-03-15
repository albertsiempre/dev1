@section('search_result')

<?php
	if(isset($result) && $result != null)
	{
		foreach($result as $data)
		{
			?>
				<tr>
					<td>{{ $data['date'] }}</td>
					<td>{{ $data["person_name"] }}</td>
					<td>{{ $data["name"] }}</td>
					<td>{{ $data['province_name'] }}</td>
					<td>{{ $data["city_name"] }}</td>
					<td>{{ $data["email"] }}</td>
					<td>{{ $data["phone"] }}</td>
					<td>{{ $data["message"] }}</td>
				</tr>
			<?php
		}
	}
?>

@stop