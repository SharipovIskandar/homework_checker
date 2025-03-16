document.addEventListener("DOMContentLoaded", async function () {
    let video = document.getElementById("video");
    let startBtn = document.getElementById("startTest");
    let stopBtn = document.getElementById("stopTest");
    let wordBox = document.getElementById("word-box");
    let statusBox = document.getElementById("status-box");
    let heardWordBox = document.getElementById("heard-word");
    let wordDataElement = document.getElementById("word-data");
    let resultRouteElement = document.getElementById("result-route");

    let faceStatusBox = document.getElementById("face-status-box");
    let liveSpeechBox = document.getElementById("live-speech");

    let audioContext, analyserNode, dataArray, canvas, canvasCtx;

    if (!video || !startBtn || !stopBtn || !wordBox || !statusBox || !heardWordBox || !wordDataElement || !resultRouteElement || !faceStatusBox || !liveSpeechBox) {
        return;
    }

    let words, index = 0, recognition, correctCount = 0, isRunning = false;
    let resultsLog = [];
    let answeredWords = new Set(); // To track answered words

    try {
        let jsonText = wordDataElement.textContent.trim();
        if (!jsonText) return;
        let parsedData = JSON.parse(jsonText);
        if (!Array.isArray(parsedData)) return;
        words = parsedData[0].split("\r\n").map(item => {
            let parts = item.split(" => ");
            return parts.length === 2 ? { en: parts[0], uz: parts[1] } : null;
        }).filter(Boolean);
    } catch (error) {
        return;
    }

    async function startCamera() {
        try {
            let stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            video.srcObject = stream;
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            analyserNode = audioContext.createAnalyser();
            analyserNode.fftSize = 256;
            dataArray = new Uint8Array(analyserNode.frequencyBinCount);
            let source = audioContext.createMediaStreamSource(stream);
            source.connect(analyserNode);

            canvas = document.createElement("canvas");
            canvas.width = 200;
            canvas.height = 100;
            liveSpeechBox.appendChild(canvas);
            canvasCtx = canvas.getContext("2d");

            drawFrequency();
        } catch (err) {
            alert("Kamera yoki mikrofonni yoqib bo‘lmadi!");
        }
    }

    function drawFrequency() {
        analyserNode.getByteFrequencyData(dataArray);
        canvasCtx.clearRect(0, 0, canvas.width, canvas.height);
        let width = canvas.width;
        let barWidth = width / dataArray.length;
        let maxHeight = canvas.height;

        for (let i = 0; i < dataArray.length; i++) {
            let barHeight = dataArray[i];
            canvasCtx.fillStyle = "rgb(" + (barHeight + 100) + ",50,50)";
            canvasCtx.fillRect(i * barWidth, maxHeight - barHeight, barWidth, barHeight);
        }
        requestAnimationFrame(drawFrequency);
    }

    function startSpeechRecognition() {
        if (!('webkitSpeechRecognition' in window)) return;
        recognition = new webkitSpeechRecognition();
        recognition.lang = "en-US";
        recognition.continuous = true;
        recognition.interimResults = true;

        recognition.onresult = function (event) {
            let transcript = event.results[event.results.length - 1][0].transcript.trim().toLowerCase();
            liveSpeechBox.innerHTML = `🔊 Jonli nutq: <b>"${transcript}"</b>`;

            if (!event.results[event.results.length - 1].isFinal) return;

            let correctWord = words[index].en.toLowerCase();
            heardWordBox.innerHTML = `Siz aytdingiz: <b>"${transcript}"</b>`;

            if (transcript === correctWord) {
                statusBox.innerHTML = `✅ To‘g‘ri!`;
                correctCount++;
                answeredWords.add(correctWord); // Mark the word as answered
                resultsLog.push({ word: correctWord, heard: transcript, correct: true });
                setTimeout(nextWord, 1000);
            } else {
                statusBox.innerHTML = `❌ Xato! <br> To‘g‘risi: "${correctWord}"`;
                resultsLog.push({ word: correctWord, heard: transcript, correct: false });
            }
        };

        recognition.onerror = function () {
            statusBox.innerHTML = "❌ Xatolik yuz berdi!";
        };

        recognition.start();
    }

    function nextWord() {
        index++;
        if (index < words.length) {
            // Skip words that have already been answered
            while (answeredWords.has(words[index].en.toLowerCase())) {
                index++;
            }

            if (index < words.length) {
                wordBox.innerHTML = `<b>${words[index].uz}</b>`;
                statusBox.innerHTML = "Inglizchasini ayting...";
                heardWordBox.innerHTML = "";
                recognition.start();
            } else {
                stopTest();
                wordBox.innerHTML = "✅ Test tugadi!";
                saveTestResult();
            }
        }
    }

    async function saveTestResult() {
        try {
            console.log("Test natijalari:", resultsLog);

            await fetch(resultRouteElement.textContent, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
                body: JSON.stringify({
                    correct_answers: correctCount,
                    incorrect_answers: resultsLog.filter(result => !result.correct).map(result => ({
                        word: result.word,
                        heard: result.heard
                    }))
                }),
            });

            alert("Natijalar saqlandi!");
        } catch (error) {
            alert("Xatolik yuz berdi!");
        }
    }

    function handleVisibilityChange() {
        if (document.hidden) {
            alert("Tabni o‘zgartirish mumkin emas! Test boshidan boshlanadi.");
            location.reload();
        }
    }

    async function loadFaceAPI() {
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri("/model");
            faceStatusBox.innerHTML = "✅ Yuz aniqlash modeli yuklandi!";
        } catch (error) {
            console.error("Face API yuklashda xatolik:", error);
            faceStatusBox.innerHTML = "❌ Face API yuklashda xatolik!";
        }
    }

    let faceTrackingInterval;

    async function trackFace() {
        if (!faceapi.nets.tinyFaceDetector.isLoaded) {
            console.log("Model hali yuklanmagan, 1 soniya kutib qayta urinamiz...");
            setTimeout(trackFace, 1000);
            return;
        }

        faceTrackingInterval = setInterval(async () => {
            let detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions());
            if (detections.length === 0) {
                faceStatusBox.innerHTML = "⚠️ Yuz ekrandan yo‘qoldi!";
                setTimeout(() => {
                    alert("Yuzingiz ekrandan yo‘qoldi! Test boshidan boshlanadi.");
                    clearInterval(faceTrackingInterval);
                    location.reload();
                }, 3000);
            } else {
                faceStatusBox.innerHTML = "✅ Yuz aniqlandi!";
            }
        }, 1000);
    }

    startBtn.addEventListener("click", async function () {
        if (isRunning) return;
        isRunning = true;
        await startCamera();
        startSpeechRecognition();
        wordBox.innerHTML = `<b>${words[index].uz}</b>`;
        statusBox.innerHTML = "Inglizchasini ayting...";
        heardWordBox.innerHTML = "";
        startBtn.disabled = true;
        stopBtn.disabled = false;
        document.addEventListener("visibilitychange", handleVisibilityChange);
        await loadFaceAPI();
        await trackFace();
    });

    stopBtn.addEventListener("click", function () {
        saveTestResult();
        stopTest();
    });

    function stopTest() {
        let tracks = video.srcObject?.getTracks();
        if (tracks) tracks.forEach(track => track.stop());
        wordBox.innerHTML = "Test yakunlandi";
        statusBox.innerHTML = "";
        heardWordBox.innerHTML = "";
        liveSpeechBox.innerHTML = "";
        startBtn.disabled = false;
        stopBtn.disabled = true;
        document.removeEventListener("visibilitychange", handleVisibilityChange);
        isRunning = false;

        clearInterval(faceTrackingInterval);
    }
});
