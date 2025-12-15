<x-app-layout>
        <script src="https://cdn.tailwindcss.com"></script>
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <div class="flex flex-col justify-between rounded-xl border-2 border-gray-200 bg-white shadow-sm transition-all hover:shadow-md">
                <div class="flex flex-col items-center text-center px-6 pt-6 space-y-2">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.8 10A10 10 0 1 1 17 3.3"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 11 3 3L22 4"/>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-900">Basic Desourcify</h3>
                    <p class="text-3xl font-bold text-gray-900">5$</p>
                    <p class="text-sm text-gray-500">per month</p>
                </div>

                <div class="mt-25 px-6 py-6 flex-1">
                    <p class="text-sm text-gray-600 mb-4">
                        View all templates and access basic features.
                    </p>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex items-center gap-2">
                            <span class="text-green-500 font-bold">✓</span>
                            <span>10 credit per month</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-500 font-bold">✓</span>
                            <span>Basic profile</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-green-500 font-bold">✓</span>
                            <span>Only photos access</span>
                        </li>
                    </ul>
                </div>

                <div class="px-6 pb-6">
                    <a href="/signup"
                       class="block w-full text-center rounded-lg border-2 border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors">
                        Start Basic
                    </a>
                </div>
            </div>

            <!-- STUDENT PLUS (HIGHLIGHTED) -->
            <div class="flex flex-col justify-between rounded-xl border-2 border-blue-500 bg-white shadow-xl relative lg:scale-105">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="bg-blue-500 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg">MOST POPULAR</span>
                </div>
                
                <div class="flex flex-col items-center text-center px-6 pt-8 space-y-2">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2l3 6 6 .9-4.5 4.3L17.8 21 12 17.7 6.2 21l1.3-7.8L3 8.9 9 8z"/>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-900">Desourcify PRO</h3>
                    <p class="text-3xl font-bold text-gray-900">10$</p>
                    <p class="text-sm text-gray-500">per month</p>
                </div>

                <div class="px-6 py-6 flex-1">
                    <p class="text-sm text-gray-600 mb-4">
                     Access to all images and advanced features.
                    </p>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex items-center gap-2">
                            <span class="text-blue-500 font-bold">✓</span>
                            <span>All Free features</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-blue-500 font-bold">✓</span>
                            <span>100 credit per month</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-blue-500 font-bold">✓</span>
                            <span>Few video access</span>
                        </li>
                    </ul>
                </div>

                <div class="px-6 pb-6">
                    <button class="w-full rounded-lg bg-blue-600 text-white px-4 py-3 text-sm font-semibold hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg">
                        Start Pro
                    </button>
                </div>
            </div>

            <!-- STUDENT PREMIUM -->
            <div class="flex flex-col justify-between rounded-xl border-2 border-gray-200 bg-white shadow-sm transition-all hover:shadow-md">
                <div class="flex flex-col items-center text-center px-6 pt-6 space-y-2">
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 21h14"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l3 6 6-3-3 12H6L3 6l6 3z"/>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-900">Desourcify Premium</h3>
                    <p class="text-3xl font-bold text-gray-900">100 $</p>
                    <p class="text-sm text-gray-500">per month</p>
                </div>

                <div class="px-6 py-6 flex-1">
                    <p class="text-sm text-gray-600 mb-4">
                        Full access, images and videos, and priority support.
                    </p>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex items-center gap-2">
                            <span class="text-yellow-500 font-bold">✓</span>
                            <span>Ilimited templates access</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-yellow-500 font-bold">✓</span>
                            <span>Ilimited credits</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-yellow-500 font-bold">✓</span>
                            <span>Priority support</span>
                        </li>
                    </ul>
                </div>

                <div class="px-6 pb-6">
                    <button class="w-full rounded-lg border-2 border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-colors">
                        Start Premium
                    </button>
                </div>
            </div>

        </div>
    </div>
        <footer class="text-center py-4 bg-gray-100 mt-8">
        <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-600">Made by<a class="text-blue-500" href="https://rami.page.gd/"> Rami Abbassi</a></p>
    </footer>
</x-app-layout>