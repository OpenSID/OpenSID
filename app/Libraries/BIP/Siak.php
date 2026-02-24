<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries\BIP;

use App\Libraries\Import;
use App\Models\LogPenduduk;

/**
 * Impor data penduduk dari file ekspor SIAK (Sistem Informasi Administrasi Kependudukan).
 *
 * Format ekspor SIAK menggunakan label teks yang berbeda dari enum internal OpenSID
 * (misalnya "Cacat Rungu/Wicara" vs "DISABILITAS RUNGU/WICARA"), serta tidak memiliki
 * beberapa kolom yang diwajibkan validasi (dokumen_kitas, warganegara_id).
 *
 * Class ini menangani semua perbedaan tersebut agar impor bisa berjalan tanpa
 * mengharuskan pengguna mengubah file yang didapat langsung dari SIAK.
 */
class Siak extends Import
{
    private readonly array $kolomSiak;

    public function __construct()
    {
        parent::__construct();

        /**
         * Mapping tambahan khusus format SIAK.
         *
         * Parent class sudah membangun tabel konversi dari enum, tapi SIAK
         * menggunakan label yang berbeda untuk beberapa field. Mapping ini
         * di-merge ke tabel yang sudah ada — pola yang sama dipakai parent
         * untuk $kodeStatusDasar ("PINDAH DALAM NEGERI", "PINDAH LUAR NEGERI").
         *
         * Semua key ditulis lowercase karena konversiKode() melakukan
         * array_change_key_case() sebelum lookup.
         */

        // Cacat — SIAK: "Cacat X" / "Tidak Ada Kelainan"
        //         Enum: "DISABILITAS X" / "TIDAK DISABILITAS"
        $this->kodeCacat = array_merge($this->kodeCacat, [
            'tidak ada kelainan'     => 7, // TIDAK DISABILITAS
            'cacat fisik'            => 1, // DISABILITAS FISIK
            'cacat netra/buta'       => 2, // DISABILITAS NETRA/BUTA
            'cacat rungu/wicara'     => 3, // DISABILITAS RUNGU/WICARA
            'cacat mental/jiwa'      => 4, // DISABILITAS MENTAL/JIWA
            'cacat fisik dan mental' => 5, // DISABILITAS FISIK DAN MENTAL
            'cacat lainnya'          => 6, // DISABILITAS LAINNYA
        ]);

        // Status dasar — SIAK: "Tidak Valid" (tidak ada di StatusDasarEnum)
        $this->kodeStatusDasar = array_merge($this->kodeStatusDasar, [
            'tidak valid' => 9,
        ]);

        /**
         * Pemetaan field ke nomor kolom pada file ekspor SIAK.
         *
         * Kolom yang dilewati (tidak dipetakan):
         *   - 15: Kecamatan           — sudah tersimpan di konfigurasi desa
         *   - 16: Desa/Kel            — sudah tersimpan di konfigurasi desa
         *   - 22: Deskripsi Pekerjaan — tidak dipetakan ke field tersendiri
         *   - 25: Cetak KTP           — tidak digunakan
         *   - 26: Ganti KTP           — tidak digunakan
         *   - 27: KTP Berakhir        — tidak digunakan
         *   - 34: Petugas Registrasi  — tidak digunakan
         *   - 35: Tgl Ubah            — tidak digunakan
         *   - 36: Petugas Entri       — tidak digunakan
         */
        $this->kolomSiak = [
            'no_kk'             => 1,
            'nik'               => 2,
            'nama'              => 3,
            'status_dasar'      => 4,
            'tempatlahir'       => 5,
            'tanggallahir'      => 6,
            'sex'               => 7,
            'ayah_nik'          => 8,
            'nama_ayah'         => 9,
            'ibu_nik'           => 10,
            'nama_ibu'          => 11,
            'status_kawin'      => 12,
            'kk_level'          => 13,
            'agama_id'          => 14,
            'alamat'            => 17,
            'rw'                => 18,
            'rt'                => 19,
            'pendidikan_kk_id'  => 20,
            'pekerjaan_id'      => 21,
            'golongan_darah_id' => 23,
            'cacat_id'          => 24,
            'dokumen_pasport'   => 28,
            'akta_lahir'        => 29,
            'akta_perkawinan'   => 30,
            'tanggalperkawinan' => 31,
            'akta_perceraian'   => 32,
            'tanggalperceraian' => 33,
            'tgl_entri'         => 37,
        ];
    }

