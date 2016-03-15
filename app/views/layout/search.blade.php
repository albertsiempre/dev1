@section('search_content')
	<div class="block">
		<p>
			<a href="#search-box" class="block-heading" data-toggle="collapse">Search</a>
		</p>
		<div id="search-box" class="block-body collapse in">
			{{ Form::open(array('url' =>isset($data_form['action']) ? $data_form['action'] : '')) }}
				<?php
					if(isset($data_form['form']))
					{
						foreach($data_form['form'] as $form)
						{
							if($form['type'] != 'checkbox')
							{
								?>
									<label>{{ isset($form['label']) ? $form['label'] : '' }}</label>
								<?php
							}
							switch($form['type'])
							{
								case "input":
									?>
										<input type="text" {{ isset($form['data']['value']) ? "value='" . $form['data']['value'] . "'" : "" }} {{ isset($form["data"]["class"]) ? "class='" . $form['data']['class'] . "'" : '' }} name="{{ isset($form['data']['name']) ? $form['data']['name'] : '' }}" class="input-xlarge" />
									<?php
									break;
								case "dropdown":
									?>
										<select 
										{{ isset($form['combo_options']['data_url']) ? 'data-url="' . $form['combo_options']['data_url'] . '"' : '' }} 
										{{ isset($form['combo_options']['data_target']) ? 'data-target="' . $form['combo_options']['data_target'] . '"' : '' }} 
										{{ isset($form['combo_options']['disable']) && $form['combo_options']['disable'] == true ? 'disable="disable"' : '' }} 
										{{ isset($form['combo_options']['is_multiple']) && $form['combo_options']['is_multiple'] == true ? ' multiple ' : '' }}
										name="{{ isset($form['data']['name']) ? $form['data']['name'] : '' }}" 
										class="input-xlarge {{ isset($form['combo_options']['class_name']) ? $form['combo_options']['class_name'] : '' }}">
											<?php
												foreach($form['combo_options']['data'] as $key => $val)
												{
													?>
														<option value="{{ $key }}" {{ isset($form['combo_options']['selected']) && $key == $form['combo_options']['selected'] ? 'selected' : '' }}>{{ $val }}</option>
													<?php
												}
											?>
										</select>
									<?php
									break;
								case 'checkbox':
									if(isset($form['check_options']))
									{
										?>
											<label>{{ isset($form['label']) ? $form['label'] : '' }}</label>
										<?php
										$dt_checks = $form['check_options']['data'];
										foreach($dt_checks as $key => $value)
										{
											?>
												<label><input type="checkbox" name="{{ isset($form['data']['name']) ? $form['data']['name'] : '' }}" value="{{ $key }}" /> {{ $value }}</label>
											<?php
										}
										?>
											<hr/>
										<?php
									} else {
										?>
											<label><input type="checkbox" name="{{ isset($form['data']['name']) ? $form['data']['name'] : '' }}" value="{{ isset($form['data']['value']) ? $form['data']['value'] : '' }}" /> {{ isset($form['label']) ? $form['label'] : '' }}</label>
										<?php
									}
									break;
							}
						}
					}
				?>
				<input type="hidden" name="QMS_t" value="{{ Request::cookie('QMS_c') }}" />
                <input type="hidden" name="page" value="1" id="page">
                <div class="btn-toolbar">
                    <button class="btn _doSearch" type="submit"><i class="icon-search"></i> Go</button>
                </div>
                <span id="loading-form"></span>
            </form>
		</div>
	</div>

	<script style="display: hidden;" type="text/qscript" id="tmp_month">
		<select name='start_month'>
			<?php
				for($i = 1; $i <= 12; $i++)
				{
					?>
						<option value='<?= $i; ?>'><?= date("M", strtotime(date("2014/" . $i . "/01"))); ?></option>
					<?php
				}
			?>
		</select>
	</script>

	<script style="display: hidden;" type="text/qscript" id="tmp_year">
		<select name='start_year'>
			<?php
				for($i = date("Y") - 2; $i <= date("Y"); $i++)
				{
					?>
						<option value='<?= $i; ?>'><?= $i; ?></option>
					<?php
				}
			?>
		</select>
	</script>

	{{ HTML::script('/main/scripts/ajaxform.js') }}
	{{ HTML::script('/main/scripts/bootstrap-datepicker.js') }}
	{{ HTML::style('/main/styles/datepicker.css') }}
	<script type="text/javascript">
	    var $form = $('#search-box form');
	    var $form_button = $('#search-box form button[type="submit"]');
	    var $form_loading = $('#loading-form');
	    $(function(){
	        $form.submit(function(){
	        	// $("input[name='page']").val("1");

	        	var canSubmit = true;
	        	if($("._start_date").length > 0 && $("._end_date").length > 0)
	        	{
	        		var start = new Date($("._start_date").val());
					var end = new Date($("._end_date").val());
					if(end < start)
				    {
				    	canSubmit = false;
				        alert("End Date harus lebih dari Start Date.");
				    }
	        	}

	        	if($("select[name='start_month']").length > 0 && $("select[name='end_month']").length > 0 && $("select[name='start_year']").length > 0 && $("select[name='end_year']").length > 0) {
	        		var start_date = $("select[name='start_year']").val() + "-" + $("select[name='start_month']").val() + "-01";
	        		var end_date = $("select[name='end_year']").val() + "-" + $("select[name='end_month']").val() + "-01";
	        		var start = new Date(start_date);
	        		var end = new Date(end_date);
	        		console.log(start_date, end_date);
	        		if(end < start)
				    {
				    	canSubmit = false;
				        alert("End Date harus lebih dari Start Date.");
				    }
	        	}

	        	if($("._start_date_act").length > 0 && $("._end_date_act").length > 0)
	        	{
	        		var start = new Date($("._start_date_act").val());
					var end = new Date($("._end_date_act").val());
					if(end < start)
				    {
				    	canSubmit = false;
				        alert("End Date Activity harus lebih dari Start Date Activity.");
				    }
	        	}

	        	if(canSubmit)
	        	{
		            $('#loading-form').html('<img src="<?= asset("/main/images/ajax-loader-small.gif"); ?>"> loading data..');
		            $form_button.attr('disabled',true);
		            $form.ajaxSubmit(function(result){
		                if(result!=null && result!=''){
		                    $('.table tbody').html(result);
		                    $('.ajax-popup-data').unbind().magnificPopup({
		                        type:'ajax',
		                        closeOnBgClick:false
		                    });
		                }else{
		                    $form_loading.html('<b style="color:red;">data not found.</b>');
		                    $form_button.attr('disabled',false);
		                }
		            });
		        }
	           return false;
	       	});

			$("._doSearch").click(function(){
				$("input[name='page']").val("1");
			});

	        <?php
	        	if(isset($data_form['doInit']))
	        	{
	        		if($data_form['doInit'] != false)
	        		{
	        			?>
	        				$form.submit();
	        			<?php
	        		}
	        	} else {
	        		?>
	        			$form.submit();
	        		<?php
	        	}
	        ?>
	    });

		// $("._start_date").datepicker();
		// $("._end_date").datepicker();

		var $start_date_el = $("._start_date");
		var $end_date_el = $("._end_date");
		var $start_date_act_el = $("._start_date_act");
		var $end_date_act_el = $("._end_date_act");

		var default_start = $("._start_date").val();
		var default_end = $("._end_date").val();

		if($("._start_date").length > 0)
		{
			$("._start_date").before("<div class='_start_container'></div>");
			$("._start_date").remove();
			$("._start_container").html($start_date_el);
			$start_date_el.datepicker({
				"format"	: "yyyy-mm-dd",
				"autoclose"	: true
			});
		}

		if($("._end_date").length > 0)
		{
			$("._end_date").before("<div class='_end_container'></div>");
			$("._end_date").remove();
			$("._end_container").html($end_date_el);
			$end_date_el.datepicker({
				"format"	: "yyyy-mm-dd",
				"autoclose"	: true
			});
		}

		if($("._start_date_act").length > 0)
		{
			$("._start_date_act").before("<div class='_start_act_container'></div>");
			$("._start_date_act").remove();
			$("._start_act_container").html($start_date_act_el);
			$start_date_act_el.datepicker({
				"format"	: "yyyy-mm-dd",
				"autoclose"	: true
			});
		}

		if($("._end_date_act").length > 0)
		{
			$("._end_date_act").before("<div class='_end_act_container'></div>");
			$("._end_date_act").remove();
			$("._end_act_container").html($end_date_act_el);
			$end_date_act_el.datepicker({
				"format"	: "yyyy-mm-dd",
				"autoclose"	: true
			});
		}

		var $combo_month_el = $("#tmp_month").html();
		var $combo_year_el = $("#tmp_year").html();

		$("._my_periode_combos").change(function(){
			var type = $(this).val();
			if(type == "monthly")
			{
				var $start_month = $($combo_month_el).attr("name", "start_month")[0];
				var $start_year = $($combo_year_el).attr("name", "start_year")[0];
				var $end_month = $($combo_month_el).attr("name", "end_month")[0];
				var $end_year = $($combo_year_el).attr("name", "end_year")[0];

				$("._start_container").html($start_month).append($start_year);
				$("._end_container").html($end_month).append($end_year);

				<?php
					$year = date("Y");
					$month = date("n");
					$last_month = date("n", strtotime("-1month"));
				?>

				$("select[name='start_month']").val("<?= $last_month; ?>");
				$("select[name='start_year']").val("<?= $year; ?>");
				$("select[name='end_month']").val("<?= $month; ?>");
				$("select[name='end_year']").val("<?= $year; ?>");
			} else {
				$("._start_container").html($start_date_el);
				$("._end_container").html($end_date_el);
				$start_date_el.datepicker({
					"format"	: "yyyy-mm-dd",
					"autoclose"	: true
				});
				$end_date_el.datepicker({
					"format"	: "yyyy-mm-dd",
					"autoclose"	: true
				});
			}
		});

	    $("._my_province_combos").change(function(){
	        console.log("FIRE!!");
	        $("input[name='page']").val("1");
	        var combo = $(this);
	        var value = combo.val();
	        var target = combo.data("target");
	        var el_target = $("." + target);
	        var url = combo.data("url");
	        if(value != 0)
	        {
	        	el_target.attr("disabled", true);
	        	combo.attr("disabled", true);
	        	$.get(url + "/" + value, function(ret){
	        		var obj = $.parseJSON(ret);
	        		{
	        			if(obj.status = true)
	        			{
	        				el_target.attr("disabled", false);
	        				combo.attr("disabled", false);
	        				el_target.empty().append(obj.data_city);
	        			}
	        		}
		        });
	        }
	    });
	</script>
@stop