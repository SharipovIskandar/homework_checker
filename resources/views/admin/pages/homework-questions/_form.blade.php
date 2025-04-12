@extends('admin.layouts.app')
@section('breadcrumb')
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-left">
        <li>
            <a href="{{ route('student.homeworks.index') }}">Main</a>
        </li>
        <li>
            <a href="{{ route('admin.homework-questions.index') }}"> Homework questions</a>
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
@section('customCss')
    <style>
        .prediction-box {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1000;
        }

        .prediction-box div {
            padding: 5px;
            cursor: pointer;
        }

        .prediction-box div:hover {
            background: #f0f0f0;
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
                                <label>Homework Conditions</label>
                                <select class="form-control" name="homework_id" id="year-select"
                                        onchange="">
                                    <option value="">Homework</option>
                                    @foreach($homeworks as $homework)
                                        <option value="{{ $homework['id'] }}"
                                            {{ (old('task_condition') ?? request('task_condition')) == $homework['id'] ? 'selected' : '' }}>
                                            {{ $homework['task_condition'] }}
                                        </option>
                                    @endforeach

                                </select>
                                @error('homework_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" style="margin-bottom: 10px;">
                                <label class="control-label">Questions</label>
                                <textarea id="questionsTextarea" name="questions"
                                          class="form-control">{{ old('questions') ?? $model->questions }}</textarea>
                                @error('questions')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" style="margin-bottom: 10px;">
                                <label class="control-label">Correct Answers</label>
                                <textarea id="correctAnswersTextarea" name="correct_answers" class="form-control">{{ old('correct_answers') ?? $model->correct_answers }}</textarea>
                                <div id="predictions" class="prediction-box"></div>
                                <button type="button" id="generateCorrectAnswers" class="btn btn-primary" style="margin-top:10px;">
                                    Generate Correct Answers
                                </button>
                                @error('correct_answers')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" style="margin-bottom: 10px;">
                                <label class="control-label">Tip (Maslahat)</label>
                                <textarea name="tip" class="form-control" rows="4" placeholder='Masalan: {"uz":"Yaxshi o‘ylab ko‘ring", "en":"Think carefully"}'>{{ old('tip') ?? $model->tip }}</textarea>
                                @error('tip')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6" style="margin-bottom: 10px;">
                                <label class="control-label">Answer Template (enter one per line)</label>
                                <textarea name="answer_template" class="form-control" rows="5">{{ old('answer_template') ?? (is_array($model->answer_template) ? implode("\n", $model->answer_template) : '') }}</textarea>
                                @error('answer_template')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <label class="control-label">Upload Image</label>
                                <input type="file" name="image" class="form-control" id="imageUpload" accept="image/*">
                                <img id="pastedImagePreview" src="" alt="Pasted Image" style="max-width: 100%; margin-top: 10px; display: none;">
                                <button id="applyImageText" class="btn btn-primary" style="margin-top: 10px; display: none;">Apply</button>
                            </div>

                        </div>
                        <br>
                        <div class="form_footer">
                            <a href="{{route('admin.homework-questions.index')}}" class="btn btn-warning">Назад</a>

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
                URL.revokeObjectURL(output.src)
            }
        };

        document.addEventListener("DOMContentLoaded", function () {
            let input = document.querySelector("textarea[name='correct_answers']");
            let predictionBox = document.getElementById("predictions");

            let replacements = {
                "is not": "isn't",
                "are not": "aren't",
                "do not": "don't",
                "does not": "doesn't",
                "cannot": "can't",
                "will not": "won't",
                "would not": "wouldn't",
                "should not": "shouldn't",
                "he will": "he'll",
                "they will": "they'll",
                "I am": "I'm",
                "you are": "you're",
                "they are": "they're"
            };

            input.addEventListener("input", function () {
                let text = input.value;
                let sentences = text.split(/[.!?]/);
                let lastSentence = sentences[sentences.length - 1].trim();

                if (lastSentence.endsWith(" or")) {
                    let sentenceBeforeOr = lastSentence.slice(0, -3).trim();
                    showPredictions(sentenceBeforeOr);
                } else {
                    hidePredictions();
                }
            });

            function showPredictions(sentence) {
                predictionBox.innerHTML = "";
                predictionBox.style.opacity = "1";

                let modifiedSentences = getShortenedVersions(sentence);

                if (modifiedSentences.length === 0) {
                    hidePredictions();
                    return;
                }

                modifiedSentences.forEach(shortened => {
                    let div = document.createElement("div");
                    div.textContent = shortened;
                    div.addEventListener("click", function () {
                        applyPrediction(shortened);
                    });
                    predictionBox.appendChild(div);
                });
            }

            function getShortenedVersions(sentence) {
                let possibleSentences = [];

                for (let longForm in replacements) {
                    if (sentence.includes(longForm)) {
                        let shortened = sentence.replace(longForm, replacements[longForm]);
                        possibleSentences.push(shortened);
                    }
                }

                return possibleSentences;
            }

            function hidePredictions() {
                predictionBox.style.opacity = "0";
                predictionBox.innerHTML = "";
            }

            function applyPrediction(selectedText) {
                input.value += " " + selectedText;
                hidePredictions();
                input.focus();
            }
        });

        document.getElementById('imageUpload').addEventListener('change', function (event) {
            uploadImage(event.target.files[0]);
        });

        document.addEventListener("paste", function(event) {
            let items = (event.clipboardData || event.originalEvent.clipboardData).items;
            for (let item of items) {
                if (item.kind === 'file' && item.type.startsWith('image/')) {
                    let blob = item.getAsFile();
                    let fileInput = document.getElementById("imageUpload");

                    let dataTransfer = new DataTransfer();
                    dataTransfer.items.add(blob);
                    fileInput.files = dataTransfer.files;

                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let imgPreview = document.getElementById("pastedImagePreview");
                        imgPreview.src = e.target.result;
                        imgPreview.style.display = "block";
                        document.getElementById("applyImageText").style.display = "block";
                    };
                    reader.readAsDataURL(blob);

                    event.preventDefault();
                }
            }
        });

        document.getElementById("applyImageText").addEventListener("click", function() {
            event.preventDefault();

            let fileInput = document.getElementById("imageUpload");
            if (fileInput.files.length === 0) {
                alert("Iltimos, rasm yuklang yoki paste qiling.");
                return;
            }
            uploadImage(fileInput.files[0]);
        });

        function uploadImage(file) {
            let formData = new FormData();
            formData.append('image', file);

            fetch('/admin/homework-questions/process-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let questionsTextarea = document.getElementById("questionsTextarea");
                        let currentText = questionsTextarea.value;
                        questionsTextarea.value = currentText ? currentText + "\n" + data.text : data.text;
                    } else {
                        alert('Error processing image');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        document.getElementById('generateCorrectAnswers').addEventListener('click', function(event) {
            event.preventDefault();

            let homeworkCondition = document.getElementById('year-select').value;
            let questions = document.getElementById('questionsTextarea').value;

            if (!homeworkCondition || !questions) {
                alert("Iltimos, Homework Conditions va Questions maydonlarini to‘ldiring!");
                return;
            }

            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let url = "/admin/homework-questions/generate-correct-answers";

            fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    homework_id: homeworkCondition,
                    questions: questions
                })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Server xatosi!");
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('correctAnswersTextarea').value = data.correct_answers;
                })
                .catch(error => console.error("Error:", error));
        });


    </script>

@endsection
