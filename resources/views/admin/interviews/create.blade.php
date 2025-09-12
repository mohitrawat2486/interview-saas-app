<x-app-layout>
  <div class="max-w-3xl mx-auto my-8">
    <h1 class="text-2xl font-semibold mb-4">Create Interview</h1>
    <form method="POST" action="{{ route('admin.interviews.store') }}" class="space-y-4">
      @csrf
      <label class="block">
        <span class="text-sm">Title</span>
        <input name="title" class="w-full border rounded p-2" required>
      </label>
      <label class="block">
        <span class="text-sm">Description</span>
        <textarea name="description" rows="3" class="w-full border rounded p-2"></textarea>
      </label>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <label class="block">
          <span class="text-sm">Default Time/Question (sec)</span>
          <input type="number" name="time_limit_per_question" class="w-full border rounded p-2" value="120">
        </label>
        <label class="block">
          <span class="text-sm">Allow Retakes</span>
          <select name="allow_retakes" class="w-full border rounded p-2">
            <option value="1" selected>Yes</option>
            <option value="0">No</option>
          </select>
        </label>
      </div>
      <label class="block">
        <span class="text-sm">Welcome Message</span>
        <textarea name="welcome" rows="2" class="w-full border rounded p-2"></textarea>
      </label>
      <label class="block">
        <span class="text-sm">Thanks Message</span>
        <textarea name="thanks" rows="2" class="w-full border rounded p-2"></textarea>
      </label>
      <div class="flex gap-3">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
        <a href="{{ route('admin.interviews.index') }}" class="px-4 py-2 border rounded">Cancel</a>
      </div>
    </form>
  </div>
</x-app-layout>
