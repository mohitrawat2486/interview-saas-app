<x-app-layout>
  <style>[x-cloak]{ display:none !important; }</style>
  <div x-data="interviewsUI()" class="max-w-6xl mx-auto my-8">

    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-semibold">Interviews</h1>
      <div class="flex gap-2">
        <button type="button" class="px-3 py-2 border rounded" x-on:click="view='list'" :class="view==='list' && 'bg-gray-900 text-white'">List</button>
        <button type="button" class="px-3 py-2 border rounded" x-on:click="view='grid'" :class="view==='grid' && 'bg-gray-900 text-white'">Grid</button>
        <button type="button" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" x-on:click="openCreate()">Create</button>
      </div>
    </div>

    <!-- Toolbar -->
    <form id="toolbar" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3 items-center" x-on:submit.prevent="applyQuery(new FormData($event.target))">
      <div class="col-span-2">
        <input name="q" value="{{ request('q') }}" placeholder="Search by title…" class="w-full border rounded px-3 py-2"
               x-ref="search" x-on:input.debounce.300ms="applyQuery(new FormData($root.querySelector('#toolbar')))">
      </div>
      <div>
        <select name="sort" class="w-full border rounded px-3 py-2" x-on:change="applyQuery(new FormData($root.querySelector('#toolbar')))">
          @php $sort=request('sort','-created_at'); @endphp
          <option value="-created_at" @selected($sort==='-created_at')>Newest</option>
          <option value="created_at"  @selected($sort==='created_at')>Oldest</option>
          <option value="title"       @selected($sort==='title')>Title A–Z</option>
          <option value="-questions_count" @selected($sort==='-questions_count')>Most questions</option>
          <option value="-submissions_count" @selected($sort==='-submissions_count')>Most submissions</option>
        </select>
      </div>
      <div>
        <select name="per" class="w-full border rounded px-3 py-2" x-on:change="applyQuery(new FormData($root.querySelector('#toolbar')))">
          @php $per=(int)request('per',10); @endphp
          @foreach([10,20,30,50] as $n)
            <option value="{{ $n }}" @selected($per===$n)>{{ $n }}/page</option>
          @endforeach
        </select>
      </div>
    </form>

    <!-- List area (AJAX replaced) -->
    <div id="list" x-ref="list">
      @include('admin.interviews._list', ['interviews'=>$interviews])
    </div>

    <!-- Delete Modal -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" x-on:click="showModal=false"></div>
      <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="text-lg font-semibold mb-2">Delete interview?</h3>
        <p class="text-sm text-gray-600 mb-4">This action cannot be undone.</p>
        <div class="flex justify-end gap-2">
          <button class="px-3 py-2 border rounded" x-on:click="showModal=false">Cancel</button>
          <form x-ref="deleteForm" method="POST" x-bind:action="modal.url" x-on:submit.prevent="doDelete()">
            @csrf @method('DELETE')
            <button class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Create Modal -->
    <div x-show="showCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
      <div class="absolute inset-0 bg-black/50" x-on:click="showCreate=false"></div>
      <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
        <h3 class="text-lg font-semibold mb-3">Create Interview</h3>
        <form x-ref="createForm" class="space-y-3" x-on:submit.prevent="createInterview()">
          @csrf
          <label class="block">
            <span class="text-sm">Title</span>
            <input name="title" class="w-full border rounded px-3 py-2" required>
          </label>
          <label class="block">
            <span class="text-sm">Description</span>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
          </label>
          <div class="flex justify-end gap-2">
            <button type="button" class="px-3 py-2 border rounded" x-on:click="showCreate=false">Cancel</button>
            <button class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Toast -->
    <div x-show="toast.show" x-cloak x-transition
         class="fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-lg shadow">
      <span x-text="toast.message"></span>
    </div>
  </div>

  <script>
    function interviewsUI(){
      return {
        view: localStorage.getItem('interviews_view') || 'list',
        showModal: false, modal: { id:null, url:'' },
        showCreate: false,
        toast: { show:false, message:'' },

        openCreate(){ this.showCreate = true; },
        confirmDelete({id,url}){ this.modal = {id,url}; this.showModal = true; },

        async createInterview(){
          const fd = new FormData(this.$refs.createForm);
          const res = await fetch("{{ route('admin.interviews.store') }}", {
            method: 'POST',
            headers: { 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
            body: fd
          });
          if (res.ok) {
            const data = await res.json();
            window.location.href = data.redirect; // go edit it
          } else {
            this.notify('Create failed');
          }
        },

        async doDelete(){
          this.showModal = false;
          const row = document.getElementById('row-'+this.modal.id) || document.getElementById('card-'+this.modal.id);
          if (row) { row.style.opacity=0; setTimeout(()=>row.remove(), 200); }
          const res = await fetch(this.modal.url, {
            method:'POST',
            headers:{ 'X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':'{{ csrf_token() }}' },
            body: new URLSearchParams({ '_method':'DELETE', '_token':'{{ csrf_token() }}' })
          });
          this.notify(res.ok ? 'Interview deleted' : 'Delete failed');
          // reload current page fragment to refresh pagination if needed
          this.reloadList(new URL(window.location.href));
        },

        notify(msg){ this.toast.message=msg; this.toast.show=true; setTimeout(()=>this.toast.show=false, 2200); },

        async applyQuery(fd){
          const url = new URL(window.location.href);
          url.search = new URLSearchParams(fd).toString();
          await this.reloadList(url);
        },

        async reloadList(url){
          const html = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' } }).then(r=>r.text());
          const doc  = new DOMParser().parseFromString(html,'text/html');
          const part = doc.querySelector('#listContent');
          this.$refs.list.innerHTML = part ? part.outerHTML : html;
          window.history.replaceState({}, '', url);
          // intercept pagination clicks for AJAX
          this.$nextTick(()=> {
            this.$refs.list.querySelectorAll('a').forEach(a => {
              if (a.href.includes('page=')) {
                a.addEventListener('click', (e)=>{ e.preventDefault(); this.reloadList(new URL(a.href)); });
              }
            });
          });
        },

        $watch: { view(v){ localStorage.setItem('interviews_view', v); } },

        // shortcuts
        init(){ document.addEventListener('keydown', (e)=>{ if(e.key==='/'){ e.preventDefault(); this.$refs.search.focus(); } }); }
      }
    }
  </script>
</x-app-layout>
