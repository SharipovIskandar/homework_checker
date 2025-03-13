<div id="sidebar" class="sidebar">
    <div data-scrollbar="true" data-height="100%">
        <ul class="nav">
            <li class="nav-profile">
                <div class="image">
                    <a href="javascript:;"><img style="object-fit: cover;"
                                                src="{{ Auth::user()->image ? Auth::user()->image : asset('site/images/default_user.png') }}"
                                                alt=""></a>
                </div>
                <div class="info">
                    {{-- @php
                    $title = Auth::user()->fullname;
                    $length = strlen($title);
                    @endphp --}}
                    {{ Auth::user()->fullname }}
                    {{-- @if ($length <= 10) {{ $title }} @else {{ mb_substr($title, 0, 10) . '...' }} @endif --}}
                    <small>{{ Auth::user()->username }}</small>
                </div>
            </li>
        </ul>

        <ul class="nav">
            <li class="{{ request()->routeIs('student.homeworks*') ? 'active' : '' }}">
                <a href="{{ route('student.homeworks.index') }}">
                    <i class="fa fa-bar-chart-o"></i>
                    <span>Students homework</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('student.homework.submissions.*') ? 'active' : '' }}">
                <a href="{{ route('student.homework.submissions.index') }}">
                    <i class="fa fa-bar-chart-o"></i>
                    <span>Students homework submissions</span>
                </a>
            </li>

            <li class="{{ request()->routeIs('student.homework.results.index') ? 'active' : '' }}">
                <a href="{{ route('student.homework.results.index') }}">
                    <i class="fa fa-bar-chart-o"></i>
                    <span>Students homework results</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                <a href="{{ route('admin.students.index') }}">
                    <i class="fa fa-bar-chart-o"></i>
                    <span>Students</span>
                </a>
            </li>
                @include('admin.layouts.homework')
                {{--            @include('admin.layouts.student')--}}
            <li>
                <a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify">
                    <i class="fa fa-angle-double-left"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
<div class="sidebar-bg"></div>
