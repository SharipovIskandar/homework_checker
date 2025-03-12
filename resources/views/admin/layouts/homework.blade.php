<li class="has-sub {{
    request()->routeIs('admin.homework.index') ||
    request()->routeIs('admin.homework.create') ||
    request()->routeIs('admin.homework.edit') ||
      request()->routeIs('admin.homework-questions*') ||
       request()->routeIs('admin.homework-types*') ? 'active' : ''}}">


    <a href="javascript:;" class="menu-item">
        <b class="caret pull-right"></b>
        <i class="glyphicon glyphicon-dashboard"></i>
        <span>Homework Controller</span>
    </a>

    <ul class="sub-menu">
        <li class="{{ request()->routeIs('admin.homework.index') ||
                      request()->routeIs('admin.homework.create') ||
                      request()->routeIs('admin.homework.edit')  ? 'active' : '' }}">
            <a href="{{ route('admin.homework.index') }}">
                <span>Homework</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.homework-questions*') ? 'active' : '' }}">
            <a href="{{ route('admin.homework-questions.index') }}">
                <span>Homework questions</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.homework-types*') ? 'active' : '' }}">
            <a href="{{ route('admin.homework-types.index') }}">
                <span>Homework types</span>
            </a>
        </li>
    </ul>
</li>
