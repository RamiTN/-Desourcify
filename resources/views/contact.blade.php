<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes wave {
            0%, 100% { transform: translateX(0) translateY(0); }
            25% { transform: translateX(5px) translateY(-5px); }
            75% { transform: translateX(-5px) translateY(5px); }
        }
        .wave-animation {
            animation: wave 3s ease-in-out infinite;
        }
    </style>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-cyan-50 via-emerald-50 to-orange-50 py-16">
        <div class="max-w-4xl mx-auto px-6 text-center animate-fadeInUp">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-4">
                Get in <span class="bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 bg-clip-text text-transparent">Touch</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Have a question or feedback? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div class="max-w-4xl mx-auto px-6 py-16">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
            <!-- Decorative Header -->
            <div class="bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32 wave-animation"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24 wave-animation" style="animation-delay: 1s;"></div>
                <div class="relative z-10 flex items-center justify-center gap-3 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h2 class="text-3xl font-bold">Send Us a Message</h2>
                </div>
                <p class="relative z-10 text-white/90 text-center">Fill out the form below and we'll get back to you within 24 hours</p>
            </div>

            <!-- Form -->
            <form id="contact-form" 
                  method="POST" 
                  action="https://formsubmit.co/ramiabbassi53@gmail.com" 
                  class="p-8 md:p-12 space-y-6">
                
                <!-- Name and Email -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">
                            Your Name <span class="text-orange-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" name="name" id="name" required 
                                   placeholder="John Doe"
                                   class="pl-10 block w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Your Email <span class="text-orange-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" required 
                                   placeholder="john@example.com"
                                   class="pl-10 block w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 transition-all outline-none">
                        </div>
                    </div>
                </div>

                <!-- Subject -->
                <div class="space-y-2">
                    <label for="subject" class="block text-sm font-semibold text-gray-700">
                        Subject <span class="text-orange-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <input type="text" name="subject" id="subject" required 
                               placeholder="How can we help you?"
                               class="pl-10 block w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all outline-none">
                    </div>
                </div>

                <!-- Message -->
                <div class="space-y-2">
                    <label for="message" class="block text-sm font-semibold text-gray-700">
                        Message <span class="text-orange-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <textarea name="message" id="message" rows="6" required 
                                  placeholder="Tell us what's on your mind..."
                                  class="pl-10 pt-3 block w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all outline-none resize-none"></textarea>
                    </div>
                </div>

                <!-- Anti-spam hidden field -->
                <input type="hidden" name="_captcha" value="false">
                <input type="hidden" name="_subject" value="New contact form submission!">

                <!-- Submit Button -->
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-center pt-4">
                    <button type="submit" 
                            class="group relative w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 text-white font-bold rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 overflow-hidden">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send Message
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-500 via-emerald-500 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                    <button type="reset" 
                            class="w-full sm:w-auto px-8 py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-300">
                        Clear Form
                    </button>
                </div>

                <!-- Privacy Note -->
                <p class="text-center text-sm text-gray-500 pt-4">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    Your information is secure and will never be shared with third parties.
                </p>
            </form>
        </div>

        <!-- Contact Info Cards -->
        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 text-center border-2 border-gray-100">
                <div class="w-12 h-12 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Email Us</h3>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 text-center border-2 border-gray-100">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Response garnated</h3>
            </div>
            
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 text-center border-2 border-gray-100">
                <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-gray-600 text-sm">Tunis, Tunisia</h3>
            </div>
        </div>
    </div>

    <script>
        // Form submission feedback
        document.getElementById('contact-form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<span class="relative z-10 flex items-center justify-center gap-2"><svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...</span>';
            button.disabled = true;
        });
    </script>
</x-app-layout>