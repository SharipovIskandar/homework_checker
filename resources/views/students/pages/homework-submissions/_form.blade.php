@extends('admin.layouts.app')

@section('breadcrumb')
    <ol class="breadcrumb pull-left">
        <li>
            <a href="{{ route('student.homeworks.index') }}">Main</a>
        </li>
        <li class="active">Homework Submission</li>
    </ol>
    <br>
@endsection

@section('content')
    <br>

    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Homework Submission</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive kv-grid-container">
                <form method="post" action="{{ route('student.homework.submissions.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($questions as $question)
                                <div class="col-md-12">
                                    <h4><strong>Mashq turi:</strong> {{ $question['homework']['homeworkTypes'][0]['name'] ?? null }}</h4>
                                    <p><strong>Shart:</strong> {{ $question['homework']['task_condition'] ?? null }}</p>
                                </div>

                                @foreach ($question['questions'] as $task_name => $task_value)
                                    <div class="form-group position-relative">
                                        <label class="w-100">{{ $task_name }}
                                            <span class="position-absolute" style="right: 10px; color: #888;">{{ $task_value }}</span>
                                        </label>
                                        <input type="text" name="answers[{{ $question['id'] }}][{{ $task_name }}]"
                                               class="form-control" required>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>

                        <br>
                        <div class="form_footer">
                            <a href="{{ route('student.homeworks.index') }}" class="btn btn-warning">Назад</a>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
