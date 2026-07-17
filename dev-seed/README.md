# dev-seed — data awal untuk pengembangan

Kumpulan skrip **data dasar (seed) khusus pengembangan**. Di-*track* ke branch agar
bisa dipakai developer lain, **tetapi tidak ikut rilis produksi**: folder ini
`export-ignore` di [`.gitattributes`](../.gitattributes), jadi `git archive`
(pembuat arsip rilis) tidak menyertakannya. `*.sql` di sini juga dikecualikan dari
aturan `*.sql` [`.gitignore`](../.gitignore) agar tetap ter-track.

> Bukan pengganti installer/seeder resmi (`opensid:desa-baru`, `StrukturTabelSeeder`,
> `DataAwalSeeder`). Ini hanya untuk cepat mengisi data agar sebuah fitur bisa dicoba.

---

## `anjungan_basic_data.sql` — menguji modul Anjungan (kios Layanan Mandiri)

Mengisi data minimum agar kios Anjungan bisa dijalankan & dilihat:

| Data | Isi |
|---|---|
| Perangkat Anjungan | 1 kios, `uuid=11111111-1111-1111-1111-111111111111`, `tipe=[1]` (ANJUNGAN), aktif |
| Wilayah / Keluarga / Warga | 1 dusun, 1 keluarga, 3 penduduk |
| Artikel | 3 artikel terbit (muncul di arsip kios) |
| Galeri | 2 gambar slider + set `setting.anjungan_slide` |
| Setting | `anjungan_teks_berjalan` (teks berjalan kios) |

Idempoten (ID tetap rentang `990001`, hapus-lalu-sisip) — aman dijalankan berulang.
`config_id` diambil dinamis dari tabel `config` (portabel lintas install).

### 1. Menjalankan seeder

Butuh klien `mysql` di PATH. Ganti nama DB sesuai install:

```bash
# Umum (situs verifikasi lokal)
mysql -h127.0.0.1 -uroot opensid_umum_verify < dev-seed/anjungan_basic_data.sql
# Premium
mysql -h127.0.0.1 -uroot opensid             < dev-seed/anjungan_basic_data.sql
```

Baris terakhir skrip menampilkan ringkasan hitungan sebagai konfirmasi, mis.:

```
kios  warga  artikel  galeri
1     3      3        2
```

### 2. Prasyarat verifikasi

- **Modul terpasang:** folder `Modules/Anjungan/` ada (add-on di-*drop*, lalu
  `composer dump-autoload`). Cek cepat: menu **Anjungan** muncul di admin (`/siteman`).
- **Entitlement aktif:** kios hanya tampil bila `cek_anjungan()` = true, yaitu situs
  mode **`development`** ATAU langganan Anjungan aktif. (Situs verifikasi lokal sudah
  `development` — lihat README-LOKAL-verifikasi.md induk.)

### 3. Verifikasi fitur Anjungan di Layanan Mandiri (browser)

Bedakan **terpasang** (modul terpaut ke situs publik) vs **berjalan** (perangkat kios
mengaktifkan landing kios).

**A. Terpasang** — tanpa perangkat kios:

1. Buka `.../index.php/layanan-mandiri`
   → **dialihkan ke `/anjungan-mandiri`** (seam `MandiriEntryResolver` diisi modul;
   tanpa modul akan tetap di halaman masuk biasa).
2. Karena browser belum jadi kios, `/anjungan-mandiri` **balik ke
   `layanan-mandiri/beranda`** — ini perilaku BENAR (perangkat kios tak dikenali).
3. Buka `.../index.php/layanan-mandiri/masuk` → halaman memuat skrip modul
   **`cek-anjungan-ajax`** (logika tombol ANJUNGAN). Ini bukti seam login terpaut.

**B. Berjalan** — aktifkan perangkat kios yang di-seed:

1. Di halaman `.../layanan-mandiri/masuk`, buka **DevTools → Console**:
   ```js
   localStorage.setItem('anjungan_uuid','11111111-1111-1111-1111-111111111111')
   ```
2. **Muat ulang** halaman → tombol hijau **ANJUNGAN** muncul.
   (AJAX ke `layanan-mandiri/cek-anjungan-ajax` mencocokkan `uuid` ke perangkat
   yang di-seed, membuat sesi kios, dan set cookie `anjungan_uuid`.)
3. Klik **ANJUNGAN** (atau buka `.../index.php/anjungan-mandiri`)
   → **landing kios tampil**: artikel terkini/populer, galeri slider, menu Anjungan,
   teks berjalan, tanggal.
4. Coba fitur: menu **surat / permohonan** (memakai 3 warga yang di-seed), pencarian
   penduduk, dsb.

**Verifikasi lewat terminal** (opsional, tanpa browser):

```bash
UUID=11111111-1111-1111-1111-111111111111
# Dengan cookie perangkat → landing kios (HTTP 200, bukan redirect):
curl -sk -b "anjungan_uuid=$UUID" https://test-umum.test/index.php/anjungan-mandiri \
  -o /tmp/kios.html -w "%{http_code}\n"
grep -c "Selamat Datang di Anjungan Desa" /tmp/kios.html   # >0 = artikel seed tampil
```

> **Artikel seed tak muncul di kios?** Kios menyaring arsip lewat setting
> `anjungan_artikel` (daftar id kategori). Bila setting itu terisi dan tak memuat
> kategori artikel seed, artikel tak tampil (kios tetap jalan). Solusi: kosongkan
> `anjungan_artikel` (tampilkan semua) atau tambahkan kategori artikel seed ke daftar.
> Di verify-Umum setting ini `NULL` → artikel seed langsung tampil.

### 4. Membersihkan / mengulang

Skrip idempoten — cukup jalankan ulang untuk mengembalikan data seed. Untuk menghapus:
hapus baris ber-`id` di rentang `990001` pada tabel `anjungan`, `tweb_penduduk`,
`tweb_keluarga`, `tweb_wil_clusterdesa`, `artikel`, `gambar_gallery`, dan kosongkan
kembali dua `setting_aplikasi` (`anjungan_slide`, `anjungan_teks_berjalan`) bila perlu.

### 5. Uji "modul absen" (degradasi mulus)

Data seed boleh tetap ada; lepas saja modulnya:

```bash
rm -rf Modules/Anjungan
/usr/local/opt/php@8.2/bin/php "$(command -v composer)" dump-autoload   # Umum PHP 8.2
```

Muat ulang → `layanan-mandiri` jatuh ke `.../masuk` biasa (tanpa jejak Anjungan),
tetap HTTP 200. Membuktikan core tak bergantung pada modul.
