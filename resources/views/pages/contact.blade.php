@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    @include('layouts.header')

    {{-- Contact Section --}}
    <section id="contact" class="py-12 md:py-20 px-6 bg-white">
        <div class="max-w-5xl mx-auto">

            {{-- Title --}}
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Ready to Get Started?
                </h2>
                <p class="text-lg text-gray-600">
                    Schedule a tour and discover why families choose Little Stars
                </p>
            </div>

            {{-- Contact Card --}}
            <div class="bg-gradient-to-br from-gray-50 to-teal-50 border border-gray-200 rounded-2xl p-12">

                {{-- Contact Info --}}
                <div class="grid md:grid-cols-3 gap-8 mb-10">

                    {{-- Phone --}}
                    <div class="text-center">
                        <div class="w-14 h-14 bg-white border border-teal-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                            📞
                        </div>
                        <div class="font-semibold text-gray-900 mb-1">Call Us</div>
                        <div class="text-sm text-gray-600">+880 1XXX-XXXXXX</div>
                    </div>

                    {{-- Email --}}
                    <div class="text-center">
                        <div class="w-14 h-14 bg-white border border-teal-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                            ✉️
                        </div>
                        <div class="font-semibold text-gray-900 mb-1">Email</div>
                        <div class="text-sm text-gray-600">hello@littlestars.com</div>
                    </div>

                    {{-- Location --}}
                    <div class="text-center">
                        <div class="w-14 h-14 bg-white border border-teal-200 rounded-xl flex items-center justify-center mx-auto mb-4">
                            📍
                        </div>
                        <div class="font-semibold text-gray-900 mb-1">Visit</div>
                        <div class="text-sm text-gray-600">Dhaka, Bangladesh</div>
                    </div>

                </div>

                {{-- CTA Button --}}
                <button
                    class="w-full bg-gradient-to-r from-teal-500 to-cyan-600 text-white py-4 rounded-xl font-semibold text-lg
                           hover:shadow-xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                    Schedule Your Visit Today
                    <span>➤</span>
                </button>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('layouts.footer')

</div>
@endsection
