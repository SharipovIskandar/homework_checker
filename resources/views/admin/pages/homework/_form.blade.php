@extends('admin.layouts.app')
@section('breadcrumb')
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-left">
        <li>
            <a href="{{ route('admin.homework.index') }}">homework</a>
        </li>
        <li>
            <a href="{{ route('admin.homework.index') }}"> Homework</a>
        </li>
        <li class="active">{{ $label }}</li>
    </ol>
    <br>
    <!-- end breadcrumb -->
@endsection
@section('customCss')
    <link rel="stylesheet" href="{{ asset('summernote/summernote.css') }}">
    <style>
        .note-editor.note-frame {
            border: 1px solid #a9a9a9 !important;
        }
    </style>
@endsection
@section('content')
    <br>

    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">{{$label}}</h4>
        </div>
        <div class="panel-body">
            <form method="post" action="{{ $route }}" enctype="multipart/form-data">
                @if($method == "PUT")
                    @method($method)
                @endif
                @csrf
                <div class="row">
                    <div class="col-md-3" style="margin-bottom: 10px;">
                        <label>Subject</label>
                        <select class="form-control" name="year" id="year-select" onchange="loadSprints2()" disabled>
                            @foreach($subjects as $subject)
                                <option
                                    value="{{ $subject['name'] }}" {{ (old('name') ?? request('name')) == $subject['name'] ? 'selected' : '' }}>
                                    {{ $subject['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @foreach($subjects as $subject)
                            <input type="hidden" name="subject_id" id="hidden-subject-id" value="{{ $subject->id }}">
                        @endforeach
                    </div>

                    <div class="col-md-3" style="margin-bottom: 10px;">
                        <label class="control-label">Exercise number</label>
                        <input autocomplete="off" type="text" name="exercise_id" class="form-control"
                               value="{{ old('exercise_id') ?? $model->exercise_id }}">
                        @error('exercise_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-3" style="margin-bottom: 10px;">
                        <label>Homework Types</label>
                        <select class="form-control" name="type_id" id="year-select" onchange="loadSprints2()">
                            <option value="">Homework Types</option>
                            @foreach($homeworkTypes as $homeworkType)
                                <option value="{{ $homeworkType['id'] }}"
                                    {{ (old('type_id') ?? request('type_id')) == $homeworkType['id'] ? 'selected' : '' }}>
                                    {{ $homeworkType['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('type_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3" style="margin-bottom: 10px;">
                        <label class="control-label">Task condition</label>
                        <input type="text" name="task_condition" class="form-control"
                               value="{{ old('task_condition') ?? $model->task_condition }}">
                        @error('task_condition')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3" style="margin-bottom: 10px;">
                        <label class="control-label">Due date</label>
                        <input type="datetime-local" name="due_date" class="form-control"
                               value="{{ old('due_date') ?? $model->due_date }}">
                        @error('due_date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <br>
                <div class="form_footer">
                    <a href="{{route('admin.homework.index')}}" class="btn btn-warning">Назад</a>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
    </div>

@endsection

@section('customJs')

    <script src="{{ asset('summernote/summernote.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var phoneInput = document.getElementById('phonecreate');

            var phoneMaskOptions = {
                mask: '+000000000000000000000000',
                lazy: true,
            };

            var phoneMask = IMask(phoneInput, phoneMaskOptions);
        });
    </script>

    <script>
        // phone maska
        $("#masked-input-phone").mask("+998 77 777 77 77")

        $(document).ready(function () {
            $('#banner_textuz').summernote({
                placeholder: 'Адрес',
                tabsize: 2,
                height: 226,
            });
        });

        $(document).ready(function () {
            $('#banner_texten').summernote({
                placeholder: 'Адрес',
                tabsize: 2,
                height: 226,
            });
        });
        $(document).ready(function () {
            $('#banner_textru').summernote({
                placeholder: 'Адрес',
                tabsize: 2,
                height: 226,
            });
        });

        var loadFile = function (event, output = 'output_create') {
            var output = document.getElementById(output);
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function () {
                URL.revokeObjectURL(output.src) // free memory
            }
        };
    </script>

@endsection
