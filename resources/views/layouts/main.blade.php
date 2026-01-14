<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ARSUS IT Solutions - World-Class Application Development')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-900 text-white font-['Poppins']">
    
    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-effect transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex flex-col items-start">
                    <span class="logo-text text-3xl">&lt;arsus /&gt;</span>
                    <span class="text-[10px] text-gray-400 font-['Montserrat'] tracking-wider self-end">IT Solutions</span>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ locale_route('home') }}" class="{{ is_active_route('home') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} transition-colors duration-300">
                        {{ __('messages.nav.home') }}
                    </a>
                    <a href="{{ locale_route('services') }}" class="{{ is_active_route('services') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} transition-colors duration-300">
                        {{ __('messages.nav.services') }}
                    </a>
                    <a href="{{ locale_route('cases') }}" class="{{ is_active_route('cases') || is_active_route('case.show') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} transition-colors duration-300">{{ __('messages.nav.cases') }}</a>
                    <a href="{{ locale_route('about') }}" class="{{ is_active_route('about') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} transition-colors duration-300">{{ __('messages.nav.about') }}</a>
                    <x-language-switcher />
                    <a href="{{ locale_route('contact') }}" class="bg-[#fe742b] px-6 py-2 rounded-full hover:bg-[#ff8c4d] transition-all duration-300 pulse-glow">
                        {{ __('messages.home.cta_primary') }}
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-2xl" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div class="md:hidden hidden mt-4 space-y-4" id="mobile-menu">
                <a href="{{ locale_route('home') }}" class="{{ is_active_route('home') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} block transition-colors duration-300">
                    {{ __('messages.nav.home') }}
                </a>
                <a href="{{ locale_route('services') }}" class="{{ is_active_route('services') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} block transition-colors duration-300">
                    {{ __('messages.nav.services') }}
                </a>
                <a href="{{ locale_route('cases') }}" class="{{ is_active_route('cases') || is_active_route('case.show') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} block transition-colors duration-300">{{ __('messages.nav.cases') }}</a>
                <a href="{{ locale_route('about') }}" class="{{ is_active_route('about') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} block transition-colors duration-300">{{ __('messages.nav.about') }}</a>

                <div class="py-2">
                    <x-language-switcher />
                </div>

                <a href="{{ locale_route('contact') }}" class="block bg-[#fe742b] px-6 py-2 rounded-full hover:bg-[#ff8c4d] transition-all duration-300 text-center">
                    {{ __('messages.home.cta_primary') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#050812] border-t border-gray-800/50 py-20">
        <div class="container mx-auto px-6">
            <!-- Top Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-16 mb-12">
                <!-- Company Info - Takes more space -->
                <div class="lg:col-span-1">
                    <div class="mb-2 inline-block">
                        <span class="logo-text text-3xl block">&lt;arsus /&gt;</span>
                        <span class="text-[10px] text-gray-400 font-['Montserrat'] tracking-wider block text-right mt-1">IT Solutions</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-2 max-w-sm">
                        Building world-class applications for businesses worldwide. Delivering excellence from our offices in the Netherlands and Indonesia.
                    </p>

                    <!-- Email -->
                    <div class="mb-2">
                        <a href="#" class="text-sm text-gray-400 hover:text-[#fe742b] transition-colors inline-flex items-center group"
                            data-email="info" data-domain="arsus.nl" onclick="sendEmail(this); return false;">
                            <i class="fas fa-envelope mr-2 text-[#fe742b] group-hover:scale-110 transition-transform"></i>
                            Click to reveal email
                        </a>
                    </div>

                    <!-- Social Links -->
                    <div class="flex space-x-4">
                        <a href="https://linkedin.com/in/arsus" target="_blank" rel="noopener noreferrer"
                           class="w-11 h-11 bg-white/5 hover:bg-[#fe742b] rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <i class="fab fa-linkedin text-lg"></i>
                        </a>
                        <a href="https://github.com/arsusbravo" target="_blank" rel="noopener noreferrer"
                           class="w-11 h-11 bg-white/5 hover:bg-[#fe742b] rounded-xl flex items-center justify-center transition-all duration-300 hover:scale-110">
                            <i class="fab fa-github text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Navigation & Services -->
                <div class="lg:col-span-1 grid grid-cols-2 gap-8">
                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-sm font-semibold mb-5 text-white uppercase tracking-wider">{{ __('messages.footer.quick_links') }}</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ locale_route('home') }}" class="text-sm text-gray-400 hover:text-[#fe742b] transition-colors block">{{ __('messages.nav.home') }}</a></li>
                            <li><a href="{{ locale_route('services') }}" class="text-sm text-gray-400 hover:text-[#fe742b] transition-colors block">{{ __('messages.nav.services') }}</a></li>
                            <li><a href="{{ locale_route('cases') }}" class="text-sm text-gray-400 hover:text-[#fe742b] transition-colors block">{{ __('messages.nav.cases') }}</a></li>
                            <li><a href="{{ locale_route('about') }}" class="text-sm text-gray-400 hover:text-[#fe742b] transition-colors block">{{ __('messages.nav.about') }}</a></li>
                            <li><a href="{{ locale_route('contact') }}" class="text-sm text-gray-400 hover:text-[#fe742b] transition-colors block">{{ __('messages.nav.contact') }}</a></li>
                        </ul>
                    </div>

                    <!-- Services -->
                    <div>
                        <h4 class="text-sm font-semibold mb-5 text-white uppercase tracking-wider">Services</h4>
                        <ul class="space-y-3">
                            <li class="text-sm text-gray-400">Web Development</li>
                            <li class="text-sm text-gray-400">Mobile Apps</li>
                            <li class="text-sm text-gray-400">Cloud Solutions</li>
                            <li class="text-sm text-gray-400">API Integration</li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="lg:col-span-1">
                    <h4 class="text-sm font-semibold mb-5 text-white uppercase tracking-wider">Get In Touch</h4>

                    <div class="space-y-6">
                        <!-- Netherlands HQ -->
                        <div>
                            <p class="text-xs font-bold text-[#fe742b] mb-2 uppercase tracking-wide">Netherlands HQ</p>
                            <p class="text-sm text-gray-400 leading-relaxed">
                                Ravelstraat 29<br>
                                2901 EH Capelle aan den IJssel
                            </p>
                        </div>

                        <!-- Indonesia Office -->
                        <div>
                            <p class="text-xs font-bold text-[#fe742b] mb-2 uppercase tracking-wide">Indonesia Office</p>
                            <p class="text-sm text-gray-400 leading-relaxed">
                                Jl. Dempo No.9<br>
                                Jakarta Pusat 10320
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800/50 pt-8">
                <p class="text-sm text-gray-500 text-center">&copy; {{ date('Y') }} ARSUS IT Solutions. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Email obfuscation - protects from spam bots
        function sendEmail(element) {
            const email = element.getAttribute('data-email');
            const domain = element.getAttribute('data-domain');
            const fullEmail = email + '@' + domain;

            // Update the link
            element.href = 'mailto:' + fullEmail;
            element.textContent = fullEmail;
            element.onclick = null; // Remove the onclick after first click

            // Optional: automatically open email client
            window.location.href = element.href;
        }

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-[#0a0e27]', 'shadow-lg');
            } else {
                navbar.classList.remove('bg-[#0a0e27]', 'shadow-lg');
            }
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>