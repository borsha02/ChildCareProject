@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    @include('layouts.header')

    {{-- Programs Section --}}
    <section id="programs" class="py-12 md:py-20 px-6 bg-white">
        <div class="max-w-7xl mx-auto">

            {{-- Title --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-full mb-6">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8c-1.1 0-2 .9-2 2v6h4v-6c0-1.1-.9-2-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 20h12"/>
                    </svg>
                    <span class="text-sm font-semibold text-indigo-700">Our Programs</span>
                </div>

                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Age-Appropriate Learning Paths
                </h2>

                <p class="text-lg text-gray-600">
                    Structured programs designed for each developmental stage
                </p>
            </div>

            {{-- Program Tabs --}}
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                <button onclick="showProgram(0)" class="program-btn bg-cyan-600 text-white px-6 py-3 rounded-lg font-semibold">
                    Toddlers
                </button>
                <button onclick="showProgram(1)" class="program-btn bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold">
                    Preschool
                </button>
                <button onclick="showProgram(2)" class="program-btn bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold">
                    Pre-K
                </button>
                <button onclick="showProgram(3)" class="program-btn bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold">
                    Young Learners
                </button>
            </div>

            {{-- Program Content Wrapper --}}
            <div class="bg-gray-50 border border-red-200 rounded-2xl overflow-hidden">

                {{-- Toddlers --}}
                <div class="program-panel grid md:grid-cols-2 gap-12 p-12">
                    <div>
                        <div class="inline-flex items-center gap-3 px-5 py-3 bg-cyan-100 rounded-xl mb-6">
                            😊
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Toddlers</h3>
                                <p class="text-sm font-medium text-cyan-600">1-2 years</p>
                            </div>
                        </div>

                        <p class="text-gray-600 mb-8">
                            Our toddlers program provides a structured yet flexible learning environment through
                            sensory-based and play-driven activities.
                        </p>

                       <!-- <button class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white px-6 py-3 rounded-lg font-semibold">
                            Learn More
                        </button>-->
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-6">Program Highlights</h4>
                        <div class="space-y-4">
                            <div class="feature">✔ Sensory exploration</div>
                            <div class="feature">✔ Basic motor skills</div>
                            <div class="feature">✔ Social interaction</div>
                            <div class="feature">✔ Creative play</div>
                        </div>
                    </div>
                </div>

                {{-- Preschool --}}
                <div class="program-panel hidden grid md:grid-cols-2 gap-12 p-12">
                    <div>
                        <div class="inline-flex items-center gap-3 px-5 py-3 bg-teal-100 rounded-xl mb-6">
                            🎵
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Preschool</h3>
                                <p class="text-sm font-medium text-teal-600">3-4 years</p>
                            </div>
                        </div>

                        <p class="text-gray-600 mb-8">
                            Our preschool program builds early literacy, creativity and curiosity.
                        </p>

                      <!--  <button class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-6 py-3 rounded-lg font-semibold">
                            Learn More
                        </button>-->
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-6">Program Highlights</h4>
                        <div class="space-y-4">
                            <div class="feature">✔ Early literacy</div>
                            <div class="feature">✔ Mathematics basics</div>
                            <div class="feature">✔ Arts & crafts</div>
                            <div class="feature">✔ Music & movement</div>
                        </div>
                    </div>
                </div>

                {{-- Pre-K --}}
                <div class="program-panel hidden grid md:grid-cols-2 gap-12 p-12">
                    <div>
                        <div class="inline-flex items-center gap-3 px-5 py-3 bg-indigo-100 rounded-xl mb-6">
                            🏆
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Pre-K</h3>
                                <p class="text-sm font-medium text-indigo-600">4-5 years</p>
                            </div>
                        </div>

                        <p class="text-gray-600 mb-8">
                            Our Pre-K program prepares children for school success.
                        </p>

                        <!--<button class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold">
                            Learn More
                        </button>-->
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-6">Program Highlights</h4>
                        <div class="space-y-4">
                            <div class="feature">✔ School preparation</div>
                            <div class="feature">✔ Advanced learning</div>
                            <div class="feature">✔ Critical thinking</div>
                            <div class="feature">✔ Team collaboration</div>
                        </div>
                    </div>
                </div>

                {{-- Young Learners --}}
                <div class="program-panel hidden grid md:grid-cols-2 gap-12 p-12">
                    <div>
                        <div class="inline-flex items-center gap-3 px-5 py-3 bg-violet-100 rounded-xl mb-6">
                            👶
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Young Learners</h3>
                                <p class="text-sm font-medium text-violet-600">5-7 years</p>
                            </div>
                        </div>

                        <p class="text-gray-600 mb-8">
                            Our young learners program introduces leadership and STEM concepts.
                        </p>

                        <!--<button class="bg-gradient-to-r from-violet-500 to-violet-600 text-white px-6 py-3 rounded-lg font-semibold">
                            Learn More
                        </button>-->
                    </div>

                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-6">Program Highlights</h4>
                        <div class="space-y-4">
                            <div class="feature">✔ Reading & writing</div>
                            <div class="feature">✔ STEM introduction</div>
                            <div class="feature">✔ Leadership skills</div>
                            <div class="feature">✔ Creative projects</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Footer --}}
    @include('layouts.footer')

</div>

{{-- Simple JS --}}
<script>
    function showProgram(index) {
        document.querySelectorAll('.program-panel').forEach((el, i) => {
            el.classList.toggle('hidden', i !== index);
        });

        document.querySelectorAll('.program-btn').forEach((btn, i) => {
            btn.className = i === index
                ? 'program-btn bg-cyan-600 text-white px-6 py-3 rounded-lg font-semibold'
                : 'program-btn bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold';
        });
    }
</script>
@endsection
