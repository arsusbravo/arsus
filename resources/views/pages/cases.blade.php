@extends('layouts.main')

@section('title', 'Cases - ARSUS IT Solutions')

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
                <i class="fas fa-briefcase mr-2"></i>Our Work
            </div>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6 animate-on-scroll">
                Success <span class="text-[#fe742b]">Stories</span>
            </h1>

            <p class="text-xl text-gray-300 animate-on-scroll">
                Learn how our team of experts has solved complex problems for businesses just like yours
            </p>
        </div>
    </div>
</section>

<!-- Cases Grid -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Case 1: Online Reservation System -->
            <div class="glass-effect rounded-2xl overflow-hidden hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="relative overflow-hidden">
                    <img src="{{ asset('images/cases/thumb-1.jpg') }}" alt="Online Reservation System" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e27] via-transparent to-transparent opacity-60"></div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-[#fe742b] transition-colors">Online Reservation System</h3>
                    <p class="text-gray-400 mb-4">
                        Streamlined vehicle rental operations with real-time booking system, inventory management, and comprehensive admin dashboard.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-xs px-3 py-1 bg-[#fe742b]/20 text-[#fe742b] rounded-full">Booking System</span>
                        <span class="text-xs px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full">Admin Dashboard</span>
                        <span class="text-xs px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full">Calendar Integration</span>
                    </div>
                    <a href="{{ route('case.show', 'online-reservation-system') }}" class="inline-flex items-center text-[#fe742b] hover:text-[#ff8c4d] transition-colors font-semibold">
                        View Case Study
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Case 2: Online Order System -->
            <div class="glass-effect rounded-2xl overflow-hidden hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="relative overflow-hidden">
                    <img src="{{ asset('images/cases/thumb-2.jpg') }}" alt="Online Order System" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e27] via-transparent to-transparent opacity-60"></div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-[#fe742b] transition-colors">Online Order System</h3>
                    <p class="text-gray-400 mb-4">
                        Members-only platform for weekly food ordering across multiple locations with menu management and multilingual support.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-xs px-3 py-1 bg-green-500/20 text-green-400 rounded-full">E-commerce</span>
                        <span class="text-xs px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full">Multi-location</span>
                        <span class="text-xs px-3 py-1 bg-pink-500/20 text-pink-400 rounded-full">Member Portal</span>
                    </div>
                    <a href="{{ route('case.show', 'online-order-system') }}" class="inline-flex items-center text-[#fe742b] hover:text-[#ff8c4d] transition-colors font-semibold">
                        View Case Study
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Case 3: Financial Calculators -->
            <div class="glass-effect rounded-2xl overflow-hidden hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="relative overflow-hidden">
                    <img src="{{ asset('images/cases/thumb-3.jpg') }}" alt="Financial Calculators" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e27] via-transparent to-transparent opacity-60"></div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-[#fe742b] transition-colors">Financial Calculators</h3>
                    <p class="text-gray-400 mb-4">
                        Interactive mortgage and loan calculators with real-time calculations, lead capture, and secure data storage.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-xs px-3 py-1 bg-emerald-500/20 text-emerald-400 rounded-full">FinTech</span>
                        <span class="text-xs px-3 py-1 bg-indigo-500/20 text-indigo-400 rounded-full">Calculator</span>
                        <span class="text-xs px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full">Lead Generation</span>
                    </div>
                    <a href="{{ route('case.show', 'financial-calculators') }}" class="inline-flex items-center text-[#fe742b] hover:text-[#ff8c4d] transition-colors font-semibold">
                        View Case Study
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Case 4: Digitalized Claim Form -->
            <div class="glass-effect rounded-2xl overflow-hidden hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="relative overflow-hidden">
                    <img src="{{ asset('images/cases/thumb-4.jpg') }}" alt="Digitalized Claim Form" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e27] via-transparent to-transparent opacity-60"></div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-[#fe742b] transition-colors">Digitalized Claim Form</h3>
                    <p class="text-gray-400 mb-4">
                        Paperless claim submission system with digital workflows, automated processing, and secure document management.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-xs px-3 py-1 bg-cyan-500/20 text-cyan-400 rounded-full">Digital Forms</span>
                        <span class="text-xs px-3 py-1 bg-teal-500/20 text-teal-400 rounded-full">Automation</span>
                        <span class="text-xs px-3 py-1 bg-violet-500/20 text-violet-400 rounded-full">Workflow</span>
                    </div>
                    <a href="{{ route('case.show', 'digitalized-form') }}" class="inline-flex items-center text-[#fe742b] hover:text-[#ff8c4d] transition-colors font-semibold">
                        View Case Study
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Case 5: Document Signing -->
            <div class="glass-effect rounded-2xl overflow-hidden hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="relative overflow-hidden">
                    <img src="{{ asset('images/cases/thumb-5.jpg') }}" alt="Effortless Document Signing" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e27] via-transparent to-transparent opacity-60"></div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-[#fe742b] transition-colors">Effortless Document Signing</h3>
                    <p class="text-gray-400 mb-4">
                        Secure e-signature platform enabling remote document signing with authentication, audit trails, and legal compliance.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-xs px-3 py-1 bg-rose-500/20 text-rose-400 rounded-full">E-Signature</span>
                        <span class="text-xs px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full">Security</span>
                        <span class="text-xs px-3 py-1 bg-lime-500/20 text-lime-400 rounded-full">Compliance</span>
                    </div>
                    <a href="{{ route('case.show', 'document-signing') }}" class="inline-flex items-center text-[#fe742b] hover:text-[#ff8c4d] transition-colors font-semibold">
                        View Case Study
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Case 6: Lead Generation -->
            <div class="glass-effect rounded-2xl overflow-hidden hover:scale-105 transition-all duration-300 animate-on-scroll group">
                <div class="relative overflow-hidden">
                    <img src="{{ asset('images/cases/thumb-6.jpg') }}" alt="Lead Generation" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0a0e27] via-transparent to-transparent opacity-60"></div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-[#fe742b] transition-colors">Lead Generation Platform</h3>
                    <p class="text-gray-400 mb-4">
                        Marketing automation system with form builders, CRM integration, analytics tracking, and conversion optimization tools.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-xs px-3 py-1 bg-fuchsia-500/20 text-fuchsia-400 rounded-full">Marketing</span>
                        <span class="text-xs px-3 py-1 bg-sky-500/20 text-sky-400 rounded-full">CRM</span>
                        <span class="text-xs px-3 py-1 bg-red-500/20 text-red-400 rounded-full">Analytics</span>
                    </div>
                    <a href="{{ route('case.show', 'lead-generation') }}" class="inline-flex items-center text-[#fe742b] hover:text-[#ff8c4d] transition-colors font-semibold">
                        View Case Study
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-[#fe742b] to-orange-600">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-on-scroll">
            Ready to Start Your Project?
        </h2>
        <p class="text-xl mb-8 max-w-2xl mx-auto animate-on-scroll">
            Let's create your success story together. Get in touch to discuss your project.
        </p>
        <div class="flex flex-wrap justify-center gap-4 animate-on-scroll">
            <a href="{{ route('contact') }}" class="bg-white text-[#fe742b] px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-envelope mr-2"></i>
                Contact Us
            </a>
            <a href="{{ route('services') }}" class="glass-effect border-2 border-white px-8 py-4 rounded-full font-semibold hover:bg-white/10 transition-all duration-300 inline-flex items-center">
                <i class="fas fa-arrow-right mr-2"></i>
                View Services
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
