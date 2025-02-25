<li class="has-sub {{(request()->is('admin/translate*'))?'active':''}}">

  <a href="javascript:;">
    <b class="caret pull-right"></b>
    <i class="fa fa-language"></i>
    <span>Перевод сайта</span>
  </a>

  <ul class="sub-menu">
    <li class="{{ request()->is('admin/translate/uz') ? 'active' : '' }}">
      <a href="{{ route('admin.translate', 'uz') }}">
        <span>Узбекский</span>
      </a>
    </li>
    <li class="{{ request()->is('admin/translate/ru') ? 'active' : '' }}">
      <a href="{{ route('admin.translate', 'ru') }}">
        <span>Русский</span>
      </a>
    </li>
    <li class="{{ request()->is('admin/translate/en') ? 'active' : '' }}">
      <a href="{{ route('admin.translate', 'en') }}">
        <span>Английский</span>
      </a>
    </li>
  </ul>
</li>
