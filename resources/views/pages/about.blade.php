@extends('layouts.main')

@section('title', __('about.hero_title') . ' - ARSUS IT Solutions')

@section('content')

<!-- Hero Section -->
<section class="min-h-[60vh] flex items-center gradient-bg relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#fe742b] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-block px-4 py-2 bg-[#fe742b]/20 rounded-full text-[#fe742b] text-sm font-medium mb-6 animate-on-scroll">
                <i class="fas fa-users mr-2"></i>{{ __('about.badge') }}
            </div>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6 animate-on-scroll">
                {{ __('about.hero_title') }}
                <span class="text-[#fe742b]">{{ __('about.hero_title_highlight') }}</span>
            </h1>

            <p class="text-xl text-gray-300 animate-on-scroll">
                {{ __('about.hero_subtitle') }}
            </p>
        </div>
    </div>
</section>

<!-- Our Story Section -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-on-scroll">
                <h2 class="text-4xl font-bold mb-6">
                    {{ __('about.story_title') }} <span class="text-[#fe742b]">{{ __('about.story_title_highlight') }}</span>
                </h2>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    {{ __('about.story_paragraph1') }}
                </p>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    {{ __('about.story_paragraph2') }}
                </p>
                <p class="text-gray-400 leading-relaxed">
                    {{ __('about.story_paragraph3') }}
                </p>
            </div>

            <div class="relative animate-on-scroll">
                <div class="glass-effect p-8 rounded-3xl">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center p-6 bg-gradient-to-br from-[#fe742b]/20 to-transparent rounded-2xl">
                            <h3 class="text-4xl font-bold text-[#fe742b] mb-2">500+</h3>
                            <p class="text-gray-400">{{ __('about.stat_projects') }}</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-purple-600/20 to-transparent rounded-2xl">
                            <h3 class="text-4xl font-bold text-purple-400 mb-2">50+</h3>
                            <p class="text-gray-400">{{ __('about.stat_countries') }}</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-blue-600/20 to-transparent rounded-2xl">
                            <h3 class="text-4xl font-bold text-blue-400 mb-2">98%</h3>
                            <p class="text-gray-400">{{ __('about.stat_satisfaction') }}</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-green-600/20 to-transparent rounded-2xl">
                            <h3 class="text-4xl font-bold text-green-400 mb-2">24/7</h3>
                            <p class="text-gray-400">{{ __('about.stat_support') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Values Section -->
<section class="py-20 bg-gradient-to-b from-[#0a0e27] to-[#050812]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                {{ __('about.values_title') }} <span class="text-[#fe742b]">{{ __('about.values_title_highlight') }}</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                {{ __('about.values_subtitle') }}
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Value 1 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-gem text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('about.value1_title') }}</h3>
                <p class="text-gray-400">
                    {{ __('about.value1_desc') }}
                </p>
            </div>

            <!-- Value 2 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-lightbulb text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('about.value2_title') }}</h3>
                <p class="text-gray-400">
                    {{ __('about.value2_desc') }}
                </p>
            </div>

            <!-- Value 3 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-handshake text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('about.value3_title') }}</h3>
                <p class="text-gray-400">
                    {{ __('about.value3_desc') }}
                </p>
            </div>

            <!-- Value 4 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-rocket text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('about.value4_title') }}</h3>
                <p class="text-gray-400">
                    {{ __('about.value4_desc') }}
                </p>
            </div>

            <!-- Value 5 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-600 to-yellow-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('about.value5_title') }}</h3>
                <p class="text-gray-400">
                    {{ __('about.value5_desc') }}
                </p>
            </div>

            <!-- Value 6 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-heart text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">{{ __('about.value6_title') }}</h3>
                <p class="text-gray-400">
                    {{ __('about.value6_desc') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                {{ __('about.what_we_do_title') }} <span class="text-[#fe742b]">{{ __('about.what_we_do_highlight') }}</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                {{ __('about.what_we_do_subtitle') }}
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Expertise 1 -->
            <div class="glass-effect p-8 rounded-2xl animate-on-scroll">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-code text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-3">{{ __('about.expertise1_title') }}</h3>
                        <p class="text-gray-400 leading-relaxed">
                            {{ __('about.expertise1_desc') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Expertise 2 -->
            <div class="glass-effect p-8 rounded-2xl animate-on-scroll">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-mobile-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-3">{{ __('about.expertise2_title') }}</h3>
                        <p class="text-gray-400 leading-relaxed">
                            {{ __('about.expertise2_desc') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Expertise 3 -->
            <div class="glass-effect p-8 rounded-2xl animate-on-scroll">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cloud text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-3">{{ __('about.expertise3_title') }}</h3>
                        <p class="text-gray-400 leading-relaxed">
                            {{ __('about.expertise3_desc') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Expertise 4 -->
            <div class="glass-effect p-8 rounded-2xl animate-on-scroll">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-green-800 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-paint-brush text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-3">{{ __('about.expertise4_title') }}</h3>
                        <p class="text-gray-400 leading-relaxed">
                            {{ __('about.expertise4_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-20 bg-gradient-to-b from-[#0a0e27] to-[#050812]">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ __('about.why_choose_title') }} <span class="text-[#fe742b]">{{ __('about.why_choose_highlight') }}</span>
                </h2>
                <p class="text-gray-400 text-lg">
                    {{ __('about.why_choose_subtitle') }}
                </p>
            </div>

            <div class="space-y-6">
                <!-- Reason 1 -->
                <div class="glass-effect p-6 rounded-xl animate-on-scroll hover:bg-white/5 transition-all">
                    <div class="flex items-start space-x-4">
                        <div class="text-[#fe742b] text-2xl font-bold">01</div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">{{ __('about.reason1_title') }}</h3>
                            <p class="text-gray-400">
                                {{ __('about.reason1_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reason 2 -->
                <div class="glass-effect p-6 rounded-xl animate-on-scroll hover:bg-white/5 transition-all">
                    <div class="flex items-start space-x-4">
                        <div class="text-[#fe742b] text-2xl font-bold">02</div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">{{ __('about.reason2_title') }}</h3>
                            <p class="text-gray-400">
                                {{ __('about.reason2_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reason 3 -->
                <div class="glass-effect p-6 rounded-xl animate-on-scroll hover:bg-white/5 transition-all">
                    <div class="flex items-start space-x-4">
                        <div class="text-[#fe742b] text-2xl font-bold">03</div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">{{ __('about.reason3_title') }}</h3>
                            <p class="text-gray-400">
                                {{ __('about.reason3_desc') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reason 4 -->
                <div class="glass-effect p-6 rounded-xl animate-on-scroll hover:bg-white/5 transition-all">
                    <div class="flex items-start space-x-4">
                        <div class="text-[#fe742b] text-2xl font-bold">04</div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">{{ __('about.reason4_title') }}</h3>
                            <p class="text-gray-400">
                                {{ __('about.reason4_desc') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-[#fe742b] to-orange-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-on-scroll">
            {{ __('about.cta_title') }}
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-on-scroll">
            {{ __('about.cta_subtitle') }}
        </p>
        <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
            <a href="{{ locale_route('contact') }}" class="bg-white text-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                {{ __('about.cta_contact') }}
            </a>
            <a href="{{ locale_route('services') }}" class="glass-effect border-2 border-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-arrow-right mr-2"></i>
                {{ __('about.cta_services') }}
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
