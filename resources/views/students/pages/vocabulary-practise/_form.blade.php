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
            max-width: 500px;
            border-radius: 10px;
            border: 2px solid #ddd;
        }

        #word-box {
            font-size: 30px;
            font-weight: bold;
            text-align: center;
            color: #2c3e50;
            background: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .btn-container button {
            margin: 0 10px;
            padding: 10px 20px;
            font-size: 18px;
        }

        #status-box {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
        }

        #heard-word {
            text-align: center;
            font-size: 20px;
            color: #555;
            margin-top: 10px;
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
                <button id="stopTest" class="btn btn-danger" disabled>Stop</button>
                <button id="skipWord">Skip</button>
            </div>

            <div id="word-data" style="display:none;">{{ json_encode($model->word) }}</div>


            <div id="result-route" style="display:none;">
                {{ route('student.vocabularies.storeResult', $model) }}
            </div>
        </div>
    </div>
@endsection

@section('customJs')
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
    <script src="{{ asset('js/vocabularyTest.js') }}"></script>
@endsection
