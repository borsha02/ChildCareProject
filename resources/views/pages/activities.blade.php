@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    @include('layouts.header')

    {{-- Activities Section --}}
    <section id="activities" class="py-12 md:py-20 px-6 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto">

            {{-- Title --}}
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 border border-purple-100 rounded-full mb-6">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 3l7 7-7 7M13 3l7 7-7 7"/>
                    </svg>
                    <span class="text-sm font-semibold text-purple-700">Daily Activities</span>
                </div>

                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Enriching Daily Experiences
                </h2>

                <p class="text-lg text-gray-600">
                    Balanced curriculum promoting physical, cognitive, and social development
                </p>
            </div>

            {{-- Activities Grid --}}
            <div class="grid md:grid-cols-4 gap-6">

                {{-- Creative Arts --}}
                <div class="bg-white border border-gray-200 rounded-xl p-8 text-center hover:shadow-xl hover:border-teal-200 transition-all group">
                    <div class="w-16 h-16 bg-rose-100 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        🎨
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Creative Arts</h3>
                    <p class="text-sm text-gray-600">Painting, drawing, and crafts</p>
                </div>

                {{-- Music & Movement --}}
                <div class="bg-white border border-gray-200 rounded-xl p-8 text-center hover:shadow-xl hover:border-teal-200 transition-all group">
                    <div class="w-16 h-16 bg-cyan-100 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        🎵
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Music & Movement</h3>
                    <p class="text-sm text-gray-600">Songs, dance, and rhythm</p>
                </div>

                {{-- Literacy --}}
                <div class="bg-white border border-gray-200 rounded-xl p-8 text-center hover:shadow-xl hover:border-teal-200 transition-all group">
                    <div class="w-16 h-16 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        📚
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Literacy & Stories</h3>
                    <p class="text-sm text-gray-600">Reading and language skills</p>
                </div>

                {{-- Physical Activity --}}
                <div class="bg-white border border-gray-200 rounded-xl p-8 text-center hover:shadow-xl hover:border-teal-200 transition-all group">
                    <div class="w-16 h-16 bg-violet-100 rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                        🏃
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Physical Activity</h3>
                    <p class="text-sm text-gray-600">Outdoor play and sports</p>
                </div>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('layouts.footer')

</div>
@endsection
