@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    @include('layouts.header')

    {{-- About Section --}}
    <section id="about" class="py-12 md:py-20 px-6 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto">

            {{-- Title --}}
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 border border-teal-100 rounded-full mb-6">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-sm font-semibold text-teal-700">Why Choose Us</span>
                </div>

                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Professional Care, Personal Touch
                </h2>

                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    We combine educational excellence with a nurturing environment to help every child thrive
                </p>
            </div>

            {{-- Feature Cards --}}
            <div class="grid md:grid-cols-3 gap-8">

                {{-- Card 1 --}}
                <div class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-xl hover:border-teal-200 transition-all group">
                    <div class="w-14 h-14 bg-cyan-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 3l7 4v5c0 5-3.5 9-7 9s-7-4-7-9V7l7-4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Licensed & Certified</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Fully accredited facility with trained early childhood educators and comprehensive safety protocols
                    </p>
                </div>

                {{-- Card 2 --}}
                <div class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-xl hover:border-teal-200 transition-all group">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 4h16v13H4z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Research-Based Curriculum</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Age-appropriate programs designed by child development experts to foster cognitive and social growth
                    </p>
                </div>

                {{-- Card 3 --}}
                <div class="bg-white border border-gray-200 rounded-xl p-8 hover:shadow-xl hover:border-teal-200 transition-all group">
                    <div class="w-14 h-14 bg-violet-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.3 4.3a5.5 5.5 0 017.8 0L12 4.2l-.1.1a5.5 5.5 0 017.8 7.8L12 21 4.3 12.1a5.5 5.5 0 010-7.8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Individualized Attention</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Low student-teacher ratios ensure personalized care and attention for each child's unique needs
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- Quick Stats --}}
    <section class="py-16 px-6 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-6">

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-teal-200 transition-all">
                    <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-lg flex items-center justify-center mb-4">
                        🕒
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Operating Hours</h3>
                    <p class="text-sm text-gray-600">Sun - Thu, 8AM - 6PM</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-teal-200 transition-all">
                    <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-4">
                        👥
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Class Size</h3>
                    <p class="text-sm text-gray-600">Small groups, max 10</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-teal-200 transition-all">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-4">
                        📍
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Location</h3>
                    <p class="text-sm text-gray-600">Dhaka, Bangladesh</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-teal-200 transition-all">
                    <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-lg flex items-center justify-center mb-4">
                        🔔
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">Updates</h3>
                    <p class="text-sm text-gray-600">Real-time notifications</p>
                </div>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('layouts.footer')

</div>
@endsection
