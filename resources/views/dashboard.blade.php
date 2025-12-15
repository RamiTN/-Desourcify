<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="mt-2 text-gray-700">
                    You have <strong id="credits-display">{{ auth()->user()->credits }}</strong> credits left.
                </p>
                <p class="text-sm text-gray-500 mt-1">Each download costs 1 credit</p>
                
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
    </footer>

<script>
const container = document.getElementById('media-container');
const creditsDisplay = document.getElementById('credits-display');
const downloadModal = document.getElementById('download-modal');
let selectedImage = null;
let isDownloading = false;

// Open modal
function openDownloadModal(photo, source) {
    selectedImage = { photo, source };
    
    const modalImage = document.getElementById('modal-image');
    const modalPhotographer = document.getElementById('modal-photographer');
    
    modalImage.src = source === 'pexels' ? photo.src.medium : photo.webformatURL;
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

// Download image using /api/download endpoint
async function downloadImage() {
    if (!selectedImage) return;

    const credits = parseInt(creditsDisplay.textContent);
    if (credits < 1) {
        showMessage('Insufficient credits. Please purchase more credits to continue downloading.', 'error');
        closeDownloadModal();
        return;
    }

    if (isDownloading) {
        showMessage('Please wait for the current download to complete.', 'warning');
        return;
    }

    isDownloading = true;
    const confirmBtn = document.getElementById('confirm-download-btn');
    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Downloading...';

    try {
        const { photo, source } = selectedImage;
        const imageUrl = source === 'pexels' ? photo.src.original : photo.largeImageURL;
        const photographer = source === 'pexels' ? photo.photographer : photo.user;

        // Make API call to download and deduct credit
        const response = await fetch('/api/download', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                image_url: imageUrl,
                photographer: photographer,
                image_id: photo.id
            })
        });

        const data = await response.json();

        if (!response.ok) {
            showMessage(data.error || 'Download failed. Please try again.', 'error');
            closeDownloadModal();
            return;
        }

        // Download the image
        const img = await fetch(imageUrl);
        if (!img.ok) {
            showMessage('Failed to fetch image.', 'error');
            closeDownloadModal();
            return;
        }

        const blob = await img.blob();
        const blobUrl = URL.createObjectURL(blob);

        const link = document.createElement('a');
        link.href = blobUrl;
        link.download = `${source}-${photo.id}.jpg`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(blobUrl);

        // Update credits from server response
        if (typeof data.remaining_credits === 'number') {
            creditsDisplay.textContent = data.remaining_credits;
        }

        showMessage('Image downloaded successfully!', 'success');
        closeDownloadModal();

    } catch (error) {
        console.error('Download error:', error);
        showMessage('Error downloading image. Please try again.', 'error');
        closeDownloadModal();
    } finally {
        isDownloading = false;
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Download';
    }
}

// Show messages
function showMessage(message, type = 'info') {
    const messageDiv = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' :
                     type === 'error' ? 'bg-red-100 border-red-400 text-red-700' :
                     type === 'warning' ? 'bg-yellow-100 border-yellow-400 text-yellow-700' :
                     'bg-blue-100 border-blue-400 text-blue-700';
    
    messageDiv.className = `${bgColor} border px-4 py-3 rounded fixed top-4 right-4 z-50 shadow-lg`;
    messageDiv.textContent = message;
    
    document.body.appendChild(messageDiv);
    
    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// Create photo card with clickable image
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

// Load images from Pexels and Pixabay
async function loadCategoryImages(category) {
    const images = [];
    
    try {
        const res = await fetch(`/pexels/${encodeURIComponent(category)}`);
        if (res.ok) {
            const data = await res.json();
            if (data.photos && Array.isArray(data.photos)) {
                images.push(...data.photos.slice(0, 2).map(photo => ({ photo, source: 'pexels' })));
            }
        }
    } catch (err) {
        console.error(`Failed to load Pexels ${category}:`, err);
    }
    
    try {
        const res = await fetch(`/pixabay/${encodeURIComponent(category)}`);
        if (res.ok) {
            const data = await res.json();
            if (data.hits && Array.isArray(data.hits)) {
                images.push(...data.hits.slice(0, 2).map(photo => ({ photo, source: 'pixabay' })));
            }
        }
    } catch (err) {
        console.error(`Failed to load Pixabay ${category}:`, err);
    }
    
    return images.sort(() => Math.random() - 0.5).slice(0, 4);
}

// Load all categories
async function loadAllCategories() {
    const categories = [
        'nature', 'technology', 'people', 'animals', 
        'food', 'travel', 'sports', 'fashion', 
        'business', 'music'
    ];
    
    const mainContainer = document.createElement('div');
    
    for (const category of categories) {
        const images = await loadCategoryImages(category);
        if (images.length === 0) continue;
        
        const header = document.createElement('div');
        header.className = 'flex items-center justify-between mt-8 mb-4';
        
        const title = document.createElement('h2');
        title.className = 'text-2xl font-bold capitalize text-gray-800';
        title.textContent = category;
        
        const link = document.createElement('a');
        link.href = '{{ route("templates") }}';
        link.className = 'text-blue-400 hover:text-blue-700 font-semibold text-underline';
        link.textContent = 'View Templates -→';
        
        header.appendChild(title);
        header.appendChild(link);
        mainContainer.appendChild(header);
        
        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6';
        
        images.forEach(({ photo, source }) => {
            grid.appendChild(createPhotoCard(photo, source));
        });
        
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

// Close modal on click outside
downloadModal.addEventListener('click', e => {
    if (e.target === downloadModal) closeDownloadModal();
});

// Start gallery
initializeGallery();
</script>
</x-app-layout>