
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Панель администратора</title>

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

    <link href="{{ asset('coloradmin/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/plugins/jquery-jvectormap/jquery-jvectormap.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/plugins/jquery-jvectormap/jquery-jvectormap.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/css/style.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/css/style-responsive.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('coloradmin/css/theme/default.css') }}" rel="stylesheet" id="theme" />
    <link href="{{ asset('coloradmin/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('customCss')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" name="_method" value="PUT">

</head>

<body>
<div id="page-loader" class="fade in"><span class="spinner"></span></div>

<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">

    @include('admin.layouts.header')
    @include('admin.layouts.sidebar')

    <div id="content" class="content">
        {{-- @include('admin.layouts.alert') --}}
        @yield('breadcrumb')
        @yield('content')
    </div>

    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade"
       data-click="scroll-top">
        <i class="fa fa-angle-up"></i>
    </a>

</div>
<script src="https://unpkg.com/imask"></script>
<!-- ================== BEGIN BASE JS ================== -->
<script src="{{ asset('coloradmin/plugins/jquery/jquery-1.9.1.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/jquery/jquery-migrate-1.1.0.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/jquery-ui/ui/minified/jquery-ui.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('coloradmin/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/jquery-cookie/jquery.cookie.js') }}"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="{{ asset('coloradmin/plugins/flot/jquery.flot.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/flot/jquery.flot.time.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/flot/jquery.flot.resize.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/flot/jquery.flot.pie.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/sparkline/jquery.sparkline.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/jquery-knob/js/jquery.knob.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/jquery-jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/masked-input/masked-input.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/gritter/js/jquery.gritter.js') }}"></script>
<script src="{{ asset('coloradmin/js/ui-modal-notification.demo.min.js') }}"></script>
<script src="{{ asset('coloradmin/js/apps.min.js') }}"></script>
<script src="{{ asset('coloradmin/plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('coloradmin/js/form-plugins.demo.min.js') }}"></script>



<!-- ================== END PAGE LEVEL JS ================== -->

<script>
    $(document).ready(function() {
        App.init();
        Notification.init();
    });


    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-53034621-1', 'auto');
    ga('send', 'pageview');

    let notif = document.querySelector('#gritter-notice-wrapper');
    setTimeout(() => {
        if (notif) {
            notif.style.display = 'none'
        }
    }, 1500);
</script>

<script>



    function calculateSprintSalary(sprintId) {

        if (!sprintId) {
            Swal.fire('Ошибка!', 'ID спринта не найден!', 'error');
            return;
        }


        let url = "/admin/calculate-salary";
        let route = url + '?sprint_id=' + sprintId;

        Swal.fire({
            title: 'Вы уверены?',
            text: "Вы не сможете это вернуть!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Да, рассчитать зарплату!',
            cancelButtonText: 'Отмена',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                        'sprint_id': sprintId,
                    },
                    success: function(result) {
                        Swal.fire(
                            'Успешный!',
                            'Зарплата успешно рассчитана!',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        Swal.fire(
                            'Ошибка!',
                            'Проблема с расчетом зарплаты!',
                            'error'
                        );
                    }
                });
            }
        });
    }

    function deleteModel(url) {
        let route = url;
        Swal.fire({
            title: 'Вы уверены?',
            text: "Вы не сможете это вернуть!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Да, удалите!',
            cancelButtonText: 'Отмена',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'DELETE',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                    },
                    success: function(result) {
                        Swal.fire(
                            'Удалено!',
                            'Существует связанная связанная таблица!',
                            'success'
                        ).then(() => {
                            $("#" + result['tr']).remove();

                            var elements = document.getElementsByClassName('row-number');
                            for (var i = 0; i < elements.length; i++) {
                                console.log(elements[i]);
                                elements[i].textContent = i + 1;
                            }

                        });
                    },
                    error: function() {
                        Swal.fire(
                            'Ошибка!',
                            'При удалении объекта произошла ошибка!',
                            'error'
                        );
                    }
                });

            }
        });

    }

    function deleteItem(e) {
        let route = e.getAttribute('data-url');
        Swal.fire({
            title: 'Вы уверены?',
            text: "Вы не сможете это вернуть!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Да, удалите!',
            cancelButtonText: 'Отмена',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'DELETE',
                    data: {
                        '_token': $('meta[name=csrf-token]').attr("content"),
                    },
                    success: function(result) {
                        location.reload();
                        // Swal.fire(
                        //     'Удалено!',
                        //     'Объект успешно удален!',
                        //     'success'
                        // ).then(() => {
                        //     // $("#" + result['tr']).slideUp("slow");
                        //     // $("#" + result['tr']).remove();

                        //     // var elements = document.getElementsByClassName('row-number');
                        //     // for (var i = 0; i < elements.length; i++) {
                        //     //     console.log(elements[i]);
                        //     //     elements[i].textContent = i + 1;
                        //     // }
                        // });
                    }
                });
            }
        });

    }
    @if (session()->has('message'))
    Swal.fire({
        title: "{{ session()->get('message') }}",
        showConfirmButton: false,
        icon: "success",
        timer: 2200
    });
    @endif

    @if (session()->has('success'))
    Swal.fire({
        title: "{{ session()->get('success') }}",
        showConfirmButton: false,
        icon: "success",
        timer: 2200
    });
    @endif

    @if (session()->has('error'))

    Swal.fire({
        title: "{{ session()->get('error') }}",
        showConfirmButton: false,
        icon: "error",
        timer: 2000
    });
    @endif
</script>


@yield('customJs')
@stack('scripts')

</body>

</html>
