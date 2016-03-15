@section('search_result')
<?php
	if(is_array($result) && !empty($result))
	{
		$total_question = 0;
		$total_user_vote = 0;
		$total_internal_vote = 0;
		$avg_user_pos = 0;
		$avg_user_neg = 0;
		$avg_internal_pos = 0;
		$avg_internal_neg = 0;

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
							<td><?= $in['service']['name']; ?></td>
							<td><?= $in['subservice']['name']; ?></td>
							<td><?= $in['question']; ?></td>
							<td style="text-align: right;">
								<?= $in['type']['user']['positive']; ?> (<?= $in['type']['user']['percent']['positive']; ?>%)
							</td>
							<td style="text-align: right;">
								<?= $in['type']['user']['negative']; ?> (<?= $in['type']['user']['percent']['negative']; ?>%)
							</td>
							<td style="text-align: right;">
								<?= $in['type']['admin']['positive']; ?> (<?= $in['type']['admin']['percent']['positive']; ?>%)
							</td>
							<td style="text-align: right;">
								<?= $in['type']['admin']['negative']; ?> (<?= $in['type']['admin']['percent']['negative']; ?>%)
							</td>
						</tr>
					<?php
				} else {
					?>
						<tr>
							<td></td>
							<td></td>
							<td><?= $in['subservice']['name']; ?></td>
							<td><?= $in['question']; ?></td>
							<td style="text-align: right;">
								<?= $in['type']['user']['positive']; ?> (<?= $in['type']['user']['percent']['positive']; ?>%)
							</td>
							<td style="text-align: right;">
								<?= $in['type']['user']['negative']; ?> (<?= $in['type']['user']['percent']['negative']; ?>%)
							</td>
							<td style="text-align: right;">
								<?= $in['type']['admin']['positive']; ?> (<?= $in['type']['admin']['percent']['positive']; ?>%)
							</td>
							<td style="text-align: right;">
								<?= $in['type']['admin']['negative']; ?> (<?= $in['type']['admin']['percent']['negative']; ?>%)
							</td>
						</tr>
					<?php
				}

				$total_question++;
				$total_user_vote += $in['type']['user']['positive'] + $in['type']['user']['negative'];
				$total_internal_vote += $in['type']['admin']['positive'] + $in['type']['admin']['negative'];
				$avg_user_pos += $in['type']['user']['positive'];
				$avg_user_neg += $in['type']['user']['negative'];
				$avg_internal_pos += $in['type']['admin']['positive'];
				$avg_internal_neg += $in['type']['admin']['negative'];
			}
		}

		?>
			<script type="text/javascript">
				$("._tq").html("<?= $total_question; ?>");
				$("._tuv").html("<?= $total_user_vote; ?>");
				$("._tiv").html("<?= $total_internal_vote; ?>");
				$("._rup").html("<?= $total_user_vote > 0 ? round($avg_user_pos / $total_user_vote * 100) : 0; ?>%");
				$("._run").html("<?= $total_user_vote > 0 ? round($avg_user_neg / $total_user_vote * 100) : 0; ?>%");
				$("._rip").html("<?= $total_internal_vote > 0 ? round($avg_internal_pos / $total_internal_vote * 100) : 0; ?>%");
				$("._rin").html("<?= $total_internal_vote > 0 ? round($avg_internal_neg / $total_internal_vote * 100) : 0; ?>%");
			</script>
		<?php
	}
?>
@stop