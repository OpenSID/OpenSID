@php
    defined('BASEPATH') OR exit('No direct script access allowed');

    $url = site_url('artikel/' . buat_slug($post));
    $abstract = potong_teks(strip_tags($post['isi']), 120);

    $image_url = ($post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar']))
        ? AmbilFotoArtikel($post['gambar'], 'sedang')
        : theme_asset('images/placeholder.png');

    $gradient_classes = [
        'from-blue-500 to-teal-400',
        'from-pink-500 to-rose-500',
        'from-purple-600 to-indigo-700',
        'from-green-400 to-lime-500',
        'from-yellow-400 to-orange-500',
        'from-sky-400 to-cyan-300',
    ];
    $gradient = $gradient_classes[$key % count($gradient_classes)];
@endphp

<article class="rounded-none shadow-lg overflow-hidden flex flex-col h-full group transition-all duration-300 hover:shadow-2xl hover:-translate-y-1"
         :class="darkMode ? 'bg-gray-800' : 'bg-gradient-to-br <?= $gradient ?>'">
    
    <a href="<?= e($url) ?>">
        <figure class="h-48 w-full overflow-hidden">
            <img class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" src="<?= e($image_url) ?>" alt="<?= e($post['judul']) ?>">
        </figure>
    </a>

    <div class="p-5 flex flex-col flex-grow text-white">
        <?php if ($post['kategori']): ?>
            <span class="text-xs font-semibold mb-2 text-white/80 dark:text-cyan-400">
                <?= e($post['kategori']) ?>
            </span>
        <?php endif; ?>

        <h3 class="text-lg font-bold flex-grow">
            <a href="<?= e($url) ?>" class="hover:underline">
                <?= e($post['judul']) ?>
            </a>
        </h3>

        <p class="mt-2 text-sm line-clamp-3 text-white/90 dark:text-gray-300">
            <?= e($abstract) ?>
        </p>

        <div class="mt-4 pt-4 flex items-center justify-between border-t border-white/20 dark:border-gray-700">
            <span class="text-xs text-white/70 dark:text-gray-400">
                <i class="fas fa-user-edit mr-1"></i> <?= e($post['owner']) ?>
            </span>
            <span class="text-xs text-white/70 dark:text-gray-400">
                <i class="fas fa-calendar-alt mr-1"></i> <?= tgl_indo($post['tgl_upload']) ?>
            </span>
        </div>
    </div>
</article>