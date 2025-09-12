<x-app-layout>
<div class="max-w-5xl mx-auto my-8">
  <h1 class="text-2xl font-semibold mb-2">
    {{ $submission->interview->title }} — {{ $submission->candidate->name }}
  </h1>
  <form method="POST" action="{{ route('review.store',$submission) }}" class="space-y-6">
    @csrf

    @foreach($submission->interview->questions as $q)
      @php $ans = $submission->answers->firstWhere('question_id',$q->id); @endphp
      <div class="p-4 border rounded">
        <h3 class="font-medium mb-2">Q{{ $q->order }}: {{ $q->prompt }}</h3>
        @if($ans)
          <video controls class="w-full rounded mb-3" src="{{ route('answer.stream', $ans) }}"></video>
        @else
          <div class="text-sm text-gray-500 mb-3">No answer.</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <label class="block">
            <span class="text-sm">Score (1-10)</span>
            <input type="number" min="1" max="10" name="items[{{ $q->id }}][score]" class="w-full border rounded p-2">
          </label>
          <label class="block md:col-span-2">
            <span class="text-sm">Comment</span>
            <textarea name="items[{{ $q->id }}][comment]" class="w-full border rounded p-2" rows="2"></textarea>
          </label>
        </div>
      </div>
    @endforeach

    <div class="p-4 border rounded">
      <h3 class="font-medium mb-2">Overall</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <label class="block"><span class="text-sm">Overall Score</span>
          <input type="number" min="1" max="10" name="overall_score" class="w-full border rounded p-2">
        </label>
        <label class="block md:col-span-2"><span class="text-sm">Overall Comment</span>
          <textarea name="overall_comment" class="w-full border rounded p-2" rows="3"></textarea>
        </label>
      </div>
    </div>

    <button class="px-4 py-2 bg-blue-600 text-white rounded">Save Review</button>
  </form>
</div>
</x-app-layout>
