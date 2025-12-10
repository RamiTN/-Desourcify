<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Add Tailwind CDN if not already loaded -->
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-gray-700">You have <strong id="credits-display">{{ auth()->user()->credits }}</strong> credits left.</p>
                <p class="text-sm text-gray-500 mt-1">Each download costs 1 credit</p>

                <div class="mt-6" id="pexels-images">
                    <div class="flex items-center justify-center py-8">
                        <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="ml-2 text-gray-600">Loading images...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 bg-gray-100 mt-8">
        <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
    </footer>

<script>
const container = document.getElementById('pexels-images');
const creditsDisplay = document.getElementById('credits-display');
const categories = ['nature', 'technology', 'people'];
let remainingCredits = parseInt("{{ auth()->user()->credits }}");

console.log('Initial credits:', remainingCredits); // Debug

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function downloadImage(url, filename, photoId, photographer) {
    console.log('Download clicked!', {url, filename, photoId}); // Debug
    
    if (remainingCredits <= 0) {
        alert('You do not have enough credits to download this image. Please purchase more credits.');
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('CSRF token not found. Please refresh the page.');
            return;
        }

        const response = await fetch('/api/download-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content
            },
            body: JSON.stringify({
                image_url: url,
                photographer: photographer,
                photo_id: photoId
            })
        });

        const data = await response.json();
        console.log('Server response:', data); // Debug

        if (!response.ok) {
            alert(data.error || 'Failed to process download');
            return;
        }

        const imageResponse = await fetch(url);
        const blob = await imageResponse.blob();
        const blobUrl = window.URL.createObjectURL(blob);
        
        const link = document.createElement('a');
        link.href = blobUrl;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(blobUrl);

        remainingCredits = data.remaining_credits;
        creditsDisplay.textContent = remainingCredits;
        
        document.querySelectorAll('button[data-photo-id]').forEach(btn => {
            updateButtonState(btn);
        });
        
        alert(data.message);

    } catch (error) {
        console.error('Download failed:', error);
        alert('Failed to download image. Please try again.');
    }
}

function updateButtonState(button) {
    if (remainingCredits <= 0) {
        button.disabled = true;
        button.className = 'bg-gray-400 cursor-not-allowed text-white px-4 py-2 rounded text-sm font-medium w-full flex items-center justify-center';
        button.innerHTML = '🔒 No Credits';
    }
}

async function loadImages() {
    console.log('Loading images...'); // Debug
    let html = '';

    for (const category of categories) {
        try {
            const res = await fetch(`/api/pexels/${category}`);
            
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            
            const data = await res.json();
            console.log(`${category} data:`, data); // Debug

            if (!data.photos || data.photos.length === 0) {
                continue;
            }

            html += `<h2 class="text-2xl font-bold mt-8 mb-4 capitalize text-gray-800">${escapeHtml(category)}</h2>`;
            html += '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">';

            data.photos.forEach(photo => {
                const hasCredits = remainingCredits > 0;
                const buttonClass = hasCredits 
                    ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                    : 'bg-gray-400 cursor-not-allowed text-white';

                html += `
                    <div class="rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow bg-white">
                        <div class="relative group">
                            <img src="${escapeHtml(photo.src.medium)}" 
                                 alt="${escapeHtml(photo.alt || 'Image')}" 
                                 class="w-full h-64 object-cover"
                                 loading="lazy">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all"></div>
                        </div>
                        <div class="p-4">
                            <p class="text-gray-700 text-sm mb-3">
                                📷 <span class="font-medium">${escapeHtml(photo.photographer)}</span>
                            </p>
                            <button 
                                onclick="downloadImage('${escapeHtml(photo.src.original)}', 'desourcify-${photo.id}.jpg', '${photo.id}', '${escapeHtml(photo.photographer)}')"
                                data-photo-id="${photo.id}"
                                class="${buttonClass} px-4 py-2 rounded-md text-sm font-semibold w-full transition-colors ${!hasCredits ? 'opacity-50' : ''}"
                                ${!hasCredits ? 'disabled' : ''}>
                                ${hasCredits ? '⬇️ Download (1 credit)' : '🔒 No Credits'}
                            </button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';

        } catch(err) {
            console.error(`Failed to load ${category}:`, err);
            html += `<p class="text-red-500 bg-red-50 p-4 rounded my-4">❌ Failed to load ${escapeHtml(category)} images.</p>`;
        }
    }

    if (!html) {
        html = '<p class="text-gray-500 text-center py-8 text-lg">No images available at this time.</p>';
    }

    container.innerHTML = html;
    console.log('Images loaded!'); // Debug
}

// Start loading
loadImages();
</script>
</x-app-layout>