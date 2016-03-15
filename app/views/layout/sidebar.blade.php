@if (isset($sidebar) && !empty($sidebar))
    <div class="sidebar-nav">
        <!-- <ul class="nav nav-list"> -->
            @if(isset($sidebar) && !empty($sidebar))
                @foreach ($sidebar as $group => $privileges)
                    @if ($privileges['links']->count() === 1)
                    <!-- <li class="nav-header nav-singular group-{{ Str::slug($group) }} {{ $privileges['is_active'] ? 'active' : '' }}"> -->
                        @foreach ($privileges['links'] as $text => $action)
                        <a href="{{ URL::action($action['action']) . '?' . $action['query'] }}" class="nav-header nav-singular group-{{ Str::slug($group) }} {{ $privileges['is_active'] ? 'active' : '' }}">
                            <i class="icon-hdd"></i>
                            {{ $text }}
                        </a>
                        @endforeach
                    <!-- </li> -->
                    @else
                    <!-- <li class="nav-header group-{{ Str::slug($group) }}"> -->
                        <a href="#menu-{{ Str::slug($group) }}" data-toggle="collapse" class="nav-header collapsed group-{{ Str::slug($group) }}">
                            <i class="icon-hdd"></i>
                            {{ $group }}
                            <i class="icon-chevron-up"></i>
                        </a>
                        <ul id="menu-{{ Str::slug($group) }}" class="nav nav-list collapse {{ $privileges['is_active'] ? 'in' : '' }}">
                            @foreach ($privileges['links'] as $text => $action)
                            <li class="{{ $action['is_active'] ? 'active' : '' }}">
                                <a href="{{ URL::action($action['action']) . '?' . $action['query'] }}">
                                    {{ $text }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    <!-- </li> -->
                    @endif
                @endforeach
            @endif
        <!-- </ul> -->
    </div>
@endif