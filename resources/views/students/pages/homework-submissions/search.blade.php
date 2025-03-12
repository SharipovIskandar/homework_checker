<div class="panel panel-inverse" data-sortable-id="ui-general-1">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"
               onclick="setLocalStorage()" title="">
                <i class="fa fa-minus"></i>
            </a>
        </div>
        <h4 class="panel-title">Поиск</h4>
    </div>
    <div class="panel-body" id="search-div">
        <form method="get" action="{{ route('admin.developer.reports.index') }}" id="search-form">
            @csrf
            <div class="row">

                <!-- Год -->
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="form-group">
                        <label>Год</label>
                        <select class="form-control filter-select" name="year" id="year-select" onchange="loadSprints2()">
                            <option value="">Выберите год</option>
                            @foreach($years as $year)
                                <option value="{{ $year['year'] }}"
                                    {{ request('year', date('Y')) == $year['year'] ? 'selected' : '' }}>
                                    {{ $year['year'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Месяц -->
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="form-group">
                        <label>Месяц</label>
                        <select class="form-control filter-select" name="month" id="month-select" onchange="loadSprints2()">
                            <option value="">Выберите месяц</option>
                            @foreach($months as $index => $month)
                                <option value="{{ ++$index }}"
                                    {{ request('month', date('n')) == $index ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2 col-12 col-lg-2 mb-3">
                    <label>Данные за месяц</label>
                    <br>
                    <button type="button" class="btn btn-info btn-sm" onclick="setCurrentMonthYear()">
                        <i class="fa fa-calendar fa-fw" style="font-size: 16px;"></i> Посмотреть данные месяца
                    </button>
                </div>

                <!-- Поиск -->
                <div class="col-12 col-md-6 col-lg-1 mb-3">
                    <div class="form-group">
                        <label>Поиск</label>
                        <br>
                        <button type="submit" class="btn btn-warning btn-sm w-100">
                            <i class="fa fa-filter fa-fw"></i> Поиск
                        </button>
                    </div>
                </div>

                <!-- Очистить -->
                <div class="col-12 col-md-6 col-lg-1 mb-3">
                    <div class="form-group">
                        <label>Очистить</label>
                        <button type="submit" class="btn btn-secondary btn-sm w-100" onclick="clearAllInputs()">
                            <i class="glyphicon glyphicon-trash"></i> Очистить
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@section('customJs')
<script>
    function updateUrlParams() {
        const url = new URL(window.location.href);
        const params = {};

        document.querySelectorAll('select, input[type="text"]').forEach(element => {
            if (element.name) {
                params[element.name] = element.value;
            }
        });

        Object.keys(params).forEach(key => {
            if (params[key]) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        });

        history.replaceState(null, '', url);
    }

    function clearAllInputs() {
        document.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        const url = new URL(window.location.href);
        document.querySelectorAll('select, input[type="text"]').forEach(element => {
            if (element.name) {
                url.searchParams.set(element.name, '');
            }
        });

        history.replaceState(null, '', url);

        if (typeof loadSprints2 === 'function') {
            loadSprints2();
        }
    }

    function loadSprints2() {
        const sprintSelect = document.getElementById('sprint-select');
        const year = document.getElementById('year-select').value;
        const month = document.getElementById('month-select').value;

        fetch(`/api/sprints?year=${year}&month=${month}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                sprintSelect.innerHTML = '<option value="" selected>Выберите спринт по году и месяцу</option>';
                if (data.length === 0) {
                    sprintSelect.innerHTML = '<option value="" selected>Спринты не найдены</option>';
                } else {
                    data.forEach(sprint => {
                        const option = document.createElement('option');
                        option.value = sprint.id;
                        option.textContent = sprint.title + ' | Дата начала: ' + sprint.start_date;
                        sprintSelect.appendChild(option);
                    });
                }
                updateSelectsFromUrl();
            })
            .catch(error => {
                console.error('Ошибка загрузки спринтов:', error);
                sprintSelect.innerHTML = '<option value="" selected>Ошибка загрузки спринтов</option>';
            });
    }

    function updateSelectsFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);

        document.querySelectorAll('select').forEach(select => {
            const paramValue = urlParams.get(select.name);
            if (paramValue) {
                select.value = paramValue;
            } else {
                select.selectedIndex = 0;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateSelectsFromUrl();
        loadSprints2();

        document.querySelectorAll('select, input[type="text"]').forEach(element => {
            element.addEventListener('change', () => {
                updateUrlParams();
                if (element.name === 'year' || element.name === 'month') {
                    loadSprints2();
                }
            });
        });
    });

    function setCurrentMonthYear() {
        const now = new Date();
        const currentYear = now.getFullYear();
        const currentMonth = now.getMonth() + 1; 

        document.getElementById('year-select').value = currentYear;
        document.getElementById('month-select').value = currentMonth;

        updateUrlParams();
        loadSprints2();
        document.getElementById('search-form').submit(); 
    }
</script>
@endsection