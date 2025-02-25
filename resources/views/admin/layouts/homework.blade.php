<li class="has-sub {{
    request()->routeIs('admin.homework*') ||
    request()->routeIs('admin.homework-types*') ||
     request()->routeIs('admin.homework-correct-answers*') ? 'active' : ''}}">

    <a href="javascript:;" class="menu-item">
        <b class="caret pull-right"></b>
        <i class="glyphicon glyphicon-dashboard"></i>
        <span>Homework Controller</span>
    </a>

    <ul class="sub-menu">
        <li class="{{ request()->routeIs('admin.homework*') ? 'active' : '' }}">
            <a href="{{ route('admin.homework.index') }}">
                <span>Homework</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.homework-types*') ? 'active' : '' }}">
            <a href="{{ route('admin.homework-types.index') }}">
                <span>Homework types</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('admin.homework-correct-answers*') ? 'active' : '' }}">
            <a href="{{ route('admin.homework-correct-answers.index') }}">
                <span>Homework correct answers</span>
            </a>
        </li>
    </ul>
</li>
