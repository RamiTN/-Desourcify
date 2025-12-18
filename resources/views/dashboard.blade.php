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

    <style>
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
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
    </style>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-cyan-50 via-emerald-50 to-orange-50 py-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-200/30 rounded-full -mr-48 -mt-48 wave-animation"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-orange-200/30 rounded-full -ml-40 -mb-40 wave-animation" style="animation-delay: 1s;"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 border-2 border-gray-100 animate-fadeInUp">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">
                            Welcome back, <span class="bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>!
                        </h1>
                        <p class="text-lg text-gray-700 mb-2">
                            You have <strong id="credits-display" class="text-2xl bg-gradient-to-r from-emerald-600 to-cyan-600 bg-clip-text text-transparent">{{ auth()->user()->credits }}</strong> <span class="text-gray-600">credits left</span>
                        </p>
                        <p class="text-sm text-gray-500">Each download costs 1 credit</p>
                    </div>
                    
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('video') }}" target="_blank" 
                           class="px-6 py-3 bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 text-center">
                            🎥 Browse Videos
                        </a>
                        <a href="{{ route('templates') }}" 
                           class="px-6 py-3 border-2 border-cyan-500 text-cyan-600 font-bold rounded-xl hover:bg-cyan-500 hover:text-white transition-all duration-300 text-center">
                            📐 View Templates
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div id="media-container">
                <div class="flex items-center justify-center py-12">
                    <div class="flex flex-col items-center gap-3">
                        <svg class="animate-spin h-12 w-12 text-cyan-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-gray-600 font-medium">Loading amazing images...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal -->
    <div id="download-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl p-6 max-w-md w-full animate-fadeInUp border-2 border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-cyan-600 to-emerald-600 bg-clip-text text-transparent">Download Image</h3>
                <button onclick="closeDownloadModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="modal-image-container" class="mb-4">
                <img id="modal-image" src="" alt="Preview" class="w-full h-64 object-cover rounded-2xl border-2 border-gray-100">
            </div>
            <div class="bg-gradient-to-r from-cyan-50 to-emerald-50 rounded-xl p-4 mb-4">
                <p class="text-gray-700 mb-1 flex items-center gap-2">
                    <span class="text-lg">📷</span>
                    <span>Photographer: <span id="modal-photographer" class="font-bold text-gray-900"></span></span>
                </p>
                <p class="text-gray-600 flex items-center gap-2">
                    <span class="text-lg">💎</span>
                    <span>Cost: <strong class="text-emerald-600">1 credit</strong></span>
                </p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeDownloadModal()" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition-all">
                    Cancel
                </button>
                <button onclick="downloadImage()" id="confirm-download-btn" class="flex-1 px-4 py-3 bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-600 hover:to-emerald-600 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                    Download
                </button>
            </div>
        </div>
    </div>

    <footer class="text-center py-8 bg-gradient-to-br from-gray-50 to-gray-100 mt-8 border-t border-gray-200">
        <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-600 mt-2">Made by <a class="bg-gradient-to-r from-cyan-600 to-emerald-600 bg-clip-text text-transparent font-bold hover:from-emerald-600 hover:to-orange-600 transition-all" href="https://rami.page.gd/">Rami Abbassi</a></p>
    </footer>

<script>
const container = document.getElementById('media-container');
const creditsDisplay = document.getElementById('credits-display');
const downloadModal = document.getElementById('download-modal');
let selectedImage = null;
let isDownloading = false;

// Toast helper with brand colors
function showToast(text, type = 'success') {
    Toastify({
        text,
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor:
            type === 'success' ? "linear-gradient(to right, #06B6D4, #10B981)" :
            type === 'error' ? "#dc2626" :
            "linear-gradient(to right, #F97316, #06B6D4)",
        className: "font-semibold"
    }).showToast();
}

// Open modal
function openDownloadModal(photo, source) {
    selectedImage = { photo, source };
    const modalImage = document.getElementById('modal-image');
    const modalPhotographer = document.getElementById('modal-photographer');

    modalImage.src = source === 'pexels' ? photo.src.large : photo.webformatURL;
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

        showToast('Download successful! 🎉', 'success');
        closeDownloadModal();
    } catch (err) {
        console.error(err);
        showToast(err.message, 'error');
    } finally {
        isDownloading = false;
    }
}

