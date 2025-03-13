    @extends('admin.layouts.app')
    @section('customCss')
    <link href="{{asset('coloradmin/plugins/flag-icon/css/flag-icon.css')}}" rel="stylesheet">
    <link href="{{asset('coloradmin/plugins/switchery/switchery.min.css')}}" rel="stylesheet">
    <link href="{{asset('coloradmin/css/animate.min.css')}}" rel="stylesheet">
@endsection

@section('breadcrumb')
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-left">
        <li>
            <a href="{{route('student.homeworks.index')}}">
                Главная
            </a>
        </li>
        <li class="active">
            Отчет о программистах
        </li>
    </ol>
    <br>
    <!-- end breadcrumb -->
@endsection

@section('content')
    <!-- begin page-header -->
    <div class="row">

        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                    </div>
                    <h4 class="panel-title">Отчет о программистах</h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive kv-grid-container">
                        @include('students.pages.homework-submissions._columns', [
                            'datas' => $datas,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <script>
        $(document).on('click', '.deleteModal', function () {
            let id = $(this).attr('data-id');
            $('#id_delete').val(id);
            $('#delete_form').attr('action', $(this).attr('data-url'));
            $('#delete_form').action = $(this).attr('data-url');
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.filter-select').forEach(select => {
                select.addEventListener('change', function () {
                    if (this.value === "") {
                        this.value = "";
                    }
                });
            });
        });
    </script>
@endsection