    /**
     * Proses impor data penduduk dari file ekspor SIAK.
     *
     * Setiap baris divalidasi terlebih dahulu. Baris yang valid ditulis ke
     * tabel wilayah, keluarga, dan penduduk. Penduduk dengan status MATI,
     * HILANG, atau PINDAH juga dicatat ke log_penduduk.
     *
     * @param mixed $data Objek pembaca Excel yang sudah dimuat
     */
    public function imporDataBip(mixed $data): void
    {
        $baris = $data->rowcount($sheetIndex = 0);

        if ($this->cariBarisPertama($data, $baris) <= 1) {
            set_session('error', 'Data penduduk gagal diimpor, data tidak tersedia.');

            return;
        }

        $gagalPenduduk = 0;
        $barisGagal    = '';
        $totalKeluarga = 0;
        $totalPenduduk = 0;

        // Baris pertama adalah header; data dimulai dari baris ke-2
        for ($i = 2; $i <= $baris; $i++) {
            // Lewati baris kosong (tiga kolom pertama semuanya kosong)
            if ($data->val($i, 1) == '' && $data->val($i, 2) == '' && $data->val($i, 3) == '') {
                continue;
            }

            $isiBaris      = $this->getIsiBaris($data, $i);
            $errorImpor    = null;
            $errorValidasi = $this->dataImportValid($isiBaris);

            if (empty($errorValidasi)) {
                $this->tulisWilayah($isiBaris);

                if ($this->tulisKeluarga($isiBaris)) {
                    $totalKeluarga++;
                }

                // tulisPenduduk() mengembalikan int ID jika berhasil,
                // atau string pesan error jika gagal
                $pendudukBaru = $this->tulisPenduduk($isiBaris);

                if (is_int($pendudukBaru)) {
                    $totalPenduduk++;

                    // Catat log untuk penduduk berstatus MATI (2), HILANG (3), atau PINDAH (4)
                    if (in_array($isiBaris['status_dasar'], ['2', '3', '4'])) {
                        $this->tulisLogPenduduk($isiBaris, $pendudukBaru);
                    }
                } else {
                    // Jika tulisPenduduk gagal, ambil pesan error dan tampilkan di hasil impor
                    $errorImpor = $pendudukBaru;
                }
            } else {
                $errorImpor = $errorValidasi;
            }

            if ($errorImpor) {
                $gagalPenduduk++;
                $barisGagal .= "{$i} ({$errorImpor})<br>";
            }
        }

        set_session('pesan_impor', [
            'gagal'          => $gagalPenduduk,
            'total_keluarga' => $totalKeluarga,
            'total_penduduk' => $totalPenduduk,
            'baris'          => $gagalPenduduk === 0 ? 'tidak ada data yang gagal diimpor.' : $barisGagal,
        ]);

        set_session('success', 'Data penduduk berhasil diimpor');
    }

    // =========================================================================
    // Private — Helper
    // =========================================================================

    /**
     * Temukan nomor baris pertama yang mengandung data.
     *
     * Baris header (baris 1) dan baris kosong dilewati. Mengembalikan 0
     * jika sheet tidak memiliki data sama sekali.
     *
     * @param mixed $data
     */
    private function cariBarisPertama($data, int $baris): int
    {
        if ($baris <= 1) {
            return 0;
        }

        for ($i = 2; $i <= $baris; $i++) {
            if ($data->val($i, 1) == '' && $data->val($i, 2) == '' && $data->val($i, 3) == '') {
                continue;
            }

            return $i;
        }

        return 1;
    }

