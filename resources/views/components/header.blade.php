<!-- Alpine.js for dropdown functionality -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Scroll script for blur-header effect -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('blur-header');
    window.addEventListener('scroll', function () {
        if (window.scrollY > 60) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});
</script>

<header id="blur-header"
    class="fixed top-0 left-0 w-full z-30 transition-all duration-300 ease-in-out bg-white/40 backdrop-blur-lg shadow-none">
    <div x-data="{ openNav: false, openMore: false, search: '', loading: false }"
         class="container mx-auto flex flex-wrap items-center justify-between py-4 px-4 transition-all duration-300 ease-in-out"
         id="header-content">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <a href="{{ url('/') }}" class="flex items-center space-x-2 hover:opacity-80 transition">
                <img src="/images/PHOTO-2025-07-19-16-17-13.jpg" alt="Logo" class="w-10 h-10 rounded-full" />
                <span class="font-bold text-xl text-gray-900">Sahityaa Sangamm</span>
            </a>
        </div>
        <!-- Hamburger menu button for mobile -->
        <button @click="openNav = !openNav" class="lg:hidden flex items-center ml-auto text-gray-900 hover:text-indigo-600 focus:outline-none" aria-label="Toggle navigation">
            <svg x-show="!openNav" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg x-show="openNav" class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center space-x-8 font-medium text-gray-900">
            <a href="{{ url('/') }}" class="hover:text-indigo-600">Home</a>
            <a href="{{ route('books.bookshelf') }}" class="hover:text-indigo-600">Bookshelf</a>
            <a href="{{ route('library.index') }}" class="hover:text-indigo-600">My Library</a>
            <a href="{{ route('publish') }}" class="hover:text-indigo-600">Publish With Us</a>
            <a href="#" class="hover:text-indigo-600">About Us</a>
            @auth
                <!-- Desktop: No "Your Cart" or "More" here! -->
            @endauth
        </nav>
        <!-- Desktop Right Section (user dropdown) -->
        <div class="hidden lg:flex items-center space-x-3">
            <!-- LIVE SEARCH BOX -->
            <div class="relative mr-3" x-data>
                <form action="#" method="GET"
                      class="flex items-center bg-white rounded-full shadow px-3 py-1 border border-gray-200">
                    <input type="text"
                           name="q"
                           x-model="search"
                           class="outline-none px-2 py-1 bg-transparent text-gray-700"
                           placeholder="Search books..."
                           autocomplete="off"
                           @input.debounce.300ms="
                               loading = true;
                               fetch('{{ route('books.ajaxSearch') }}?q=' + encodeURIComponent(search))
                                   .then(response => response.text())
                                   .then(html => {
                                       $refs.dropdown.innerHTML = html;
                                       loading = false;
                                   });
                           "
                    />
                    <button type="submit" class="text-indigo-600 hover:text-indigo-800 px-2">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
                <div x-show="loading" class="absolute left-0 right-0 bg-white z-40 px-4 py-3 rounded shadow mt-1 text-center">
                    <i class="fa fa-spinner fa-spin text-indigo-600"></i> Searching...
                </div>
                <!-- Dropdown AJAX Results -->
                <div x-ref="dropdown"
                     x-show="search.length && !loading"
                     class="absolute left-0 right-0 bg-white z-40 px-4 py-3 rounded shadow mt-1"
                     style="max-height: 400px; overflow-y: auto;"
                     @click.away="search = ''"></div>
            </div>

            @auth
                <!-- User avatar and name (dropdown for profile/logout) -->
                <div x-data="{ open: false }" class="relative ml-4">
                    <button @click="open = !open" type="button"
                        class="flex items-center px-6 py-2 bg-black text-white rounded-full font-semibold hover:bg-indigo-700 transition focus:outline-none"
                        aria-haspopup="true" :aria-expanded="open">
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}"
                            alt="Avatar"
                            class="w-7 h-7 rounded-full mr-2 border border-white shadow">
                        <span>{{ Auth::user()->name }}</span>
                        <i class="fa fa-caret-down ml-2"></i>
                    </button>
                    <!-- Dropdown menu -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                        class="absolute right-0 mt-2 w-56 bg-white rounded shadow-lg border border-gray-100 py-2 z-40"
                        style="display: none;">
                        <a href="{{ route('profile.show') }}" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <i class="fa fa-user mr-2"></i> My Profile
                        </a>
                        <a href="{{ route('library.index') }}" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <i class="fa fa-book mr-2"></i> My Library
                        </a>
                        <a href="{{ route('cart.index') }}" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <i class="fa fa-shopping-cart mr-2"></i> Your Cart
                        </a>
                        <a href="" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <i class="fa fa-shopping-bag mr-2"></i> Orders
                        </a>
                        <div class="border-t my-2"></div>
                        <a href="{{ route('logout') }}" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out mr-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            @else
                <!-- User is NOT logged in -->
                <a href="{{ route('login') }}"
                   class="ml-4 px-6 py-2 bg-black text-white rounded-full font-semibold hover:bg-indigo-700 transition">Login</a>
            @endauth
        </div>
        <!-- Mobile Nav (collapsible) -->
        <div class="w-full block lg:hidden" x-show="openNav" x-transition x-cloak>
            <nav class="flex flex-col space-y-2 font-medium text-gray-900 py-2">
                <a href="{{ url('/') }}" class="hover:text-indigo-600">Home</a>
                <a href="{{ route('books.bookshelf') }}" class="hover:text-indigo-600">Bookshelf</a>
                <a href="{{ route('library.index') }}" class="hover:text-indigo-600">My Library</a>
                <a href="{{ route('publish') }}" class="hover:text-indigo-600">Publish With Us</a>
                <a href="#" class="hover:text-indigo-600">About Us</a>
                @auth
                    <!-- Mobile only: Your Cart and More button -->
                    <button @click="openMore = !openMore" class="flex items-center px-3 py-2 bg-gray-200 hover:bg-gray-300 rounded-full text-gray-800 font-semibold transition mt-2">
                        <span>More</span>
                        <i class="fa fa-caret-down ml-2"></i>
                    </button>
                    <div x-show="openMore" @click.away="openMore = false"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                        class="mt-2 bg-white rounded shadow-lg border border-gray-100 py-2 z-40"
                        style="display: none;">
                        <a href="{{ route('profile.show') }}" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <i class="fa fa-user mr-2"></i> My Profile
                        </a>
                        <a href="{{ route('library.index') }}" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <i class="fa fa-book mr-2"></i> My Library
                        </a>
                        <a href="{{ route('cart.index') }}" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <i class="fa fa-shopping-cart mr-2"></i> Your Cart
                        </a>
                        <a href="" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition">
                            <i class="fa fa-shopping-bag mr-2"></i> Orders
                        </a>
                        <div class="border-t my-2"></div>
                        <a href="{{ route('logout') }}" class="block px-5 py-2 text-gray-700 hover:bg-gray-100 transition"
                           onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            <i class="fa fa-sign-out mr-2"></i> Logout
                        </a>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="px-6 py-2 bg-black text-white rounded-full font-semibold hover:bg-indigo-700 transition mt-2">Login</a>
                @endauth
            </nav>
        </div>
    </div>
</header>