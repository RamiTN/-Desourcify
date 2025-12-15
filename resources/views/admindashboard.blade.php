{{-- resources/views/admin/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold">Welcome back, Admin {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-gray-700">
                    You have <strong>{{ auth()->user()->credits }}</strong> credits.
                </p>

                {{-- Admin Options --}}
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Manage Users --}}
                    <div class="p-4 bg-gray-100 rounded shadow hover:bg-gray-200 cursor-pointer">
                        <h3 class="font-semibold text-lg">Manage Users</h3>
                        <p class="text-gray-600 text-sm">View, edit, or delete users.</p>
                        <a href="{{ route('admin.users') }}" class="text-blue-600 hover:underline mt-2 inline-block">Go</a>
                    </div>

                    {{-- View Stats --}}
                    <div class="p-4 bg-gray-100 rounded shadow hover:bg-gray-200 cursor-pointer">
                        <h3 class="font-semibold text-lg">Site Statistics</h3>
                        <p class="text-gray-600 text-sm">Check overall usage and activity.</p>
                        <a href="{{ route('admin.stats') }}" class="text-blue-600 hover:underline mt-2 inline-block">Go</a>
                    </div>

                    {{-- Manage Credits --}}
                    <div class="p-4 bg-gray-100 rounded shadow hover:bg-gray-200 cursor-pointer">
                        <h3 class="font-semibold text-lg">Manage Credits</h3>
                        <p class="text-gray-600 text-sm">Add or remove credits for users.</p>
                        <a href="{{ route('admin.credits') }}" class="text-blue-600 hover:underline mt-2 inline-block">Go</a>
                    </div>

                    {{-- Additional Options --}}
                    <div class="p-4 bg-gray-100 rounded shadow hover:bg-gray-200 cursor-pointer">
                        <h3 class="font-semibold text-lg">Preview</h3>
                        <p class="text-gray-600 text-sm">View as avrage user.</p>
                        <a href="{{ route('admin.pexels') }}" class="text-blue-600 hover:underline mt-2 inline-block">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<hr>
    <footer class="text-center py-4 bg-gray-100 mt-8">
        <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-600">Made by<a class="text-blue-500" href="https://rami.page.gd/" target="_blank"> Rami Abbassi</a></p>
    </footer>
</x-app-layout>
