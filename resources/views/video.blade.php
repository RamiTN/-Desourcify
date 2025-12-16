<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Toast CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="mt-2 text-gray-700">
                            You have <strong id="credits-display" class="text-blue-600">{{ auth()->user()->credits }}</strong> credits left.
                        </p>
                        <p class="text-sm text-gray-500 mt-1">Each download costs 3 credit</p>
                        <p class="text-sm text-gray-600 mt-2" id="results-count">Loading videos...</p>
                    </div>
                    <button id="clearFilters" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition hidden">
                        Clear Filters
                    </button>
                </div>

                <!-- Filters -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" id="searchInput" placeholder="Search by description or videographer..." 
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

                <!-- Videos container -->
                <div id="media-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-12">
                    <div class="col-span-full text-center py-12" id="loading-indicator">
                        <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-3 text-gray-600 font-medium">Loading videos...</p>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="absolute left-1/2 -translate-x-1/2 -bottom-5 hidden" id="load-more-container">
                    <button id="loadMoreBtn" class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition font-medium shadow-md">
                        Load More Videos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal -->
    <div id="downloadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Download Video</h3>
            <video id="modalVideo" controls class="w-full h-64 object-cover rounded-md mb-4">
                <source src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <p class="text-gray-700 mb-2"><strong>🎥 Videographer:</strong> <span id="modalVideographer"></span></p>
            <p class="text-gray-700 mb-2"><strong>⏱️ Duration:</strong> <span id="modalDuration"></span></p>
            <p class="text-gray-700 mb-4"><strong>💳 Cost:</strong> 3 credit</p>
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
        let selectedVideo = null;
        let currentVideos = [];

        const categoryMap = {
            'nature': 'nature',
            'technology': 'technology',
            'people': 'people',
            'animals': 'animals',
            'food': 'food',
            'travel': 'travel',
            'sports': 'sports',
            'fashion': 'fashion',
            'business': 'business',
            'music': 'music',
            'cars': 'cars',
            'architecture': 'architecture',
            'art': 'art',
            'beaches': 'beach',
            'mountains': 'mountain',
            'flowers': 'flowers',
            'cities': 'city',
            'space': 'space',
            'underwater': 'underwater'
        };

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

        function formatDuration(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        }

        async function loadVideos(append = false) {
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
                            <p class="mt-3 text-gray-600 font-medium">Loading videos...</p>
                        </div>
                    `;
                }

                let category = checkedCategories.length > 0 
                    ? categoryMap[checkedCategories[0]] || checkedCategories[0]
                    : 'nature';

                const searchQuery = searchTerm || category;

                const pexelsUrl = `/pexels/video/${encodeURIComponent(searchQuery)}?page=${currentPage}`;

                const pexelsRes = await fetch(pexelsUrl);
                
                if (pexelsRes.ok) {
                    const pexelsData = await pexelsRes.json();
                    let videos = [];
                    if (pexelsData.videos && Array.isArray(pexelsData.videos)) {
                        videos = pexelsData.videos.map(video => {
                            const videoFile = video.video_files.find(f => f.quality === 'hd') || video.video_files[0];
                            return {
                                id: video.id,
                                videographer: video.user.name,
                                videographerUrl: video.user.url,
                                videoUrl: videoFile.link,
                                image: video.image,
                                duration: video.duration,
                                width: video.width,
                                height: video.height,
                                category: searchQuery,
                                source: 'pexels',
                                video_files: video.video_files
                            };
                        });
                    }

                    if (append) currentVideos = [...currentVideos, ...videos];
                    else currentVideos = videos;

                    displayVideos(videos, append);
                } else {
                    showToast('Failed to load videos from Pexels', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('Error loading videos', 'error');
            } finally {
                isLoading = false;
            }
        }

        function displayVideos(videos, append = false) {
            const container = document.getElementById('media-container');
            if (!append) container.innerHTML = '';

            if (videos.length === 0 && !append) {
                container.innerHTML = '<div class="col-span-full text-center py-12"><p class="text-gray-500 text-lg">No videos found</p></div>';
                document.getElementById('load-more-container').classList.add('hidden');
                updateResultsCount(0);
                return;
            }

            let html = '';
            videos.forEach(video => {
                const videoData = {
                    id: video.id,
                    videoUrl: video.videoUrl,
                    videographer: video.videographer,
                    duration: video.duration,
                    video_files: video.video_files
                };
                html += `
                    <div class="rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer transform hover:-translate-y-1 bg-white" 
                         data-video='${JSON.stringify(videoData).replace(/'/g, "&#39;")}' onclick="handleVideoClick(this)">
                        <div class="relative">
                            <img src="${video.image}" alt="Video by ${video.videographer.replace(/"/g, '&quot;')}" class="w-full h-56 object-cover" loading="lazy">
                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 hover:bg-opacity-40 transition">
                                <svg class="w-16 h-16 text-white opacity-80" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                            </div>
                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                ${formatDuration(video.duration)}
                            </div>
                            <div class="absolute top-2 left-2 bg-purple-600 text-white text-xs px-2 py-1 rounded">
                                ${video.width}x${video.height}
                            </div>
                        </div>
                        <div class="p-3">
                            <p class="text-gray-800 font-medium text-sm truncate">🎥 ${video.videographer.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                            <div class="flex gap-2 mt-2">
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">${video.category}</span>
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded-full">Pexels</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML += html;
            updateResultsCount(currentVideos.length);
            document.getElementById('load-more-container').classList.remove('hidden');
        }

        function handleVideoClick(element) {
            const videoData = JSON.parse(element.getAttribute('data-video'));
            openDownloadModal(videoData.id, videoData.videoUrl, videoData.videographer, videoData.duration, videoData.video_files);
        }

        function updateResultsCount(count) {
            document.getElementById('results-count').textContent = `Showing ${count} videos`;
        }

        function applyFilters() {
            currentPage = 1;
            currentVideos = [];
            loadVideos(false);

            const searchTerm = document.getElementById('searchInput').value;
            const checkedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'));
            const clearBtn = document.getElementById('clearFilters');

            if (searchTerm || checkedCategories.length > 0) clearBtn.classList.remove('hidden');
            else clearBtn.classList.add('hidden');
        }

        let searchTimeout;
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        }

        function openDownloadModal(videoId, videoUrl, videographer, duration, videoFiles) {
            selectedVideo = { id: videoId, videoUrl, videographer, duration, video_files: videoFiles };
            const modalVideo = document.getElementById('modalVideo');
            modalVideo.src = videoUrl;
            modalVideo.load();
            document.getElementById('modalVideographer').textContent = videographer;
            document.getElementById('modalDuration').textContent = formatDuration(duration);
            document.getElementById('downloadModal').classList.remove('hidden');
            document.getElementById('downloadModal').classList.add('flex');
        }

        function closeDownloadModal() {
            const modalVideo = document.getElementById('modalVideo');
            modalVideo.pause();
            modalVideo.src = '';
            document.getElementById('downloadModal').classList.add('hidden');
            document.getElementById('downloadModal').classList.remove('flex');
            selectedVideo = null;
        }
