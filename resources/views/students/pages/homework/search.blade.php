<div class="panel panel-inverse" data-sortable-id="ui-general-1">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse" onclick="setLocalStorage()" data-original-title="" title="" data-init="true">
                <i class="fa fa-minus"></i>
            </a>
        </div>
        <h4 class="panel-title">Search</h4>
    </div>
    <div class="panel-body" id="search-div">
        <form method="get" action="{{ route('student.homeworks.index') }}" id="search-form">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <label>Exercise number</label>
                    <input type="number" name="exercise_id" value="{{ request('exercise_id') }}" class="form-control">
                </div>

                <div class="col-md-5">
                    <label>Due date</label>
                    <select name="due_date" class="form-control">
                        <option value="">Due date</option>
                        <option value="past" {{ request('due_date') == 'past' ? 'selected' : '' }}>Past</option>
                        <option value="future" {{ request('due_date') == 'future' ? 'selected' : '' }}>Future</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label>Search</label>
                    <br>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fa fa-filter fa-fw" style="font-size: 16px;"></i> Search
                    </button>
                </div>

                <div class="col-md-1">
                    <label>Clear</label>
                    <br>
                    <button type="submit" class="btn btn-secondary btn-sm" onclick="clearAllInputs()">
                        <i class="glyphicon glyphicon-trash" style="font-size: 12px; display: inline-block; width: 16px; text-align: center;"></i> Clear
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('customJs')
    <script>


        function clearAllInputs() {
            document.querySelectorAll('input[type="text"], input[type="number"], input[type="email"], input[type="checkbox"], input[type="radio"]').forEach(input => {
                input.value = '';
                input.checked = false;
            });

            document.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });

            const url = new URL(window.location.href);
            url.search = '';
            history.replaceState(null, '', url);

            if (typeof loadSprints2 === 'function') {
                loadSprints2();
            }
        }


    </script>
@endsection
