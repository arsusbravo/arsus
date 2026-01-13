<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ARSUS IT Solutions - World-Class Application Development')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
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
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} transition-colors duration-300">
                        Home
                    </a>
                    <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} transition-colors duration-300">
                        Services
                    </a>
                    <a href="#cases" class="hover:text-[#fe742b] transition-colors duration-300">Cases</a>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} transition-colors duration-300">About</a>
                    <a href="{{ route('contact') }}" class="bg-[#fe742b] px-6 py-2 rounded-full hover:bg-[#ff8c4d] transition-all duration-300 pulse-glow">
                        Get Started
                    </a>
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-2xl" id="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div class="md:hidden hidden mt-4 space-y-4" id="mobile-menu">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} block transition-colors duration-300">
                    Home
                </a>
                <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} block transition-colors duration-300">
                    Services
                </a>
                <a href="#cases" class="block hover:text-[#fe742b] transition-colors duration-300">Cases</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'text-[#fe742b] font-semibold' : 'hover:text-[#fe742b]' }} block transition-colors duration-300">About</a>
                <a href="{{ route('contact') }}" class="block bg-[#fe742b] px-6 py-2 rounded-full hover:bg-[#ff8c4d] transition-all duration-300 text-center">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#050812] py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <div class="mb-4 inline-block">
                        <span class="logo-text text-2xl block">&lt;arsus /&gt;</span>
                        <span class="text-[10px] text-gray-400 font-['Montserrat'] tracking-wider block text-right">IT Solutions</span>
                    </div>
                    <p class="text-gray-400 text-sm">Building world-class applications for businesses worldwide.</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-[#fe742b]">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-[#fe742b] transition-colors">Home</a></li>
                        <li><a href="{{ route('services') }}" class="hover:text-[#fe742b] transition-colors">Services</a></li>
                        <li><a href="#cases" class="hover:text-[#fe742b] transition-colors">Cases</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-[#fe742b] transition-colors">About Us</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-[#fe742b]">Services</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>Web Development</li>
                        <li>Mobile Apps</li>
                        <li>Cloud Solutions</li>
                        <li>API Integration</li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-[#fe742b]">Get In Touch</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Ravelstraat 29<br><span class="ml-6">2901 EH, Capelle aan den IJssel</span><br><span class="ml-6">The Netherlands</span></li>
                        <li><i class="fas fa-envelope mr-2"></i> <a href="#" class="hover:text-[#fe742b] transition-colors" data-email="info" data-domain="arsus.nl" onclick="sendEmail(this); return false;">Click to reveal email</a></li>
                        <li class="flex space-x-4 mt-4">
                            <a href="#" class="hover:text-[#fe742b] transition-colors"><i class="fab fa-linkedin text-xl"></i></a>
                            <a href="#" class="hover:text-[#fe742b] transition-colors"><i class="fab fa-github text-xl"></i></a>
                            <a href="#" class="hover:text-[#fe742b] transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} ARSUS IT Solutions. All rights reserved.</p>
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