@section('search_result')
<?php
	if(is_array($result) && !empty($result))
	{
		$total_request = 0;
		$latest_period = null;
		$latest_province = null;

		$show_subtotal_periode = count($result) > 1 && isset($inputs['city_id']) && $inputs['city_id'] == 0 ? true : false;
		$show_subtotal_city = isset($inputs['city_id']) && $inputs['city_id'] == 0 ? true : false;

		$page_html = null;
		$html = null;

		foreach($result as $data)
		{
			$periode = true;
			$inner_counter = 0;

			$html = null;
			foreach($data['data'] as $inner)
			{
				if($periode)
				{
					$periode = false;
					$latest_period = isset($data['date']) ? $data['date'] : '';
				}

				$province = true;
				$province_count = 0;
				ob_start();
				foreach($inner['data'] as $in)
				{
					$total_request += isset($in['count']) ? $in['count'] : 0;
					$inner_counter += isset($in['count']) ? $in['count'] : 0;
					$province_count += isset($in['count']) ? $in['count'] : 0;

					if($province)
					{
						$province = false;
						$latest_province = isset($inner['name']) ? $inner['name'] : '';
					}

					?>
						<tr>
							<td style="border: none;"></td>
			                <td style="border: none;"><?= isset($in['city_name']) ? $in['city_name'] : ''; ?></td>
			                <td style="border: none;"><?= isset($in['count']) ? $in['count'] : ''; ?></td>
			                <td style="border: none;"><?= isset($in['status']['new']) ? $in['status']['new']['count'] . ' - ' . $in['status']['new']['percent'] : ''; ?></td>
			                <td style="border: none;"><?= isset($in['status']['checkout']) ? $in['status']['checkout']['count'] . ' - ' . $in['status']['checkout']['percent'] : ''; ?></td>
			                <td style="border: none;"><?= isset($in['status']['canceled']) ? $in['status']['canceled']['count'] . ' - ' . $in['status']['canceled']['percent'] : ''; ?></td>
			                <td style="border: none;"><?= isset($in['status']['finished']) ? $in['status']['finished']['count'] . ' - ' . $in['status']['finished']['percent'] : ''; ?></td>
						</tr>
					<?php
				}

				$head_province = "<tr style='background: #F6F6F6; border-bottom: 1px solid #DDD; color: #4e4e4e; font-weight: bold; text-shadow: 1px 1px 0px #fff;'><td></td><td>" . $latest_province . "</td><td colspan='5'>" . $province_count . "</td></tr>";
				$htmls = ob_get_clean();
				$html .= $head_province . $htmls;
			}

			$head_period = "<tr style='background: #DDDDDD; font-weight: bold;'><td>" . $latest_period . "</td><td></td><td colspan='5'>" . $inner_counter . "</td></tr>";
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