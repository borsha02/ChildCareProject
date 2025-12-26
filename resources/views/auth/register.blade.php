<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register | Little Stars</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-teal-100 px-4 relative overflow-hidden">

        {{-- Sparkles --}}
        <div class="absolute top-10 left-10 text-teal-300 animate-pulse opacity-60">✨</div>
        <div class="absolute bottom-10 right-10 text-teal-300 animate-pulse opacity-60">✨</div>

        <div class="relative w-full max-w-md z-50">

            {{-- Glow border --}}
            <div
                class="absolute -inset-1 bg-gradient-to-r from-teal-400 via-blue-400 to-purple-500 rounded-3xl blur-lg opacity-30">
            </div>



            {{-- Card --}}
            <div class="relative bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-2xl p-8">
                {{-- Back --}}
                <a href="/"
                    class="absolute top-6 left-6 p-2 rounded-xl bg-white hover:bg-gray-50 border text-gray-500 hover:text-teal-500 transition">
                    ←
                </a>


                <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">
                    Create an Account
                </h2>
                <p class="text-center text-gray-500 mb-6">
                    Join our community today
                </p>

                {{-- Errors --}}
                @if ($errors->any())
                <div class="mb-4 text-red-500 text-sm">
                    {{ $errors->first() }}
                </div>
                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Name --}}
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">👤</span>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" required
                            class="w-full pl-10 pr-4 py-2 bg-white/70 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" />
                    </div>

                    {{-- Email --}}
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">📧</span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required
                            class="w-full pl-10 pr-4 py-2 bg-white/70 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" />
                    </div>

                    {{-- Phone --}}
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">📞</span>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone Number" required
                            class="w-full pl-10 pr-4 py-2 bg-white/70 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" />
                    </div>

                    {{-- Password --}}
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">🔒</span>
                        <input type="password" name="password" placeholder="Password" required
                            class="w-full pl-10 pr-4 py-2 bg-white/70 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" />
                    </div>

                    {{-- Confirm Password --}}
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">🔒</span>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                            class="w-full pl-10 pr-4 py-2 bg-white/70 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-400 outline-none" />
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full py-2 bg-gradient-to-r from-teal-500 to-blue-500 text-white rounded-lg font-medium shadow-md hover:opacity-90 transition">
                        Register
                    </button>
                </form>

                {{-- Login Link --}}
                <div class="mt-6 text-center">
                    <p class="text-gray-500">
                        Already have an account?
                        <a href="/login" class="font-semibold text-teal-600 hover:text-teal-700 relative group">
                            Sign In
                            <span
                                class="absolute bottom-0 left-0 w-0 h-0.5 bg-teal-600 group-hover:w-full transition-all duration-300"></span>
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>

</body>

</html>