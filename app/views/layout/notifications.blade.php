@section('notifications')
<div class="alert alert-error" style="{{ $errors->any() ? '' : 'display: none;' }}">
    <button type="button" class="close" data-hide="alert">×</button>
    <ul style="margin-bottom: 0;">
        @foreach ($errors->all() as $error)
            <li>
                {{ $error }}
            </li>
        @endforeach
    </ul>
</div>
<div class="alert alert-success" style="{{ $success ? '' : 'display: none;' }}">
    <button type="button" class="close" data-hide="alert">×</button>
    <span>
        {{ $success }}
    </span>
</div>
<div class="alert alert-info" style="display: none;">
    <button type="button" class="close" data-hide="alert">×</button>
    <ul style="margin-bottom: 0;"></ul>
</div>
@show