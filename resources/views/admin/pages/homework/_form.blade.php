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
        .input-group {
            display: flex;
            align-items: stretch;
            width: 100%;
        }
        .input-group .form-control {
            flex: 1;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            white-space: nowrap;
            padding: 6px 12px;
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
                        
                        @foreach($subjects as $subject)
                            <input type="hidden" name="subject_id" id="hidden-subject-id" value="{{ $subject->id }}">
                        @endforeach

                        @foreach($homeworkTypes as $homeworkType)
                                <input type="hidden" name="type_id" value="{{$homeworkType->id}}">
                        @endforeach

                    <div class="col-md-2" style="margin-bottom: 10px;">
                        <label class="control-label">Exercise number</label>
                        <input autocomplete="off" type="text" name="exercise_id" class="form-control"
                               value="{{ old('exercise_id') ?? $model->exercise_id }}">
                        @error('exercise_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6" style="margin-bottom: 10px;">
                        <label class="control-label">Task condition</label>
                        <input type="text" name="task_condition" class="form-control"
                               value="{{ old('task_condition') ?? $model->task_condition }}">
                        @error('task_condition')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4" style="margin-bottom: 10px;">
                        <label class="control-label">Due date</label>
                        <div class="input-group">
                            <input type="datetime-local" name="due_date" class="form-control" id="due_date_input"
                                   value="{{ old('due_date') ?? $model->due_date }}">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" id="set_due_date">
                                    Set 2 days from now
                                </button>
                            </div>
                        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dueDateInput = document.getElementById('due_date_input');
            const setDueDateBtn = document.getElementById('set_due_date');

            setDueDateBtn.addEventListener('click', function() {
                // Hozirgi vaqtni olish
                const now = new Date();
                
                // 2 kun qo'shish
                now.setDate(now.getDate() + 2);
                
                // Vaqtni input formatiga o'tkazish
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                
                // Input qiymatini o'zgartirish
                dueDateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
            });
        });
    </script>

@endsection
