@section('search_result')
<?php
	if(is_array($result) && !empty($result))
	{
		$latest_date = null;
		foreach($result as $data)
		{
			if($latest_date != $data['date'])
			{
				$periode = $data['date'];
				?>
					<tr>
						<td><?= isset($data['date']) ? $data['date'] : ''; ?></td>
				<?php
			} else {
				?>
					<tr>
						<td></td>
				<?php
			}

			$service = true;
			foreach($data['data'] as $in)
			{
				if($service)
				{
					$service = false;
					?>
							<td><?= isset($in['service']['name']) ? $in['service']['name'] : ''; ?></td>
							<td><?= date("d M Y (H:i:s)", strtotime($in['created_date'])); ?></td>
							<td><?= $in['username']; ?></td>
							<td><?= $in['email']; ?></td>
							<td><?= $in['feedback']; ?></td>
						</tr>
					<?php
				} else {
					?>
						<tr>
							<td></td>
							<td></td>
							<td><?= date("d M Y (H:i:s)", strtotime($in['created_date'])); ?></td>
							<td><?= $in['username']; ?></td>
							<td><?= $in['email']; ?></td>
							<td><?= $in['feedback']; ?></td>
						</tr>
					<?php
				}
			}
		}
	}
?>
@stop