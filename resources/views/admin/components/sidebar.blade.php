<aside class="bg-white shadow h-screen w-64 fixed top-0 left-0 flex flex-col z-40">
    <div class="p-6 border-b">
        <span class="text-xl font-bold text-indigo-700">Admin Panel</span>
    </div>
    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-indigo-50 text-gray-700 font-medium">Dashboard</a>
        <a href="{{ route('admin.books.create') }}" class="block px-4 py-2 rounded hover:bg-indigo-50 text-indigo-600 font-semibold bg-indigo-100 flex items-center gap-2">
            <!-- Plus Icon SVG -->
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Book
        </a>
        <!-- Add more sidebar links here -->
    </nav>
</aside>