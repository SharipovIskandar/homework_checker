@extends('admin.layouts.app')

@section('breadcrumb')
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-left">
        <li>
            <a href="{{ route('admin.vocabularies.index') }}">Статистика</a>
        </li>
        <li>
            <a href="{{ route('admin.vocabularies.index') }}">Словарь</a>
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
            <h4 class="panel-title">{{ $label }}</h4>
        </div>
        <div class="panel-body">
            <div class="table-responsive kv-grid-container">
                <form method="post" action="{{ $route }}" enctype="multipart/form-data">
                    @if($method == "POST")
                        @method($method)
                    @endif
                    @csrf
                    <div class="modal-body" id="smallBody">
                        <div class="row">

                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <label class="control-label">Слово (Word)</label>
                                <textarea name="word[]" id="wordTextarea" class="form-control" rows="3">
                                    {{ old('word') ? implode(', ', old('word')) : ($model->word ? implode(', ', $model->word) : '') }}
                                </textarea>
                                <br>
                                <img id="imagePreview" src="" style="max-width: 100%; display: none;"
                                     alt="Pasted Image">
                                <br>
                                <button type="button" id="applyText" class="btn btn-success" style="display: none;">
                                    Apply
                                </button>
                                @error('word')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <label class="control-label">Total vocabularies</label>
                                <input type="text" name="total_vocabularies" class="form-control"
                                       value="{{ old('total_vocabularies') ?? $model->total_vocabularies }}" >
                                @error('total_vocabularies')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <label class="control-label">Уровень (Level)</label>
                                <input type="text" name="level" class="form-control"
                                       value="{{ old('level') ?? $model->level }}">
                                @error('level')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4" style="margin-bottom: 10px;">
                                <label class="control-label">Срок выполнения (Due Date)</label>
                                <input type="datetime-local" name="due_date" class="form-control"
                                       value="{{ old('due_date') ?? $model->due_date }}">
                                @error('due_date')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <br>
                        <div class="form_footer">
                            <a href="{{ route('admin.vocabularies.index') }}" class="btn btn-warning">Назад</a>

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
        $(document).ready(function () {
            $('#word_translation').summernote({
                placeholder: 'Перевод слова',
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

        document.addEventListener("paste", function (event) {
            let items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (let item of items) {
                if (item.kind === "file" && item.type.startsWith("image/")) {
                    let file = item.getAsFile();
                    let formData = new FormData();
                    formData.append("image", file);

                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let imagePreview = document.getElementById("imagePreview");
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = "block";
                        document.getElementById("applyText").style.display = "inline-block";
                    };
                    reader.readAsDataURL(file);

                    fetch('{{ route("api.vocabulary.process-image") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById("wordTextarea").value = data.text;

                                let totalVocabulariesInput = document.querySelector("input[name='total_vocabularies']");
                                totalVocabulariesInput.value = data.total_vocabularies; // Update the total vocabularies field
                            } else {
                                alert("Rasmni o'qishda xatolik yuz berdi!");
                            }
                        })
                        .catch(error => console.error("Error:", error));

                    event.preventDefault();
                }
            }
        });

    </script>

@endsection
