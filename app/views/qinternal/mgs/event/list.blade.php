@section('search_result')
<?php
	if(isset($result) && $result != null)
	{ $no = 1;
		foreach($result as $data)
		{
			?>
				<tr>
					<td>{{ $no }}</td>
					<td><?php echo isset($data["is_finished"]) && $data["is_finished"] != 0 ? "<span style='color: red;'>". $data["name"] ."</span>" : $data["name"]?></td>
					<td>{{ $data["subtitle"] }}</td>
					<td>{{ date("Y-m-d", strtotime($data["start_date"])) }}</td>
					<td>{{ date("Y-m-d", strtotime($data["end_date"])) }}</td>
					<td>{{ isset($data["is_finished"]) && $data["is_finished"] != 0 ? '<span style="color: red;">Finished<span>' : '-'  }}</td>
					<td>
						<a class="__action_btn btn-edit-event"  href="{{ URL::Route(GROUP_INTERNAL . '.form.event', $data['id']) }}" title="Edit">
							<i class="icon-pencil"></i> Edit
						</a> 
<!--                                            | 
						<a class="__action_btn" href="{{ URL::Route(GROUP_INTERNAL . '.form.event') }}" title="Delete">
							<i class="icon-remove"></i> Delete
						</a>-->
					</td>
				</tr>
			<?php $no++;
		}
	}
?>

<script>
	$("body").ready(function(){
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
						location.reload();
					}
				}
			}
		});

//		$(".total_order").html("<?= isset($total_checkout) ? $total_checkout : '0'; ?>");
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
						location.reload();
					}
				}
			}

		});
	});
        
        setMagnificEdit(); 
</script>

<style>
	.__action_btn {
		margin-right: 10px;
		text-decoration: none;
	}
</style>
@stop