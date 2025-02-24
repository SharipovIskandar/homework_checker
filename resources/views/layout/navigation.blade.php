<nav class="bg-white shadow p-4">
    <div class="container mx-auto flex justify-between">
        <h1 class="text-xl font-bold">Dashboard</h1>
        <div>
            <a href="{{ route('dashboard') }}" class="text-blue-500">Bosh sahifa</a>
            <a href="{{ route('logout') }}" class="ml-4 text-red-500"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Chiqish
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</nav>
