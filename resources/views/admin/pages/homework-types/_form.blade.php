@extends('admin.layouts.app')
@section('breadcrumb')
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-left">
        <li>
            <a href="{{ route('admin.students.index') }}">Статистика</a>
        </li>
        <li>
            <a href="{{ route('admin.homework-types.index') }}"> Клиент</a>
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
            <div class="table-responsive kv-grid-container">
                <form method="post" action="{{ $route }}" enctype="multipart/form-data">
                    @if($method == "PUT")
                        @method($method)
                    @endif
                    @csrf
                    <div class="modal-body" id="smallBody">


                        <div class="row">

                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <label class="control-label">Homework type name</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name') ?? $model->name }}">
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="form_footer">
                            <a href="{{route('admin.homework-types.index')}}" class="btn btn-warning">Назад</a>

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
