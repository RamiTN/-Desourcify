<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Toastify CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-gray-700">
                    You have <strong id="credits-display">{{ auth()->user()->credits }}</strong> credits left.
                </p>
<div class="flex justify-between">
                <p class="text-sm text-gray-500 mt-1">Each download costs 1 credit</p>
                <p><a class="text-blue-500 underline" href="{{ route('video') }}" target="_blank">need videos? browse from here -></a></p>
                </div>
                <div class="mt-6" id="media-container">
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

    <!-- Download Modal -->
    <div id="download-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Download Image</h3>
            <div id="modal-image-container" class="mb-4">
                <img id="modal-image" src="" alt="Preview" class="w-full h-64 object-cover rounded-lg">
            </div>
            <p class="text-gray-700 mb-2">📷 Photographer: <span id="modal-photographer" class="font-semibold"></span></p>
            <p class="text-gray-600 mb-4">This will cost <strong>1 credit</strong></p>
            <div class="flex gap-3">
                <button onclick="closeDownloadModal()" class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition-colors">
                    Cancel
                </button>
                <button onclick="downloadImage()" id="confirm-download-btn" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    Download
                </button>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 bg-gray-100 mt-8">
        <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-600">Made by<a class="text-blue-500" href="https://rami.page.gd/"> Rami Abbassi</a></p>
    </footer>

<script>
const container = document.getElementById('media-container');
const creditsDisplay = document.getElementById('credits-display');
const downloadModal = document.getElementById('download-modal');
let selectedImage = null;
let isDownloading = false;

// Toast helper
function showToast(text, type = 'success') {
    Toastify({
        text,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor:
            type === 'success' ? "#16a34a" :
            type === 'error' ? "#dc2626" :
            "#2563eb"
    }).showToast();
}

// Open modal
function openDownloadModal(photo, source) {
    selectedImage = { photo, source };
    const modalImage = document.getElementById('modal-image');
    const modalPhotographer = document.getElementById('modal-photographer');

    modalImage.src = source === 'pexels' ? photo.src.large : photo.largeImageURL;
    modalPhotographer.textContent = source === 'pexels' ? photo.photographer : photo.user;

    downloadModal.classList.remove('hidden');
    downloadModal.classList.add('flex');
}

// Close modal
function closeDownloadModal() {
    downloadModal.classList.add('hidden');
    downloadModal.classList.remove('flex');
    selectedImage = null;
}

// Download image
async function downloadImage() {
    if (!selectedImage || isDownloading) return;
    isDownloading = true;

    const credits = parseInt(creditsDisplay.textContent);
    if (credits < 1) {
        showToast('Insufficient credits. Please purchase more.', 'error');
        closeDownloadModal();
        isDownloading = false;
        return;
    }

    const photo = selectedImage.photo;
    const source = selectedImage.source;
    const imageUrl = source === 'pexels' ? photo.src.original : photo.largeImageURL;
    const photographer = source === 'pexels' ? photo.photographer : photo.user;

    try {
        const response = await fetch('/api/download-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                image_url: imageUrl,
                photographer,
                photo_id: photo.id.toString(),
                source
            })
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Download failed');

        creditsDisplay.textContent = data.remaining_credits;

        const link = document.createElement('a');
        link.href = imageUrl;
        link.download = `${photographer}-${photo.id}.jpg`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showToast('Download successful 🎉', 'success');
        closeDownloadModal();
    } catch (err) {
        console.error(err);
        showToast(err.message, 'error');
    } finally {
        isDownloading = false;
    }
}

// Create photo card
function createPhotoCard(photo, source) {
    const card = document.createElement('div');
    card.className = 'rounded-lg overflow-hidden shadow-md bg-white hover:shadow-xl transition-shadow';

    const img = document.createElement('img');
    img.src = source === 'pexels' ? photo.src.medium : photo.webformatURL;
    img.alt = 'Photo';
    img.className = 'w-full h-64 object-cover cursor-pointer hover:opacity-90 transition-opacity';
    img.loading = 'lazy';
    img.addEventListener('click', () => openDownloadModal(photo, source));

    const contentDiv = document.createElement('div');
    contentDiv.className = 'p-4';
    
    const photographerP = document.createElement('p');
    photographerP.className = 'text-gray-700 text-sm';
    
    const photographerLink = document.createElement('a');
    photographerLink.href = '{{ route("templates") }}';
    photographerLink.className = 'hover:text-indigo-600 transition-colors';
    photographerLink.textContent = source === 'pexels' ? photo.photographer : photo.user;
    photographerLink.title = 'View templates';
    
    photographerP.innerHTML = '📷 ';
    photographerP.appendChild(photographerLink);

    contentDiv.appendChild(photographerP);
    card.appendChild(img);
    card.appendChild(contentDiv);

    return card;
}

// Load images from Pexels & Pixabay
async function loadCategoryImages(category) {
    const images = [];
    try {
        const res = await fetch(`/pexels/${encodeURIComponent(category)}`);
        if (res.ok) {
            const data = await res.json();
            if (data.photos) images.push(...data.photos.slice(0, 2).map(photo => ({ photo, source: 'pexels' })));
        }
    } catch (err) { console.error(err); }
    
    try {
        const res = await fetch(`/pixabay/${encodeURIComponent(category)}`);
        if (res.ok) {
            const data = await res.json();
            if (data.hits) images.push(...data.hits.slice(0, 2).map(photo => ({ photo, source: 'pixabay' })));
        }
    } catch (err) { console.error(err); }

    return images.sort(() => Math.random() - 0.5).slice(0, 4);
}

// Load all categories
async function loadAllCategories() {
    const categories = ['nature','technology','people','animals','food','travel','sports','fashion','business','music'];
    const mainContainer = document.createElement('div');

    for (const category of categories) {
        const images = await loadCategoryImages(category);
        if (!images.length) continue;

        const header = document.createElement('div');
        header.className = 'flex items-center justify-between mt-8 mb-4';

        const title = document.createElement('h2');
        title.className = 'text-2xl font-bold capitalize text-gray-800';
        title.textContent = category;

        const link = document.createElement('a');
        link.href = '{{ route("templates") }}';
        link.className = 'text-blue-400 hover:text-blue-700 font-semibold underline';
        link.textContent = 'View Templates →';

        header.appendChild(title);
        header.appendChild(link);
        mainContainer.appendChild(header);

        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6';

        images.forEach(({ photo, source }) => grid.appendChild(createPhotoCard(photo, source)));
        mainContainer.appendChild(grid);
    }

    return mainContainer;
}

// Initialize gallery
async function initializeGallery() {
    container.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <svg class="animate-spin h-8 w-8 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-2 text-gray-600">Loading images...</span>
        </div>
    `;

    const allCategories = await loadAllCategories();
    container.innerHTML = '';
    container.appendChild(allCategories);
}

// Close modal on outside click
downloadModal.addEventListener('click', e => { if(e.target === downloadModal) closeDownloadModal(); });

// Start gallery
initializeGallery();
</script>
</x-app-layout>
