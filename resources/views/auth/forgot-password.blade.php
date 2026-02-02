<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Little Stars</title>
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
            <a href="/login" class="absolute top-6 left-6 p-2 rounded-xl bg-white hover:bg-gray-50 border text-gray-500 hover:text-teal-500 transition">
                ←
            </a>

            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-2xl shadow-lg mb-4">
                    🔑
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Forgot Password?</h1>
                <p class="text-gray-500 mt-1">No worries, we'll send you reset instructions.</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 text-teal-600 bg-teal-50 p-3 rounded-xl text-center text-sm border border-teal-100">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 text-red-500 bg-red-50 p-3 rounded-xl text-center text-sm border border-red-100">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Forgot Password Form --}}
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                {{-- Email Address --}}
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        class="w-full py-3 px-4 rounded-xl border-2 border-gray-200 focus:border-teal-400 outline-none"
                        required
                        autofocus
                    >
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="w-full bg-gradient-to-r from-teal-500 to-cyan-600 text-white py-3.5 rounded-xl font-semibold hover:shadow-xl transition">
                    Email Password Reset Link
                </button>
            </form>

            {{-- Back to Login --}}
            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">
                    Remember your password?
                    <a href="/login" class="font-semibold text-teal-600 hover:text-teal-700">
                        Back to Login
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

</body>
</html>
