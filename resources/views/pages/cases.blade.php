@extends('layouts.main')

@section('title', __('cases_landing.hero_title') . ' ' . __('cases_landing.hero_title_highlight') . ' - ARSUS IT Solutions')

@section('content')

<!-- Hero Section -->
<section class="min-h-[60vh] flex items-center gradient-bg relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#fe742b] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-block px-4 py-2 bg-[#fe742b]/20 rounded-full text-[#fe742b] text-sm font-medium mb-6 animate-on-scroll">
                <i class="fas fa-briefcase mr-2"></i>{{ __('cases_landing.badge') }}
            </div>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6 animate-on-scroll">
                {{ __('cases_landing.hero_title') }} <span class="text-[#fe742b]">{{ __('cases_landing.hero_title_highlight') }}</span>
            </h1>

            <p class="text-xl text-gray-300 animate-on-scroll">
                {{ __('cases_landing.hero_subtitle') }}
            </p>
        </div>
    </div>
</section>

<!-- Two Sections -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-8">

            <!-- Success Stories -->
            <a href="{{ locale_route('cases.success-stories') }}" class="glass-effect rounded-2xl p-10 border border-transparent hover:border-[#fe742b] transition-all duration-300 animate-on-scroll group flex flex-col">
                <div class="w-16 h-16 bg-[#fe742b]/20 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-trophy text-[#fe742b] text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold mb-4 group-hover:text-[#fe742b] transition-colors">{{ __('cases_landing.success_stories_title') }}</h2>
                <p class="text-gray-400 text-lg mb-8 flex-grow">{{ __('cases_landing.success_stories_text') }}</p>
                <span class="inline-flex items-center text-[#fe742b] font-semibold">
                    {{ __('cases_landing.success_stories_cta') }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </span>
            </a>

            <!-- Projects -->
            <a href="{{ locale_route('cases.projects') }}" class="glass-effect rounded-2xl p-10 border border-transparent hover:border-[#fe742b] transition-all duration-300 animate-on-scroll group flex flex-col">
                <div class="w-16 h-16 bg-purple-500/20 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-rocket text-purple-400 text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold mb-4 group-hover:text-[#fe742b] transition-colors">{{ __('cases_landing.projects_title') }}</h2>
                <p class="text-gray-400 text-lg mb-8 flex-grow">{{ __('cases_landing.projects_text') }}</p>
                <span class="inline-flex items-center text-[#fe742b] font-semibold">
                    {{ __('cases_landing.projects_cta') }}
                    <i class="fas fa-arrow-right ml-2"></i>
                </span>
            </a>

        </div>
    </div>
</section>

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

@endsection