    /**
     * Pisahkan nama dusun dari string alamat format SIAK.
     *
     * SIAK menyertakan keyword dusun di dalam kolom Alamat dengan berbagai
     * variasi penulisan, misalnya: "JL. MERDEKA DSN LIWET", "RT 01 DUSUN. SARI",
     * "DS. PRAPAT", "Dsn JIWET", "Dusun MBABRIK".
     *
     * Method ini mengekstrak nama dusun lalu mengembalikan alamat bersih
     * tanpa bagian dusun tersebut. Jika keyword tidak ditemukan, seluruh
     * string alamat digunakan sebagai fallback untuk field dusun.
     *
     * @param string $alamat Nilai mentah kolom Alamat dari file SIAK
     *
     * @return array{alamat: string, dusun: string}
     */
    private function pisahAlamatDusun(string $alamat): array
    {
        // Cocokkan keyword DUSUN/DSN/DS (dengan atau tanpa titik) diikuti nama dusun.
        // Nama dusun diambil hingga koma, slash, atau akhir string.
        $pattern = '/\b(?:DUSUN|DSN|DS)\.?\s+([^\s,\/]+(?:\s+[^\s,\/]+)*)/i';

        if (preg_match($pattern, $alamat, $matches)) {
            $dusun        = trim($matches[1]);
            $alamatBersih = rtrim(trim(preg_replace($pattern, '', $alamat)), ' ,/-');

            return [
                'alamat' => $alamatBersih !== '' ? $alamatBersih : $alamat,
                'dusun'  => $dusun,
            ];
        }

        // Keyword tidak ditemukan — gunakan seluruh alamat sebagai fallback
        return [
            'alamat' => $alamat,
            'dusun'  => $alamat,
        ];
    }

    /**
     * Konversi nilai dari SIAK ke integer kode enum, dengan guard is_numeric.
     *
     * konversiKode() mengembalikan nilai aslinya (string) jika tidak ditemukan
     * di tabel referensi — tidak cukup hanya cek falsy. Guard ini memastikan
     * hanya nilai yang benar-benar numerik yang diteruskan ke database.
     *
     * @param array    $kode    Tabel konversi (label => kode integer)
     * @param mixed    $nilai   Nilai mentah dari file SIAK
     * @param int|null $default Nilai default jika konversi gagal (null = field nullable)
     */
    private function konversiKeInt(array $kode, mixed $nilai, ?int $default = null): ?int
    {
        $hasil = $this->konversiKode($kode, $nilai);

        return is_numeric($hasil) ? (int) $hasil : $default;
    }

