<div x-data="{ loading: true, onLoading() { setTimeout(() => { this.loading = false }, 1500) } }" x-init="onLoading()">
    <div class="fixed inset-0 bg-white z-[9999] flex justify-center items-center" x-show="loading" x-transition.opacity>
        <div class="relative flex items-center justify-center" style="width: 120px; height: 120px;">

        <!-- Ring yang berputar dan zoom (INLINE CSS) -->
            <div style="
            width: 100px;
            height: 100px;
            border: 4px solid #006A82;
            border-top: 4px solid transparent;
            border-radius: 50%;
            position: absolute;
            animation: spinZoom 1.5s linear infinite;"></div>

            <!-- Logo -->
            <div style="
            width: 64px;
            height: 64px;
            background: white;
            border-radius: 9999px;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            <img src="{{ gambar_desa($desa['logo']) }}"
                    alt="Logo {{ ucfirst(setting('sebutan_desa')) . ' ' . ucwords($desa['nama_desa']) }}"
                    style="width: 40px; height: 40px; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spinZoom {
        0% {
            transform: rotate(0deg) scale(1);
            opacity: 1;
        }

        50% {
            transform: rotate(180deg) scale(1.2);
            opacity: 0.6;
        }

        100% {
            transform: rotate(360deg) scale(1);
            opacity: 1;
        }
    }
</style>