{{-- resources/views/about.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-green-50">
    <script src="https://cdn.tailwindcss.com"></script>

    <x-slot name="header">
        <h2 class="text-gray-900 font-semibold text-xl leading-tight">
            {{ __('About Desourcify') }}
        </h2>
    </x-slot>

    <!-- Hero Section -->
    <div class="relative overflow-hidden py-16 px-4">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-400/10 via-purple-400/10 to-green-400/10 blur-3xl"></div>
        <div class="relative max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center justify-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 via-purple-400 to-green-400 rounded-3xl flex items-center justify-center shadow-2xl transform rotate-3 hover:rotate-6 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-5xl sm:text-6xl font-bold mb-4 bg-gradient-to-r from-blue-600 via-purple-600 to-green-600 bg-clip-text text-transparent animate-gradient">
                About Desourcify
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Your trusted partner for legal, high-quality media content
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <!-- Content Cards -->
        <div class="space-y-6">
            <!-- Card 1 -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-blue-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Legal & Safe Content</h3>
                        <p class="text-gray-700 text-lg leading-relaxed">
                            At Desourcify, we are committed to providing high-quality HD and 4K images and videos while ensuring that everything we offer is <span class="font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">completely legal and safe to use</span>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-purple-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Trusted Sources</h3>
                        <p class="text-gray-700 text-lg leading-relaxed">
                            Our platform works with trusted sources like Pexels and other licensed content providers to make sure that every download is compliant with copyright and usage policies. We do not host copyrighted content — we provide a legal interface that allows our subscribers to access and download media through official APIs and agreements.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl p-8 border border-green-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">AI-Powered Innovation</h3>
                        <p class="text-gray-700 text-lg leading-relaxed">
                            The AI features on Desourcify, including smart search, recommendations, and content organization, are part of our proprietary system. We sell access to this AI structure, giving users a seamless, intelligent experience, without infringing on any rights.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 rounded-3xl shadow-2xl p-8 text-white hover:shadow-3xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold mb-3">Our Promise</h3>
                        <p class="text-lg leading-relaxed text-white/95">
                            By subscribing, our users can enjoy a hassle-free, fully legal way to download images and videos for personal or professional use, while supporting a platform that values <span class="font-bold">ethics, legality, and technology innovation</span>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-3 gap-6 mt-12">
            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl text-center border border-blue-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-lg text-gray-900 mb-2">100% Legal</h4>
                <p class="text-gray-600 text-sm">Fully compliant with copyright laws</p>
            </div>

            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl text-center border border-purple-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-lg text-gray-900 mb-2">Premium Quality</h4>
                <p class="text-gray-600 text-sm">HD & 4K content from trusted sources</p>
            </div>

            <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl text-center border border-green-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h4 class="font-bold text-lg text-gray-900 mb-2">AI-Powered</h4>
                <p class="text-gray-600 text-sm">Smart search and recommendations</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-8 bg-white/40 backdrop-blur-sm border-t border-gray-200">
        <p class="text-gray-700 mb-2">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-700">
            Made with <span class="text-red-500">♥</span> by <a class="text-blue-600 hover:text-purple-600 transition-colors font-medium" href="https://rami.page.gd/">Rami Abbassi</a>
        </p>
    </footer>
</div>

<style>
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    .animate-gradient {
        background-size: 200% 200%;
        animation: gradient 3s ease infinite;
    }
</style>