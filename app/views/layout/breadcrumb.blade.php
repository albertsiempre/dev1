<ul class="breadcrumb">
    @for ($i = 0; $i < $breadcrumb_counter - 1; $i++)
            <li>
                <a href="{{ URL::action($breadcrumb_links[$i][1], $breadcrumb_links[$i][2]) }}">{{ $breadcrumb_links[$i][0] }}</a>
                <span class="divider">/</span>
            </li>
    @endfor
    <li class="active">{{ $breadcrumb_links[$breadcrumb_counter - 1][0] }}</li>
</ul>