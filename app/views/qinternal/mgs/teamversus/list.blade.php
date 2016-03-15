@section('search_result')
<?php
	if(isset($result) && $result != null)
	{ $no = 1;
		foreach($result as $data)
		{
			?>
				<tr>
					<td>{{ $no }}</td>
					<td>{{ $data["team1"]["name"] }}</td>
					<td>{{ $data["team2"]["name"] }}</td>
					<td>{{ date("Y-m-d", strtotime($data["start_date"])) }}</td>
					<td>{{ date("Y-m-d", strtotime($data["end_date"])) }}</td>
					<td>
						<a class="__action_btn btn-edit-event"  href="{{ URL::Route(GROUP_INTERNAL . '.form.teamversus', $data['id']) }}" title="Edit">
							<i class="icon-pencil"></i> Edit
						</a> 
<!--                                            | 
						<a class="__action_btn" href="{{ URL::Route(GROUP_INTERNAL . '.form.teamversus') }}" title="Delete">
							<i class="icon-remove"></i> Delete
						</a>-->
					</td>
				</tr>
			<?php $no++;
		}
	}
?>

<script>        
        setMagnificEdit(); 
</script>

<style>
	.__action_btn {
		margin-right: 10px;
		text-decoration: none;
	}
</style>
@stop