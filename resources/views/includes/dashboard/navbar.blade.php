<nav id="navbar"
    class="bg-white border-b border-gray-200 flex items-center h-16 fixed top-0 right-0 w-full md:w-[calc(100%-250px)] px-5 md:px-10">
    <div id="menu-bar" class="block md:hidden">
        <i class="fa-solid fa-bars text-2xl"></i>
    </div>
    <div class="w-full flex items-center justify-end gap-x-4">
        <div class="flex items-center gap-x-1">
            <i class="fa-solid fa-user text-sm"></i>
            <span>{{ Auth::user()->nama }} ({{ Auth::user()->role->role_name }})</span>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                class="flex items-center px-2 py-1.5 border rounded border-red-400 text-red-400 text-xs hover:bg-red-600 hover:text-white cursor-pointer"><i
                    class="fa-solid fa-arrow-right-from-bracket"></i>Logout</button>
        </form>
    </div>
</nav>
