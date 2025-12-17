<x-guest-layout>
<!-- Gradient Background -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-green-50">
    <!-- Navigation -->
    <nav class="p-6 flex justify-between items-center backdrop-blur-sm bg-white/30">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 via-purple-400 to-green-400 rounded-lg"></div>
            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-green-600 bg-clip-text text-transparent">Desourcify</span>
        </div>
        @if (Route::has('login'))
            <div class="flex gap-4 items-center">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white font-medium hover:shadow-lg transition-all duration-300 hover:scale-105">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 font-medium hover:text-blue-600 transition-colors">Login</a>
                    @if (Route::has('register'))
                        <button type="button" id="signup-btn" class="px-6 py-2 rounded-full bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 text-white font-medium hover:shadow-xl transition-all duration-300 hover:scale-105">
                            Sign Up
                        </button>
                    @endif
                @endauth
            </div>
        @endif
    </nav>

    <!-- Modal -->
    <div id="terms-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-all duration-300">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-96 max-w-full mx-4 transform transition-all duration-300 scale-95" id="modal-content">
            <div class="flex items-center justify-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 via-purple-400 to-green-400 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold mb-2 text-center bg-gradient-to-r from-blue-600 via-purple-600 to-green-600 bg-clip-text text-transparent">Terms and Conditions</h2>
            <p class="mb-6 text-gray-600 text-sm text-center">
                Please review and accept our Terms and Conditions to create your account.
            </p>
            <label class="flex items-start mb-6 cursor-pointer group">
                <input type="checkbox" id="terms-checkbox" class="mr-3 mt-1 w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-gray-700 group-hover:text-gray-900"> I have read and accept the <a href="/terms" class="text-blue-600 underline hover:text-purple-600 transition-colors" target="_blank">Terms and Conditions</a></span>
            </label>
            <div class="flex gap-3">
                <button type="button" id="cancel-btn" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-300 font-medium">
                    Cancel
                </button>
                <button type="button" id="accept-signup-btn" class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 text-white rounded-xl hover:shadow-xl transition-all duration-300 font-medium hover:scale-105">
                    Continue
                </button>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <main class="text-center py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-blue-600 via-purple-600 to-green-600 bg-clip-text text-transparent animate-gradient">
                Welcome to Desourcify
            </h1>
            <a href="{{ route('about') }}" class="inline-block group">
                <div class="flex justify-center py-8 transform transition-all duration-500 hover:scale-110">
                    <x-application-logo class="w-[220px] h-[220px] drop-shadow-2xl group-hover:drop-shadow-3xl transition-all duration-500" />
                </div>
            </a>
            <p class="text-gray-700 text-xl md:text-2xl mb-8 leading-relaxed max-w-2xl mx-auto">
                Download <span class="font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">HD & 4K</span> images and videos with your subscription plan.
            </p>
            
            <!-- Feature Cards -->
            <div class="grid md:grid-cols-3 gap-6 mt-12 max-w-5xl mx-auto">
                <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-blue-100">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800">High Quality</h3>
                    <p class="text-gray-600 text-sm">Premium HD and 4K content</p>
                </div>
                
                <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-purple-100">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800">Fast Downloads</h3>
                    <p class="text-gray-600 text-sm">Lightning-fast delivery</p>
                </div>
                
                <div class="bg-white/60 backdrop-blur-sm p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-green-100">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-800">Secure Access</h3>
                    <p class="text-gray-600 text-sm">Safe and reliable platform</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center py-8 bg-white/40 backdrop-blur-sm mt-16 border-t border-gray-200">
        <p class="text-gray-600 mb-2">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-600">Made with <span class="text-red-500">♥</span> by <a class="text-blue-600 hover:text-purple-600 transition-colors font-medium" href="https://rami.page.gd/">Rami Abbassi</a></p>
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
    
    #terms-modal:not(.hidden) #modal-content {
        animation: modalSlideIn 0.3s ease-out forwards;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>

<script>
    const modal = document.getElementById('terms-modal');
    const signupBtn = document.getElementById('signup-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const acceptSignupBtn = document.getElementById('accept-signup-btn');
    const termsCheckbox = document.getElementById('terms-checkbox');

    if (signupBtn) {
        signupBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });
    }

    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        termsCheckbox.checked = false;
    });

    acceptSignupBtn.addEventListener('click', () => {
        if (termsCheckbox.checked) {
            window.location.href = "{{ route('register') }}";
        } else {
            alert('You must accept the Terms and Conditions to create an account.');
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            termsCheckbox.checked = false;
        }
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            termsCheckbox.checked = false;
        }
    });
</script>
</x-guest-layout>