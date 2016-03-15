@section('search_result')
<?php
	if(is_array($result) && !empty($result))
	{
		//print_r($result); die();
		$total_request = 0;
		$latest_province = null;
		$page_html = null;
		$html = null;

		$paid_user = 0;
		$active_user = 0;
		$total_qash = 0;
		$total_bonus = 0;
		$total_spend = 0;
		$total_playtime = 0;
		$counter = 0;
		foreach($result as $data)
		{
			$province = isset($data['province_name']) ? $data['province_name'] : '-';
			$city = isset($data['city_name']) ? $data['city_name'] : '-';
			if($latest_province != $province)
			{
				$latest_province = $province;
				?>
					<tr style='background: #F6F6F6; border-bottom: 1px solid #DDD; color: #4e4e4e; font-weight: bold; text-shadow: 1px 1px 0px #fff;'>
						<td colspan='6'><?= $latest_province; ?></td>
					</tr>
				<?php
			}

			?>
				<tr>
					<td style="border: none;"><?= isset($data['city_name']) ? $data['city_name'] : '-'; ?></td>
					<td style="border: none;"><?= isset($data['name']) ? $data['name'] : '-'; ?></td>
					<td style="border: none;"><?= isset($data['total_qash']) && $data['total_qash'] != null ? number_format($data['total_qash'], 0, ',', '.') : '0'; ?></td>
					<td style="border: none;"><?= isset($data['bonus_qash']) && $data['bonus_qash'] != null ? number_format($data['bonus_qash'], 0, ',', '.') : '0'; ?></td>
					<td style="border: none;"><?= isset($data['total_spend']) && $data['total_spend'] != null ? number_format($data['total_spend'], 0, ',', '.') : '0'; ?></td>
					<td style="border: none;"><?= isset($data['second']) && $data['second'] != null ? get_hour($data['second']) : '0'; ?>h</td>
				</tr>
			<?php

			if(isset($data["total_qash"]) && $data["total_qash"] > 0) 
			{
				$total_qash += $data['total_qash'];
				$paid_user++;
			}

			if(isset($data['bonus_qash']) && $data['bonus_qash'] > 0) $total_bonus += $data['bonus_qash'];
			if(isset($data['total_spend']) && $data['total_spend'] > 0) $total_spend += $data['total_spend'];

			if(isset($data['second']) && $data['second'] > 0)
			{
				$active_user++;
				$total_playtime += $data['second'];
			}

			$counter++;
			if($counter == count($result))
			{
				?>
					<tr style='background: #F4f4f4; border-bottom: 1px solid #DDD; color: #4e4e4e; font-weight: bold; text-shadow: 1px 1px 0px #fff;'>
						<td colspan='2' align="right">TOTAL</td>
						<td><?= number_format($total_qash, 0, ',', '.'); ?></td>
						<td><?= number_format($total_bonus, 0, ',', '.'); ?></td>
						<td><?= number_format($total_spend, 0, ',', '.'); ?></td>
						<td><?= get_hour($total_playtime); ?>h</td>
					</tr>

					<tr style='background: #F4f4f4; border-bottom: 1px solid #DDD; color: #4e4e4e; font-weight: bold; text-shadow: 1px 1px 0px #fff;'>
						<td colspan='6'>
							Total Active User : <?= $active_user; ?><br/>
							Total Paid User : <?= $paid_user; ?>
						</td>
					</tr>
				<?php
			}
		}
	} else {
		?>
			<tr style='background: #F8F8F8; border-bottom: 1px solid #DDD; color: #4e4e4e; font-size: 14px;'>
				<td colspan='6' style="text-align: center !important;">Data Tidak Ditemukan</td>
			</tr>
		<?php
	}

	function get_hour($second = 0)
	{
		return floor($second / 60 / 60);
	}
?>
@stop