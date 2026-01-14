@extends('layouts.main')

@section('title', 'ARSUS IT Solutions - Enterprise Application Development')

@section('content')

<!-- Hero Section -->
<section id="home" class="min-h-screen flex items-center gradient-bg relative overflow-hidden page-section">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#fe742b] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-20 left-1/2 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-4000"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div class="space-y-6">
                <div class="inline-block px-4 py-2 bg-[#fe742b]/20 rounded-full text-[#fe742b] text-sm font-medium animate-on-scroll">
                    <i class="fas fa-bolt mr-2"></i>{{ __('home.badge') }}
                </div>

                <h1 class="text-5xl md:text-7xl font-bold leading-tight animate-on-scroll">
                    {{ __('home.hero_title') }}
                    <span class="text-[#fe742b]">{{ __('home.hero_title_highlight') }}</span>
                </h1>

                <p class="text-xl text-gray-300 animate-on-scroll">
                    {{ __('home.hero_subtitle') }}
                </p>

                <div class="flex flex-wrap gap-4 animate-on-scroll">
                    <a href="{{ locale_route('contact') }}" class="bg-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-[#ff8c4d] transition-all duration-300 pulse-glow inline-flex items-center">
                        {{ __('home.cta_start_project') }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="{{ locale_route('cases') }}" class="glass-effect px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                        {{ __('home.cta_view_work') }}
                        <i class="fas fa-play ml-2"></i>
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 pt-8 animate-on-scroll">
                    <div>
                        <h3 class="text-3xl font-bold text-[#fe742b]">500+</h3>
                        <p class="text-gray-400 text-sm">{{ __('home.stats_projects') }}</p>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-[#fe742b]">50+</h3>
                        <p class="text-gray-400 text-sm">{{ __('home.stats_countries') }}</p>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-[#fe742b]">98%</h3>
                        <p class="text-gray-400 text-sm">{{ __('home.stats_satisfaction') }}</p>
                    </div>
                </div>
            </div>

            <!-- Illustration -->
            <div class="relative animate-on-scroll">
                <div class="float">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#fe742b] to-purple-600 rounded-3xl blur-3xl opacity-30"></div>
                        <div class="relative glass-effect p-8 rounded-3xl">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gradient-to-br from-[#fe742b]/20 to-transparent p-6 rounded-2xl">
                                    <i class="fas fa-code text-4xl text-[#fe742b] mb-4"></i>
                                    <h4 class="font-semibold">{{ __('home.feature_clean_code') }}</h4>
                                </div>
                                <div class="bg-gradient-to-br from-purple-600/20 to-transparent p-6 rounded-2xl">
                                    <i class="fas fa-mobile-alt text-4xl text-purple-400 mb-4"></i>
                                    <h4 class="font-semibold">{{ __('home.feature_responsive') }}</h4>
                                </div>
                                <div class="bg-gradient-to-br from-blue-600/20 to-transparent p-6 rounded-2xl">
                                    <i class="fas fa-shield-alt text-4xl text-blue-400 mb-4"></i>
                                    <h4 class="font-semibold">{{ __('home.feature_secure') }}</h4>
                                </div>
                                <div class="bg-gradient-to-br from-green-600/20 to-transparent p-6 rounded-2xl">
                                    <i class="fas fa-rocket text-4xl text-green-400 mb-4"></i>
                                    <h4 class="font-semibold">{{ __('home.feature_fast') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
        <a href="#services" class="text-gray-400 hover:text-[#fe742b] transition-colors">
            <i class="fas fa-chevron-down text-2xl"></i>
        </a>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                {{ __('home.services_title') }} <span class="text-[#fe742b]">{{ __('home.services_title_highlight') }}</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                {{ __('home.services_subtitle') }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Service Card 1 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="w-16 h-16 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-laptop-code text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('home.service_web_title') }}</h3>
                <p class="text-gray-400 mb-6">
                    {{ __('home.service_web_desc') }}
                </p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check text-[#fe742b] mr-2"></i>{{ __('home.service_web_feat1') }}</li>
                    <li><i class="fas fa-check text-[#fe742b] mr-2"></i>{{ __('home.service_web_feat2') }}</li>
                    <li><i class="fas fa-check text-[#fe742b] mr-2"></i>{{ __('home.service_web_feat3') }}</li>
                </ul>
            </div>

            <!-- Service Card 2 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-mobile-alt text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('home.service_mobile_title') }}</h3>
                <p class="text-gray-400 mb-6">
                    {{ __('home.service_mobile_desc') }}
                </p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check text-purple-400 mr-2"></i>{{ __('home.service_mobile_feat1') }}</li>
                    <li><i class="fas fa-check text-purple-400 mr-2"></i>{{ __('home.service_mobile_feat2') }}</li>
                    <li><i class="fas fa-check text-purple-400 mr-2"></i>{{ __('home.service_mobile_feat3') }}</li>
                </ul>
            </div>

            <!-- Service Card 3 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-cloud text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('home.service_cloud_title') }}</h3>
                <p class="text-gray-400 mb-6">
                    {{ __('home.service_cloud_desc') }}
                </p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check text-blue-400 mr-2"></i>{{ __('home.service_cloud_feat1') }}</li>
                    <li><i class="fas fa-check text-blue-400 mr-2"></i>{{ __('home.service_cloud_feat2') }}</li>
                    <li><i class="fas fa-check text-blue-400 mr-2"></i>{{ __('home.service_cloud_feat3') }}</li>
                </ul>
            </div>

            <!-- Service Card 4 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plug text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('home.service_api_title') }}</h3>
                <p class="text-gray-400 mb-6">
                    {{ __('home.service_api_desc') }}
                </p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check text-green-400 mr-2"></i>{{ __('home.service_api_feat1') }}</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>{{ __('home.service_api_feat2') }}</li>
                    <li><i class="fas fa-check text-green-400 mr-2"></i>{{ __('home.service_api_feat3') }}</li>
                </ul>
            </div>

            <!-- Service Card 5 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-600 to-yellow-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-paint-brush text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('home.service_uiux_title') }}</h3>
                <p class="text-gray-400 mb-6">
                    {{ __('home.service_uiux_desc') }}
                </p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check text-yellow-400 mr-2"></i>{{ __('home.service_uiux_feat1') }}</li>
                    <li><i class="fas fa-check text-yellow-400 mr-2"></i>{{ __('home.service_uiux_feat2') }}</li>
                    <li><i class="fas fa-check text-yellow-400 mr-2"></i>{{ __('home.service_uiux_feat3') }}</li>
                </ul>
            </div>

            <!-- Service Card 6 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fas fa-headset text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('home.service_support_title') }}</h3>
                <p class="text-gray-400 mb-6">
                    {{ __('home.service_support_desc') }}
                </p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check text-red-400 mr-2"></i>{{ __('home.service_support_feat1') }}</li>
                    <li><i class="fas fa-check text-red-400 mr-2"></i>{{ __('home.service_support_feat2') }}</li>
                    <li><i class="fas fa-check text-red-400 mr-2"></i>{{ __('home.service_support_feat3') }}</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Technologies Section -->
