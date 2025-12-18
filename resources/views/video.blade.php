<x-app-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Toast CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
                        <p class="text-sm text-gray-500">Each video download costs 3 credits</p>
                        <p class="text-sm text-gray-600 mt-2" id="results-count">Loading videos...</p>
                    </div>
                    
                    <button id="clearFilters" class="px-6 py-3 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 font-bold rounded-xl hover:from-gray-300 hover:to-gray-400 transition-all duration-300 hidden shadow-md">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative bg-white border-2 border-gray-100 rounded-3xl p-6 md:p-8 shadow-xl">
                
                <!-- Filters -->
                <div class="bg-gradient-to-br from-cyan-50 to-emerald-50 rounded-2xl p-6 mb-8 border-2 border-gray-100">
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </label>
                        <input type="text" id="searchInput" placeholder="Search by description or videographer..." 
                            class="border-2 border-gray-200 rounded-xl px-4 py-3 w-full focus:ring-4 focus:ring-cyan-100 focus:border-cyan-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Categories
                        </label>
                        <div class="flex flex-wrap gap-3">
                            @php
                                $categories = ['Nature', 'Technology', 'People', 'Animals', 'Food', 'Travel', 'Sports', 'Fashion', 'Business', 'Music', 'Cars', 'Architecture', 'Art', 'Beaches', 'Mountains', 'Flowers', 'Cities', 'Space', 'Underwater'];
                            @endphp

                            @foreach ($categories as $category)
                                <label class="inline-flex items-center space-x-2 px-4 py-2 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:border-cyan-400 hover:bg-cyan-50 transition-all group">
                                    <input type="checkbox" class="category-checkbox rounded text-cyan-600 focus:ring-cyan-500" value="{{ strtolower($category) }}">
                                    <span class="text-sm font-medium text-gray-700 group-hover:text-cyan-700">{{ $category }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Videos container -->
                <div id="media-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-12">
                    <div class="col-span-full text-center py-12" id="loading-indicator">
                        <svg class="animate-spin h-12 w-12 text-cyan-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-3 text-gray-600 font-medium">Loading amazing videos...</p>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="absolute left-1/2 -translate-x-1/2 -bottom-6 hidden" id="load-more-container">
                    <button id="loadMoreBtn" class="px-8 py-4 bg-gradient-to-r from-cyan-500 via-emerald-500 to-orange-500 text-white font-bold rounded-xl hover:shadow-2xl transition-all duration-300 shadow-lg transform hover:scale-105">
                        Load More Videos
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Modal -->
    <div id="downloadModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl p-6 max-w-2xl w-full mx-4 shadow-2xl border-2 border-gray-100 animate-fadeInUp">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold bg-gradient-to-r from-cyan-600 to-emerald-600 bg-clip-text text-transparent">Download Video</h3>
                <button onclick="closeDownloadModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <video id="modalVideo" controls class="w-full h-80 object-cover rounded-2xl mb-4 border-2 border-gray-100 bg-black">
                <source src="" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="bg-gradient-to-r from-cyan-50 to-emerald-50 rounded-xl p-4 mb-4">
                <p class="text-gray-700 mb-2 flex items-center gap-2">
                    <span class="text-lg">🎥</span>
                    <span>Videographer: <strong id="modalVideographer" class="text-gray-900"></strong></span>
                </p>
                <p class="text-gray-700 mb-2 flex items-center gap-2">
                    <span class="text-lg">⏱️</span>
                    <span>Duration: <strong id="modalDuration" class="text-gray-900"></strong></span>
                </p>
                <p class="text-gray-700 flex items-center gap-2">
                    <span class="text-lg">💎</span>
                    <span>Cost: <strong class="text-orange-600">3 credits</strong></span>
                </p>
            </div>
            <div class="flex gap-3">
                <button id="cancelDownload" class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition-all">
                    Cancel
                </button>
                <button id="confirmDownload" class="flex-1 px-4 py-3 bg-gradient-to-r from-cyan-500 to-emerald-500 hover:from-cyan-600 hover:to-emerald-600 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
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
        let currentPage = 1;
        let isLoading = false;
        let selectedVideo = null;
        let currentVideos = [];
        const PIXABAY_API_KEY = 'qSGU1JPjVWDhGFWoAS8nM1ELauoQFhDMvLhnDSKV';

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
            const bgColor = type === 'success' ? 'linear-gradient(to right, #06B6D4, #10B981)' : 
                           type === 'error' ? 'linear-gradient(to right, #dc2626, #ef4444)' :
                           'linear-gradient(to right, #F97316, #06B6D4)';

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: bgColor,
                    borderRadius: "12px",
                    fontSize: "14px",
                    fontWeight: "600",
                    padding: "16px"
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
                            <svg class="animate-spin h-12 w-12 text-cyan-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-3 text-gray-600 font-medium">Loading amazing videos...</p>
                        </div>
                    `;
                }

                let videos = [];
                let category = checkedCategories.length > 0 
                    ? categoryMap[checkedCategories[0]] || checkedCategories[0]
                    : 'nature';

                const searchQuery = searchTerm || category;

                // Load from Pexels
                try {
                    const pexelsUrl = `/pexels/video/${encodeURIComponent(searchQuery)}?page=${currentPage}`;
                    const pexelsRes = await fetch(pexelsUrl);
                    
                    if (pexelsRes.ok) {
                        const pexelsData = await pexelsRes.json();
                        if (pexelsData.videos && Array.isArray(pexelsData.videos)) {
                            videos.push(...pexelsData.videos.map(video => {
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
                            }));
                        }
                    }
                } catch (err) {
                    console.error('Pexels error:', err);
                }

                // Load from Pixabay
                try {
                    const pixabayUrl = `https://pixabay.com/api/videos/?key=${PIXABAY_API_KEY}&q=${encodeURIComponent(searchQuery)}&page=${currentPage}&per_page=20`;
                    const pixabayRes = await fetch(pixabayUrl);
                    
                    if (pixabayRes.ok) {
                        const pixabayData = await pixabayRes.json();
                        if (pixabayData.hits && Array.isArray(pixabayData.hits)) {
                            videos.push(...pixabayData.hits.map(video => {
                                const videoFile = video.videos.large || video.videos.medium || video.videos.small;
                                return {
                                    id: video.id,
                                    videographer: video.user,
                                    videographerUrl: `https://pixabay.com/users/${video.user}-${video.user_id}/`,
                                    videoUrl: videoFile.url,
                                    image: video.userImageURL || 'https://via.placeholder.com/640x360?text=Video',
                                    duration: video.duration,
                                    width: videoFile.width,
                                    height: videoFile.height,
                                    category: searchQuery,
                                    source: 'pixabay',
                                    video_files: [{ link: videoFile.url, quality: videoFile.quality }]
                                };
                            }));
                        }
                    }
                } catch (err) {
                    console.error('Pixabay error:', err);
                }

                // Randomize and limit
                videos = videos.sort(() => Math.random() - 0.5).slice(0, 20);

                if (append) currentVideos = [...currentVideos, ...videos];
                else currentVideos = videos;

                displayVideos(videos, append);

            } catch (error) {
                console.error(error);
                showToast('Error loading videos', 'error');
                document.getElementById('media-container').innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <p class="text-red-500 text-lg font-bold">Error loading videos</p>
                    </div>
                `;
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
                const sourceColor = video.source === 'pexels' ? 'from-purple-100 to-purple-200 text-purple-700' : 'from-emerald-100 to-emerald-200 text-emerald-700';
                const videoData = {
                    id: video.id,
                    videoUrl: video.videoUrl,
                    videographer: video.videographer,
                    duration: video.duration,
                    video_files: video.video_files,
                    source: video.source
                };
                html += `
                    <div class="rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2 bg-white border-2 border-gray-100 group" 
                         data-video='${JSON.stringify(videoData).replace(/'/g, "&#39;")}' onclick="handleVideoClick(this)">
                        <div class="relative overflow-hidden">
                            <img src="${video.image}" alt="Video by ${video.videographer.replace(/"/g, '&quot;')}" class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy">
                            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-t from-black/70 to-black/30 group-hover:from-black/80 group-hover:to-black/40 transition-all">
                                <svg class="w-16 h-16 text-white opacity-90 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                </svg>
                            </div>
                            <div class="absolute bottom-2 right-2 bg-black/75 text-white text-xs px-2 py-1 rounded-lg font-semibold">
                                ${formatDuration(video.duration)}
                            </div>
                            <div class="absolute top-2 left-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white text-xs px-2 py-1 rounded-lg font-semibold">
                                ${video.width}x${video.height}
                            </div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-cyan-50/50 to-emerald-50/50">
                            <p class="text-gray-800 font-bold text-sm truncate flex items-center gap-2">
                                <span>🎥</span>
                                ${video.videographer.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                            </p>
                            <div class="flex gap-2 mt-3">
                                <span class="inline-block px-3 py-1 bg-gradient-to-r from-cyan-100 to-cyan-200 text-cyan-700 text-xs rounded-full font-semibold">${video.category}</span>
                                <span class="inline-block px-3 py-1 bg-gradient-to-r ${sourceColor} text-xs rounded-full capitalize font-semibold">${video.source}</span>
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
            openDownloadModal(videoData.id, videoData.videoUrl, videoData.videographer, videoData.duration, videoData.video_files, videoData.source);
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

        function openDownloadModal(videoId, videoUrl, videographer, duration, videoFiles, source) {
            selectedVideo = { id: videoId, videoUrl, videographer, duration, video_files: videoFiles, source };
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

                // Download video
                const link = document.createElement('a');
                link.href = selectedVideo.videoUrl;
                link.download = `${selectedVideo.source}-${selectedVideo.videographer}-${selectedVideo.id}.mp4`;
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

        // Close modal with Escape key
        document.addEventListener('keydown', e => { if(e.key === 'Escape') closeDownloadModal(); });

        // Initial load
        loadVideos();
    </script>
</x-app-layout>