@extends('admin.layouts.app')

@section('breadcrumb')
    <ol class="breadcrumb pull-left">
        <li><a href="{{ route('student.vocabularies.index') }}">Студент Вокаб</a></li>
        <li class="active">{{ $label }}</li>
    </ol>
    <br>
@endsection

@section('customCss')
    <style>
        video {
            width: 100%;
            max-width: 600px;
            border-radius: 15px;
            border: 3px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        #word-box {
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            color: #2c3e50;
            background: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-container button {
            padding: 12px 25px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-container button:hover {
            background-color: #27ae60;
            color: white;
        }

        #status-box {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 20px;
        }

        #heard-word {
            text-align: center;
            font-size: 24px;
            color: #8e44ad;
            margin-top: 10px;
        }

        #face-status-box {
            font-size: 18px;
            color: #e74c3c;
            margin-top: 10px;
        }

        #live-speech {
            font-size: 18px;
            color: #3498db;
            margin-top: 10px;
        }

        .hidden {
            display: none;
        }

        .word-data-box {
            background-color: #f0f0f0;
            padding: 10px;
            margin-top: 30px;
            border-radius: 10px;
            font-size: 16px;
            color: #7f8c8d;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">{{ $label }}</h4>
        </div>
        <div class="panel-body text-center">
            <video id="video" autoplay playsinline></video>
            <p id="word-box">Boshlash uchun "Start" tugmasini bosing</p>

            <div id="face-status-box">🔍 Yuz aniqlash statusi...</div>
            <div id="live-speech">🔊 Jonli nutq...</div>

            <p id="status-box"></p>
            <p id="heard-word"></p>

            <div class="btn-container">
                <button id="startTest" class="btn btn-success">Start</button>
                <button id="stopTest" class="btn btn-danger" disabled>Finish</button>
                <button id="skipWord" class="btn btn-warning">Skip</button>
                <button id="markAsFinished" class="btn btn-success">Mark as finished</button>
            </div>

            <div class="word-data-box hidden" id="word-data">{{ json_encode($model->word) }}</div>

            <div id="result-route" class="hidden">
                {{ route('student.vocabularies.storeResult', $model) }}
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
    <script src="{{ asset('js/vocabularyTest.js') }}"></script>
    <script>
        document.getElementById('markAsFinished').addEventListener('click', function() {
            let resultRouteElement = document.getElementById("result-route");

            fetch(resultRouteElement.textContent, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                        "content"),
                },
                body: JSON.stringify({
                    is_accepted: true,
                }),
            }).then(response => {
                if (response.ok) {
                    alert('Success!');
                    // Redirect to the desired route if success
                    window.location.href =
                    "{{ route('student.vocabularies.index') }}"; // Update the route as needed
                } else {
                    alert('Failed to mark as finished');
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Error occurred while making request');
            });
        });
    </script>
@endsection
