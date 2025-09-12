<x-app-layout>
  <div class="max-w-3xl mx-auto my-8">
    <h1 class="text-2xl font-semibold mb-4">Add Question</h1>
    <form method="POST" action="{{ route('admin.interviews.questions.store',$interview) }}" class="space-y-4">
      @csrf
      <label class="block">
        <span class="text-sm">Prompt</span>
        <textarea name="prompt" class="w-full border rounded p-2" rows="3" required></textarea>
      </label>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <label class="block">
          <span class="text-sm">Order</span>
          <input type="number" min="1" name="order" class="w-full border rounded p-2" value="{{ ($interview->questions->max('order') ?? 0) + 1 }}">
        </label>
        <label class="block">
          <span class="text-sm">Time Limit (sec)</span>
          <input type="number" min="10" max="900" name="time_limit_seconds" class="w-full border rounded p-2" value="{{ $interview->settings['time_limit_per_question'] ?? 120 }}">
        </label>
        <label class="block">
          <span class="text-sm">Thinking Time (sec)</span>
          <input type="number" min="0" max="60" name="thinking_time_seconds" class="w-full border rounded p-2" value="5">
        </label>
      </div>
      <label class="block">
        <span class="text-sm">Allow Retake</span>
        <select name="allow_retake" class="w-full border rounded p-2">
          <option value="1" selected>Yes</option>
          <option value="0">No</option>
        </select>
      </label>
      <div class="flex gap-3">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Add</button>
        <a href="{{ route('admin.interviews.edit',$interview) }}" class="px-4 py-2 border rounded">Cancel</a>
      </div>
    </form>
  </div>
</x-app-layout>
