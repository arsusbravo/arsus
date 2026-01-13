@extends('layouts.main')

@section('title', 'Our Services - ARSUS IT Solutions')

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
                <i class="fas fa-cogs mr-2"></i>What We Offer
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-on-scroll">
                Our <span class="text-[#fe742b]">Services</span>
            </h1>
            <p class="text-xl text-gray-300 animate-on-scroll">
                Comprehensive digital solutions tailored to accelerate your business growth and digital transformation
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
                    <span>Web Development</span>
                </div>
                <h2 class="text-4xl font-bold mb-6">Build Powerful Web Applications</h2>
                <p class="text-gray-400 mb-6">
                    We craft high-performance web applications using modern frameworks like Laravel and Vue.js. Our solutions are scalable, secure, and optimized for exceptional user experiences.
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shopping-cart text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">E-commerce Solutions</h4>
                            <p class="text-sm text-gray-400">Custom online stores with secure payment integration and inventory management</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-globe text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Progressive Web Apps</h4>
                            <p class="text-sm text-gray-400">Fast, reliable, and engaging apps that work offline and feel native</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Content Management Systems</h4>
                            <p class="text-sm text-gray-400">Custom CMS solutions that give you full control over your content</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <a href="#contact" class="bg-[#fe742b] px-8 py-3 rounded-full font-semibold hover:bg-[#ff8c4d] transition-all duration-300 inline-flex items-center">
                        Get Started
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
                                        
                                        <h3 class="text-2xl font-bold mb-2">Mobile First</h3>
                                        <p class="text-sm opacity-80 mb-6">Beautiful native experiences</p>
                                        
                                        <div class="space-y-3">
                                            <div class="bg-white/10 backdrop-blur p-4 rounded-2xl">
                                                <div class="flex items-center space-x-3">
                                                    <i class="fas fa-rocket text-yellow-400"></i>
                                                    <span class="text-sm">Fast Performance</span>
                                                </div>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur p-4 rounded-2xl">
                                                <div class="flex items-center space-x-3">
                                                    <i class="fas fa-shield-alt text-green-400"></i>
                                                    <span class="text-sm">Secure & Reliable</span>
                                                </div>
                                            </div>
                                            <div class="bg-white/10 backdrop-blur p-4 rounded-2xl">
                                                <div class="flex items-center space-x-3">
                                                    <i class="fas fa-heart text-red-400"></i>
                                                    <span class="text-sm">Loved by Users</span>
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
                    <span>Mobile Development</span>
                </div>
                <h2 class="text-4xl font-bold mb-6">Native & Cross-Platform Apps</h2>
                <p class="text-gray-400 mb-6">
                    Create stunning mobile applications for iOS and Android. We build native apps and cross-platform solutions that deliver seamless experiences across all devices.
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-apple text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">iOS Development</h4>
                            <p class="text-sm text-gray-400">Native Swift apps optimized for iPhone and iPad</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-green-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-android text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Android Development</h4>
                            <p class="text-sm text-gray-400">High-performance Kotlin apps for Android devices</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-react text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-1">Cross-Platform Development</h4>
                            <p class="text-sm text-gray-400">React Native apps that work everywhere with a single codebase</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <a href="#contact" class="bg-purple-600 px-8 py-3 rounded-full font-semibold hover:bg-purple-700 transition-all duration-300 inline-flex items-center">
                        Discuss Your App
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
                Cloud & <span class="text-[#fe742b]">Integration</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Scale your infrastructure and connect your systems seamlessly
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
                        <h3 class="text-2xl font-bold mb-2">Cloud Solutions</h3>
                        <p class="text-gray-400">Enterprise-grade cloud infrastructure</p>
                    </div>
                </div>
                
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        AWS & Azure cloud deployment
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        Serverless architecture design
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        Auto-scaling & load balancing
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        Cloud migration services
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-blue-400 mr-3"></i>
                        DevOps & CI/CD pipelines
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
                        <h3 class="text-2xl font-bold mb-2">API Integration</h3>
                        <p class="text-gray-400">Connect your systems seamlessly</p>
                    </div>
                </div>
                
                <ul class="space-y-3 mb-6">
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        RESTful API development
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        Third-party API integration
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        Payment gateway integration
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        CRM & ERP connectivity
                    </li>
                    <li class="flex items-center text-gray-300">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        Webhook & real-time data sync
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
                Our <span class="text-[#fe742b]">Process</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                A proven methodology to deliver exceptional results
            </p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-8">
            <div class="text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <i class="fas fa-lightbulb text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-[#fe742b] rounded-full flex items-center justify-center text-sm font-bold">1</div>
                </div>
                <h3 class="text-xl font-bold mb-2">Discovery</h3>
                <p class="text-gray-400 text-sm">Understanding your business goals and requirements</p>
            </div>
            
            <div class="text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-purple-800 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <i class="fas fa-pencil-ruler text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                </div>
                <h3 class="text-xl font-bold mb-2">Design</h3>
                <p class="text-gray-400 text-sm">Creating intuitive interfaces and user experiences</p>
            </div>
            
            <div class="text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-blue-800 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <i class="fas fa-code text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                </div>
                <h3 class="text-xl font-bold mb-2">Development</h3>
                <p class="text-gray-400 text-sm">Building robust solutions with clean code</p>
            </div>
            
            <div class="text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gradient-to-br from-green-600 to-green-800 rounded-full flex items-center justify-center mx-auto mb-4 relative">
                    <i class="fas fa-rocket text-3xl"></i>
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-sm font-bold">4</div>
                </div>
                <h3 class="text-xl font-bold mb-2">Launch</h3>
                <p class="text-gray-400 text-sm">Deploying and supporting your success</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-[#fe742b] to-orange-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-on-scroll">
            Let's Build Something Great Together
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-on-scroll">
            Ready to transform your ideas into reality? Our team is here to help.
        </p>
        <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
            <a href="#contact" class="bg-white text-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-paper-plane mr-2"></i>
                Start Your Project
            </a>
            <a href="{{ route('home') }}" class="glass-effect border-2 border-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Home
            </a>
        </div>
    </div>
</section>

@endsection