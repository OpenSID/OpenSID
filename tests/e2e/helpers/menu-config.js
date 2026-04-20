/**
 * Konfigurasi menu admin OpenSID
 *
 * Berdasarkan data setting_modul dari database (aktif=1, hidden=0 atau 1)
 * Digunakan sebagai source of truth untuk semua admin menu tests.
 *
 * Struktur setiap menu:
 *   name       : Nama menu (label di sidebar)
 *   url        : URL path setelah BASE_URL (tanpa slash di awal)
 *   parent     : Nama menu parent (null jika top-level)
 *   hasTable   : Apakah halaman menggunakan DataTables (#tabeldata)
 *   hasTambah  : Apakah ada tombol Tambah/Add
 *   hasFilter  : Selector filter yang ada di halaman (array of CSS selector)
 *   skip       : Alasan skip test (null = tidak di-skip)
 */

// ---------------------------------------------------------------------------
// MENU UTAMA (Top-level, parent = 0)
// ---------------------------------------------------------------------------
export const PARENT_MENUS = [
    { name: 'Beranda',                    url: 'beranda' },
    { name: 'Kependudukan',               url: null },
    { name: 'Statistik',                  url: null },
    { name: 'Layanan Surat',              url: null },
    { name: 'Hubung Warga',               url: null },
    { name: 'Admin Web',                  url: null },
    { name: 'Layanan Mandiri',            url: null },
    { name: 'Sekretariat',                url: null },
    { name: 'Pembangunan',                url: 'admin_pembangunan' },
    { name: 'Buku Administrasi Desa',     url: null },
    { name: 'Lapak',                      url: 'lapak_admin' },
    { name: 'Kehadiran',                  url: null },
    { name: 'Pengaduan',                  url: 'pengaduan_admin' },
    { name: 'Buku Tamu',                  url: null },
    { name: 'PPID',                       url: null },
    { name: 'Prodeskel',                  url: null },
];