<section class="py-20 bg-gradient-to-b from-[#0a0e27] to-[#050812]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                {{ __('home.tech_title') }} <span class="text-[#fe742b]">{{ __('home.tech_title_highlight') }}</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                {{ __('home.tech_subtitle') }}
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <div class="glass-effect p-6 rounded-xl text-center hover:scale-110 transition-transform animate-on-scroll">
                <i class="fab fa-laravel text-5xl text-red-500 mb-3"></i>
                <p class="text-sm font-semibold">Laravel</p>
            </div>
            <div class="glass-effect p-6 rounded-xl text-center hover:scale-110 transition-transform animate-on-scroll">
                <i class="fab fa-vuejs text-5xl text-green-500 mb-3"></i>
                <p class="text-sm font-semibold">Vue.js</p>
            </div>
            <div class="glass-effect p-6 rounded-xl text-center hover:scale-110 transition-transform animate-on-scroll">
                <i class="fab fa-react text-5xl text-blue-400 mb-3"></i>
                <p class="text-sm font-semibold">React</p>
            </div>
            <div class="glass-effect p-6 rounded-xl text-center hover:scale-110 transition-transform animate-on-scroll">
                <i class="fab fa-node-js text-5xl text-green-600 mb-3"></i>
                <p class="text-sm font-semibold">Node.js</p>
            </div>
            <div class="glass-effect p-6 rounded-xl text-center hover:scale-110 transition-transform animate-on-scroll">
                <i class="fab fa-php text-5xl text-purple-500 mb-3"></i>
                <p class="text-sm font-semibold">PHP</p>
            </div>
            <div class="glass-effect p-6 rounded-xl text-center hover:scale-110 transition-transform animate-on-scroll">
                <i class="fab fa-python text-5xl text-yellow-500 mb-3"></i>
                <p class="text-sm font-semibold">Python</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section id="contact" class="py-20 bg-gradient-to-r from-[#fe742b] to-orange-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-on-scroll">
            {{ __('home.cta_title') }}
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-on-scroll">
            {{ __('home.cta_subtitle') }}
        </p>
        <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
            <a href="{{ locale_route('contact') }}" class="bg-white text-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                {{ __('home.cta_contact') }}
            </a>
            <a href="{{ locale_route('contact') }}" class="glass-effect border-2 border-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-calendar mr-2"></i>
                {{ __('home.cta_schedule') }}
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

.animation-delay-4000 {
    animation-delay: 4s;
}
</style>
