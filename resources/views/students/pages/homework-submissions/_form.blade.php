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
                <form method="post" action="{{ route('student.homework.submissions.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($questions as $question)
                                <div class="col-md-12">
                                    <h4><strong>Mashq turi:</strong>
                                        {{ $question['homework']['homeworkTypes'][0]['name'] ?? null }}</h4>
                                    <p><strong>Shart:</strong> {{ $question['homework']['task_condition'] ?? null }}</p>
                                </div>

                                @if (!empty($question['tip']))
                                    <div class="col-md-12">
                                        <div class="alert alert-info p-2 mb-2">
                                            <strong>Maslahat:</strong> {{ trim($question['tip'], '"') }}
                                        </div>
                                    </div>
                                @endif

                                @if (!empty($question['answer_template']))
                                    <div class="col-md-12">
                                        <div class="accordion mb-2" id="accordion-{{ $question['homework']['id'] }}">
                                            <div class="card border-0 shadow-sm mb-3">
                                                <div class="card-header bg-light border-0"
                                                    id="heading-{{ $question['homework']['id'] }}">
                                                    <h5 class="mb-0 d-flex justify-content-between align-items-center">
                                                        <span class="text-dark font-weight-bold">Javob shu kabi yoziladi</span>
                                                        <button class="btn btn-sm btn-outline-primary" type="button"
                                                            data-toggle="collapse"
                                                            data-target="#collapse-{{ $question['homework']['id'] }}"
                                                            aria-expanded="false"
                                                            aria-controls="collapse-{{ $question['homework']['id'] }}">
                                                            Ko‘rsatish / Yopish
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="collapse-{{ $question['homework']['id'] }}" class="collapse"
                                                    aria-labelledby="heading-{{ $question['homework']['id'] }}"
                                                    data-parent="#accordion-{{ $question['homework']['id'] }}">
                                                    <div class="card-body bg-white border rounded"
                                                        style="font-family: monospace; white-space: pre-wrap;">
                                                        <code class="d-block p-2 text-dark">
                                                            {{ $question['answer_template'] }}
                                                        </code>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Har bir topshiriq uchun input maydoni --}}
                                @foreach ($question['questions'] as $task_name => $task_value)
                                    <div class="col-md-12">
                                        <div class="form-group position-relative mb-4">
                                            <label class="w-100">
                                                {{ $task_name }}
                                                <span class="position-absolute" style="right: 10px; color: #888;">
                                                    {{ $task_value }}
                                                </span>
                                            </label>

                                            <input type="text"
                                                name="answers[{{ $question['homework']['id'] }}][{{ $task_name }}]"
                                                class="form-control"
                                                value="{{ old(
                                                    'answers.' . $question['homework']['id'] . '.' . $task_name,
                                                    is_array($model['answers'] ?? null)
                                                        ? $model['answers'][$question['homework']['id']][$task_name] ?? ''
                                                        : json_decode($model['answers'] ?? '{}', true)[$question['homework']['id']][$task_name] ?? '',
                                                ) }}"
                                                required>
                                        </div>
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
