<x-app-layout>
  <div class="max-w-3xl mx-auto my-8">
    <h1 class="text-2xl font-semibold mb-4">Edit Question</h1>
    <form method="POST" action="{{ route('admin.questions.update',$question) }}" class="space-y-4">
      @csrf @method('PUT')
      <label class="block">
        <span class="text-sm">Prompt</span>
        <textarea name="prompt" class="w-full border rounded p-2" rows="3" required>{{ $question->prompt }}</textarea>
      </label>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <label class="block">
          <span class="text-sm">Order</span>
          <input type="number" min="1" name="order" class="w-full border rounded p-2" value="{{ $question->order }}">
        </label>
        <label class="block">
          <span class="text-sm">Time Limit (sec)</span>
          <input type="number" min="10" max="900" name="time_limit_seconds" class="w-full border rounded p-2" value="{{ $question->time_limit_seconds }}">
        </label>
        <label class="block">
          <span class="text-sm">Thinking Time (sec)</span>
          <input type="number" min="0" max="60" name="thinking_time_seconds" class="w-full border rounded p-2" value="{{ $question->thinking_time_seconds }}">
        </label>
      </div>
      <label class="block">
        <span class="text-sm">Allow Retake</span>
        <select name="allow_retake" class="w-full border rounded p-2">
          <option value="1" @selected($question->allow_retake)>Yes</option>
          <option value="0" @selected(!$question->allow_retake)>No</option>
        </select>
      </label>
      <div class="flex gap-3">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        <a href="{{ route('admin.interviews.edit',$question->interview_id) }}" class="px-4 py-2 border rounded">Back</a>
      </div>
    </form>
  </div>
</x-app-layout>
