<x-app-layout>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Header -->
                <div class="flex justify-between tems-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="mt-2 text-gray-700">You have <strong id="credits-display" class="text-blue-600">{{ auth()->user()->credits }}</strong> credits left.</p>
                        <p class="text-sm text-gray-500 mt-1">Each download costs 1 credit</p>
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
                                $categories = ['Nature', 'Technology', 'People', 'Animals', 'Food', 'Travel', 'Sports', 'Fashion', 'Business', 'Music'];
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
                <div id="media-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <div class="col-span-full text-center py-12" id="loading-indicator">
                        <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-3 text-gray-600 font-medium">Loading images...</p>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="mt-8 text-center hidden" id="load-more-container">
                    <button id="loadMoreBtn" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition font-medium">
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
            <p class="text-gray-700 mb-2"><strong>Photographer:</strong> <span id="modalPhotographer"></span></p>
            <p class="text-gray-700 mb-4"><strong>Cost:</strong> 1 credit</p>
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

    <script>
        let allImages = [];
        let displayedImages = [];
        let currentPage = 0;
        const imagesPerPage = 5;
        let selectedImage = null;

        const categories = [
            'backgrounds', 'fashion', 'nature', 'science', 'education', 
            'feelings', 'health', 'people', 'religion', 'places', 
            'animals', 'industry', 'computer', 'food', 'sports', 
            'transportation', 'travel', 'buildings', 'business', 'music'
        ];

        // Load images from both Pexels and Pixabay APIs
        async function loadImages() {
            let images = [];
            let loadedCount = 0;
            const totalSources = categories.length * 2; // Both Pexels and Pixabay

            // Load from Pexels
            for (const cat of categories) {
                try {
                    const res = await fetch(`/api/pexels/${cat}`);
                    const data = await res.json();
                    if (data.photos) {
                        images.push(...data.photos.map(photo => ({ 
                            ...photo, 
                            category: cat,
                            alt: photo.alt || 'Image',
                            source: 'pexels'
                        })));
                    }
                    loadedCount++;
                    updateLoadingProgress(loadedCount, totalSources);
                } catch (err) {
                    console.error(`Failed to load Pexels ${cat}:`, err);
                    loadedCount++;
                    updateLoadingProgress(loadedCount, totalSources);
                }
            }

            // Load from Pixabay
            for (const cat of categories) {
                try {
                    const res = await fetch(`/api/pixabay/${cat}`);
                    const data = await res.json();
                    if (data.hits) {
                        images.push(...data.hits.map(photo => ({ 
                            id: photo.id,
                            photographer: photo.user,
                            src: {
                                original: photo.largeImageURL,
                                large: photo.webformatURL,
                                medium: photo.webformatURL
                            },
                            category: cat,
                            alt: photo.tags || 'Image',
                            source: 'pixabay'
                        })));
                    }
                    loadedCount++;
                    updateLoadingProgress(loadedCount, totalSources);
                } catch (err) {
                    console.error(`Failed to load Pixabay ${cat}:`, err);
                    loadedCount++;
                    updateLoadingProgress(loadedCount, totalSources);
                }
            }

            // Shuffle images for variety
            allImages = images.sort(() => Math.random() - 0.5);
            displayImages(allImages);
            updateResultsCount(allImages.length);
        }

        function updateLoadingProgress(loaded, total) {
            const indicator = document.getElementById('loading-indicator');
            indicator.innerHTML = `
                <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-3 text-gray-600 font-medium">Loading images... (${loaded}/${total})</p>
            `;
        }

        function displayImages(images, append = false) {
            const container = document.getElementById('media-container');
            
            if (images.length === 0) {
                container.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-gray-500 text-lg">No images found</p></div>';
                document.getElementById('load-more-container').classList.add('hidden');
                return;
            }

            displayedImages = images;
            
            if (!append) {
                currentPage = 0;
            }
            
            const start = currentPage * imagesPerPage;
            const end = start + imagesPerPage;
            const imagesToShow = images.slice(start, end);

            let html = '';
            
            imagesToShow.forEach(photo => {
                const sourceColor = photo.source === 'pexels' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700';
                html += `
                    <div class="rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1 bg-white" 
                         onclick="openDownloadModal(${photo.id}, '${photo.source}')">
                        <img src="${photo.src.medium}" alt="${photo.alt}" class="w-full h-56 object-cover" loading="lazy">
                        <div class="p-3">
                            <p class="text-gray-800 font-medium text-sm truncate">${photo.photographer}</p>
                            <p class="text-gray-500 text-xs mt-1 truncate">${photo.alt}</p>
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

            // Update results count
            updateResultsCount(images.length, end);

            // Show/hide load more button
            if (end < images.length) {
                document.getElementById('load-more-container').classList.remove('hidden');
            } else {
                document.getElementById('load-more-container').classList.add('hidden');
            }
        }

        function updateResultsCount(total, displayed) {
            const showing = Math.min(displayed, total);
            document.getElementById('results-count').textContent = `Showing ${showing} of ${total} images`;
        }

        function filtrage() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const checkedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
                .map(cb => cb.value);

            const filtered = allImages.filter(img => {
                const matchesSearch = !searchTerm || 
                    img.alt.toLowerCase().includes(searchTerm) || 
                    img.photographer.toLowerCase().includes(searchTerm);
                const matchesCategory = checkedCategories.length === 0 || 
                    checkedCategories.includes(img.category);
                return matchesSearch && matchesCategory;
            });

            displayImages(filtered);
            updateResultsCount(filtered.length);

            // Show/hide clear filters button
            const clearBtn = document.getElementById('clearFilters');
            if (searchTerm || checkedCategories.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        function openDownloadModal(photoId, source) {
            const photo = allImages.find(img => img.id === photoId && img.source === source);
            if (!photo) return;

            selectedImage = photo;
            document.getElementById('modalImage').src = photo.src.large;
            document.getElementById('modalPhotographer').textContent = photo.photographer;
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
                alert('Insufficient credits. Please purchase more credits to continue downloading.');
                closeDownloadModal();
                return;
            }

            try {
                // Make API call to download and deduct credit
                const response = await fetch('/api/download', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        image_url: selectedImage.src.original,
                        photographer: selectedImage.photographer,
                        image_id: selectedImage.id
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update credits display
                    document.getElementById('credits-display').textContent = data.remaining_credits;
                    
                    // Download the image
                    const link = document.createElement('a');
                    link.href = selectedImage.src.original;
                    link.download = `${selectedImage.photographer}-${selectedImage.id}.jpg`;
                    link.target = '_blank';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    closeDownloadModal();
                } else {
                    alert(data.message || 'Download failed. Please try again.');
                }
            } catch (error) {
                console.error('Download error:', error);
                alert('An error occurred during download. Please try again.');
            }
        }

        // Event listeners
        document.getElementById('searchInput').addEventListener('input', filtrage);
        document.querySelectorAll('.category-checkbox').forEach(cb => {
            cb.addEventListener('change', filtrage);
        });

        document.getElementById('clearFilters').addEventListener('click', () => {
            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
            filtrage();
        });

        document.getElementById('loadMoreBtn').addEventListener('click', () => {
            currentPage++;
            displayImages(displayedImages, true);
        });

        document.getElementById('confirmDownload').addEventListener('click', downloadImage);
        document.getElementById('cancelDownload').addEventListener('click', closeDownloadModal);

        // Close modal on background click
        document.getElementById('downloadModal').addEventListener('click', (e) => {
            if (e.target.id === 'downloadModal') {
                closeDownloadModal();
            }
        });

        // Initialize
        loadImages();
    </script>

</x-app-layout>