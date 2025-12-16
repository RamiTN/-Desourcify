<x-guest-layout>
<nav class="p-6 flex justify-between">
    @if (Route::has('login'))
        @auth
            <a href="{{ url('/dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
            @if (Route::has('register'))
                <button type="button" id="signup-btn" class="text-blue-600 hover:underline">
                    Sign Up
                </button>
            @endif
        @endauth
    @endif
</nav>

<!-- Modal -->
<div id="terms-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded shadow-lg w-96 max-w-full mx-4">
        <h2 class="text-xl font-semibold mb-4">Terms and Conditions</h2>
        <p class="mb-4 text-gray-700 text-sm">
            You must accept our Terms and Conditions to create an account.
        </p>
        <label class="flex items-center mb-4">
            <input type="checkbox" id="terms-checkbox" class="mr-2">
            <span> I accept the <a href="/terms" class="text-blue-500 underline" target="_blank">Terms and Conditions</a></span>
        </label>
        <div class="flex justify-end gap-2">
            <button type="button" id="cancel-btn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                Cancel
            </button>
            <button type="button" id="accept-signup-btn" class="px-4 py-2 bg-blue-600 rounded hover:bg-blue-700">
                Continue
            </button>
        </div>
    </div>
</div>

<main class="text-center py-20">
    <h1 class="text-4xl font-bold mb-4">
        Welcome to
    </h1>
    <a href="{{ route('about') }}" class="inline-block">
        <div class="flex justify-center py-5">
            <x-application-logo class="w-[200px] h-[200px] hover:opacity-80 transition-opacity" />
        </div>
    </a>
    <p class="text-gray-700 text-lg">
        Download HD & 4K images and videos with your subscription plan.
    </p>
</main>

<hr class="my-10 border-gray-300">

<footer class="text-center py-4 bg-gray-100 mt-8">
    <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
    <p class="text-gray-600">Made by <a class="text-blue-500 hover:underline" href="https://rami.page.gd/">Rami Abbassi</a></p>
</footer>

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

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
            termsCheckbox.checked = false;
        }
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            termsCheckbox.checked = false;
        }
    });
</script>
</x-guest-layout>