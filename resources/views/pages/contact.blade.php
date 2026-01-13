@extends('layouts.main')

@section('title', 'Contact Us - ARSUS IT Solutions')

@section('content')

<!-- Hero Section -->
<section class="min-h-[50vh] flex items-center gradient-bg relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-72 h-72 bg-[#fe742b] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-block px-4 py-2 bg-[#fe742b]/20 rounded-full text-[#fe742b] text-sm font-medium mb-6 animate-on-scroll">
                <i class="fas fa-envelope mr-2"></i>Get In Touch
            </div>

            <h1 class="text-5xl md:text-6xl font-bold leading-tight mb-6 animate-on-scroll">
                Let's Build Something
                <span class="text-[#fe742b]">Amazing Together</span>
            </h1>

            <p class="text-xl text-gray-300 animate-on-scroll">
                Have a project in mind? We'd love to hear about it. Send us a message and we'll respond within 24 hours.
            </p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-[#0a0e27]">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="animate-on-scroll">
                <h2 class="text-3xl font-bold mb-6">Contact Information</h2>
                <p class="text-gray-400 mb-8">
                    We're here to help and answer any questions you might have. We look forward to hearing from you.
                </p>

                <div class="space-y-6">
                    <!-- Address -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#fe742b] to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Visit Us</h3>
                            <p class="text-gray-400">
                                Ravelstraat 29<br>
                                2901 EH, Capelle aan den IJssel<br>
                                The Netherlands
                            </p>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Email Us</h3>
                            <a href="#" class="text-gray-400 hover:text-[#fe742b] transition-colors" data-email="info" data-domain="arsus.nl" onclick="revealEmail(this); return false;">
                                Click to reveal email
                            </a>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-1">Business Hours</h3>
                            <p class="text-gray-400">
                                Monday - Friday: 9:00 AM - 6:00 PM CET<br>
                                Saturday - Sunday: Closed
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="mt-8 pt-8 border-t border-gray-800">
                    <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 glass-effect rounded-lg flex items-center justify-center hover:bg-[#fe742b] transition-all duration-300">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="w-10 h-10 glass-effect rounded-lg flex items-center justify-center hover:bg-[#fe742b] transition-all duration-300">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="w-10 h-10 glass-effect rounded-lg flex items-center justify-center hover:bg-[#fe742b] transition-all duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="animate-on-scroll">
                <div class="glass-effect p-8 rounded-2xl">
                    <h2 class="text-3xl font-bold mb-6">Send Us a Message</h2>

                    <!-- Success Message -->
                    <div id="successMessage" class="hidden bg-green-500/20 border border-green-500 text-green-400 px-6 py-4 rounded-lg mb-6">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>Thank you! Your message has been sent successfully. We'll get back to you soon.</span>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="hidden bg-red-500/20 border border-red-500 text-red-400 px-6 py-4 rounded-lg mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span id="errorText">Something went wrong. Please try again.</span>
                    </div>

                    <form id="contactForm" class="space-y-6">
                        <!-- Honeypot (hidden field for spam protection) -->
                        <input type="text" name="website" id="website" style="display: none;" tabindex="-1" autocomplete="off">

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium mb-2">
                                Full Name <span class="text-[#fe742b]">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                required
                                class="w-full bg-[#050812] border border-gray-700 rounded-lg px-4 py-3 focus:border-[#fe742b] focus:outline-none focus:ring-2 focus:ring-[#fe742b]/20 transition-all"
                                placeholder="John Doe"
                            >
                            <p class="text-red-400 text-sm mt-1 hidden" id="nameError">Please enter your full name</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium mb-2">
                                Email Address <span class="text-[#fe742b]">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                class="w-full bg-[#050812] border border-gray-700 rounded-lg px-4 py-3 focus:border-[#fe742b] focus:outline-none focus:ring-2 focus:ring-[#fe742b]/20 transition-all"
                                placeholder="john@example.com"
                            >
                            <p class="text-red-400 text-sm mt-1 hidden" id="emailError">Please enter a valid email address</p>
                        </div>

                        <!-- Company (Optional) -->
                        <div>
                            <label for="company" class="block text-sm font-medium mb-2">
                                Company Name <span class="text-gray-500 text-xs">(Optional)</span>
                            </label>
                            <input
                                type="text"
                                id="company"
                                name="company"
                                class="w-full bg-[#050812] border border-gray-700 rounded-lg px-4 py-3 focus:border-[#fe742b] focus:outline-none focus:ring-2 focus:ring-[#fe742b]/20 transition-all"
                                placeholder="Your Company"
                            >
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium mb-2">
                                Subject <span class="text-[#fe742b]">*</span>
                            </label>
                            <select
                                id="subject"
                                name="subject"
                                required
                                class="w-full bg-[#050812] border border-gray-700 rounded-lg px-4 py-3 focus:border-[#fe742b] focus:outline-none focus:ring-2 focus:ring-[#fe742b]/20 transition-all"
                            >
                                <option value="">Select a subject</option>
                                <option value="general">General Inquiry</option>
                                <option value="quote">Request a Quote</option>
                                <option value="web">Web Development</option>
                                <option value="mobile">Mobile Development</option>
                                <option value="cloud">Cloud Solutions</option>
                                <option value="support">Technical Support</option>
                                <option value="other">Other</option>
                            </select>
                            <p class="text-red-400 text-sm mt-1 hidden" id="subjectError">Please select a subject</p>
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium mb-2">
                                Message <span class="text-[#fe742b]">*</span>
                            </label>
                            <textarea
                                id="message"
                                name="message"
                                required
                                rows="5"
                                class="w-full bg-[#050812] border border-gray-700 rounded-lg px-4 py-3 focus:border-[#fe742b] focus:outline-none focus:ring-2 focus:ring-[#fe742b]/20 transition-all resize-none"
                                placeholder="Tell us about your project..."
                            ></textarea>
                            <p class="text-red-400 text-sm mt-1 hidden" id="messageError">Please enter a message (minimum 10 characters)</p>
                        </div>

                        <!-- Privacy Policy -->
                        <div class="flex items-start space-x-3">
                            <input
                                type="checkbox"
                                id="privacy"
                                name="privacy"
                                required
                                class="mt-1 w-4 h-4 bg-[#050812] border border-gray-700 rounded focus:ring-[#fe742b] focus:ring-2 text-[#fe742b]"
                            >
                            <label for="privacy" class="text-sm text-gray-400">
                                I agree to the privacy policy and consent to ARSUS IT Solutions storing my submitted information. <span class="text-[#fe742b]">*</span>
                            </label>
                        </div>
                        <p class="text-red-400 text-sm mt-1 hidden" id="privacyError">Please accept the privacy policy</p>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            id="submitBtn"
                            class="w-full bg-gradient-to-r from-[#fe742b] to-orange-600 px-8 py-4 rounded-full font-semibold hover:from-[#ff8c4d] hover:to-orange-700 transition-all duration-300 pulse-glow inline-flex items-center justify-center"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>
                            <span id="btnText">Send Message</span>
                            <svg id="spinner" class="hidden animate-spin ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>

                        <p class="text-sm text-gray-400 text-center">
                            <i class="fas fa-lock mr-1"></i>
                            Your information is secure and will never be shared with third parties.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gradient-to-b from-[#0a0e27] to-[#050812]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                Frequently Asked <span class="text-[#fe742b]">Questions</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                Quick answers to common questions
            </p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
            <!-- FAQ 1 -->
            <div class="glass-effect p-6 rounded-xl animate-on-scroll">
                <h3 class="text-lg font-semibold mb-2">How quickly will you respond to my inquiry?</h3>
                <p class="text-gray-400">We typically respond within 24 hours during business days. For urgent matters, please mention it in your message.</p>
            </div>

            <!-- FAQ 2 -->
            <div class="glass-effect p-6 rounded-xl animate-on-scroll">
                <h3 class="text-lg font-semibold mb-2">Do you offer free consultations?</h3>
                <p class="text-gray-400">Yes! We offer a free initial consultation to discuss your project requirements and provide recommendations.</p>
            </div>

            <!-- FAQ 3 -->
            <div class="glass-effect p-6 rounded-xl animate-on-scroll">
                <h3 class="text-lg font-semibold mb-2">What information should I include in my message?</h3>
                <p class="text-gray-400">Please include details about your project goals, timeline, budget range, and any specific requirements you may have.</p>
            </div>

            <!-- FAQ 4 -->
            <div class="glass-effect p-6 rounded-xl animate-on-scroll">
                <h3 class="text-lg font-semibold mb-2">Do you work with clients internationally?</h3>
                <p class="text-gray-400">Absolutely! We work with clients from over 50 countries and have experience with remote collaboration.</p>
            </div>
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

<script>
// Email obfuscation function
function revealEmail(element) {
    const email = element.getAttribute('data-email');
    const domain = element.getAttribute('data-domain');
    const fullEmail = email + '@' + domain;

    // Update the link
    element.href = 'mailto:' + fullEmail;
    element.textContent = fullEmail;
    element.onclick = null; // Remove the onclick after first click

    // Automatically open email client
    window.location.href = element.href;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');

    // Honeypot timestamp - additional spam protection
    const formLoadTime = Date.now();

    // Real-time validation
    const fields = {
        name: document.getElementById('name'),
        email: document.getElementById('email'),
        subject: document.getElementById('subject'),
        message: document.getElementById('message'),
        privacy: document.getElementById('privacy')
    };

    // Add blur event listeners for real-time validation
    Object.keys(fields).forEach(key => {
        if (fields[key]) {
            fields[key].addEventListener('blur', () => validateField(key));
        }
    });

    function validateField(fieldName) {
        const field = fields[fieldName];
        const errorElement = document.getElementById(fieldName + 'Error');
        let isValid = true;

        switch(fieldName) {
            case 'name':
                isValid = field.value.trim().length >= 2;
                break;
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailRegex.test(field.value);
                break;
            case 'subject':
                isValid = field.value !== '';
                break;
            case 'message':
                isValid = field.value.trim().length >= 10;
                break;
            case 'privacy':
                isValid = field.checked;
                break;
        }

        if (errorElement) {
            if (isValid) {
                errorElement.classList.add('hidden');
                field.classList.remove('border-red-500');
                field.classList.add('border-gray-700');
            } else {
                errorElement.classList.remove('hidden');
                field.classList.remove('border-gray-700');
                field.classList.add('border-red-500');
            }
        }

        return isValid;
    }

    function validateForm() {
        let isValid = true;
        Object.keys(fields).forEach(key => {
            if (!validateField(key)) {
                isValid = false;
            }
        });
        return isValid;
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Hide previous messages
        successMessage.classList.add('hidden');
        errorMessage.classList.add('hidden');

        // Validate form
        if (!validateForm()) {
            errorText.textContent = 'Please fix the errors above and try again.';
            errorMessage.classList.remove('hidden');
            return;
        }

        // Honeypot check - if website field is filled, it's likely spam
        const honeypot = document.getElementById('website').value;
        if (honeypot !== '') {
            console.log('Spam detected - honeypot filled');
            // Silently fail for spam bots
            form.reset();
            successMessage.classList.remove('hidden');
            return;
        }

        // Time-based check - if form submitted too quickly (< 3 seconds), likely spam
        const timeTaken = Date.now() - formLoadTime;
        if (timeTaken < 3000) {
            console.log('Spam detected - form submitted too quickly');
            errorText.textContent = 'Please take your time filling out the form.';
            errorMessage.classList.remove('hidden');
            return;
        }

        // Disable button and show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Sending...';
        spinner.classList.remove('hidden');

        // Get form data
        const formData = {
            name: fields.name.value.trim(),
            email: fields.email.value.trim(),
            company: document.getElementById('company').value.trim(),
            subject: fields.subject.value,
            message: fields.message.value.trim(),
            timestamp: new Date().toISOString()
        };

        try {
            // Send form data to Laravel backend
            const response = await fetch('{{ route("contact.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Show success message
                successMessage.querySelector('span').textContent = result.message;
                successMessage.classList.remove('hidden');
                form.reset();

                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                throw new Error(result.message || 'Submission failed');
            }

        } catch (error) {
            console.error('Error:', error);
            errorText.textContent = error.message || 'Something went wrong. Please try again or use the email link above to contact us directly.';
            errorMessage.classList.remove('hidden');
        } finally {
            // Re-enable button
            submitBtn.disabled = false;
            btnText.textContent = 'Send Message';
            spinner.classList.add('hidden');
        }
    });
});
</script>
