<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Little Stars</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="min-h-screen flex items-center justify-center bg-white p-4 relative overflow-hidden">

    {{-- Decorative sparkles --}}
    <div class="absolute top-10 left-10 text-teal-300 animate-pulse">✨</div>
    <div class="absolute top-20 right-20 text-cyan-300 animate-pulse">✨</div>
    <div class="absolute bottom-20 left-20 text-teal-200 animate-pulse">✨</div>
    <div class="absolute bottom-10 right-10 text-indigo-200 animate-pulse">✨</div>

    <div class="relative w-full max-w-md">

        {{-- Glow --}}
        <div class="absolute -inset-1 bg-gradient-to-r from-teal-400 via-cyan-400 to-indigo-400 rounded-3xl blur-lg opacity-30 animate-pulse"></div>

        <div class="relative bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl p-8 border border-white/50">

            {{-- Back --}}
            <a href="/" class="absolute top-6 left-6 p-2 rounded-xl bg-white hover:bg-gray-50 border text-gray-500 hover:text-teal-500 transition">
                ←
            </a>

            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-2xl shadow-lg mb-4">
                    👶
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Welcome Back!</h1>
                <p class="text-gray-500 mt-1">Sign in to Little Stars</p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-4 text-teal-600 bg-teal-50 p-3 rounded-xl text-center text-sm border border-teal-100">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-4 text-red-500 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email/Phone --}}
                <label for="email" class="sr-only">Email or Phone</label>
                <input
                    id="email"
                    type="text"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Email Address or Phone"
                    class="w-full py-3 px-4 rounded-xl border-2 border-gray-200 focus:border-teal-400 outline-none"
                    required
                    autocomplete="username"
                >

                {{-- Password --}}
                <label for="password" class="sr-only">Password</label>
                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Password"
                        class="w-full py-3 px-4 rounded-xl border-2 border-gray-200 focus:border-teal-400 outline-none pr-12"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition focus:outline-none">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg id="eye-slash-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>

                {{-- Login Options --}}
                <div class="text-center text-sm">
                    <a href="/forgot-password" class="text-teal-600 font-medium hover:text-teal-700 transition">Forgot Password?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full bg-gradient-to-r from-teal-500 to-cyan-600 text-white py-3.5 rounded-xl font-semibold hover:shadow-xl transition">
                    Sign In
                </button>
            </form>

            {{-- Register --}}
            <div class="mt-6 text-center">
                <p class="text-gray-500">
                    Don't have an account?
                    <a href="/register" class="font-semibold text-teal-600 hover:text-teal-700">
                        Sign Up
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

</body>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeSlashIcon = document.getElementById('eye-slash-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        }
    }
</script>
</html>
