/**
 * Articles Page JavaScript
 * Extracted from partials/articles.blade.php for better maintainability
 */

document.addEventListener('DOMContentLoaded', function () {
    // ========================================
    // PEMERINTAH DESA POPUP
    // ========================================

    const btnPemerintah = document.getElementById('btn-pemerintah-desa');
    const popup = document.getElementById('pemerintah-popup');
    const pemerintahList = document.getElementById('pemerintah-list');

    // Get configuration from global object
    const config = window.ARTICLES_CONFIG || {};
    const desaLabel = config.desaLabel || 'Desa';
    const desaNama = config.desaNama || '';

    let pemerintahData = [];

    // Make closePopup global so it can be called from overlay onclick
    window.closePopup = function () {
        console.log('Closing popup...');
        popup.classList.remove('show');
        setTimeout(() => {
            popup.classList.add('hidden');
            document.body.style.overflow = '';
        }, 400);
    };

    function openPopup() {
        popup.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => popup.classList.add('show'), 10);

        // Fetch data
        fetch('/internal_api/pemerintah')
            .then(response => response.json())
            .then(data => {
                console.log('Pemerintah Desa Data:', data);
                pemerintahData = data.data;
                renderPemerintah(pemerintahData);
            })
            .catch(error => {
                console.error('Error fetching pemerintah:', error);
                showError();
            });
    }

    function showError() {
        pemerintahList.innerHTML = `
            <div class="flex items-center justify-center min-h-[400px] empty-state">
                <div class="text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Gagal memuat data</p>
                    <p class="text-sm text-gray-400 mt-2">Silakan coba lagi nanti</p>
                </div>
            </div>
        `;
    }

    function renderPemerintah(data) {
        if (!data || data.length === 0) {
            pemerintahList.innerHTML = `
                <div class="flex items-center justify-center min-h-[400px] empty-state">
                    <div class="text-center px-4">
                        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="text-gray-500 font-medium text-lg">Data tidak tersedia</p>
                        <p class="text-sm text-gray-400 mt-2">Belum ada data pemerintah desa</p>
                    </div>
                </div>
            `;
            return;
        }

        let html = `
            <div class="w-full px-4 sm:px-6 py-6 sm:py-8">
                <div class="text-center mb-8 sm:mb-12">
                    <h2 class="text-xl sm:text-xl lg:text-2xl font-bold text-gray-900">
                        Aparatur Desa
                    </h2>
                    <div class="inline-block bg-green-600 px-6 py-2.5 rounded-full shadow-lg">
                        <span class="text-white font-bold text-sm px-1.5 sm:text-base">
                            Pemerintah ${desaLabel} ${desaNama}
                        </span>
                    </div>
                </div>
                
                <div class="flex flex-wrap justify-center gap-4 sm:gap-6 lg:gap-8 pb-8">
        `;

        data.forEach((item, index) => {
            const attr = item.attributes;
            const nama = attr.pamong_nama || attr.nama || '-';
            const jabatan = attr.jabatan?.nama || attr.nama_jabatan || '-';
            const foto = attr.foto || '';
            const statusKehadiran = attr.status_kehadiran || 'Tidak diketahui';
            const isHadir = statusKehadiran.toLowerCase().includes('hadir') && !statusKehadiran.toLowerCase().includes('belum');

            const statusColor = isHadir ? 'bg-green-500' : 'bg-yellow-400';
            const statusText = isHadir ? 'text-green-600' : 'text-gray-500';
            const statusBg = isHadir ? 'bg-green-50' : 'bg-yellow-50';
            const statusDotClass = isHadir ? 'status-dot-green' : '';

            html += `
                <div class="pemerintah-item group w-24 sm:w-28 lg:w-36 flex-shrink-0" style="animation-delay: ${index * 0.04}s">
                    
                    <div class="relative w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 mx-auto mb-3 sm:mb-4">
                        <div class="w-full h-full rounded-full overflow-hidden bg-gray-100 ring-2 ring-gray-200 group-hover:ring-green-500 transition-all duration-300 shadow-md group-hover:shadow-xl">
                            <img src="${foto}" 
                                 alt="${nama}" 
                                 class="w-full h-full object-cover grayscale-[15%] group-hover:grayscale-0"
                                 onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(nama)}&background=f1f5f9&color=64748b&size=200&bold=true'">
                        </div>
                    </div>

                    <div class="w-full px-1">
                        <h4 class="font-bold text-gray-900 text-xs sm:text-sm lg:text-base leading-tight line-clamp-2 min-h-[2.2rem] sm:min-h-[2.5rem] group-hover:text-green-700 transition-colors text-center mb-1">
                            ${nama}
                        </h4>
                        <p class="text-[10px] sm:text-xs lg:text-sm font-semibold text-green-700 tracking-tight mb-2 sm:mb-3 line-clamp-2 text-center">
                            ${jabatan}
                        </p>
                        
                        <div class="flex items-center justify-center gap-1.5 px-2 py-1.5 ${statusBg} rounded-full status-badge">
                            <span class="w-2 h-2 rounded-full ${statusColor} ${statusDotClass}"></span>
                            <span class="text-[10px] sm:text-xs ${statusText} font-medium">
                                ${statusKehadiran}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        });

        html += `
                </div>
            </div>
        `;

        pemerintahList.innerHTML = html;

        // Force scroll reset to top
        pemerintahList.scrollTop = 0;

        console.log('Rendered', data.length, 'pemerintah items');
        console.log('Scroll height:', pemerintahList.scrollHeight, 'Client height:', pemerintahList.clientHeight);
    }

    // Event Listeners
    if (btnPemerintah) {
        btnPemerintah.addEventListener('click', function (e) {
            e.preventDefault();
            console.log('Opening pemerintah popup...');
            openPopup();
        });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        if (popup && !popup.classList.contains('hidden')) {
            if (e.key === 'Escape') {
                e.preventDefault();
                window.closePopup();
            }
        }
    });

    // ========================================
    // MOBILE CAROUSEL
    // ========================================

    const carousel = document.getElementById('mobile-articles-carousel');
    const prevBtn = document.getElementById('carousel-prev');
    const nextBtn = document.getElementById('carousel-next');
    const dots = document.querySelectorAll('.carousel-dot');

    if (!carousel || dots.length === 0) return;

    let currentSlide = 0;
    const slideWidth = 336; // 320px + 16px gap (w-80 + gap-4)
    const totalSlides = dots.length;

    // Throttle function for performance
    function throttle(func, limit) {
        let inThrottle;
        return function () {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }

    // Update carousel position and indicators
    function updateCarousel() {
        if (!carousel) return;

        carousel.scrollTo({
            left: currentSlide * slideWidth,
            behavior: 'smooth'
        });

        // Update dot indicators
        dots.forEach((dot, index) => {
            const isActive = index === currentSlide;
            dot.style.backgroundColor = isActive ? '#16a34a' : '#d1d5db';
            dot.style.transform = isActive ? 'scale(1.2)' : 'scale(1)';
            dot.setAttribute('aria-selected', isActive);
        });

        // Update button states and accessibility
        if (prevBtn) {
            const isDisabled = currentSlide === 0;
            prevBtn.style.opacity = isDisabled ? '0.5' : '0.9';
            prevBtn.disabled = isDisabled;
            prevBtn.setAttribute('aria-disabled', isDisabled);
        }
        if (nextBtn) {
            const isDisabled = currentSlide === totalSlides - 1;
            nextBtn.style.opacity = isDisabled ? '0.5' : '0.9';
            nextBtn.disabled = isDisabled;
            nextBtn.setAttribute('aria-disabled', isDisabled);
        }
    }

    // Navigation functions
    function goToPrevSlide() {
        if (currentSlide > 0) {
            currentSlide--;
            updateCarousel();
        }
    }

    function goToNextSlide() {
        if (currentSlide < totalSlides - 1) {
            currentSlide++;
            updateCarousel();
        }
    }

    function goToSlide(index) {
        if (index >= 0 && index < totalSlides) {
            currentSlide = index;
            updateCarousel();
        }
    }

    // Event listeners
    if (prevBtn) {
        prevBtn.addEventListener('click', goToPrevSlide);
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', goToNextSlide);
    }

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => goToSlide(index));
    });

    // Touch and swipe support
    let touchState = {
        startX: 0,
        currentX: 0,
        isDragging: false,
        startScrollLeft: 0
    };

    function handleTouchStart(e) {
        touchState.startX = e.touches[0].clientX;
        touchState.startScrollLeft = carousel.scrollLeft;
        touchState.isDragging = true;
        carousel.classList.add('dragging');
    }

    function handleTouchMove(e) {
        if (!touchState.isDragging) return;

        touchState.currentX = e.touches[0].clientX;
        const diffX = touchState.startX - touchState.currentX;
        carousel.scrollLeft = touchState.startScrollLeft + diffX;
    }

    function handleTouchEnd() {
        if (!touchState.isDragging) return;

        touchState.isDragging = false;
        carousel.classList.remove('dragging');

        const diffX = touchState.startX - touchState.currentX;
        const threshold = slideWidth / 3;

        if (Math.abs(diffX) > threshold) {
            if (diffX > 0 && currentSlide < totalSlides - 1) {
                goToNextSlide();
            } else if (diffX < 0 && currentSlide > 0) {
                goToPrevSlide();
            }
        } else {
            updateCarousel(); // Snap back to current slide
        }

        // Reset touch state
        Object.assign(touchState, {
            startX: 0,
            currentX: 0,
            startScrollLeft: 0
        });
    }

    carousel.addEventListener('touchstart', handleTouchStart, { passive: true });
    carousel.addEventListener('touchmove', handleTouchMove, { passive: true });
    carousel.addEventListener('touchend', handleTouchEnd, { passive: true });

    // Mouse drag support (for desktop testing)
    let mouseState = {
        isDown: false,
        startX: 0,
        scrollLeft: 0
    };

    carousel.addEventListener('mousedown', function (e) {
        mouseState.isDown = true;
        mouseState.startX = e.pageX - carousel.offsetLeft;
        mouseState.scrollLeft = carousel.scrollLeft;
        carousel.style.cursor = 'grabbing';
        carousel.classList.add('dragging');
        e.preventDefault();
    });

    carousel.addEventListener('mouseleave', function () {
        mouseState.isDown = false;
        carousel.style.cursor = 'grab';
        carousel.classList.remove('dragging');
    });

    carousel.addEventListener('mouseup', function () {
        mouseState.isDown = false;
        carousel.style.cursor = 'grab';
        carousel.classList.remove('dragging');
    });

    carousel.addEventListener('mousemove', function (e) {
        if (!mouseState.isDown) return;
        e.preventDefault();
        const x = e.pageX - carousel.offsetLeft;
        const walk = (x - mouseState.startX) * 2;
        carousel.scrollLeft = mouseState.scrollLeft - walk;
    });

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        if (window.innerWidth >= 640) return; // Only on mobile

        switch (e.key) {
            case 'ArrowLeft':
                if (currentSlide > 0) {
                    goToPrevSlide();
                    e.preventDefault();
                }
                break;
            case 'ArrowRight':
                if (currentSlide < totalSlides - 1) {
                    goToNextSlide();
                    e.preventDefault();
                }
                break;
        }
    });

    // Update current slide based on scroll position (throttled for performance)
    const handleScroll = throttle(function () {
        const newSlide = Math.round(carousel.scrollLeft / slideWidth);
        if (newSlide !== currentSlide && newSlide >= 0 && newSlide < totalSlides) {
            currentSlide = newSlide;
            dots.forEach((dot, index) => {
                const isActive = index === currentSlide;
                dot.style.backgroundColor = isActive ? '#16a34a' : '#d1d5db';
                dot.style.transform = isActive ? 'scale(1.2)' : 'scale(1)';
                dot.setAttribute('aria-selected', isActive);
            });
        }
    }, 100);

    carousel.addEventListener('scroll', handleScroll);

    // Auto-play functionality with proper cleanup
    let autoPlayState = {
        interval: null,
        isPlaying: false,
        inactivityTimer: null
    };

    function startAutoPlay() {
        if (autoPlayState.isPlaying) return;

        autoPlayState.isPlaying = true;
        autoPlayState.interval = setInterval(() => {
            if (currentSlide < totalSlides - 1) {
                goToNextSlide();
            } else {
                goToSlide(0);
            }
        }, 4000);
    }

    function stopAutoPlay() {
        if (autoPlayState.interval) {
            clearInterval(autoPlayState.interval);
            autoPlayState.interval = null;
            autoPlayState.isPlaying = false;
        }
    }

    function resetInactivityTimer() {
        clearTimeout(autoPlayState.inactivityTimer);
        stopAutoPlay();

        autoPlayState.inactivityTimer = setTimeout(() => {
            if (window.innerWidth < 640 && document.visibilityState === 'visible') {
                startAutoPlay();
            }
        }, 2000);
    }

    // Track user interactions for auto-play
    const interactionEvents = ['touchstart', 'mousedown', 'click', 'keydown'];
    interactionEvents.forEach(event => {
        carousel.addEventListener(event, resetInactivityTimer);
    });

    // Pause auto-play when page is not visible
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            stopAutoPlay();
        } else {
            resetInactivityTimer();
        }
    });

    // Initialize carousel
    updateCarousel();
    resetInactivityTimer();

    // Cleanup on page unload
    window.addEventListener('beforeunload', function () {
        stopAutoPlay();
        clearTimeout(autoPlayState.inactivityTimer);
    });

    // Article section scroll functionality
    function scrollToArticles() {
        const articlesSection = document.getElementById('articles-section');
        if (articlesSection) {
            const offsetTop = articlesSection.offsetTop - 100;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    }

    // Handle scroll to articles from pagination
    if (sessionStorage.getItem('scrollToArticles') === 'true') {
        sessionStorage.removeItem('scrollToArticles');
        setTimeout(scrollToArticles, 300);
    }

    // Handle page parameter
    const urlParams = new URLSearchParams(window.location.search);
    const currentPage = urlParams.get('page');

    if (currentPage && currentPage !== '1') {
        setTimeout(scrollToArticles, 300);
    }

    // Handle direct anchor links
    if (window.location.hash === '#articles-section') {
        setTimeout(scrollToArticles, 300);
    }
});
