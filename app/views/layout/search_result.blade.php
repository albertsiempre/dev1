@yield('search_result')
<script type="text/javascript">
	<?php
		if(isset($pages))
		{
			if(!is_null($pages))
			{
				$current_page = $pages['current_page'];
				$total_page = $pages['total_page'];
				$cur_page = $current_page > $total_page ? 1 : $current_page;
				?>
					$('div[name="paging"]').pagination({
				        pages:<?= isset($total_page) ? $total_page : 1; ?>,
				        displayedPages: 5,
				        edges:1,
				        cssStyle: 'light-theme',
				        currentPage:<?= isset($cur_page) ? $cur_page : 1; ?>,
				        onPageClick : function(pageNumber, event){
				            $('#page').val(pageNumber);
				            $form.submit();
				            return false;
				        }
				    });

					<?php
					    if(isset($total) && $total != null)
					    {
					    	?>
					    		$form_loading.html('<span style="color:green;"><?= $total; ?> data found.</span>');
					    	<?php
					    } else {
					    	?>
					    		$form_loading.html("");
					    	<?php
					    }
				    ?>
					
				<?php
			} else {
				?>
					$form_loading.html('<span style="color:red;">0 data found.</span>');
				<?php
			}
		} else {
			?>
				$form_loading.html('<span style="color:red;">0 data found.</span>');
			<?php
		}
	?>
    $form_button.attr('disabled',false);
</script>