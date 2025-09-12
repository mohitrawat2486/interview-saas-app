@php
  // Uses any of these flash keys, or an explicit "message" prop
  $msg = session('ok') ?? session('success') ?? session('error') ?? ($attributes->get('message'));
@endphp

@if ($msg)
  <div x-data="{ show:true }"
       x-init="setTimeout(()=>show=false, 2600)"
       x-show="show" x-transition
       role="status" aria-live="polite"
       class="fixed bottom-4 right-4 z-50">
    <div class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg bg-black text-white">
      <span class="text-sm">{{ $msg }}</span>
    </div>
  </div>
@endif
