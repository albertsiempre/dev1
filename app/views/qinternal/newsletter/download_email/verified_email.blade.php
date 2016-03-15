<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<table>
	<tr>
		<td>No.</td>
		<td>Email</td>
	</tr>
	@if (isset($emails))
		{{-- */$i=1/* --}}
		@foreach ($emails as $key => $value)
		<tr>
			<td>{{ $i }}</td>
			<td>{{ $value }}</td>
		</tr>
		{{-- */$i++/* --}}
		@endforeach
	@endif
</table>
</body>
</html>