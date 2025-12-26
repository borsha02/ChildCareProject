@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Shared Header --}}
    @include('layouts.header')

    {{-- Hero Section with Background Slider --}}
            <section id="home" class="relative h-[300px] sm:h-[400px] md:h-[600px]">

                {{-- Background images container --}}
                <div class="absolute inset-0 sm:inset-x-6 overflow-hidden">
                    <div id="heroSlider" class="w-full h-full">
                        <div class="absolute inset-0 bg-cover bg-center lg:bg-top transition-opacity duration-1000 opacity-100"
                            style="background-image: url('/images/daycare7.jpg');"></div>
                        <div class="absolute inset-0 bg-cover bg-center lg:bg-top transition-opacity duration-1000 opacity-0"
                            style="background-image: url('/images/daycare4.jpg');"></div>
                        <div class="absolute inset-0 bg-cover bg-center lg:bg-top transition-opacity duration-1000 opacity-0"
                            style="background-image: url('/images/daycare6.jpg');"></div>
                    </div>

                    {{-- Overlay --}}
                    <div class="absolute inset-0 bg-black/30"></div>
                </div>

                {{-- Content --}}
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6
                            py-8 sm:py-12 md:py-24
                            text-center space-y-6 sm:space-y-8
                            mt-20 sm:mt-24 flex flex-col items-center justify-center h-full">

                    <h1 class="text-2xl sm:text-3xl lg:text-5xl font-semibold text-white leading-tight drop-shadow-lg">
                        Excellence in Early<br />Childhood Development
                    </h1>

                    <p class="text-base sm:text-lg md:text-xl text-gray-100 leading-relaxed max-w-3xl mx-auto">
                        Little Stars Child Care Center offers a structured, developmentally appropriate curriculum for
                        children aged 1 to 7 years, designed to meet specific learning milestones across each age group.
                        Our certified educators implement measurable and research-based programs that promote cognitive,
                        social, and emotional development in a safe, nurturing environment.
                    </p>

                    <div class="flex flex-wrap justify-center gap-3 sm:gap-4 pt-3 sm:pt-4">
                        <button
                            class="bg-teal-600 text-white px-6 sm:px-10 py-3 sm:py-4 font-semibold
                                hover:bg-teal-700 transition-colors
                                flex items-center gap-2 text-sm sm:text-base">
                            Schedule Consultation
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 sm:w-5 sm:h-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 12h14m-7-7l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </section>


            {{-- Slider Script --}}
            <script>
                const slides = document.querySelectorAll('#heroSlider div');
                let current = 0;

                setInterval(() => {
                    slides[current].classList.remove('opacity-100');
                    slides[current].classList.add('opacity-0');

                    current = (current + 1) % slides.length;

                    slides[current].classList.remove('opacity-0');
                    slides[current].classList.add('opacity-100');
                }, 3000); // change every 2 seconds
            </script>


    {{-- Three Images Section --}}
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid md:grid-cols-3 gap-6">

                {{-- Image 1 --}}
                <div class="relative h-[400px] bg-gray-200 overflow-hidden group">
                    <img src="{{ asset('images/daycare7.jpg') }}"
                         alt="Learning environment"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                        <h3 class="text-white font-bold text-xl mb-1">Modern Facilities</h3>
                        <p class="text-white/90 text-sm">State-of-the-art learning spaces</p>
                    </div>

                    <div class="absolute top-0 left-0 w-1 h-full bg-teal-600"></div>
                </div>

                {{-- Image 2 --}}
                <div class="relative h-[400px] bg-gray-200 overflow-hidden group">
                    <img src="{{ asset('images/daycare9.jpg') }}"
                         alt="Educational activities"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                        <h3 class="text-white font-bold text-xl mb-1">Engaging Programs</h3>
                        <p class="text-white/90 text-sm">Research-based curriculum</p>
                    </div>

                    <div class="absolute top-0 left-0 w-1 h-full bg-cyan-600"></div>
                </div>

                {{-- Image 3 --}}
                <div class="relative h-[400px] bg-gray-200 overflow-hidden group">
                    <img src="{{ asset('images/daycare6.jpg') }}"
                         alt="Safe outdoor play"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                        <h3 class="text-white font-bold text-xl mb-1">Festive Gatherings</h3>
                        <p class="text-white/90 text-sm">Joyful moments of tradition</p>
                    </div>

                    <div class="absolute top-0 left-0 w-1 h-full bg-indigo-600"></div>
                </div>

            </div>
        </div>
    </section>

    {{-- Quick Info Bar --}}
    <section class="bg-gradient-to-r from-gray-900 to-gray-800 py-4 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">

                {{-- Time --}}
                <div>
                    <svg class="w-8 h-8 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"></path>
                    </svg>
                    <div class="font-semibold">Sun - Thu</div>
                    <div class="text-sm opacity-90">8:00 AM - 7:00 PM</div>
                </div>

                {{-- Groups --}}
                <div>
                    <svg class="w-8 h-8 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75"/>
                    </svg>
                    <div class="font-semibold">Small Groups</div>
                    <div class="text-sm opacity-90">1:6 Staff Ratio</div>
                </div>

                {{-- Location --}}
                <div>
                    <svg class="w-8 h-8 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 11c1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3 1.34 3 3 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21s8-4.5 8-10a8 8 0 10-16 0c0 5.5 8 10 8 10z"/>
                    </svg>
                    <div class="font-semibold">Location</div>
                    <div class="text-sm opacity-90">Dhaka, Bangladesh</div>
                </div>

                {{-- Updates --}}
                <div>
                    <svg class="w-8 h-8 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.4-1.4a2 2 0 01-.6-1.4V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 21h6"/>
                    </svg>
                    <div class="font-semibold">Daily Updates</div>
                    <div class="text-sm opacity-90">Photos & Reports</div>
                </div>

            </div>
        </div>
    </section>


    {{-- Shared Footer --}}
    @include('layouts.footer')

</div>
@endsection
