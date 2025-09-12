<x-app-layout>
  <div class="max-w-xl mx-auto my-8">
    <h1 class="text-2xl font-semibold mb-4">New Invitation</h1>
    <form method="POST" action="{{ route('admin.interviews.invitations.store',$interview) }}" class="space-y-4">
      @csrf
      <label class="block">
        <span class="text-sm">Candidate Email</span>
        <input type="email" name="candidate_email" class="w-full border rounded p-2" required>
      </label>
      <label class="block">
        <span class="text-sm">Expires At (optional)</span>
        <input type="datetime-local" name="expires_at" class="w-full border rounded p-2">
      </label>
      <div class="flex gap-3">
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Create</button>
        <a href="{{ route('admin.interviews.edit',$interview) }}" class="px-4 py-2 border rounded">Back</a>
      </div>
    </form>
  </div>
</x-app-layout>
