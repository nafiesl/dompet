<header class="shadow bg-white p-4">
  <nav class="md:mx-16 flex items-center justify-between">
    <a href="/">Dompet</a>
    {{-- Todo: replace login link with logout link when user logged in --}}
    @guest
      <a href="/login" class="border-2 border-black border-solid rounded p-2">Masuk</a>
    @else
      <button onclick="event.preventDefault();document.getElementById('logout-form').submit();" 
        class="border-2 border-black border-solid rounded p-2">Logout</button>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          <input type="submit" value="{{ __('auth.logout') }}" style="display: none;">
          {{ csrf_field() }}
      </form>
    @endguest
  </nav>
</header>