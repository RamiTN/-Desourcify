<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Toast CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <!-- Toast JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="mt-2 text-gray-700">You have <strong id="credits-display" class="text-blue-600">{{ auth()->user()->credits }}</strong> credits left.</p>
                        <p class="text-sm text-gray-500 mt-1">Each download costs 1 credit</p>
                        <p class="text-sm text-gray-600 mt-2" id="results-count">Loading images...</p>
                    </div>
                    <button id="clearFilters" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition hidden">
                        Clear Filters
                    </button>
                </div>

                <!-- Filters -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" id="searchInput" placeholder="Search by description or photographer..." 
                            class="border border-gray-300 rounded-md px-4 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                        <div class="flex flex-wrap gap-3">
                            @php
                                $categories = ['Nature', 'Technology', 'People', 'Animals', 'Food', 'Travel', 'Sports', 'Fashion', 'Business', 'Music', 'Cars', 'Architecture', 'Art', 'Beaches', 'Mountains', 'Flowers', 'Cities', 'Space', 'Underwater'];
                            @endphp

                            @foreach ($categories as $category)
                                <label class="inline-flex items-center space-x-2 px-3 py-2 bg-white border border-gray-300 rounded-md cursor-pointer hover:bg-blue-50 transition">
                                    <input type="checkbox" class="category-checkbox rounded text-blue-600 focus:ring-blue-500" value="{{ strtolower($category) }}">
                                    <span class="text-sm">{{ $category }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Images container -->
                <div id="media-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-12">
                    <div class="col-span-full text-center py-12" id="loading-indicator">
                        <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-3 text-gray-600 font-medium">Loading images...</p>
                    </div>
                </div>

                <!-- Load More Button - Positioned at bottom border center -->
                <div class="absolute left-1/2 -translate-x-1/2 -bottom-5 hidden" id="load-more-container">
                    <button id="loadMoreBtn" class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition font-medium shadow-md">
                        Load More Images
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal -->
    <div id="downloadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Download Image</h3>
            <img id="modalImage" src="" alt="" class="w-full h-64 object-cover rounded-md mb-4">
            <p class="text-gray-700 mb-2"><strong>📷 Photographer:</strong> <span id="modalPhotographer"></span></p>
            <p class="text-gray-700 mb-4"><strong>💳 Cost:</strong> 1 credit</p>
            <div class="flex gap-3">
                <button id="confirmDownload" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
                    Download
                </button>
                <button id="cancelDownload" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    <footer class="text-center py-4 bg-gray-100 mt-8">
        <p class="text-gray-600">&copy; 2025 Desourcify. All rights reserved.</p>
        <p class="text-gray-600">Made by<a class="text-blue-500" href="https://rami.page.gd/"> Rami Abbassi</a></p>
    </footer>
    <script>
        let currentPage = 1;
        let isLoading = false;
        let selectedImage = null;
        let currentImages = [];

        const categoryMap = {
            'nature': 'nature',
            'technology': 'computer',
            'people': 'people',
            'animals': 'animals',
            'food': 'food',
            'travel': 'travel',
            'sports': 'sports',
            'fashion': 'fashion',
            'business': 'business',
            'music': 'music',
            "cars": "cars",
            "architecture": "architecture",
            "art": "art",
            "beaches": "beach",
            "mountains": "mountain",
            "flowers": "flowers",
            "cities": "city",
            "space": "space",
            "underwater": "underwater"
        };

        // Toast notification helper
        function showToast(message, type = 'success') {
            const bgColor = type === 'success' ? 'linear-gradient(to right, #00b09b, #96c93d)' : 
                           type === 'error' ? 'linear-gradient(to right, #ff5f6d, #ffc371)' :
                           'linear-gradient(to right, #4facfe, #00f2fe)';
            
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: bgColor,
                    borderRadius: "8px",
                    fontSize: "14px",
                    fontWeight: "500"
                }
            }).showToast();
        }

        async function loadImages(append = false) {
            if (isLoading) return;
            isLoading = true;

            const searchTerm = document.getElementById('searchInput').value.toLowerCase().trim();
            const checkedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
                .map(cb => cb.value);

            try {
                if (!append) {
                    document.getElementById('media-container').innerHTML = `
                        <div class="col-span-full text-center py-12">
                            <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-3 text-gray-600 font-medium">Loading images...</p>
                        </div>
                    `;
                }

                let images = [];
                
                // Determine categories to fetch
                let categoriesToFetch = checkedCategories.length > 0 
                    ? checkedCategories.map(cat => categoryMap[cat] || cat)
                    : Object.values(categoryMap);

                // Randomize category selection for variety
                const randomCategory = categoriesToFetch[Math.floor(Math.random() * categoriesToFetch.length)];

                console.log('Fetching category:', randomCategory, 'page:', currentPage);

                // Fetch from Pexels with error handling
                try {
                    const pexelsUrl = `/pexels/${randomCategory}?page=${currentPage}`;
                    console.log('Pexels URL:', pexelsUrl);
                    const pexelsRes = await fetch(pexelsUrl);
                    console.log('Pexels response status:', pexelsRes.status);
                    
                    if (pexelsRes.ok) {
                        const pexelsData = await pexelsRes.json();
                        console.log('Pexels data:', pexelsData);
                        
                        if (pexelsData.photos && Array.isArray(pexelsData.photos)) {
                            images.push(...pexelsData.photos.slice(0, 10).map(photo => ({
                                id: photo.id,
                                photographer: photo.photographer,
                                src: {
                                    original: photo.src.original,
                                    large: photo.src.large,
                                    medium: photo.src.medium
                                },
                                category: randomCategory,
                                alt: photo.alt || 'Image',
                                source: 'pexels'
                            })));
                        }
                    } else {
                        console.error('Pexels API error:', pexelsRes.status, await pexelsRes.text());
                    }
                } catch (err) {
                    console.error('Pexels API error:', err);
                }

                // Fetch from Pixabay with error handling
                try {
                    const pixabayUrl = `/pixabay/${randomCategory}?page=${currentPage}`;
                    console.log('Pixabay URL:', pixabayUrl);
                    const pixabayRes = await fetch(pixabayUrl);
                    console.log('Pixabay response status:', pixabayRes.status);
                    
                    if (pixabayRes.ok) {
                        const pixabayData = await pixabayRes.json();
                        console.log('Pixabay data:', pixabayData);
                        
                        if (pixabayData.hits && Array.isArray(pixabayData.hits)) {
                            images.push(...pixabayData.hits.slice(0, 10).map(photo => ({
                                id: photo.id,
                                photographer: photo.user,
                                src: {
                                    original: photo.largeImageURL,
                                    large: photo.webformatURL,
                                    medium: photo.webformatURL
                                },
                                category: randomCategory,
                                alt: photo.tags || 'Image',
                                source: 'pixabay'
                            })));
                        }
                    } else {
                        console.error('Pixabay API error:', pixabayRes.status, await pixabayRes.text());
                    }
                } catch (err) {
                    console.error('Pixabay API error:', err);
                }

                console.log('Total images fetched:', images.length);

                // Shuffle for variety
                images = images.sort(() => Math.random() - 0.5);

                // Apply search filter if exists
                if (searchTerm) {
                    images = images.filter(img => 
                        img.alt.toLowerCase().includes(searchTerm) || 
                        img.photographer.toLowerCase().includes(searchTerm)
                    );
                }

                // Take only 20 images
                images = images.slice(0, 20);

                console.log('Images after filtering:', images.length);

                if (append) {
                    currentImages = [...currentImages, ...images];
                } else {
                    currentImages = images;
                }

                displayImages(images, append);

            } catch (error) {
                console.error('Error loading images:', error);
                document.getElementById('media-container').innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <p class="text-red-500 text-lg">Error loading images. Please try again.</p>
                        <p class="text-sm text-gray-600 mt-2">Check console for details</p>
                    </div>
                `;
                showToast('Failed to load images', 'error');
            } finally {
                isLoading = false;
            }
        }

        function displayImages(images, append = false) {
            const container = document.getElementById('media-container');
            
            if (images.length === 0 && !append) {
                container.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-gray-500 text-lg">No images found</p></div>';
                document.getElementById('load-more-container').classList.add('hidden');
                updateResultsCount(0);
                return;
            }

            let html = '';
            
            images.forEach(photo => {
                const sourceColor = photo.source === 'pexels' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700';
                
                // Properly escape strings for HTML attributes
                const photoData = {
                    id: photo.id,
                    source: photo.source,
                    original: photo.src.original,
                    large: photo.src.large,
                    photographer: photo.photographer
                };
                
                html += `
                    <div class="rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1 bg-white" 
                         data-photo='${JSON.stringify(photoData).replace(/'/g, "&#39;")}' onclick="handleImageClick(this)">
                        <img src="${photo.src.medium}" alt="${photo.alt.replace(/"/g, '&quot;')}" class="w-full h-56 object-cover" loading="lazy">
                        <div class="p-3">
                            <p class="text-gray-800 font-medium text-sm truncate">${photo.photographer.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <p class="text-gray-500 text-xs mt-1 truncate">${photo.alt.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <div class="flex gap-2 mt-2">
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">${photo.category}</span>
                                <span class="inline-block px-2 py-1 ${sourceColor} text-xs rounded-full capitalize">${photo.source}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            if (append) {
                container.innerHTML += html;
            } else {
                container.innerHTML = html;
            }

            updateResultsCount(currentImages.length);
            document.getElementById('load-more-container').classList.remove('hidden');
        }

        function handleImageClick(element) {
            const photoData = JSON.parse(element.getAttribute('data-photo'));
            openDownloadModal(photoData.id, photoData.source, photoData.original, photoData.large, photoData.photographer);
        }

        function updateResultsCount(count) {
            document.getElementById('results-count').textContent = `Showing ${count} images`;
        }

        function applyFilters() {
            currentPage = 1;
            currentImages = [];
            loadImages(false);

            const searchTerm = document.getElementById('searchInput').value;
            const checkedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'));
            const clearBtn = document.getElementById('clearFilters');
            
            if (searchTerm || checkedCategories.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        let searchTimeout;
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        }

        function openDownloadModal(photoId, source, originalUrl, largeUrl, photographer) {
            selectedImage = {
                id: photoId,
                source: source,
                src: {
                    original: originalUrl,
                    large: largeUrl
                },
                photographer: photographer
            };
            
            document.getElementById('modalImage').src = largeUrl;
            document.getElementById('modalPhotographer').textContent = photographer;
            document.getElementById('downloadModal').classList.remove('hidden');
            document.getElementById('downloadModal').classList.add('flex');
        }

        function closeDownloadModal() {
            document.getElementById('downloadModal').classList.add('hidden');
            document.getElementById('downloadModal').classList.remove('flex');
            selectedImage = null;
        }

        async function downloadImage() {
            if (!selectedImage) return;

            const credits = parseInt(document.getElementById('credits-display').textContent);
            if (credits < 1) {
                showToast('Insufficient credits. Please purchase more credits.', 'error');
                closeDownloadModal();
                return;
            }

            try {
                const response = await fetch('/api/download-image', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        image_url: selectedImage.src.original,
                        photographer: selectedImage.photographer,
                        photo_id: selectedImage.id.toString(),
                        source: selectedImage.source
                    })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    document.getElementById('credits-display').textContent = data.remaining_credits;
                    
                    // Download directly without opening new tab
                    const link = document.createElement('a');
                    link.href = selectedImage.src.original;
                    link.download = `${selectedImage.photographer}-${selectedImage.id}.jpg`;
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    showToast('Download Successful! 🎉', 'success');
                    closeDownloadModal();
                } else {
                    showToast(data.error || 'Download failed. Please try again.', 'error');
                }
            } catch (error) {
                console.error('Download error:', error);
                showToast('An error occurred: ' + error.message, 'error');
            }
        }

        // Event listeners
        document.getElementById('searchInput').addEventListener('input', debounceSearch);
        
        document.querySelectorAll('.category-checkbox').forEach(cb => {
            cb.addEventListener('change', applyFilters);
        });

        document.getElementById('clearFilters').addEventListener('click', () => {
            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
            applyFilters();
        });

        document.getElementById('loadMoreBtn').addEventListener('click', () => {
            currentPage++;
            loadImages(true);
        });

        document.getElementById('confirmDownload').addEventListener('click', downloadImage);
        document.getElementById('cancelDownload').addEventListener('click', closeDownloadModal);

        document.getElementById('downloadModal').addEventListener('click', (e) => {
            if (e.target.id === 'downloadModal') {
                closeDownloadModal();
            }
        });

        // Initialize
        loadImages(false);
    </script>
</x-app-layout>