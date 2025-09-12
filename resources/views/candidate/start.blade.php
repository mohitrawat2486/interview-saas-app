<x-guest-layout>
  <div class="max-w-2xl mx-auto my-12">
    <h1 class="text-2xl font-bold mb-4">{{ $interview->title }}</h1>
    @if(($interview->settings['welcome'] ?? null))
      <p class="mb-4">{{ $interview->settings['welcome'] }}</p>
    @endif
    <ul class="list-disc pl-6 mb-6">
      <li>{{ $interview->questions->count() }} questions</li>
      <li>Camera + microphone required</li>
    </ul>
    <form method="POST" action="{{ route('candidate.begin',$inv->token) }}">
      @csrf
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Start Interview</button>
    </form>
  </div>
</x-guest-layout>