async function downloadVideo() {
    if (!selectedVideo) return;

    let credits = parseInt(document.getElementById('credits-display').textContent);

    if (credits < 3) {
        showToast('Insufficient credits. You need at least 3 credits.', 'error');
        closeDownloadModal();
        return;
    }

    try {
        // Decrease credits in frontend
        credits -= 3;
        document.getElementById('credits-display').textContent = credits;

        // Update database
        await fetch('/api/update-credits', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ used_credits: 3 })
        });

        // Download directly from Pexels
        const link = document.createElement('a');
        link.href = selectedVideo.videoUrl; // direct Pexels video URL
        link.download = `pexels-${selectedVideo.videographer}-${selectedVideo.id}.mp4`;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        showToast('Download started! 🎉', 'success');
        closeDownloadModal();

    } catch (error) {
        console.error('Download error:', error);
        showToast('Error downloading video', 'error');
    }
}



        // Event listeners
        document.getElementById('searchInput').addEventListener('input', debounceSearch);

        document.querySelectorAll('.category-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                document.querySelectorAll('.category-checkbox').forEach(other => { if (other !== cb) other.checked = false; });
                applyFilters();
            });
        });

        document.getElementById('clearFilters').addEventListener('click', () => {
            document.getElementById('searchInput').value = '';
            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
            applyFilters();
        });

        document.getElementById('loadMoreBtn').addEventListener('click', () => {
            currentPage++;
            loadVideos(true);
        });

        document.getElementById('cancelDownload').addEventListener('click', closeDownloadModal);
        document.getElementById('confirmDownload').addEventListener('click', downloadVideo);

        // Initial load
        loadVideos();
    </script>
</x-app-layout>