    /**
     * Baca dan normalisasi seluruh field dari satu baris Excel.
     *
     * @param mixed $data Objek pembaca Excel
     * @param int   $i    Nomor baris yang sedang diproses
     *
     * @return array Array asosiatif field penduduk siap pakai
     */
    private function getIsiBaris($data, int $i): array
    {
        $k        = $this->kolomSiak;
        $isiBaris = [];

        // Alamat & dusun
        $pecahAlamat        = $this->pisahAlamatDusun(trim((string) $data->val($i, $k['alamat'])));
        $isiBaris['alamat'] = $pecahAlamat['alamat'];
        $isiBaris['dusun']  = $pecahAlamat['dusun'];

        // RW & RT — SIAK terkadang menyertakan tanda kutip di depan nilai
        $isiBaris['rw'] = ltrim(trim((string) $data->val($i, $k['rw'])), "'");
        $isiBaris['rt'] = ltrim(trim((string) $data->val($i, $k['rt'])), "'");

        // Nama — buang karakter selain huruf, koma, titik, apostrof, dan tanda hubung
        $isiBaris['nama'] = preg_replace("/[^a-zA-Z,\\.'-]/", ' ', trim((string) $data->val($i, $k['nama'])));

        // No KK & NIK — data Disdukcapil kadang mengandung karakter non-printable
        $isiBaris['no_kk'] = preg_replace('/[^0-9]/', '', trim((string) $data->val($i, $k['no_kk'])));
        $isiBaris['nik']   = buang_nondigit($data->val($i, $k['nik']));

        // Data pribadi
        $isiBaris['sex']          = $this->konversiKeInt($this->kodeSex, $data->val($i, $k['sex']));
        $isiBaris['tempatlahir']  = trim((string) $data->val($i, $k['tempatlahir']));
        $isiBaris['tanggallahir'] = $this->formatTanggal($data->val($i, $k['tanggallahir']));
        $isiBaris['agama_id']     = $this->konversiKeInt($this->kodeAgama, $data->val($i, $k['agama_id']));
        $isiBaris['status_dasar'] = $this->konversiKeInt($this->kodeStatusDasar, $data->val($i, $k['status_dasar']));

        // Pendidikan & pekerjaan
        $isiBaris['pendidikan_kk_id'] = $this->konversiKeInt($this->kodePendidikanKK, $data->val($i, $k['pendidikan_kk_id']));
        $isiBaris['pekerjaan_id']     = $this->konversiKeInt($this->kodePekerjaan, $this->normalkanData($data->val($i, $k['pekerjaan_id'])));

        // Status kawin & hubungan dalam KK
        $isiBaris['status_kawin'] = $this->konversiKeInt($this->kodeStatus, $data->val($i, $k['status_kawin']));
        $isiBaris['kk_level']     = $this->konversiKeInt($this->kodeHubungan, $data->val($i, $k['kk_level']));

        // Golongan darah
        $isiBaris['golongan_darah_id'] = $this->konversiKeInt($this->kodeGolonganDarah, $data->val($i, $k['golongan_darah_id']));

        // Cacat — nullable, null jika nilai tidak dikenal
        $isiBaris['cacat_id'] = $this->konversiKeInt($this->kodeCacat, $data->val($i, $k['cacat_id']), null);

        // Kewarganegaraan — tidak ada di format SIAK; default WNI (1)
        $isiBaris['warganegara_id'] = 1;

        // Nama ayah & ibu — default '-' jika kosong
        $namaAyah              = trim((string) $data->val($i, $k['nama_ayah']));
        $isiBaris['nama_ayah'] = $namaAyah !== '' ? $namaAyah : '-';

        $namaIbu              = trim((string) $data->val($i, $k['nama_ibu']));
        $isiBaris['nama_ibu'] = $namaIbu !== '' ? $namaIbu : '-';

        // NIK orang tua
        $isiBaris['ayah_nik'] = buang_nondigit($data->val($i, $k['ayah_nik']));
        $isiBaris['ibu_nik']  = buang_nondigit($data->val($i, $k['ibu_nik']));

        // Dokumen
        $isiBaris['akta_lahir']      = trim((string) $data->val($i, $k['akta_lahir']));
        $pasport                     = trim((string) $data->val($i, $k['dokumen_pasport']));
        $isiBaris['dokumen_pasport'] = $pasport !== '' ? $pasport : '-';
        $isiBaris['dokumen_kitas']   = '-'; // tidak ada di format SIAK

        // Perkawinan & perceraian
        $isiBaris['akta_perkawinan']   = trim((string) $data->val($i, $k['akta_perkawinan']));
        $isiBaris['tanggalperkawinan'] = $this->formatTanggal($data->val($i, $k['tanggalperkawinan']));
        $isiBaris['akta_perceraian']   = trim((string) $data->val($i, $k['akta_perceraian']));
        $isiBaris['tanggalperceraian'] = $this->formatTanggal($data->val($i, $k['tanggalperceraian']));

        // Field tambahan untuk pencatatan log_penduduk
        $isiBaris['status_dasar_orig'] = trim((string) $data->val($i, $k['status_dasar']));
        $isiBaris['tgl_entri']         = $this->formatTanggal($data->val($i, $k['tgl_entri']));

        return $isiBaris;
    }

    /**
     * Normalisasi nilai kolom pekerjaan/pendidikan dari format SIAK.
     *
     * Contoh: "SLTP / SEDERAJAT" → "sltp/sederajat"
     */
    private function normalkanData(?string $str): ?string
    {
        return preg_replace('/\s*\/\s*/', '/', strtolower(trim((string) $str)));
    }

    /**
     * Catat perubahan status penduduk ke tabel log_penduduk.
     *
     * Dipanggil hanya untuk penduduk berstatus MATI (2), HILANG (3),
     * atau PINDAH (4) agar riwayat perubahan dapat dilacak.
     *
     * @param array $data Field penduduk yang baru diimpor
     * @param int   $id   ID penduduk yang baru tersimpan
     */
    private function tulisLogPenduduk(array $data, int $id): void
    {
        LogPenduduk::upsert(
            [
                'id_pend'        => $id,
                'no_kk'          => $data['no_kk'],
                'tgl_peristiwa'  => $data['tgl_entri'],
                'tgl_lapor'      => $data['tgl_entri'],
                'created_by'     => auth()->id(),
                'kode_peristiwa' => $data['status_dasar'],
                'catatan'        => 'Status impor data SIAK: ' . $data['status_dasar_orig'],
            ],
            ['config_id', 'id_pend', 'kode_peristiwa', 'tgl_peristiwa']
        );
    }
}
