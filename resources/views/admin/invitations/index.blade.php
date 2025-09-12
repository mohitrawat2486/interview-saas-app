<x-app-layout>
  <div class="max-w-5xl mx-auto my-8">
    <div class="flex items-center justify-between mb-3">
      <h1 class="text-2xl font-semibold">Invitations — {{ $interview->title }}</h1>
      <a class="px-3 py-2 border rounded" href="{{ route('admin.interviews.invitations.create',$interview) }}">New Invitation</a>
    </div>

    @include('admin.invitations._list', [
      'interview' => $interview,
      'invitations' => $invitations
    ])

    <div class="mt-6">
      <a href="{{ route('admin.interviews.edit',$interview) }}" class="text-sm underline text-gray-600">
        ← Back to interview
      </a>
    </div>
  </div>
</x-app-layout>
