@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- Header --}}
    @include('layouts.header')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="fixed top-24 right-4 z-50 max-w-sm w-full mx-auto p-4 transition-all duration-500 ease-in-out">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg flex items-center justify-between" role="alert">
                <div>
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <script>
                // Auto-dismiss after 5 seconds
                setTimeout(function() {
                    const alert = document.querySelector('.bg-green-100').parentElement;
                    if(alert) {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 5000);
            </script>
        </div>
    @endif

    {{-- Career Hero Section --}}
    <section class="relative h-[300px] md:h-[400px] mt-20">
        {{-- Background Image with Blue Overlay --}}
        <div class="absolute inset-0">
            <img src="{{ asset('images/career.jpg') }}" 
                 alt="Career at Little Stars" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-blue-900/70"></div>
        </div>

        {{-- Content --}}
        <div class="relative h-full max-w-7xl mx-auto px-6 flex items-center">
            <div class="grid md:grid-cols-2 gap-8 md:gap-12 w-full items-center">
                {{-- Left Side - Title --}}
                <div class="text-white">
                    <h1 class="text-3xl md:text-5xl font-bold">
                        Join Our Family
                    </h1>
                </div>

                {{-- Right Side - Description --}}
                <div class="text-white">
                    <p class="text-base md:text-lg leading-relaxed">
                        We require individuals who are technically competent and are highly driven by innovation, creativity, and excellence.
                    </p>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Apply Button Section -->
    <section class="py-20 px-6 bg-gray-50 text-center">
        <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
            Join Our Team
        </h3>
        <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
            We are always looking for passionate individuals to work with children and grow with us.
        </p>

        <button 
            onclick="openForm()"
            class="bg-teal-600 text-white px-10 py-4 rounded-xl text-lg font-semibold hover:bg-teal-700 transition">
            Apply Now
        </button>
    </section>

   <!-- Application Modal -->
<div id="applyModal" class="fixed inset-0 bg-black/60 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl relative max-h-[90vh] overflow-y-auto">

        <!-- Notification Messages -->
        @if($errors->any())
            <div class="absolute top-4 left-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
             <script>
                document.addEventListener("DOMContentLoaded", function() {
                    openForm();
                });
            </script>
        @endif

        <!-- Header Section -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-6 md:px-8 py-6 rounded-t-2xl">
            <!-- Close Button -->
            <button onclick="closeForm()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 text-center pr-8">
                Career Application Form
            </h3>
            <p class="text-gray-600 text-center mt-2 text-sm">Fill in your details to apply for a position</p>
        </div>

        <!-- Form Content -->
        <div class="px-6 md:px-8 py-6">
            <form id="careerApplicationForm" action="{{ route('career.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-600">*</span></label>
                    <input type="text" name="full_name" placeholder="Enter your full name" required value="{{ old('full_name') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                </div>

                <!-- Email & Phone in Grid -->
                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-600">*</span></label>
                        <input type="email" name="email" placeholder="your.email@example.com" required value="{{ old('email') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                    </div>

                    <!-- Phone -->
                    <div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number <span class="text-red-600">*</span></label>
                        <input type="tel" name="phone_number" placeholder="+880 1XXX-XXXXXX" required value="{{ old('phone_number') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                    </div>
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Address <span class="text-red-600">*</span></label>
                    <textarea name="address" rows="3" placeholder="Enter your complete address" required
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition resize-none">{{ old('address') }}</textarea>
                </div>

                <!-- Position Dropdown -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select Position <span class="text-red-600">*</span></label>
                    <select name="position" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition">
                        <option value="" disabled selected>Choose a position</option>
                        <option value="Senior teacher" {{ old('position') == 'Senior teacher' ? 'selected' : '' }}>Senior teacher</option>
                        <option value="Assistant teacher" {{ old('position') == 'Assistant teacher' ? 'selected' : '' }}>Assistant teacher</option>
                        <option value="Junior teacher" {{ old('position') == 'Junior teacher' ? 'selected' : '' }}>Junior teacher</option>
                    </select>
                </div>

                <!-- Upload CV -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload CV / Resume (PDF only) <span class="text-red-600">*</span></label>
                    <div class="relative">
                        <input type="file" name="resume" accept="application/pdf" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-teal-50 file:text-teal-700 file:font-semibold hover:file:bg-teal-100 cursor-pointer">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Maximum file size: 5MB</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeForm()"
                            class="flex-1 bg-gray-100 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-200 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </button>
                    <button type="submit"
                            class="flex-1 bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition flex items-center justify-center gap-2">
                        Submit Application
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>




    {{-- Footer --}}
    @include('layouts.footer')

</div>

<script>
    function openForm() {
        document.getElementById("applyModal").style.display = "flex";
    }

    function closeForm() {
        // Reset the form to clear all input fields
        document.getElementById("careerApplicationForm").reset();
        // Close the modal
        document.getElementById("applyModal").style.display = "none";
    }
</script>

@endsection
