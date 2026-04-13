<link type='text/css' href="{{ asset('front/css/slider.css') }}" rel='stylesheet' />
<script src="{{ asset('front/js/jquery.cycle2.caption2.min.js') }}"></script>

<style type="text/css">
    #aparatur_desa .cycle-pager span {
        height: 10px;
        width: 10px;
    }

    .cycle-slideshow {
        max-height: none;
        margin-bottom: 0;
        border: 0;
    }

    .cycle-next,
    .cycle-prev {
        mix-blend-mode: difference;
        cursor: pointer;
    }

    .cycle-slideshow img {
        max-width: 100%;
        height: auto;
    }

    .alert-info {
        padding: 15px;
        margin: 10px 0;
    }
</style>

<!-- Widget Aparatur Desa -->
<div class="modal-body">
    <div class="box box-info box-solid">
        <div class="box-body">
            @if (!empty($aparatur_desa['daftar_perangkat']) && is_array($aparatur_desa['daftar_perangkat']) && count($aparatur_desa['daftar_perangkat']) > 0)
                <div
                    id="aparatur_desa"
                    class="cycle-slideshow"
                    data-cycle-pause-on-hover="true"
                    data-cycle-fx="scrollHorz"
                    data-cycle-timeout="2000"
                    data-cycle-caption-plugin="caption2"
                    data-cycle-overlay-fx-out="slideUp"
                    data-cycle-overlay-fx-in="slideDown"
                    data-cycle-auto-height="4:6"
                >
                    @php
                        $useOverlay = getWidgetSetting('aparatur_desa', 'overlay') == true;
                    @endphp

                    @if ($useOverlay)
                        <span class="cycle-prev">
                            <img src="{{ asset('images/back_button.png') }}" alt="Previous">
                        </span>
                        <span class="cycle-next">
                            <img src="{{ asset('images/next_button.png') }}" alt="Next">
                        </span>
                        <div class="cycle-caption"></div>
                        <div class="cycle-overlay"></div>
                    @else
                        <!-- Pager untuk membuat indikator bulat pada slider -->
                        <span class="cycle-pager"></span>
                    @endif

                    @foreach ($aparatur_desa['daftar_perangkat'] as $index => $data)
                        @php
                            // Validate and sanitize data
                            $fotoUrl = !empty($data['foto']) ? $data['foto'] : asset('images/default-avatar.png');
                            $nama = !empty($data['nama']) ? htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8') : 'Tidak Ada Nama';
                            $jabatan = !empty($data['jabatan']) ? htmlspecialchars($data['jabatan'], ENT_QUOTES, 'UTF-8') : 'Tidak Ada Jabatan';
                        @endphp
                        
                        <img 
                            src="{{ $fotoUrl }}" 
                            alt="{{ $nama }}"
                            data-cycle-title="<span class='cycle-overlay-title'>{{ $nama }}</span>" 
                            data-cycle-desc="{{ $jabatan }}"
                            onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';"
                            loading="lazy"
                        >
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="fa fa-info-circle"></i> 
                    Data aparatur desa tidak tersedia
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    (function() {
        'use strict';

        $(document).ready(function() {
            // Initialize cycle slideshow with error handling
            const $slideshow = $('.cycle-slideshow');
            
            if ($slideshow.length > 0) {
                try {
                    // Check if cycle plugin is available
                    if (typeof $.fn.cycle !== 'function') {
                        console.error('jQuery Cycle2 plugin not loaded');
                        return;
                    }

                    // Initialize slideshow
                    $slideshow.cycle();
                    
                    // Add keyboard navigation
                    $(document).on('keydown', function(e) {
                        if ($slideshow.is(':visible')) {
                            // Left arrow key
                            if (e.keyCode === 37) {
                                $slideshow.cycle('prev');
                            }
                            // Right arrow key
                            else if (e.keyCode === 39) {
                                $slideshow.cycle('next');
                            }
                        }
                    });

                    // Handle pause/resume on visibility change
                    $(document).on('visibilitychange', function() {
                        if (document.hidden) {
                            $slideshow.cycle('pause');
                        } else {
                            $slideshow.cycle('resume');
                        }
                    });

                } catch (error) {
                    console.error('Error initializing aparatur slideshow:', error);
                    
                    // Show error message to user
                    $slideshow.parent().html(
                        '<div class="alert alert-warning text-center">' +
                        '<i class="fa fa-exclamation-triangle"></i> ' +
                        'Gagal menampilkan slideshow aparatur desa' +
                        '</div>'
                    );
                }
            }
        });
    })();
</script>