@section('search_result')
<?php
	if(isset($result) && $result != null)
	{ $no = 1;
		foreach($result as $data)
		{
			$style_red = isset($data["is_active"]) && $data["is_active"] == false ? "style=color:red;" : '';
			?>
				<tr {{ $style_red }}>
					<td>{{ $no }}</td>
					<td><a href="{{ $data["image_url"] != null ? $data["image_url"] : asset('/main/images/no_image.png') }}" class="view_image">
						<img src="{{ $data["image_url"] != null ? $data["image_url"] : asset('/main/images/no_image.png')  }}" width="73" style="max-height: 40px;"></a>
					</td>
					<td>{{ isset($data["game"]) && is_array($data["game"]) || is_object($data["game"]) && isset($data["game"]["category_name"]) ? $data["game"]["category_name"] : "" }}</td>
					<td><a href="{{ isset($data["link"]) && !empty($data["link"]) ? $data["link"] : "#" }}" target="_blank" {{ $style_red }}>Link</a></td>
					<td>{{ isset($data["order"]) && !empty($data["order"]) ? $data["order"] : "-" }}</td>
					<td>{{ date("Y-m-d", strtotime($data["start_date"])) }}</td>
					<td>{{ date("Y-m-d", strtotime($data["end_date"])) }}</td>
					<td>
						<a class="__action_btn btn-edit-banner"  href="{{ URL::Route(GROUP_INTERNAL . '.form.banner', $data['id']) }}" title="Edit" {{ $style_red }}>
							<i class="icon-pencil"></i> Edit
						</a> 
<!--                                            | 
						<a class="__action_btn" href="{{ URL::Route(GROUP_INTERNAL . '.form.team') }}" title="Delete">
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
        setMagnificEdit();
        setMagnificImageView();
	});
        
</script>

<style>
	.__action_btn {
		margin-right: 10px;
		text-decoration: none;
	}
</style>
@stop