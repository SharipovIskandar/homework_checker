<aside class="w-64 bg-gray-800 text-white h-screen p-4">
    <ul>
        <li><a href="{{ route('dashboard') }}" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a></li>
        @if(auth()->user()->role->name === 'teacher')
            <li><a href="{{ route('homeworks.index') }}" class="block py-2 px-4 hover:bg-gray-700">Uy vazifalari</a></li>
        @endif
        @if(auth()->user()->role->name === 'student')
            <li><a href="{{ route('student.homeworks') }}" class="block py-2 px-4 hover:bg-gray-700">Mening vazifalarim</a></li>
        @endif
    </ul>
</aside>
