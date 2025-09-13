<x-app-layout>
<div class="max-w-3xl mx-auto my-8">
  <h2 class="text-xl font-semibold">Question {{ $order }} of {{ $submission->interview->questions->count() }}</h2>
  <p class="mt-2 mb-4">{{ $question->prompt }}</p>

  <div id="alerts" class="text-sm text-red-600 mb-2"></div>

  <div class="aspect-video bg-black rounded mb-4 flex items-center justify-center">
    <video id="preview" autoplay playsinline muted class="w-full h-full rounded"></video>
  </div>

  <div class="flex items-center gap-3 mb-4">
    <button id="btnStart" class="px-4 py-2 bg-green-600 text-white rounded">Start</button>
    <button id="btnStop" class="px-4 py-2 bg-red-600 text-white rounded" disabled>Stop</button>
    <span id="timer" class="text-gray-600"></span>
  </div>

  <div class="flex gap-3">
    <button id="btnRetake" class="px-3 py-2 border rounded" disabled>Retake</button>
    <button id="btnNext" class="px-3 py-2 bg-blue-600 text-white rounded" disabled>Next</button>
  </div>
</div>

{{-- 🔒 Hidden POST form for finalize (avoids GET to a POST-only route) --}}
<form id="finalizeForm" method="POST" action="{{ route('candidate.finalize', $submission->id) }}" class="hidden">
  @csrf
</form>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
const timeLimit   = {{ $question->time_limit_seconds }};
const allowRetake = {{ $question->allow_retake ? 'true' : 'false' }};
const uploadUrl   = "{{ route('candidate.upload', [$submission->id, $question->id]) }}";
const nextUrlAfter= "{{ route('candidate.question', [$submission->id, $order+1]) }}";
const finalizeUrl = "{{ route('candidate.finalize', $submission->id) }}"; // (kept for reference)
const totalQs     = {{ $submission->interview->questions->count() }};
const isLast      = {{ $order }} >= totalQs;

let stream, recorder, chunks = [], retake = 1, timerInt, secs = 0, blob;

const $ = sel => document.querySelector(sel);
const alertBox = $('#alerts');
const preview  = $('#preview');

async function initCam() {
  try {
    stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
    preview.srcObject = stream;
  } catch (e) {
    alertBox.textContent = 'Camera/mic permission required.';
  }
}
initCam();

$('#btnStart').onclick = () => {
  if (!stream) return;
  alertBox.textContent = '';
  chunks = []; secs = 0; blob = null;

  const options = { mimeType: 'video/webm' };
  if (!MediaRecorder.isTypeSupported(options.mimeType)) delete options.mimeType;

  recorder = new MediaRecorder(stream, options);
  recorder.ondataavailable = e => { if (e.data && e.data.size) chunks.push(e.data); };
  recorder.onstop = () => {
    blob = new Blob(chunks, { type: 'video/webm' });
    $('#btnRetake').disabled = !allowRetake;
    $('#btnNext').disabled = false;
  };
  recorder.start();

  $('#btnStart').disabled = true;
  $('#btnStop').disabled  = false;
  startTimer();
};

$('#btnStop').onclick = () => {
  if (recorder && recorder.state !== 'inactive') recorder.stop();
  $('#btnStop').disabled  = true;
  $('#btnStart').disabled = true;
  stopTimer();
};

$('#btnRetake').onclick = () => {
  if (!allowRetake) return;
  retake++;
  blob = null;
  alertBox.textContent = `Retake #${retake}`;
  $('#btnStart').disabled = false;
  $('#btnRetake').disabled = true;
  $('#btnNext').disabled = true;
};

$('#btnNext').onclick = async () => {
  if (!blob) { alertBox.textContent = 'Please record first.'; return; }
  $('#btnNext').disabled = true;

  const ok = await upload(blob, secs, retake);
  if (!ok) {
    alertBox.textContent = 'Upload failed. Please try again.';
    $('#btnNext').disabled = false;
    return;
  }

  if (isLast) {
    // ✅ POST finalize instead of GET
    document.getElementById('finalizeForm').submit();
  } else {
    window.location.href = nextUrlAfter;
  }
};

async function upload(videoBlob, duration, retakeNumber){
  const fd = new FormData();
  fd.append('video', videoBlob, `answer-q{{ $question->id }}.webm`);
  fd.append('duration_seconds', duration);
  fd.append('retake_number', retakeNumber);

  try {
    const res = await fetch(uploadUrl, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: fd
    });
    return res.ok;
  } catch (e) {
    return false;
  }
}

function startTimer() {
  clearInterval(timerInt);
  $('#timer').textContent = `0s / ${timeLimit}s`;
  timerInt = setInterval(() => {
    secs++;
    $('#timer').textContent = `${secs}s / ${timeLimit}s`;
    if (secs >= timeLimit) { $('#btnStop').click(); }
  }, 1000);
}
function stopTimer(){ clearInterval(timerInt); }
</script>
</x-app-layout>
