<div id="listContent">
  <div x-show="view==='list'" class="space-y-3">
    @forelse($interviews as $i)
      <div id="row-{{ $i->id }}" class="p-4 border rounded flex items-center justify-between transition">
        <div class="min-w-0">
          <div class="font-medium truncate" title="{{ $i->title }}">{{ $i->title }}</div>
          <div class="text-sm text-gray-600 flex items-center gap-2">
            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-indigo-500"></span>{{ $i->questions_count }} Qs</span>
            <span>•</span>
            <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>{{ $i->submissions_count }} Subs</span>
            <span>•</span>
            <span class="text-gray-500">Created {{ $i->created_at?->diffForHumans() }}</span>
          </div>
        </div>
        <div class="flex items-center gap-2">
          <a class="px-3 py-2 border rounded hover:bg-gray-50" href="{{ route('admin.interviews.edit',$i) }}">Edit</a>
          <button class="px-3 py-2 border rounded text-red-600 hover:bg-red-50"
                  x-on:click="confirmDelete({ id: {{ $i->id }}, url: '{{ route('admin.interviews.destroy',$i) }}' })">
            Delete
          </button>
        </div>
      </div>
    @empty
      <div class="text-gray-600">No interviews yet.</div>
    @endforelse
  </div>

  <div x-show="view==='grid'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($interviews as $i)
      <div id="card-{{ $i->id }}" class="p-4 border rounded flex flex-col gap-3 transition">
        <div class="font-medium" title="{{ $i->title }}">{{ $i->title }}</div>
        <div class="flex gap-2 text-sm text-gray-600">
          <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700">{{ $i->questions_count }} Qs</span>
          <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">{{ $i->submissions_count }} Subs</span>
        </div>
        <div class="mt-auto flex items-center justify-between">
          <a class="px-3 py-2 border rounded hover:bg-gray-50" href="{{ route('admin.interviews.edit',$i) }}">Edit</a>
          <button class="px-3 py-2 border rounded text-red-600 hover:bg-red-50"
                  x-on:click="confirmDelete({ id: {{ $i->id }}, url: '{{ route('admin.interviews.destroy',$i) }}' })">
            Delete
          </button>
        </div>
      </div>
    @empty
      <div class="text-gray-600">No interviews yet.</div>
    @endforelse
  </div>

  <div class="mt-6" x-ref="pagination">
    {{ $interviews->links() }}
  </div>
</div>
