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
                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="w-full py-3 px-4 rounded-xl border-2 border-gray-200 focus:border-teal-400 outline-none"
                    required
                    autocomplete="current-password"
                >

                {{-- Remember --}}
                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" value="true" class="accent-teal-500">
                        Remember me
                    </label>
                    <a href="/forgot-password" class="text-teal-600 font-medium">Forgot Password?</a>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full bg-gradient-to-r from-teal-500 to-cyan-600 text-white py-3.5 rounded-xl font-semibold hover:shadow-xl transition">
                    Sign In
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="px-4 text-sm text-gray-400">or</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Google placeholder --}}
            <button class="w-full py-3 border-2 border-gray-200 rounded-xl text-gray-600 hover:bg-teal-50 transition">
                Continue with Google
            </button>

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
</html>
