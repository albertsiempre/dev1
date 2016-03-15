@section('search_result')
<?php
	if(isset($result) && $result != null)
	{
		foreach($result as $data)
		{
			?>
				<tr>
					<td>{{ $data["user_id"] }}</td>
					<td>{{ $data["email"] }}</td>
					<td>{{ $data["first_name"] }}</td>
					<td>{{ $data["phone"] }}</td>
					<td>{{ $data["active_request"] }}/{{ $data["total_request"] }}</td>
					<td>
						<span class="pull-left">
							{{ date("d M Y", strtotime($data["last_update"])) }}
						</span>
						<?php
							if($data["is_sent"])
							{
								?>
									<!-- <div class="__success_box_small">
										<i class="fa fa-check"></i>
									</div> -->
								<?php
							}
						?>
					</td>
					<td>
						<a class="__action_btn detail-popup" data-reqid="{{ $data['req_id'] }}" data-note="{{ $data['note'] }}" href="{{ URL::Route(GROUP_INTERNAL . '.detail_request') }}" title="View Detail">
							<i class="fa fa-eye"></i> View Details
						</a>
						<?php
							if(isset($data['new_request']) && $data['new_request'] > 0)
							{
								?>
									<a class="__action_btn send_popup" href="{{ URL::Route(GROUP_INTERNAL . '.send_request') }}" title="Send">
										<i class="fa fa-envelope"></i> Send
									</a>
								<?php
							}
						?>
					</td>
				</tr>
			<?php
		}
	}
?>

<script>
	$("body").ready(function(){
		var SearchFrom = $("#search-box").find("form").serialize();
		$(".send_popup").magnificPopup({
			type: 'ajax',
			ajax: {
				settings: {
					type: 'POST'
				}
			},
			closeOnBgClick: false,
			enableEscapeKey: false,
			callbacks: {
				beforeOpen: function() {
					el = this.st.el;
					tr = el.parents("tr");
					user_id = tr.find("td:eq(0)").text();
					this.st.ajax.settings.data = {
		                "id" : user_id,
						"_token" : "<?= csrf_token(); ?>"
		            }
			    },
			    ajaxContentAdded: function() {
					$("#_frm_user_id").val(user_id);
				},
				beforeClose: function() {
					if(needRefresh)
					{
						var url         = "<?php echo URL::route(GROUP_INTERNAL . '.free_dvd')?>?"+SearchFrom;
						window.location.href = url;
					}
				}
			}
		});

		$(".total_order").html("<?= isset($total_checkout) ? $total_checkout : '0'; ?>");
		$(".btn_checkout").attr("disabled", false);

		var el, tr, user_id, email, total_request, active_request;
		$('.detail-popup').magnificPopup({
			type: 'ajax',
			ajax: {
				settings: {
					type: 'POST'
				}
			},
			closeOnBgClick: false,
			callbacks: {
				beforeOpen: function() {
			      	el = this.st.el;
					tr = el.parents("tr");
					user_id = tr.find("td:eq(0)").text();
					email = tr.find("td:eq(1)").text();
					total_request = tr.find("td:eq(4)").text();
					active_request = total_request.split("/")[0];
					total_request = total_request.split("/")[1];
					note = el.data("note");
					req_id = el.data("reqid");
					this.st.ajax.settings.data = {
		                "id" : user_id,
						"_token" : "<?= csrf_token(); ?>"
		            }
			    },
				ajaxContentAdded: function() {
					$("#_user_id").html(user_id);
					$("#_user_email").html(email);
					$("#_user_current_request").html(active_request);
					$("#_user_total_request").html(total_request);
					$("._my_textarea").html(note);
					$("input[name='req_id']").val(req_id);
				},
				beforeClose: function() {
					console.log("need refresh = " + needRefresh);
					if(needRefresh)
					{
/*						var debug		= "http://devinternal.qeon.co.id/dvd?"+SearchFrom;
						console.log(debug);*/
						var url         = "<?php echo URL::route(GROUP_INTERNAL . '.free_dvd')?>?"+SearchFrom;
						window.location.href = url;
					}
				}
			}

		});
	});
</script>

<style>
	.__action_btn {
		margin-right: 10px;
		text-decoration: none;
	}
</style>
@stop