<!-- begin #header -->
<div id="header" class="header navbar navbar-inverse navbar-fixed-top">
    <!-- begin container-fluid -->
    <div class="container-fluid">
        <!-- begin mobile sidebar expand / collapse button -->
        <div class="navbar-header">

            <div class="image">
                <a href="{{ route('student.homeworks.index') }}" class="navbar-brand">
                    <span>
                        Панель админ
                    </span>
                </a>
            </div>

            <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- end mobile sidebar expand / collapse button -->

        <!-- begin header navigation right -->
        <ul class="nav navbar-nav navbar-right">
            <li>
                <form action="{{ route('admin.clear_cash') }}" method="get" enctype="multipart/form-data">

                    <button class="dropdown-toggle f-s-14"
                        style="padding-right:0px; background: transparent; border: none">
                        <span style="margin-top:16.5px " class="btn btn-danger btn-xs "><span
                                class="fa fa-trash-o"></span> Очистить кеш</span>
                    </button>
                </form>

            </li>
            <li>

            </li>


            <li class="dropdown navbar-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <img style="object-fit: cover;"
                        src="{{ Auth::user()->image ? Auth::user()->image : asset('site/images/default_user.png') }}"
                        alt="" />
                    <span class="hidden-xs">
                        {{-- @php
                            $title = Auth::user()->fullname;
                            $length = strlen($title);
                        @endphp --}}

                        {{ Auth::user()->fullname }}
                        {{-- @if ($length <= 10)
                            {{ $title }}
                        @else
                            {{ mb_substr($title, 0, 10) . '...' }}
                        @endif --}}
                    </span> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInLeft">
                    <li class="arrow"></li>
                    <li class="divider"></li>
                    <li>
                         <form id="form-logout" method="POST" action="{{ route('logout') }}">
                        @csrf
                        </form>
                        <a href="{{ route('logout') }}">
                            {{-- onclick="event.preventDefault(); document.getElementById('form-logout').submit();"> --}}
                            Выйти
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- end header navigation right -->
    </div>
    <!-- end container-fluid -->
</div>
<!-- end #header -->

<!-- begin #sidebar -->


<!-- begin scroll to top btn -->
