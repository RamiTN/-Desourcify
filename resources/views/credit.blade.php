<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes wave {
            0%, 100% { transform: translateX(0) translateY(0); }
            25% { transform: translateX(5px) translateY(-5px); }
            75% { transform: translateX(-5px) translateY(5px); }
        }
        .wave-animation {
            animation: wave 3s ease-in-out infinite;
        }
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
    </style>

    <!-- Header Section -->
    <div class="bg-gradient-to-br from-cyan-50 via-emerald-50 to-orange-50 py-16 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-200/30 rounded-full -mr-48 -mt-48 wave-animation"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-orange-200/30 rounded-full -ml-40 -mb-40 wave-animation" style="animation-delay: 1s;"></div>
        
        <div class="max-w-7xl mx-auto px-6 pt-16 pb-8 text-center relative z-10 animate-fadeInUp">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-4">
                Choose Your <span class="bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 bg-clip-text text-transparent">Perfect Plan</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Get started with credits that power your creative projects. Simple pricing, powerful features.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Basic Offer -->
            <div class="flex flex-col justify-between rounded-3xl border-2 border-gray-200 bg-white shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 group">
                <div class="flex flex-col items-center text-center px-6 pt-8 space-y-3">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.8 10A10 10 0 1 1 17 3.3"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9 11 3 3L22 4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Basic Starter</h3>
                    <div class="py-4">
                        <p class="text-5xl font-bold text-gray-900">$20</p>
                        <p class="text-sm text-gray-500 mt-1">one-time purchase</p>
                    </div>
                </div>

                <div class="px-6 py-6 flex-1">
                    <p class="text-sm text-gray-600 mb-6 text-center">
                        Perfect for getting started with essential features and templates.
                    </p>
                    <ul class="space-y-4 text-sm text-gray-700">
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500 font-bold text-lg flex-shrink-0">✓</span>
                            <span><strong>100 credits</strong> added to your account</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500 font-bold text-lg flex-shrink-0">✓</span>
                            <span>Access to all basic templates</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-emerald-500 font-bold text-lg flex-shrink-0">✓</span>
                            <span>Community support</span>
                        </li>
                    </ul>
                </div>
                
                <div class="px-6 pb-6">
                    <button
                       class="block w-full text-center rounded-xl border-2 border-emerald-500 px-6 py-3.5 text-sm font-bold text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all duration-300">
                        Get Started →
                    </button>
                </div>
            </div>

            <!-- Best Offer (HIGHLIGHTED) -->
            <div class="flex flex-col justify-between rounded-3xl border-2 border-transparent bg-white shadow-2xl relative lg:scale-105 lg:z-10">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500 via-emerald-500 to-orange-500 opacity-10 rounded-3xl"></div>
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 z-20">
                    <span class="bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 text-white text-xs font-bold px-6 py-2 rounded-full shadow-lg float-animation whitespace-nowrap">
                        ⭐ RECOMMENDED
                    </span>
                </div>
                
                <div class="flex flex-col items-center text-center px-6 pt-12 space-y-3 relative z-10">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-400 via-emerald-400 to-orange-400 flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 21h14"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l3 6 6-3-3 12H6L3 6l6 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Best Value</h3>
                    <div class="py-4">
                        <p class="text-5xl font-bold bg-gradient-to-r from-cyan-600 via-emerald-600 to-orange-600 bg-clip-text text-transparent">$100</p>
                        <p class="text-sm text-gray-500 mt-1">one-time purchase</p>
                        <div class="mt-2 inline-block bg-gradient-to-r from-emerald-100 to-emerald-200 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">
                            Save 17%
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6 flex-1 relative z-10">
                    <p class="text-sm text-gray-600 mb-6 text-center">
                        Most popular choice with maximum credits and priority support.
                    </p>
                    <ul class="space-y-4 text-sm text-gray-700">
                        <li class="flex items-start gap-3">
                            <span class="text-transparent bg-gradient-to-r from-cyan-500 to-emerald-500 bg-clip-text font-bold text-lg flex-shrink-0">✓</span>
                            <span><strong>1,000 credits</strong> added to your account</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-transparent bg-gradient-to-r from-emerald-500 to-orange-500 bg-clip-text font-bold text-lg flex-shrink-0">✓</span>
                            <span>Access to <strong>all premium templates</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-transparent bg-gradient-to-r from-cyan-500 to-orange-500 bg-clip-text font-bold text-lg flex-shrink-0">✓</span>
                            <span><strong>Priority support</strong> (24h response)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-transparent bg-gradient-to-r from-orange-500 to-cyan-500 bg-clip-text font-bold text-lg flex-shrink-0">✓</span>
                            <span>Early access to new features</span>
                        </li>
                    </ul>
                </div>

                <div class="px-6 pb-6 relative z-10">
                    <button class="w-full rounded-xl bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 text-white px-6 py-4 text-sm font-bold hover:shadow-2xl transition-all duration-300 shadow-lg transform hover:scale-105 relative overflow-hidden group">
                        <span class="relative z-10">Start Now →</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-500 via-emerald-500 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </div>
            </div>

            <!-- Medium Offer -->
            <div class="flex flex-col justify-between rounded-3xl border-2 border-gray-200 bg-white shadow-lg transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 group">
                <div class="flex flex-col items-center text-center px-6 pt-8 space-y-3">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-100 to-cyan-200 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3 6 6 .9-4.5 4.3L17.8 21 12 17.7 6.2 21l1.3-7.8L3 8.9 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Medium Pro</h3>
                    <div class="py-4">
                        <p class="text-5xl font-bold text-gray-900">$40</p>
                        <p class="text-sm text-gray-500 mt-1">one-time purchase</p>
                    </div>
                </div>

                <div class="px-6 py-6 flex-1">
                    <p class="text-sm text-gray-600 mb-6 text-center">
                        Great for regular users who need more credits and full access.
                    </p>
                    <ul class="space-y-4 text-sm text-gray-700">
                        <li class="flex items-start gap-3">
                            <span class="text-cyan-500 font-bold text-lg flex-shrink-0">✓</span>
                            <span><strong>300 credits</strong> added to your account</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-cyan-500 font-bold text-lg flex-shrink-0">✓</span>
                            <span>Full access to all features</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-cyan-500 font-bold text-lg flex-shrink-0">✓</span>
                            <span>Standard email support</span>
                        </li>
                    </ul>
                </div>

                <div class="px-6 pb-6">
                    <button class="w-full rounded-xl border-2 border-cyan-500 px-6 py-3.5 text-sm font-bold text-cyan-600 hover:bg-cyan-500 hover:text-white transition-all duration-300">
                        Start Now →
                    </button>
                </div>
            </div>

        </div>

        <!-- Trust Indicators -->
        <div class="mt-16 text-center">
            <p class="text-gray-600 text-sm mb-6 font-medium">Trusted by thousands of creators worldwide</p>
            <div class="flex justify-center gap-8 flex-wrap">
                <div class="flex items-center gap-2 bg-white rounded-xl px-4 py-3 shadow-md">
                    <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-gray-700 font-medium">Instant credit delivery</span>
                </div>
                <div class="flex items-center gap-2 bg-white rounded-xl px-4 py-3 shadow-md">
                    <svg class="w-5 h-5 text-cyan-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-gray-700 font-medium">Secure payment</span>
                </div>
                <div class="flex items-center gap-2 bg-white rounded-xl px-4 py-3 shadow-md">
                    <svg class="w-5 h-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm text-gray-700 font-medium">No hidden fees</span>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-20 bg-white rounded-3xl p-8 md:p-12 shadow-xl border-2 border-gray-100">
            <h2 class="text-3xl font-bold text-center mb-8">
                Frequently Asked <span class="bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 bg-clip-text text-transparent">Questions</span>
            </h2>
            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <div class="space-y-2">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 bg-gradient-to-br from-cyan-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-xs">?</span>
                        How do credits work?
                    </h3>
                    <p class="text-sm text-gray-600 pl-8">Credits are used to access premium templates and features. Each action costs a specific number of credits.</p>
                </div>
                <div class="space-y-2">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-orange-500 rounded-full flex items-center justify-center text-white text-xs">?</span>
                        Do credits expire?
                    </h3>
                    <p class="text-sm text-gray-600 pl-8">No! Your credits never expire. Use them whenever you need them.</p>
                </div>
                <div class="space-y-2">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 bg-gradient-to-br from-orange-500 to-cyan-500 rounded-full flex items-center justify-center text-white text-xs">?</span>
                        Can I upgrade later?
                    </h3>
                    <p class="text-sm text-gray-600 pl-8">Absolutely! You can purchase more credits anytime to upgrade your account.</p>
                </div>
                <div class="space-y-2">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-6 h-6 bg-gradient-to-br from-cyan-500 to-orange-500 rounded-full flex items-center justify-center text-white text-xs">?</span>
                        What payment methods?
                    </h3>
                    <p class="text-sm text-gray-600 pl-8">We accept all major credit cards, PayPal, and other secure payment methods.</p>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <footer class="text-center py-8 bg-gradient-to-br from-gray-50 to-gray-100 mt-8">
        <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-600 mt-2">Made by <a class="bg-gradient-to-r from-cyan-600 to-emerald-600 bg-clip-text text-transparent font-bold hover:from-emerald-600 hover:to-orange-600 transition-all" href="https://rami.page.gd/">Rami Abbassi</a></p>
    </footer>
</x-app-layout>