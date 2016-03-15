@section('search_result')
<?php
	if(isset($result) && $result != null)
	{ $no = 1;
		foreach($result as $data)
		{
			?>
				<tr>
                                        <td>{{ $no }}</td>
					<td>{{ $data["name"] }}</td>
					<td>{{ $data["event"]["full_name"] }}</td>
					<td>{{ isset($data["team"]["name"]) && $data["team"] != null ? $data["team"]["name"] : '-' }}</td>
					<td>{{ number_format($data["value"], 0, ',', '.') }}</td>
					<!--<td>{{ $data["detail"] }}</td>-->
					<td>
						<a class="__action_btn btn-edit-event"  href="{{ URL::Route(GROUP_INTERNAL . '.form.winner', $data['id']) }}" title="Edit">
							<i class="icon-pencil"></i> Edit
						</a> 
<!--                                            | 
						<a class="__action_btn" href="{{ URL::Route(GROUP_INTERNAL . '.form.winner') }}" title="Delete">
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