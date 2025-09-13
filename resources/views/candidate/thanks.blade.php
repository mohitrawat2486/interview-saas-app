<x-guest-layout>
  <div class="max-w-xl mx-auto my-16 text-center">
    <h2 class="text-2xl font-bold mb-4">Thanks!</h2>
    <p>Your interview was submitted successfully.</p>

    @auth
      <div class="mt-6 text-sm">
        <a href="{{ route('logout') }}"
           class="text-blue-600 underline hover:text-blue-700"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          Logout
        </a>
      </div>
      <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
      </form>
    @endauth
  </div>
</x-guest-layout>
