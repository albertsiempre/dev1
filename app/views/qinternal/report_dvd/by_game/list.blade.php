@section('search_result')
<?php
	if(is_array($result) && !empty($result))
	{
		$total_request = 0;
		$latest_date = null;

		$show_subtotal = count($result) > 1 && isset($inputs['game_id']) && $inputs['game_id'] == 0 ? true : false;
		
		$page_html = null;
		$html = null;

		foreach($result as $data)
		{
			$inner_counter = 0;
			$html = null;
			
			$province = true;

			ob_start();
			foreach($data['data'] as $in)
			{
				$total_request += isset($in['count']) ? $in['count'] : 0;
				$inner_counter += isset($in['count']) ? $in['count'] : 0;
				if($province)
				{
					$province = false;
					?>
						<tr>
							<td></td>
							<td><?= isset($in['game_name']) ? $in['game_name'] : ''; ?></td>
			                <td><?= isset($in['count']) ? $in['count'] : ''; ?></td>
			                <td><?= isset($in['status']['new']) ? $in['status']['new']['count'] . ' - ' . $in['status']['new']['percent'] : ''; ?></td>
			                <td><?= isset($in['status']['checkout']) ? $in['status']['checkout']['count'] . ' - ' . $in['status']['checkout']['percent'] : ''; ?></td>
			                <td><?= isset($in['status']['canceled']) ? $in['status']['canceled']['count'] . ' - ' . $in['status']['canceled']['percent'] : ''; ?></td>
			                <td><?= isset($in['status']['finished']) ? $in['status']['finished']['count'] . ' - ' . $in['status']['finished']['percent'] : ''; ?></td>
						</tr>
					<?php
				} else {
					?>
						<tr>
							<td></td>
							<td><?= isset($in['game_name']) ? $in['game_name'] : ''; ?></td>
			                <td><?= isset($in['count']) ? $in['count'] : ''; ?></td>
			                <td><?= isset($in['status']['new']) ? $in['status']['new']['count'] . ' - ' . $in['status']['new']['percent'] : ''; ?></td>
			                <td><?= isset($in['status']['checkout']) ? $in['status']['checkout']['count'] . ' - ' . $in['status']['checkout']['percent'] : ''; ?></td>
			                <td><?= isset($in['status']['canceled']) ? $in['status']['canceled']['count'] . ' - ' . $in['status']['canceled']['percent'] : ''; ?></td>
			                <td><?= isset($in['status']['finished']) ? $in['status']['finished']['count'] . ' - ' . $in['status']['finished']['percent'] : ''; ?></td>
						</tr>
					<?php
				}
			}

			$htmls = ob_get_clean();
			$html .= $htmls;

			$head_period = "<tr style='background: #DDDDDD; font-weight: bold;'><td>" . $data['date'] . "</td><td></td><td colspan='5'>" . $inner_counter . "</td></tr>";
			$page_html .= $head_period . $html;
		}
		
		echo $page_html;
		
		?>
			<tr style="background: #f4f4f4; font-weight: bold;">
				<td colspan="2">Total :</td>
				<td><?= $total_request; ?></td>
				<td colspan="4"></td>
			</tr>
		<?php
	}
?>
@stop