# dev-seed — data awal untuk pengembangan

Kumpulan skrip **data dasar (seed) khusus pengembangan**. Di-*track* ke branch agar
bisa dipakai developer lain, **tetapi tidak ikut rilis produksi**: folder ini
`export-ignore` di [`.gitattributes`](../.gitattributes), jadi `git archive`
(pembuat arsip rilis) tidak menyertakannya. `*.sql` di sini juga dikecualikan dari
aturan `*.sql` [`.gitignore`](../.gitignore) agar tetap ter-track.

> Bukan pengganti installer/seeder resmi (`opensid:desa-baru`, `StrukturTabelSeeder`,
> `DataAwalSeeder`). Ini hanya untuk cepat mengisi data agar sebuah fitur bisa dicoba.

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

### Menjalankan

```bash
# Umum (situs verifikasi lokal)
mysql -h127.0.0.1 -uroot opensid_umum_verify < dev-seed/anjungan_basic_data.sql
# Premium
mysql -h127.0.0.1 -uroot opensid             < dev-seed/anjungan_basic_data.sql
```

### Melihat kios di browser

Modul Anjungan harus terpasang (`Modules/Anjungan/`) dan **entitlement aktif**
(situs mode `development`, atau langganan Anjungan aktif).

1. Buka `.../index.php/layanan-mandiri/masuk`.
2. DevTools → Console:
   `localStorage.setItem('anjungan_uuid','11111111-1111-1111-1111-111111111111')`
3. Muat ulang halaman → tombol **ANJUNGAN** muncul (bukti `cek-anjungan-ajax` mengenali
   perangkat & membuat sesi kios).
4. Klik **ANJUNGAN** (atau buka `.../index.php/anjungan-mandiri`) → landing kios tampil
   (artikel, galeri, menu, teks berjalan).

Tanpa langkah 2–3, `/anjungan-mandiri` mengalihkan ke `layanan-mandiri/beranda`
(perangkat kios tidak dikenali) — itu perilaku benar.
