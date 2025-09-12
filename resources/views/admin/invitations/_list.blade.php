<div class="space-y-3">
  @forelse($invitations as $inv)
    <div class="p-4 border rounded flex items-center justify-between">
      <div>
        <div class="font-medium">{{ $inv->candidate_email }}</div>
        <div class="text-sm text-gray-600">Link: {{ route('candidate.start',$inv->token) }}</div>
      </div>
      <form method="POST" action="{{ route('admin.interviews.invitations.destroy', [$interview, $inv]) }}">
        @csrf @method('DELETE')
        <button class="px-3 py-2 border rounded text-red-600" onclick="return confirm('Delete invitation?')">Delete</button>
      </form>
    </div>
  @empty
    <div class="text-gray-600">No invitations yet.</div>
  @endforelse
</div>
@if(method_exists($invitations,'links'))
  <div class="mt-6">{{ $invitations->links() }}</div>
@endif
