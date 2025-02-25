<li
    class="has-sub {{ request()->routeIs('admin.contact*') || request()->routeIs('admin.applications*') ? 'active' : '' }}">

    <a href="javascript:;">
        {{-- <b class="caret pull-right"></b> --}}
        <i class="fa fa-address-book-o"></i>
        <span>Заявки <span class="badge pull-right" style="background-color: #ff5b57;">{{ $appCount + $conCount }}</span></span>
    </a>

    <ul class="sub-menu">
        <li class="{{ request()->routeIs('admin.applications*') ? 'active' : '' }}">
            <a href="{{ route('admin.applications') }}">
                <span>Заявки <span class="badge pull-right badge-danger">{{ $appCount }}</span></span>
            </a>
        </li>

        <li class="{{ request()->routeIs('admin.contact*') ? 'active' : '' }}">
            <a href="{{ route('admin.contact') }}">
                <span>Контакт <span class="badge pull-right badge-danger">{{ $conCount }}</span></span>
            </a>
        </li>
    </ul>
</li>
