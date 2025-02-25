<li class="has-sub {{(request()->is('admin/industry*')|| request()->is('admin/solution*'))?'active':''}}">

  <a href="javascript:;">
    <b class="caret pull-right"></b>
    <i class="fa fa-list-alt"></i>
    <span>Содержание</span>
  </a>

  <ul class="sub-menu">
    <li class="{{ request()->routeIs('admin.industry*') ? 'active' : '' }}">
      <a href="{{ route('admin.industry.index')}}">
        <span>Направление</span>
      </a>
    </li>
    <li class="{{ request()->routeIs('admin.solution*') ? 'active' : '' }}">
      <a href="{{ route('admin.solution.index')}}">
        <span>ИТ-решения</span>
      </a>
    </li>

  </ul>
</li>