// Create photo card with brand colors
function createPhotoCard(photo, source) {
    const card = document.createElement('div');
    card.className = 'rounded-2xl overflow-hidden shadow-lg bg-white hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border-2 border-gray-100 group';

    const imgContainer = document.createElement('div');
    imgContainer.className = 'relative overflow-hidden cursor-pointer';
    imgContainer.addEventListener('click', (e) => {
        console.log('Image clicked!', photo, source);
        e.preventDefault();
        e.stopPropagation();
        openDownloadModal(photo, source);
    });

    const img = document.createElement('img');
    img.src = source === 'pexels' ? photo.src.medium : photo.webformatURL;
    img.alt = 'Photo';
    img.className = 'w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300 pointer-events-none';
    img.loading = 'lazy';

    const overlay = document.createElement('div');
    overlay.className = 'absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4 pointer-events-none';
    overlay.innerHTML = '<span class="text-white font-bold text-sm">Click to download</span>';

    imgContainer.appendChild(img);
    imgContainer.appendChild(overlay);

    const contentDiv = document.createElement('div');
    contentDiv.className = 'p-4 bg-gradient-to-br from-cyan-50/50 to-emerald-50/50';
    
    const photographerP = document.createElement('p');
    photographerP.className = 'text-gray-700 text-sm flex items-center gap-2';
    
    const photographerLink = document.createElement('a');
    photographerLink.href = '{{ route("templates") }}';
    photographerLink.className = 'hover:text-cyan-600 transition-colors font-semibold';
    photographerLink.textContent = source === 'pexels' ? photo.photographer : photo.user;
    photographerLink.title = 'View templates';
    
    photographerP.innerHTML = '<span class="text-lg">📷</span> ';
    photographerP.appendChild(photographerLink);

    contentDiv.appendChild(photographerP);
    card.appendChild(imgContainer);
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

        const categorySection = document.createElement('div');
        categorySection.className = 'mb-12 animate-fadeInUp';

        const header = document.createElement('div');
        header.className = 'flex items-center justify-between mb-6 bg-white rounded-2xl p-6 shadow-md border-2 border-gray-100';

        const titleDiv = document.createElement('div');
        titleDiv.className = 'flex items-center gap-3';

        const icon = document.createElement('div');
        icon.className = 'w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-emerald-500 flex items-center justify-center text-white font-bold text-lg';
        icon.textContent = category.charAt(0).toUpperCase();

        const title = document.createElement('h2');
        title.className = 'text-2xl font-bold capitalize text-gray-900';
        title.textContent = category;

        titleDiv.appendChild(icon);
        titleDiv.appendChild(title);

        const link = document.createElement('a');
        link.href = '{{ route("templates") }}';
        link.className = 'text-cyan-600 hover:text-emerald-600 font-bold transition-colors flex items-center gap-2 group';
        link.innerHTML = `
            <span>View Templates</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        `;

        header.appendChild(titleDiv);
        header.appendChild(link);
        categorySection.appendChild(header);

        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6';

        images.forEach(({ photo, source }) => grid.appendChild(createPhotoCard(photo, source)));
        categorySection.appendChild(grid);
        mainContainer.appendChild(categorySection);
    }

    return mainContainer;
}

// Initialize gallery
async function initializeGallery() {
    container.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="flex flex-col items-center gap-3">
                <svg class="animate-spin h-12 w-12 text-cyan-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600 font-medium">Loading amazing images...</span>
            </div>
        </div>
    `;

    const allCategories = await loadAllCategories();
    container.innerHTML = '';
    container.appendChild(allCategories);
}

// Close modal on outside click
downloadModal.addEventListener('click', e => { if(e.target === downloadModal) closeDownloadModal(); });

// Close modal with Escape key
document.addEventListener('keydown', e => { if(e.key === 'Escape') closeDownloadModal(); });

// Start gallery
initializeGallery();
</script>
</x-app-layout>