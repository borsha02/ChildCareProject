<footer class="bg-gray-900 text-white py-12 px-6">
    <div class="max-w-7xl mx-auto">

        {{-- Top Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-8 pb-8 border-b border-gray-800">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl flex items-center justify-center">
                    {{-- Sparkles Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 3l1.9 4.3L18 9l-4.1 1.7L12 15l-1.9-4.3L6 9l4.1-1.7L12 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold">Little Stars</h3>
                    <p class="text-sm text-gray-400">Child Care Center</p>
                </div>
            </div>

            {{-- Navigation Menu --}}
            <div class="flex flex-wrap justify-center gap-6 text-lg">
                <a href="{{ url('/') }}" class="hover:text-teal-400 transition">Home</a>
                <a href="{{ url('/about') }}" class="hover:text-teal-400 transition">About</a>
                <a href="{{ url('/programs') }}" class="hover:text-teal-400 transition">Programs</a>
                <a href="{{ url('/activities') }}" class="hover:text-teal-400 transition">Activities</a>
                <a href="{{ url('/contact') }}" class="hover:text-teal-400 transition">Contact</a>
            </div>

            {{-- Social Links --}}
            <div class="flex gap-3">

                {{-- Facebook --}}
                <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-teal-600 rounded-lg flex items-center justify-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                    </svg>
                </a>

                {{-- Instagram --}}
                <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-teal-600 rounded-lg flex items-center justify-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                        <line x1="17.5" y1="6.5" x2="17.5" y2="6.5"/>
                    </svg>
                </a>

                {{-- Twitter --}}
                <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-teal-600 rounded-lg flex items-center justify-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53A4.48 4.48 0 0 0 16 3a4.48 4.48 0 0 0-4.47 4.47v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                    </svg>
                </a>

                {{-- YouTube --}}
                <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-teal-600 rounded-lg flex items-center justify-center transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
                        <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>
                    </svg>
                </a>

            </div>
        </div>

        {{-- Bottom Section --}}
        <div class="pt-8 text-lg text-gray-400">
            <div class="max-w-7xl mx-auto flex justify-between items-center">

                <div class="w-1/3 text-left space-y-1">
                    <p class="hover:text-teal-400 cursor-pointer">Privacy Policy</p>
                    <p class="hover:text-teal-400 cursor-pointer">Terms & Conditions</p>
                </div>

                <div class="w-1/3 text-center">
                    <p>© 2025 Little Stars Child Care Center. All rights reserved.</p>
                </div>

                <div class="w-1/3 text-right">
                    <p>Developed by Samsun Nahar Borsha</p>
                </div>

            </div>
        </div>

    </div>
</footer>
