<header id="blur-header"
    class="fixed top-0 left-0 w-full z-30 transition-all duration-300 ease-in-out bg-white/40 backdrop-blur-lg shadow-none">
    <div class="container mx-auto flex justify-between items-center py-6 px-4 transition-all duration-300 ease-in-out"
        id="header-content">
        <div class="flex items-center space-x-2">
            <a href="{{ url('/') }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                <img src="https://svgshare.com/i/14wB.svg" alt="Logo" class="w-8 h-8" />
                <span class="font-bold text-xl text-gray-900">BookNest</span>
            </a>
        </div>
        <nav class="flex items-center space-x-8 font-medium text-gray-900">
            <a href="{{ url('/') }}" class="hover:text-indigo-600">Home</a>
            <a href="{{ route('books.bookshelf') }}" class="hover:text-indigo-600">Bookshelf</a>
            <a href="#" class="hover:text-indigo-600">About Us</a>
            <a href="{{ route('publish') }}" class="hover:text-indigo-600">Publish With Us</a>
        </nav>
        <div class="flex items-center space-x-3">
            <!-- Always-visible Search Box -->
            <form action="#" method="GET"
                class="flex items-center bg-white rounded-full shadow px-3 py-1 mr-3 border border-gray-200">
                <input type="text" name="q" class="outline-none px-2 py-1 bg-transparent text-gray-700"
                    placeholder="Search books..." autocomplete="off" />
                <button type="submit" class="text-indigo-600 hover:text-indigo-800 px-2">
                    <i class="fa fa-search"></i>
                </button>
            </form>
            <!-- Social Icons -->
            <a href="#" class="text-gray-500 hover:text-indigo-600"><i class="fa fa-facebook"></i></a>
            <a href="#" class="text-gray-500 hover:text-indigo-600"><i class="fa fa-twitter"></i></a>
            <a href="#" class="text-gray-500 hover:text-indigo-600"><i class="fa fa-instagram"></i></a>
            <a href="#" class="text-gray-500 hover:text-indigo-600"><i class="fa fa-linkedin"></i></a>
            <a href="{{ route('login') }}"
                class="ml-4 px-6 py-2 bg-black text-white rounded-full font-semibold hover:bg-indigo-700 transition">Login</a>
        </div>
    </div>
</header>