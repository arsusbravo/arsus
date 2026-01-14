@extends('layouts.main')

@section('title', $case['title'] . ' - ARSUS IT Solutions')

@section('content')

<!-- Spacer for fixed navigation -->
<div class="h-20"></div>

<!-- Hero Section -->
<section class="min-h-[60vh] gradient-bg relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#fe742b] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10 pt-8 pb-20">
        <div class="max-w-5xl mx-auto">
            <!-- Breadcrumb -->
            <div class="mb-8 animate-on-scroll">
                <a href="{{ locale_route('cases') }}" class="text-gray-400 hover:text-[#fe742b] transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('case_detail.back_to_cases') }}
                </a>
            </div>

            <!-- Case Number Badge -->
            <div class="inline-block px-4 py-2 bg-[#fe742b]/20 rounded-full text-[#fe742b] text-sm font-medium mb-6 animate-on-scroll">
                <i class="fas fa-briefcase mr-2"></i>{{ __('case_detail.case_study') }} #{{ $case['case_number'] }}
            </div>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6 animate-on-scroll">
                {{ $case['title'] }}
            </h1>

            <p class="text-2xl text-[#fe742b] mb-6 animate-on-scroll">
                {{ $case['subtitle'] }}
            </p>

            <p class="text-xl text-gray-300 animate-on-scroll">
                {{ $case['description'] }}
            </p>
        </div>
    </div>
</section>

<!-- Main Image Gallery -->
<section class="py-12 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <!-- Image Grid - Skip first image (0) which is thumbnail -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @for($i = 1; $i < $case['images']; $i++)
                <div class="animate-on-scroll">
                    <img src="{{ asset('images/cases/' . $case['case_number'] . '-' . $i . '.jpg') }}"
                         alt="{{ $case['title'] }} - Screenshot {{ $i }}"
                         class="w-full rounded-xl shadow-lg hover:scale-105 transition-transform duration-300">
                </div>
                @endfor
            </div>
        </div>
    </div>
</section>

<!-- Challenge & Solution -->
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-12">
            <!-- The Challenge -->
            <div class="glass-effect p-8 rounded-2xl animate-on-scroll">
                <div class="flex items-center gap-6 mb-6">
                    <div class="w-12 h-12 bg-red-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold leading-none">{{ __('case_detail.the_challenge') }}</h2>
                </div>
                <p class="text-gray-300 text-lg leading-relaxed">
                    {{ $case['challenge'] }}
                </p>
            </div>

            <!-- The Solution -->
            <div class="glass-effect p-8 rounded-2xl animate-on-scroll">
                <div class="flex items-center gap-6 mb-6">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-lightbulb text-green-400 text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold leading-none">{{ __('case_detail.the_solution') }}</h2>
                </div>
                <p class="text-gray-300 text-lg leading-relaxed">
                    {{ $case['solution'] }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Key Features -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ __('case_detail.key_features_title') }} <span class="text-[#fe742b]">{{ __('case_detail.key_features_highlight') }}</span>
                </h2>
                <p class="text-xl text-gray-400">
                    {{ __('case_detail.key_features_subtitle') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                @foreach($case['features'] as $feature)
                <div class="glass-effect p-6 rounded-xl hover:border-[#fe742b]/50 border border-transparent transition-all duration-300 animate-on-scroll">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 pt-1">
                            <i class="fas fa-check-circle text-[#fe742b] text-xl"></i>
                        </div>
                        <p class="text-gray-300 leading-relaxed">{{ $feature }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Technology Stack -->
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ __('case_detail.tech_stack_title') }} <span class="text-[#fe742b]">{{ __('case_detail.tech_stack_highlight') }}</span>
                </h2>
                <p class="text-xl text-gray-400">
                    {{ __('case_detail.tech_stack_subtitle') }}
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
                @foreach($case['tech_stack'] as $tech)
                <div class="glass-effect px-6 py-3 rounded-full hover:border-[#fe742b] border border-transparent transition-all duration-300">
                    <span class="text-lg font-medium">{{ $tech }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Results & Impact -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ __('case_detail.results_title') }} <span class="text-[#fe742b]">{{ __('case_detail.results_highlight') }}</span>
                </h2>
                <p class="text-xl text-gray-400">
                    {{ __('case_detail.results_subtitle') }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($case['results'] as $result)
                <div class="glass-effect p-6 rounded-xl text-center hover:scale-105 transition-transform duration-300 animate-on-scroll">
                    <div class="mb-4">
                        <i class="fas fa-chart-line text-[#fe742b] text-3xl"></i>
                    </div>
                    <p class="text-lg text-gray-300">{{ $result }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- More Cases Navigation -->
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ __('case_detail.more_cases_title') }} <span class="text-[#fe742b]">{{ __('case_detail.more_cases_highlight') }}</span>
                </h2>
                <p class="text-xl text-gray-400">
                    {{ __('case_detail.more_cases_subtitle') }}
                </p>
            </div>

            @php
                $allCases = [
                    ['slug' => 'online-reservation-system', 'title' => 'Online Reservation System', 'category' => 'Booking System'],
                    ['slug' => 'online-order-system', 'title' => 'Online Order System', 'category' => 'E-commerce'],
                    ['slug' => 'financial-calculators', 'title' => 'Financial Calculators', 'category' => 'FinTech'],
                    ['slug' => 'digitalized-form', 'title' => 'Digitalized Claim Form', 'category' => 'Digital Forms'],
                    ['slug' => 'document-signing', 'title' => 'Effortless Document Signing', 'category' => 'E-Signature'],
                    ['slug' => 'lead-generation', 'title' => 'Lead Generation Platform', 'category' => 'Marketing'],
                ];

                // Filter out current case and get up to 3 other cases
                $otherCases = collect($allCases)->reject(fn($c) => $c['slug'] === $slug)->take(3);
            @endphp

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($otherCases as $otherCase)
                <a href="{{ locale_route('case.show', [$otherCase['slug']]) }}" class="glass-effect rounded-xl p-6 hover:border-[#fe742b] border border-transparent transition-all duration-300 animate-on-scroll group">
                    <div class="text-sm text-[#fe742b] mb-2">{{ $otherCase['category'] }}</div>
                    <h3 class="text-xl font-bold mb-3 group-hover:text-[#fe742b] transition-colors">{{ $otherCase['title'] }}</h3>
                    <div class="flex items-center text-gray-400 group-hover:text-[#fe742b] transition-colors">
                        <span>{{ __('case_detail.view_case_study') }}</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="text-center mt-12 animate-on-scroll">
                <a href="{{ locale_route('cases') }}" class="inline-flex items-center text-lg text-[#fe742b] hover:text-[#ff8c4d] transition-colors font-semibold">
                    <i class="fas fa-th mr-2"></i>
                    {{ __('case_detail.view_all_cases') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-[#fe742b] to-orange-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-on-scroll">
            {{ __('case_detail.cta_title') }}
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-on-scroll">
            {{ __('case_detail.cta_subtitle') }}
        </p>
        <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
            <a href="{{ locale_route('contact') }}" class="bg-white text-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                {{ __('case_detail.cta_start') }}
            </a>
            <a href="{{ locale_route('cases') }}" class="glass-effect border-2 border-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('case_detail.cta_all_cases') }}
            </a>
        </div>
    </div>
</section>

@endsection

<style>
@keyframes blob {
    0%, 100% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}
</style>
