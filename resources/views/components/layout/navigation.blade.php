<nav class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">WeSpeak Verify</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('home') ? 'text-blue-600 font-medium' : '' }}">
                    Home
                </a>
                <a href="{{ route('how-it-works') }}" class="text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('how-it-works') ? 'text-blue-600 font-medium' : '' }}">
                    How It Works
                </a>
                <a href="{{ route('pricing') }}" class="text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('pricing') ? 'text-blue-600 font-medium' : '' }}">
                    Pricing
                </a>
            </div>

            <!-- Desktop CTA Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                <x-ui.button variant="ghost" href="{{ route('login') }}">
                    Login
                </x-ui.button>
                <x-ui.button variant="primary" href="{{ route('search') }}">
                    Try Free Search
                </x-ui.button>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="text-gray-600 hover:text-gray-900 focus:outline-none focus:text-gray-900" x-data @click="mobileMenuOpen = !mobileMenuOpen">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden" x-data="{ mobileMenuOpen: false }" x-show="mobileMenuOpen" x-transition>
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-200">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('home') ? 'text-blue-600 font-medium' : '' }}">
                    Home
                </a>
                <a href="{{ route('how-it-works') }}" class="block px-3 py-2 text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('how-it-works') ? 'text-blue-600 font-medium' : '' }}">
                    How It Works
                </a>
                <a href="{{ route('pricing') }}" class="block px-3 py-2 text-gray-600 hover:text-blue-600 transition duration-200 {{ request()->routeIs('pricing') ? 'text-blue-600 font-medium' : '' }}">
                    Pricing
                </a>
                <div class="pt-4 space-y-2">
                    <x-ui.button variant="ghost" href="{{ route('login') }}" class="w-full justify-center">
                        Login
                    </x-ui.button>
                    <x-ui.button variant="primary" href="{{ route('search') }}" class="w-full justify-center">
                        Try Free Search
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</nav>
