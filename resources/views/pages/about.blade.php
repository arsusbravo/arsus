@extends('layouts.main')

@section('title', 'About Us - ARSUS IT Solutions')

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
                <i class="fas fa-users mr-2"></i>About ARSUS IT Solutions
            </div>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6 animate-on-scroll">
                Crafting Digital Solutions
                <span class="text-[#fe742b]">Since Day One</span>
            </h1>

            <p class="text-xl text-gray-300 animate-on-scroll">
                We're a passionate team of developers, designers, and innovators dedicated to transforming businesses through exceptional technology solutions.
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
                    Our <span class="text-[#fe742b]">Story</span>
                </h2>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    Based in the heart of The Netherlands, ARSUS IT Solutions was founded with a clear mission: to empower businesses with world-class digital solutions that drive growth and innovation.
                </p>
                <p class="text-gray-400 mb-4 leading-relaxed">
                    What started as a small team of passionate developers has grown into a full-service development agency serving clients across 50+ countries. We've successfully delivered over 500 projects, ranging from simple websites to complex enterprise applications.
                </p>
                <p class="text-gray-400 leading-relaxed">
                    Our commitment to quality, innovation, and client satisfaction has earned us a 98% client satisfaction rate and long-term partnerships with businesses worldwide.
                </p>
            </div>

            <div class="relative animate-on-scroll">
                <div class="glass-effect p-8 rounded-3xl">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center p-6 bg-gradient-to-br from-[#fe742b]/20 to-transparent rounded-2xl">
                            <h3 class="text-4xl font-bold text-[#fe742b] mb-2">500+</h3>
                            <p class="text-gray-400">Projects Delivered</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-purple-600/20 to-transparent rounded-2xl">
                            <h3 class="text-4xl font-bold text-purple-400 mb-2">50+</h3>
                            <p class="text-gray-400">Countries Served</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-blue-600/20 to-transparent rounded-2xl">
                            <h3 class="text-4xl font-bold text-blue-400 mb-2">98%</h3>
                            <p class="text-gray-400">Satisfaction Rate</p>
                        </div>
                        <div class="text-center p-6 bg-gradient-to-br from-green-600/20 to-transparent rounded-2xl">
                            <h3 class="text-4xl font-bold text-green-400 mb-2">24/7</h3>
                            <p class="text-gray-400">Support Available</p>
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
                Our <span class="text-[#fe742b]">Core Values</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                The principles that guide everything we do
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Value 1 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-gem text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Quality First</h3>
                <p class="text-gray-400">
                    We never compromise on quality. Every line of code, every design element, and every feature is crafted with meticulous attention to detail.
                </p>
            </div>

            <!-- Value 2 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-purple-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-lightbulb text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Innovation Driven</h3>
                <p class="text-gray-400">
                    We stay ahead of the curve by embracing cutting-edge technologies and innovative approaches to solve complex business challenges.
                </p>
            </div>

            <!-- Value 3 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-handshake text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Client Partnership</h3>
                <p class="text-gray-400">
                    Your success is our success. We build long-term partnerships based on trust, transparency, and mutual growth.
                </p>
            </div>

            <!-- Value 4 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-green-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-rocket text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Agile & Fast</h3>
                <p class="text-gray-400">
                    We deliver results quickly without sacrificing quality. Our agile approach ensures rapid development and continuous improvement.
                </p>
            </div>

            <!-- Value 5 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-600 to-yellow-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Security Focused</h3>
                <p class="text-gray-400">
                    We prioritize security in every project, implementing industry best practices to protect your data and users.
                </p>
            </div>

            <!-- Value 6 -->
            <div class="glass-effect p-8 rounded-2xl hover:scale-105 transition-all duration-300 animate-on-scroll">
                <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-heart text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Passion & Dedication</h3>
                <p class="text-gray-400">
                    We love what we do. Our team is genuinely passionate about creating exceptional digital experiences that make a difference.
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
                What We <span class="text-[#fe742b]">Do Best</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Our expertise spans across the entire digital landscape
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
                        <h3 class="text-xl font-bold mb-3">Full-Stack Development</h3>
                        <p class="text-gray-400 leading-relaxed">
                            From frontend interfaces to backend systems, we handle the complete development lifecycle. Our tech stack includes Laravel, Vue.js, React, Node.js, and more.
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
                        <h3 class="text-xl font-bold mb-3">Mobile-First Approach</h3>
                        <p class="text-gray-400 leading-relaxed">
                            With mobile usage dominating the digital landscape, we build responsive web apps and native mobile applications that work flawlessly on any device.
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
                        <h3 class="text-xl font-bold mb-3">Cloud Architecture</h3>
                        <p class="text-gray-400 leading-relaxed">
                            We design and deploy scalable cloud solutions on AWS, Azure, and other platforms, ensuring your applications can grow with your business.
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
                        <h3 class="text-xl font-bold mb-3">UX/UI Excellence</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Great functionality deserves great design. Our design team creates intuitive, beautiful interfaces that users love to interact with.
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
                    Why Choose <span class="text-[#fe742b]">ARSUS</span>
                </h2>
                <p class="text-gray-400 text-lg">
                    We're not just another development agency
                </p>
            </div>

            <div class="space-y-6">
                <!-- Reason 1 -->
                <div class="glass-effect p-6 rounded-xl animate-on-scroll hover:bg-white/5 transition-all">
                    <div class="flex items-start space-x-4">
                        <div class="text-[#fe742b] text-2xl font-bold">01</div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Proven Track Record</h3>
                            <p class="text-gray-400">
                                With 500+ successful projects across diverse industries, we've demonstrated our ability to deliver exceptional results consistently.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reason 2 -->
                <div class="glass-effect p-6 rounded-xl animate-on-scroll hover:bg-white/5 transition-all">
                    <div class="flex items-start space-x-4">
                        <div class="text-[#fe742b] text-2xl font-bold">02</div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Transparent Communication</h3>
                            <p class="text-gray-400">
                                We keep you informed every step of the way. No surprises, no hidden costs—just honest, open communication.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reason 3 -->
                <div class="glass-effect p-6 rounded-xl animate-on-scroll hover:bg-white/5 transition-all">
                    <div class="flex items-start space-x-4">
                        <div class="text-[#fe742b] text-2xl font-bold">03</div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Cutting-Edge Technology</h3>
                            <p class="text-gray-400">
                                We use the latest technologies and best practices to ensure your application is modern, maintainable, and future-proof.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Reason 4 -->
                <div class="glass-effect p-6 rounded-xl animate-on-scroll hover:bg-white/5 transition-all">
                    <div class="flex items-start space-x-4">
                        <div class="text-[#fe742b] text-2xl font-bold">04</div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Post-Launch Support</h3>
                            <p class="text-gray-400">
                                Our relationship doesn't end at launch. We provide ongoing support, maintenance, and enhancements to ensure long-term success.
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
            Ready to Work Together?
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-on-scroll">
            Let's discuss how we can help transform your business with cutting-edge digital solutions.
        </p>
        <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
            <a href="{{ route('contact') }}" class="bg-white text-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                Get In Touch
            </a>
            <a href="{{ route('services') }}" class="glass-effect border-2 border-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-arrow-right mr-2"></i>
                View Our Services
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
