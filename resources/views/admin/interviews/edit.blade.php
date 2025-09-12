<x-app-layout>
  <div class="max-w-5xl mx-auto my-8 space-y-8">
    <div>
      <h1 class="text-2xl font-semibold mb-4">Edit Interview</h1>
      <form method="POST" action="{{ route('admin.interviews.update',$interview) }}" class="space-y-4">
        @csrf @method('PUT')
        <label class="block"><span class="text-sm">Title</span>
          <input name="title" class="w-full border rounded p-2" value="{{ $interview->title }}" required>
        </label>
        <label class="block"><span class="text-sm">Description</span>
          <textarea name="description" rows="3" class="w-full border rounded p-2">{{ $interview->description }}</textarea>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <label class="block"><span class="text-sm">Default Time/Question (sec)</span>
            <input type="number" name="time_limit_per_question" class="w-full border rounded p-2" value="{{ $interview->settings['time_limit_per_question'] ?? 120 }}">
          </label>
          <label class="block"><span class="text-sm">Allow Retakes</span>
            <select name="allow_retakes" class="w-full border rounded p-2">
              <option value="1" @selected(($interview->settings['allow_retakes'] ?? true))>Yes</option>
              <option value="0" @selected(!($interview->settings['allow_retakes'] ?? true))>No</option>
            </select>
          </label>
        </div>
        <label class="block"><span class="text-sm">Welcome Message</span>
          <textarea name="welcome" rows="2" class="w-full border rounded p-2">{{ $interview->settings['welcome'] ?? '' }}</textarea>
        </label>
        <label class="block"><span class="text-sm">Thanks Message</span>
          <textarea name="thanks" rows="2" class="w-full border rounded p-2">{{ $interview->settings['thanks'] ?? '' }}</textarea>
        </label>
        <div class="flex gap-3">
          <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
          <a href="{{ route('admin.interviews.index') }}" class="px-4 py-2 border rounded">Back</a>
        </div>
      </form>
    </div>

    <div>
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-xl font-semibold">Questions</h2>
        <a class="px-3 py-2 border rounded" href="{{ route('admin.interviews.questions.create',$interview) }}">Add Question</a>
      </div>
      @if($interview->questions->isEmpty())
        <div class="text-gray-600">No questions yet.</div>
      @else
        <div class="space-y-3">
          @foreach($interview->questions as $q)
            <div class="p-4 border rounded flex items-center justify-between">
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
          @endforeach
        </div>
      @endif
    </div>

    <div>
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-xl font-semibold">Invitations</h2>
        <a class="px-3 py-2 border rounded" href="{{ route('admin.interviews.invitations.create',$interview) }}">New Invitation</a>
      </div>
      @include('admin.invitations._list',['interview'=>$interview,'invitations'=>$interview->invitations()->latest()->paginate(50)])
    </div>
  </div>
</x-app-layout>
