@extends('admin.layouts.app')

@section('content')
    <!-- begin page-header -->
    <h1 class="page-header">Статистика</h1>
    <!-- end page-header -->

    <!-- begin row -->
    <div class="row">
        @if (Auth::user()->role != 'admin')
            <!-- begin col-3 -->
            <div class="col-md-3 col-sm-6">
                <div class="widget widget-stats bg-green">
                    <div class="stats-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="stats-info">
                        <h4>Всего пользователей</h4>
                        <p>{{ $users }}</p>
                    </div>
                    <div class="stats-link">
                    </div>
                </div>
            </div>
            <!-- end col-3 -->
        @endif
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-stats" style="background-color: rgb(37, 143, 192);">
                <div class="stats-icon"><i class="fa fa-database"></i></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">

            </div>
        </div>

        <div class="col-md-12">
            <div class="panel panel-inverse" data-sortable-id="flot-chart-2">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand">
                            <i class="fa fa-expand"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning"
                            data-click="panel-collapse">
                            <i class="fa fa-minus"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <br>

                    <canvas id="traffic" style="width: 100%; height: 300px"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
    <!-- begin row -->

    <!-- end #content -->
@endsection

@section('customJs')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        const ctx_all = document.getElementById('traffic').getContext('2d');



        const studentAll = new Chart(ctx_all, {
            type: 'line',
            data: {
                labels: label_all,
                datasets: [{
                    label: 'Все',
                    data: data_all,
                    backgroundColor: [
                        '#ffffff4a'
                    ],
                    borderWidth: 2,
                    borderColor: '#348fe2',
                    pointBackgroundColor: '#0088c7',
                    pointBorderColor: '#a4f5b1',
                    pointHoverBackgroundColor: 'green',
                    pointHoverBorderColor: "rgba(179,181,198,1)",
                    lineTension: 0,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0,
                    }
                }
            }
        });
    </script>
@endsection
