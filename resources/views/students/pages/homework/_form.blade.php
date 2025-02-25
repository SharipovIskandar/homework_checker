@extends('admin.layouts.app')
@section('breadcrumb')
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-left">
        <li>
            <a href="{{ route('admin.dashboard') }}">Статистика</a>
        </li>
        <li>
            <a href="{{ route('admin.developer.reports.index') }}"> Клиент</a>
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
            <ul class="nav nav-pills" style="background: #ffffff;padding-left: 14px; margin-bottom:25px">
                @foreach ($languages as $language)
                    <li id="tab-orders-{{$language->id+1}}" class="{{ $language->default==true ? 'active' : '' }}">
                        <a href="#default-tab-orders-{{$language->id+1}}" data-toggle="tab" aria-expanded="true">
                            {{$language->name}}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="table-responsive kv-grid-container">
                <form method="post" action="{{ $route }}" enctype="multipart/form-data">
                    @if($method == "PUT")
                        @method($method)
                    @endif
                    @csrf
                    <div class="modal-body" id="smallBody">
                        <div class="row">
                            <div class="col-md-6" style=" margin-bottom: 15px;">
                                <label style="display: block;" for="categories">Спринт</label>
                                <select style="width: 100%;" class="form-control" disabled>
                                    @foreach ($sprints as $data)
                                        @if($data->status == 'active')
                                            <option value="{{ $data->id }}"
                                                    @if (old('sprint_id') || in_array($data->id, $model->pluck('sprint_id')->toArray())) selected @endif>
                                                {{ $data->title . " | year " . $data->year . " month " . $data->month  }}
                                                <input type="hidden" name="sprint_id" value="{{ $data->id }}">
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6" style=" margin-bottom: 15px;">
                                <label style="display: block;" for="categories">Руководитель проекта</label>
                                <select name="project_manager_id" style="width: 100%;" class="form-control">
                                    @foreach ($users as $data)
                                        <option value="{{ $data->id }}"
                                                @if (old('project_manager_id') || in_array($data->id, $model->pluck('project_manager_id')->toArray())) selected @endif>
                                            {{ $data->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6" style=" margin-bottom: 15px;">
                                <label style="display: block;" for="categories">Проект</label>
                                <select name="project_id" style="width: 100%;" class="form-control">
                                    @foreach ($projects as $data)
                                        <option value="{{ $data->id }}"
                                                @if (old('project_id') || in_array($data->id, $model->pluck('project_id')->toArray())) selected @endif>
                                            {{ $data->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6" style=" margin-bottom: 15px;">
                                <label style="display: block;" for="categories">Программист</label>
                                <select name="coder_id" style="width: 100%;" class="form-control">
                                    @foreach ($coders as $data)
                                        <option value="{{ $data->id }}"
                                                @if (old('coder_id') || in_array($data->id, $model->pluck('coder_id')->toArray())) selected @endif>
                                            {{ $data->fullname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <label class="control-label">Общее запланированное время</label>
                                <input type="number" name="total_planned_time" class="form-control"
                                       value="{{ old('total_planned_time') ?? $model->total_planned_time }}">
                                @error('total_planned_time')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <label class="control-label">Затраченное время</label>
                                <input autocomplete="off" type="number" name="done_time" class="form-control"
                                       value="{{ old('done_time') ?? $model->done_time }}">
                                @error('done_time')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <label class="control-label"> Время В Процессе</label>
                                <input type="number" name="inprogress_time" class="form-control"

                                       value="{{ old('inprogress_time') ?? $model->testing_time }}">
                                @error('inprogress_time')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3" style="margin-bottom: 10px;">
                                <label class="control-label">Время на исправление ошибок</label>
                                <input type="number" name="bug_time" class="form-control"
                                       value="{{ old('bug_time') ?? $model->bug_time }}">
                                @error('bug_time')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <br>
                        <div class="form_footer">
                            <a href="{{route('admin.developer.reports.index')}}" class="btn btn-warning">Назад</a>

                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
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