// ---------------------------------------------------------------------------
// SUB MENU — semua halaman yang dapat diakses
// ---------------------------------------------------------------------------
export const SUB_MENUS = [

    // ── Kependudukan ──────────────────────────────────────────────────────
    {
        name: 'Penduduk',            url: 'penduduk',          parent: 'Kependudukan',
        hasTable: true,  hasTambah: true,
        hasFilter: ['#status', '#sex', '#agama', '#pendidikan', '#pekerjaan', '#kewarganegaraan'],
    },
    {
        name: 'Keluarga',            url: 'keluarga',          parent: 'Kependudukan',
        hasTable: true,  hasTambah: true,
        hasFilter: ['#status'],
    },
    {
        name: 'Rumah Tangga',        url: 'rtm',               parent: 'Kependudukan',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Kelompok',            url: 'kelompok/clear',    parent: 'Kependudukan',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Data Suplemen',       url: 'suplemen',          parent: 'Kependudukan',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Calon Pemilih',       url: 'dpt',               parent: 'Kependudukan',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Kategori Kelompok',   url: 'kelompok_master',   parent: 'Kependudukan',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Catatan Peristiwa',   url: 'penduduk_log',      parent: 'Kependudukan',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },

    // ── Statistik ─────────────────────────────────────────────────────────
    {
        name: 'Statistik Kependudukan', url: 'statistik',              parent: 'Statistik',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Laporan Bulanan',     url: 'laporan/clear',     parent: 'Statistik',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Laporan Kelompok Rentan', url: 'laporan_rentan/clear', parent: 'Statistik',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Laporan Penduduk',    url: 'laporan_penduduk',  parent: 'Statistik',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },

    // ── Layanan Surat ─────────────────────────────────────────────────────
    {
        name: 'Pengaturan Surat',    url: 'surat_master',      parent: 'Layanan Surat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Cetak Surat',         url: 'surat',             parent: 'Layanan Surat',
        hasTable: true,  hasTambah: false,
        hasFilter: ['#kategori'],
    },
    {
        name: 'Arsip Layanan',       url: 'keluar',            parent: 'Layanan Surat',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Daftar Persyaratan',  url: 'surat_mohon',       parent: 'Layanan Surat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Permohonan Surat',    url: 'permohonan_surat_admin', parent: 'Layanan Surat',
        hasTable: true,  hasTambah: false,
        hasFilter: ['#status'],
    },

    // ── Hubung Warga ──────────────────────────────────────────────────────
    {
        name: 'Kirim Pesan',         url: 'sms',               parent: 'Hubung Warga',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Daftar Kontak',       url: 'daftar_kontak',     parent: 'Hubung Warga',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },

    // ── Admin Web ─────────────────────────────────────────────────────────
    {
        name: 'Artikel',             url: 'web',               parent: 'Admin Web',
        hasTable: true,  hasTambah: true,
        hasFilter: ['#kategori'],
    },
    {
        name: 'Widget',              url: 'web_widget',        parent: 'Admin Web',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Menu',                url: 'menu',              parent: 'Admin Web',
        hasTable: true,  hasTambah: true,
        hasFilter: ['#status'],
    },
    {
        name: 'Komentar',            url: 'komentar',          parent: 'Admin Web',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Galeri',              url: 'gallery',           parent: 'Admin Web',
        hasTable: true,  hasTambah: true,
        hasFilter: ['#kategori'],
    },
    {
        name: 'Media Sosial',        url: 'sosmed',            parent: 'Admin Web',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Slider',              url: 'slider',            parent: 'Admin Web',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Teks Berjalan',       url: 'teks_berjalan',     parent: 'Admin Web',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Kategori',            url: 'kategori',          parent: 'Admin Web',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Pengunjung',          url: 'pengunjung',        parent: 'Admin Web',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Tema',                url: 'theme',             parent: 'Admin Web',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Sinergi Program',     url: 'sinergi_program',   parent: 'Admin Web',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Pengaturan Web',      url: 'setting_web',       parent: 'Admin Web',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },

    // ── Layanan Mandiri ───────────────────────────────────────────────────
    {
        name: 'Kotak Pesan',         url: 'mailbox',           parent: 'Layanan Mandiri',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Pendaftar Layanan Mandiri', url: 'mandiri',     parent: 'Layanan Mandiri',
        hasTable: true,  hasTambah: false,
        hasFilter: ['#status'],
    },
    {
        name: 'Gawai Layanan',       url: 'gawai_layanan',     parent: 'Layanan Mandiri',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Pengaturan Mandiri',  url: 'setting_mandiri',   parent: 'Layanan Mandiri',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Pendapat',            url: 'pendapat',          parent: 'Layanan Mandiri',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },

    // ── Sekretariat ───────────────────────────────────────────────────────
    {
        name: 'Informasi Publik',    url: 'dokumen',           parent: 'Sekretariat',
        hasTable: true,  hasTambah: true,
        hasFilter: ['#kategori'],
    },
    {
        name: 'Inventaris',          url: 'inventaris_master', parent: 'Sekretariat',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Klasifikasi Surat',   url: 'klasifikasi',       parent: 'Sekretariat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Laporan Inventaris',  url: 'laporan_inventaris', parent: 'Sekretariat',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Inventaris Tanah',    url: 'inventaris_tanah',  parent: 'Sekretariat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Inventaris Asset',    url: 'inventaris_asset',  parent: 'Sekretariat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Inventaris Gedung',   url: 'inventaris_gedung', parent: 'Sekretariat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Inventaris Jalan',    url: 'inventaris_jalan',  parent: 'Sekretariat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Inventaris Kontruksi', url: 'inventaris_kontruksi', parent: 'Sekretariat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Inventaris Peralatan', url: 'inventaris_peralatan', parent: 'Sekretariat',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },

    // ── Buku Administrasi Desa ────────────────────────────────────────────
    {
        name: 'Administrasi Umum',   url: 'bumindes_umum',              parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Administrasi Penduduk', url: 'bumindes_penduduk_induk',  parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Administrasi Pembangunan', url: 'bumindes_rencana_pembangunan', parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Buku Mutasi Penduduk', url: 'bumindes_penduduk_mutasi',  parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Buku Rekapitulasi Penduduk', url: 'bumindes_penduduk_rekapitulasi', parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Buku Penduduk Sementara', url: 'bumindes_penduduk_sementara', parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Buku KTP dan KK',     url: 'bumindes_penduduk_ktpkk',   parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Buku Tanah Kas Desa', url: 'bumindes_tanah_kas_desa',   parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Buku Tanah di Desa',  url: 'bumindes_tanah_desa',       parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Buku Inventaris Kekayaan Desa', url: 'bumindes_inventaris_kekayaan', parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Kader Pemberdayaan Masyarakat', url: 'bumindes_kader',  parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Buku Inventaris Hasil Pembangunan', url: 'bumindes_hasil_pembangunan', parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Arsip Desa',          url: 'bumindes_arsip',            parent: 'Buku Administrasi Desa',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Buku Kegiatan Pembangunan', url: 'bumindes_kegiatan_pembangunan', parent: 'Buku Administrasi Desa',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },

    // ── Kehadiran ─────────────────────────────────────────────────────────
    {
        name: 'Jam Kerja',           url: 'kehadiran_jam_kerja',       parent: 'Kehadiran',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Hari Libur',          url: 'kehadiran_hari_libur',      parent: 'Kehadiran',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Rekapitulasi Kehadiran', url: 'kehadiran_rekapitulasi', parent: 'Kehadiran',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Pengaduan Kehadiran', url: 'kehadiran_pengaduan',       parent: 'Kehadiran',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Alasan Keluar',       url: 'kehadiran_keluar',          parent: 'Kehadiran',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },

    // ── Buku Tamu ─────────────────────────────────────────────────────────
    {
        name: 'Data Tamu',           url: 'buku_tamu',                 parent: 'Buku Tamu',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Data Kepuasan',       url: 'buku_kepuasan',             parent: 'Buku Tamu',
        hasTable: true,  hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Data Pertanyaan',     url: 'buku_pertanyaan',           parent: 'Buku Tamu',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Data Keperluan',      url: 'buku_keperluan',            parent: 'Buku Tamu',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },

    // ── PPID ──────────────────────────────────────────────────────────────
    {
        name: 'Daftar Dokumen PPID', url: 'ppid/daftar-dokumen',       parent: 'PPID',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Permohonan Informasi', url: 'ppid/permohonan-informasi', parent: 'PPID',
        hasTable: true,  hasTambah: false,
        hasFilter: ['#status'],
    },
    {
        name: 'Permohonan Keberatan', url: 'ppid/permohonan-keberatan', parent: 'PPID',
        hasTable: true,  hasTambah: false,
        hasFilter: ['#status'],
    },
    {
        name: 'Jenis Dokumen PPID',  url: 'ppid/jenis-dokumen',        parent: 'PPID',
        hasTable: true,  hasTambah: true,
        hasFilter: [],
    },
    {
        name: 'Pengaturan PPID',     url: 'ppid/pengaturan',           parent: 'PPID',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },

    // ── Prodeskel ─────────────────────────────────────────────────────────
    {
        name: 'Prodeskel DDK',       url: 'prodeskel/ddk',             parent: 'Prodeskel',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Prodeskel Potensi',   url: 'prodeskel/potensi',         parent: 'Prodeskel',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Prodeskel Perkembangan', url: 'prodeskel/perkembangan', parent: 'Prodeskel',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Prodeskel Rekapitulasi', url: 'prodeskel/rekapitulasi', parent: 'Prodeskel',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
    {
        name: 'Prodeskel Pengaturan', url: 'prodeskel/pengaturan',     parent: 'Prodeskel',
        hasTable: false, hasTambah: false,
        hasFilter: [],
    },
];

// ---------------------------------------------------------------------------
// Semua halaman (gabungan parent yang memiliki URL + semua sub menu)
// ---------------------------------------------------------------------------
export const ALL_PAGES = [
    { name: 'Beranda', url: 'beranda', parent: null, hasTable: false, hasTambah: false, hasFilter: [] },
    { name: 'Pembangunan', url: 'admin_pembangunan', parent: null, hasTable: true, hasTambah: false, hasFilter: [] },
    { name: 'Lapak', url: 'lapak_admin', parent: null, hasTable: true, hasTambah: true, hasFilter: [] },
    { name: 'Pengaduan', url: 'pengaduan_admin', parent: null, hasTable: true, hasTambah: false, hasFilter: ['#status'] },
    ...SUB_MENUS,
];

// Hanya halaman yang menggunakan DataTables
export const DATATABLES_PAGES = ALL_PAGES.filter(p => p.hasTable);

// Hanya halaman yang memiliki tombol Tambah
export const TAMBAH_PAGES = ALL_PAGES.filter(p => p.hasTambah);

// Expected jumlah parent menu yang terlihat di sidebar
// (berdasarkan aktif=1, parent=0, hidden=0 atau 1, dan memiliki anak atau URL)
export const EXPECTED_PARENT_MENU_COUNT = PARENT_MENUS.length;
