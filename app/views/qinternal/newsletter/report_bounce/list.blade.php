@section('search_result')
<?php
	if(isset($result) && $result != null)
	{ $no = 1;
		foreach($result as $data)
		{
			?>
				<tr>
                    <td>{{ $no }}</td>
					<td>{{ isset($data['date']) ? date('d-m-Y', strtotime($data['date'])) : '-' }}</td>
					<td style="text-align: center;">{{ isset($data['n']) ? $data['n'] : '-' }}</td>
				</tr>
			<?php $no++;
		}
	}
?>

<style>
	.__action_btn {
		margin-right: 10px;
		text-decoration: none;
	}
</style>
@stop