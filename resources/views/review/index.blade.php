<x-app-layout>
  <div class="max-w-5xl mx-auto my-8">
    <h1 class="text-2xl font-semibold mb-4">Submissions</h1>
    <div class="space-y-3">
      @forelse($subs as $s)
        <div class="p-4 border rounded flex items-center justify-between">
          <div>
            <div class="font-medium">{{ $s->interview->title }}</div>
            <div class="text-sm text-gray-600">Candidate: {{ $s->candidate->name }} — {{ $s->submitted_at?->diffForHumans() }}</div>
          </div>
          <a class="px-3 py-2 bg-blue-600 text-white rounded" href="{{ route('review.show',$s) }}">Open</a>
        </div>
      @empty
        <div class="text-gray-600">No submitted interviews yet.</div>
      @endforelse
    </div>
    <div class="mt-6">{{ $subs->links() }}</div>
  </div>
</x-app-layout>
