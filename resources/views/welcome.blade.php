<x-guest-layout>
    <nav class="p-6 flex justify-between">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign Up</a>
                @endif
            @endauth
        @endif
    </nav>

    <main class="text-center py-20">
<h1 style="font-size: 2em; font-weight: bold; margin: 0.67em 0;text-align: center;">
    Welcome to
</h1>
<button><a href="{{ route('about') }}">
        <div class="flex justify-center py-5">
            <x-application-logo class="w-[200px] h-[200px]" />
        </div>
</a></button>
        <p class="text-gray-700 text-lg">
            Download HD & 4K images and videos with your subscription plan.
        </p>
    </main>

    <hr class="my-10 border-gray-300">

    <footer class="text-center py-4 bg-gray-100 mt-8">
        <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-600">Made by<a class="text-blue-500" href="https://rami.page.gd/"> Rami Abbassi</a></p>
    </footer>

</x-guest-layout>
