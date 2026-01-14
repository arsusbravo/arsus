@extends('layouts.main')

@section('title', __('services.hero_title') . ' - ARSUS IT Solutions')

@section('content')

<!-- Hero Section -->
<section class="min-h-[60vh] flex items-center gradient-bg relative overflow-hidden page-section">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#fe742b] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-block px-4 py-2 bg-[#fe742b]/20 rounded-full text-[#fe742b] text-sm font-medium mb-6 animate-on-scroll">
                <i class="fas fa-cogs mr-2"></i>{{ __('services.badge') }}
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-on-scroll">
                {{ __('services.hero_title') }} <span class="text-[#fe742b]">{{ __('services.hero_title_highlight') }}</span>
            </h1>
            <p class="text-xl text-gray-300 animate-on-scroll">
                {{ __('services.hero_subtitle') }}
            </p>
        </div>
    </div>
</section>

<!-- Web Development -->
<section class="py-20 bg-[#0a0e27] relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-[#fe742b]/10 to-transparent rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-on-scroll">
                <div class="inline-flex items-center space-x-2 px-4 py-2 bg-[#fe742b]/10 rounded-full text-[#fe742b] text-sm font-medium mb-6">
                    <i class="fas fa-laptop-code"></i>
                    <span>{{ __('services.web_badge') }}</span>
                </div>
                <h2 class="text-4xl font-bold mb-6">{{ __('services.web_title') }}</h2>
                <p class="text-gray-400 mb-6">
                    {{ __('services.web_description') }}
                </p>

                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shopping-cart text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">{{ __('services.web_ecommerce_title') }}</h4>
                            <p class="text-sm text-gray-400">{{ __('services.web_ecommerce_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-globe text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">{{ __('services.web_pwa_title') }}</h4>
                            <p class="text-sm text-gray-400">{{ __('services.web_pwa_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">{{ __('services.web_cms_title') }}</h4>
                            <p class="text-sm text-gray-400">{{ __('services.web_cms_desc') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ locale_route('contact') }}" class="bg-[#fe742b] px-8 py-3 rounded-full font-semibold hover:bg-[#ff8c4d] transition-all duration-300 inline-flex items-center">
                        {{ __('services.web_cta') }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            
            <div class="animate-on-scroll">
                <div class="relative">
                    <div class="glass-effect p-8 rounded-3xl">
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="bg-gradient-to-br from-[#fe742b]/20 to-transparent p-4 rounded-xl text-center">
                                <i class="fab fa-laravel text-3xl text-red-500 mb-2"></i>
                                <p class="text-xs">Laravel</p>
                            </div>
                            <div class="bg-gradient-to-br from-green-600/20 to-transparent p-4 rounded-xl text-center">
                                <i class="fab fa-vuejs text-3xl text-green-500 mb-2"></i>
                                <p class="text-xs">Vue.js</p>
                            </div>
                            <div class="bg-gradient-to-br from-blue-600/20 to-transparent p-4 rounded-xl text-center">
                                <i class="fab fa-react text-3xl text-blue-400 mb-2"></i>
                                <p class="text-xs">React</p>
                            </div>
                        </div>
                        <div class="bg-[#050812] p-6 rounded-2xl font-mono text-sm">
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <code class="text-[#fe742b]">class</code> <code class="text-blue-400">WebApp</code> <code class="text-gray-400">{</code><br>
                            <code class="text-gray-400 ml-4">public function</code> <code class="text-green-400">build</code><code class="text-gray-400">() {</code><br>
                            <code class="text-gray-400 ml-8">return</code> <code class="text-yellow-400">'excellence'</code><code class="text-gray-400">;</code><br>
                            <code class="text-gray-400 ml-4">}</code><br>
                            <code class="text-gray-400">}</code>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-gradient-to-br from-[#fe742b] to-purple-600 rounded-full blur-2xl opacity-30"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Development -->
<section class="py-20 bg-gradient-to-b from-[#0a0e27] to-[#050812] relative overflow-hidden">
    <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-purple-600/10 to-transparent rounded-full blur-3xl"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="order-2 md:order-1 animate-on-scroll">
                <div class="relative">
                    <div class="glass-effect p-8 rounded-3xl">
                        <div class="relative mx-auto" style="width: 280px;">
                            <!-- Phone mockup -->
                            <div class="bg-gray-900 rounded-[3rem] p-3 shadow-2xl border-8 border-gray-800">
                                <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-[2.5rem] h-[500px] relative overflow-hidden">
                                    <!-- Notch -->
                                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-32 h-6 bg-gray-900 rounded-b-3xl"></div>
                                    
                                    <!-- Screen content -->
                                    <div class="pt-8 px-4 text-white">
                                        <div class="flex items-center justify-between mb-6">
                                            <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                                                <i class="fas fa-bars"></i>
                                            </div>
                                            <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-full flex items-center justify-center">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                        </div>

                                        <h3 class="text-2xl font-bold mb-2">{{ __('services.mobile_screen_title') }}</h3>
                                        <p class="text-sm opacity-80 mb-6">{{ __('services.mobile_screen_subtitle') }}</p>

                                        <div class="space-y-3">
                                            <div class="bg-white/10 backdrop-blur p-4 rounded-2xl">
                                                <div class="flex items-center space-x-3">
                                                    <i class="fas fa-rocket text-yellow-400"></i>
                                                    <span class="text-sm">{{ __('services.mobile_fast') }}</span>
                                                </div>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur p-4 rounded-2xl">
                                                <div class="flex items-center space-x-3">
                                                    <i class="fas fa-shield-alt text-green-400"></i>
                                                    <span class="text-sm">{{ __('services.mobile_secure') }}</span>
                                                </div>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur p-4 rounded-2xl">
                                                <div class="flex items-center space-x-3">
                                                    <i class="fas fa-heart text-red-400"></i>
                                                    <span class="text-sm">{{ __('services.mobile_loved') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-4 -left-4 w-32 h-32 bg-gradient-to-br from-purple-600 to-blue-600 rounded-full blur-2xl opacity-30"></div>
                </div>
            </div>
            
            <div class="order-1 md:order-2 animate-on-scroll">
                <div class="inline-flex items-center space-x-2 px-4 py-2 bg-purple-600/10 rounded-full text-purple-400 text-sm font-medium mb-6">
                    <i class="fas fa-mobile-alt"></i>
                    <span>{{ __('services.mobile_badge') }}</span>
                </div>
                <h2 class="text-4xl font-bold mb-6">{{ __('services.mobile_title') }}</h2>
                <p class="text-gray-400 mb-6">
                    {{ __('services.mobile_description') }}
                </p>

                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-apple text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">{{ __('services.mobile_ios_title') }}</h4>
                            <p class="text-sm text-gray-400">{{ __('services.mobile_ios_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-green-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-android text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">{{ __('services.mobile_android_title') }}</h4>
                            <p class="text-sm text-gray-400">{{ __('services.mobile_android_desc') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-react text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">{{ __('services.mobile_cross_title') }}</h4>
                            <p class="text-sm text-gray-400">{{ __('services.mobile_cross_desc') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ locale_route('contact') }}" class="bg-purple-600 px-8 py-3 rounded-full font-semibold hover:bg-purple-700 transition-all duration-300 inline-flex items-center">
                        {{ __('services.mobile_cta') }}
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cloud & API Integration -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                {{ __('services.cloud_section_title') }} <span class="text-[#fe742b]">{{ __('services.cloud_section_highlight') }}</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                {{ __('services.cloud_section_subtitle') }}
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Cloud Solutions -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="flex items-start space-x-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cloud text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-2">{{ __('services.cloud_title') }}</h3>
                        <p class="text-gray-400">{{ __('services.cloud_subtitle') }}</p>
                    </div>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        {{ __('services.cloud_feat1') }}
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        {{ __('services.cloud_feat2') }}
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        {{ __('services.cloud_feat3') }}
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        {{ __('services.cloud_feat4') }}
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        {{ __('services.cloud_feat5') }}
                    </li>
                </ul>

                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-blue-600/20 text-blue-400 rounded-full text-xs">AWS</span>
                    <span class="px-3 py-1 bg-blue-600/20 text-blue-400 rounded-full text-xs">Azure</span>
                    <span class="px-3 py-1 bg-blue-600/20 text-blue-400 rounded-full text-xs">Docker</span>
                    <span class="px-3 py-1 bg-blue-600/20 text-blue-400 rounded-full text-xs">Kubernetes</span>
                </div>
            </div>
            
            <!-- API Integration -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="flex items-start space-x-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-800 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-plug text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-2">{{ __('services.api_title') }}</h3>
                        <p class="text-gray-400">{{ __('services.api_subtitle') }}</p>
                    </div>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        {{ __('services.api_feat1') }}
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        {{ __('services.api_feat2') }}
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        {{ __('services.api_feat3') }}
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        {{ __('services.api_feat4') }}
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        {{ __('services.api_feat5') }}
                    </li>
                </ul>

                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-green-600/20 text-green-400 rounded-full text-xs">Stripe</span>
                    <span class="px-3 py-1 bg-green-600/20 text-green-400 rounded-full text-xs">Salesforce</span>
                    <span class="px-3 py-1 bg-green-600/20 text-green-400 rounded-full text-xs">Google APIs</span>
                    <span class="px-3 py-1 bg-green-600/20 text-green-400 rounded-full text-xs">Custom APIs</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="py-20 bg-gradient-to-b from-[#0a0e27] to-[#050812]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                {{ __('services.process_title') }} <span class="text-[#fe742b]">{{ __('services.process_title_highlight') }}</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                {{ __('services.process_subtitle') }}
            </p>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <i class="fas fa-lightbulb text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-[#fe742b] rounded-full flex items-center justify-center text-sm font-bold">1</div>
                </div>
                <h3 class="text-xl font-bold mb-2">{{ __('services.process_step1_title') }}</h3>
                <p class="text-gray-400 text-sm">{{ __('services.process_step1_desc') }}</p>
            </div>

            <div class="text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <i class="fas fa-pencil-ruler text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                </div>
                <h3 class="text-xl font-bold mb-2">{{ __('services.process_step2_title') }}</h3>
                <p class="text-gray-400 text-sm">{{ __('services.process_step2_desc') }}</p>
            </div>

            <div class="text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-800 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <i class="fas fa-code text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                </div>
                <h3 class="text-xl font-bold mb-2">{{ __('services.process_step3_title') }}</h3>
                <p class="text-gray-400 text-sm">{{ __('services.process_step3_desc') }}</p>
            </div>

            <div class="text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gradient-to-br from-green-600 to-green-800 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <i class="fas fa-rocket text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-sm font-bold">4</div>
                </div>
                <h3 class="text-xl font-bold mb-2">{{ __('services.process_step4_title') }}</h3>
                <p class="text-gray-400 text-sm">{{ __('services.process_step4_desc') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-[#fe742b] to-orange-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-on-scroll">
            {{ __('services.cta_title') }}
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-on-scroll">
            {{ __('services.cta_subtitle') }}
        </p>
        <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
            <a href="{{ locale_route('contact') }}" class="bg-white text-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-paper-plane mr-2"></i>
                {{ __('services.cta_start') }}
            </a>
            <a href="{{ locale_route('home') }}" class="glass-effect border-2 border-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('services.cta_back') }}
            </a>
        </div>
    </div>
</section>

@endsection