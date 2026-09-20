@extends('layouts.main')

@section('title', __('projects.hero_title') . ' ' . __('projects.hero_title_highlight') . ' - ARSUS IT Solutions')

@section('content')

<!-- Hero Section -->
<section class="min-h-[60vh] flex items-center gradient-bg relative overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#fe742b] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="mb-8 animate-on-scroll">
                <a href="{{ locale_route('cases') }}" class="text-gray-400 hover:text-[#fe742b] transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('cases_landing.back_to_cases') }}
                </a>
            </div>

            <div class="inline-block px-4 py-2 bg-[#fe742b]/20 rounded-full text-[#fe742b] text-sm font-medium mb-6 animate-on-scroll">
                <i class="fas fa-rocket mr-2"></i>{{ __('projects.badge') }}
            </div>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6 animate-on-scroll">
                {{ __('projects.hero_title') }} <span class="text-[#fe742b]">{{ __('projects.hero_title_highlight') }}</span>
            </h1>

            <p class="text-xl text-gray-300 animate-on-scroll">
                {{ __('projects.hero_subtitle') }}
            </p>
        </div>
    </div>
</section>

<!-- Projects Grid -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $slug => $project)
            @php $title = __("projects.items.{$slug}.title"); @endphp
            <a href="{{ $project['url'] }}" target="_blank" rel="noopener noreferrer"
               class="glass-effect rounded-2xl overflow-hidden hover:scale-105 transition-all duration-300 animate-on-scroll group flex flex-col">
                <div class="relative overflow-hidden aspect-video">
                    @if(!empty($project['image']))
                        <img src="{{ asset($project['image']) }}" alt="{{ $title }}" loading="lazy" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br {{ $project['gradient'] }} flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                            <i class="fas {{ $project['icon'] }} text-white/80 text-7xl"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e27] via-transparent to-transparent opacity-60"></div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-[#fe742b] transition-colors">{{ $title }}</h3>
                    <p class="text-gray-400 mb-4 flex-grow">{{ __("projects.items.{$slug}.description") }}</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($project['tags'] as $tag)
                        <span class="text-xs px-3 py-1 bg-[#fe742b]/20 text-[#fe742b] rounded-full">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <span class="inline-flex items-center text-[#fe742b] group-hover:text-[#ff8c4d] transition-colors font-semibold">
                        {{ __('projects.visit_project') }}
                        <i class="fas fa-arrow-up-right-from-square ml-2"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-[#fe742b] to-orange-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-on-scroll">
            {{ __('projects.cta_title') }}
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-on-scroll">
            {{ __('projects.cta_subtitle') }}
        </p>
        <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
            <a href="{{ locale_route('contact') }}" class="bg-white text-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                {{ __('projects.cta_contact') }}
            </a>
            <a href="{{ locale_route('cases.success-stories') }}" class="glass-effect border-2 border-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-trophy mr-2"></i>
                {{ __('projects.cta_success_stories') }}
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
