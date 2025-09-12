<x-app-layout>
  <div class="max-w-5xl mx-auto my-8">
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-2xl font-semibold">Questions</h1>
      <a class="px-3 py-2 border rounded" href="{{ route('admin.interviews.questions.create',$interview) }}">Add Question</a>
    </div>
    @forelse($questions as $q)
      <div class="p-4 border rounded flex items-center justify-between mb-3">
        <div>
          <div class="font-medium">#{{ $q->order }} — {{ $q->prompt }}</div>
          <div class="text-sm text-gray-600">{{ $q->time_limit_seconds }}s • retakes: {{ $q->allow_retake ? 'yes' : 'no' }}</div>
        </div>
        <div class="flex gap-2">
          <a class="px-3 py-2 border rounded" href="{{ route('admin.questions.edit',$q) }}">Edit</a>
          <form method="POST" action="{{ route('admin.questions.destroy',$q) }}">
            @csrf @method('DELETE')
            <button class="px-3 py-2 border rounded text-red-600" onclick="return confirm('Delete question?')">Delete</button>
          </form>
        </div>
      </div>
    @empty
      <div class="text-gray-600">No questions yet.</div>
    @endforelse
  </div>
</x-app-layout>
