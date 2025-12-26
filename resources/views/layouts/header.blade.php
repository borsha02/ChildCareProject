<nav id="navbar"
     class="fixed top-0 w-full z-50 transition-all duration-500 bg-gradient-to-r from-white via-teal-50 to-white backdrop-blur-xl py-3">

    <div class="max-w-7xl mx-auto px-6">
        <div class="flex justify-between items-center">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 cursor-pointer">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-md relative">
                    {{-- Central sparkle/star icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3l1.9 4.3L18 9l-4.1 1.7L12 15l-1.9-4.3L6 9l4.1-1.7L12 3z"/>
                    </svg>
                    {{-- Small dot --}}
                    <div class="absolute bottom-1 left-1 w-2 h-2 bg-white rounded-full"></div>
                    {{-- Plus sign --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute top-1 right-1 w-3 h-3 text-white" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Little Stars</h1>
                    <p class="text-xs text-gray-500 font-medium">Child Care Center</p>
                </div>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-10">
                <a href="{{ url('/') }}" class="text-lg font-bold text-gray-700 hover:text-teal-600 transition-colors">Home</a>
                <a href="{{ url('/aboutus') }}" class="text-lg font-bold text-gray-700 hover:text-teal-600 transition-colors">About</a>
                <a href="{{ url('/programs') }}" class="text-lg font-bold text-gray-700 hover:text-teal-600 transition-colors">Programs</a>
                <a href="{{ url('/activities') }}" class="text-lg font-bold text-gray-700 hover:text-teal-600 transition-colors">Activities</a>
                <a href="{{ url('/contact') }}" class="text-lg font-bold text-gray-700 hover:text-teal-600 transition-colors">Contact</a>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3">
                <a href="{{ url('/login') }}">
                    <button class="hidden lg:block text-lg font-bold text-gray-700 hover:text-teal-600 px-5 py-1.5">
                        Login
                    </button>
                </a>
                <a href="{{ url('/register') }}">
                    <button class="hidden lg:block bg-gradient-to-r from-teal-500 to-cyan-600 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:shadow-lg hover:scale-105 transition-all">
                        Register now
                    </button>
                </a>
                {{-- Mobile toggle --}}
                <button id="menuToggle" class="lg:hidden p-2 hover:bg-gray-100 rounded-lg">
                    <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="hidden lg:hidden bg-white border-t mt-3">
        <div class="px-6 py-6 space-y-4">
            <a href="{{ url('/') }}" class="block text-gray-700 font-medium hover:text-teal-600">Home</a>
            <a href="{{ url('/aboutus') }}" class="block text-gray-700 font-medium hover:text-teal-600">About</a>
            <a href="{{ url('/programs') }}" class="block text-gray-700 font-medium hover:text-teal-600">Programs</a>
            <a href="{{ url('/activities') }}" class="block text-gray-700 font-medium hover:text-teal-600">Activities</a>
            <a href="{{ url('/contact') }}" class="block text-gray-700 font-medium hover:text-teal-600">Contact</a>
            <a href="{{ url('/register') }}">
                <button class="w-full bg-gradient-to-r from-teal-500 to-cyan-600 text-white px-6 py-3 rounded-lg font-semibold">
                    Get Started
                </button>
            </a>
        </div>
    </div>
</nav>

{{-- JS: Scroll + Mobile Menu --}}
<script>
    const navbar = document.getElementById('navbar');
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY > 50;
        if (scrolled) {
            navbar.classList.add('bg-white', 'shadow-sm');
            navbar.classList.remove('bg-gradient-to-r', 'from-white', 'via-teal-50', 'to-white', 'backdrop-blur-xl');
        } else {
            navbar.classList.add('bg-gradient-to-r', 'from-white', 'via-teal-50', 'to-white', 'backdrop-blur-xl');
            navbar.classList.remove('bg-white', 'shadow-sm');
        }
    });

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
