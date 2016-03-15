@section('actual_content')
    <div class="btn-toolbar">
    @if(isset($part))
        @for($i=1; $i <= $part; $i++)
            <a class="btn btn-primary btn_new" href="{{ isset($url_download_verified_email) ? $url_download_verified_email.'?page='.$i : '#' }}"><i class="icon-envelope"></i> Download Verified Email
            {{ isset($part) && $part > 1 ? "(Part ". $i.")" : "" }}
            </a>
            @if($part > 1)
                <br /><br />
            @endif
        @endfor
    @endif
        <br /><hr>
        <a class="btn btn-primary btn_new" href="{{ isset($url_download_read_email) ? $url_download_read_email : '#' }}"><i class="icon-envelope"></i> Download Email From Google Analytics</a>
    </div>
    <style>
        .btn-toolbar {
            display: block;
            position: relative;
            overflow: hidden;
            font-size: 14px;
        }

        .__right_info {
            margin-top: 5px;
            margin-right: 5px;
        }

        span.total_order {
            font-weight: bold;
        }
    </style>

@stop