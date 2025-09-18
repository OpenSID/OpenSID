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
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Libraries\BIP;

use App\Libraries\Import;
use App\Models\LogPenduduk;

class Siak extends Import
{
    private $kolomSiak;

    public function __construct()
    {
        parent::__construct();
        $this->kolomSiak = serialize([
            'no_kk'             => '1',
            'nik'               => '2',
            'nama'              => '3',
            'status_dasar'      => '4',
            'tempatlahir'       => '5',
            'tanggallahir'      => '6',
            'sex'               => '7',
            'ayah_nik'          => '8',
            'nama_ayah'         => '9',
            'ibu_nik'           => '10',
            'nama_ibu'          => '11',
            'status_kawin'      => '12',
            'kk_level'          => '13',
            'agama_id'          => '14',
            'alamat'            => '17',
            'rw'                => '18',
            'rt'                => '19',
            'pendidikan_kk_id'  => '20',
            'pekerjaan_id'      => '21',
            'golongan_darah_id' => '23',
            'cacat_id'          => '24',
            'dokumen_pasport'   => '28',
            'akta_lahir'        => '29',
            'akta_perkawinan'   => '30',
            'tanggalperkawinan' => '31',
            'akta_perceraian'   => '32',
            'tanggalperceraian' => '33',
            'tgl_entri'         => '37',
        ]);
    }

    /* 	======================================================
            IMPOR DATA DALAM FORMAT SIAK
            ======================================================
    */

    private function cariBarisPertama($data, $baris)
    {
        if ($baris <= 1) {
            return 0;
        }

        $barisPertama = 1;

        for ($i = 2; $i <= $baris; $i++) {
            // Baris dengan tiga kolom pertama kosong menandakan baris tanpa data
            if ($data->val($i, 1) == '' && $data->val($i, 2) == '' && $data->val($i, 3) == '') {
                continue;
            }

            // Ketemu baris data pertama
            $barisPertama = $i;
            break;
        }

        return $barisPertama;
    }

    private function getIsiBaris($data, int $i)
    {
        $kolomImpor         = unserialize($this->kolomSiak);
        $isiBaris['alamat'] = trim($data->val($i, $kolomImpor['alamat']));
        // alamat berbentuk 'DSN LIWET'
        $pecahAlamat        = preg_split('/DSN |DS |DUSUN |DSN\\. |DS\\. |DUSUN\\. /i', $isiBaris['alamat']);
        $isiBaris['alamat'] = $pecahAlamat[0];
        $isiBaris['dusun']  = $pecahAlamat[1];
        if (empty($isiBaris['dusun'])) {
            $isiBaris['dusun'] = $isiBaris['alamat'];
        }

        $isiBaris['rw'] = ltrim(trim($data->val($i, $kolomImpor['rw'])), "'");
        $isiBaris['rt'] = ltrim(trim($data->val($i, $kolomImpor['rt'])), "'");

        $nama             = trim($data->val($i, $kolomImpor['nama']));
        $nama             = preg_replace("/[^a-zA-Z,\\.'-]/", ' ', $nama);
        $isiBaris['nama'] = $nama;

        // Konversi status dasar dari string / integer.

        $isiBaris['status_dasar'] = $this->konversiKode($this->kodeStatusDasar, $data->val($i, $kolomImpor['status_dasar']));

        // Data Disdukcapil adakalanya berisi karakter tambahan pada no_kk dan nik
        // yang tidak tampak (non-printable characters),
        // jadi perlu dibuang
        $no_kk             = trim($data->val($i, $kolomImpor['no_kk']));
        $no_kk             = preg_replace('/[^0-9]/', '', $no_kk);
        $isiBaris['no_kk'] = $no_kk;

        $isiBaris['nik']              = buang_nondigit($data->val($i, $kolomImpor['nik']));
        $isiBaris['sex']              = $this->konversiKode($this->kodeSex, $data->val($i, $kolomImpor['sex']));
        $isiBaris['tempatlahir']      = trim($data->val($i, $kolomImpor['tempatlahir']));
        $isiBaris['tanggallahir']     = $this->formatTanggal($data->val($i, $kolomImpor['tanggallahir']));
        $isiBaris['agama_id']         = $this->konversiKode($this->kodeAgama, $data->val($i, $kolomImpor['agama_id']));
        $isiBaris['pendidikan_kk_id'] = $this->konversiKode($this->kodePendidikanKK, $data->val($i, $kolomImpor['pendidikan_kk_id']));
        $isiBaris['pekerjaan_id']     = $this->konversiKode($this->kodePekerjaan, $this->normalkanData($data->val($i, $kolomImpor['pekerjaan_id'])));
        $isiBaris['status_kawin']     = $this->konversiKode($this->kodeStatus, $data->val($i, $kolomImpor['status_kawin']));
        $isiBaris['kk_level']         = $this->konversiKode($this->kodeHubungan, $data->val($i, $kolomImpor['kk_level']));
        $isiBaris['warganegara_id']   = $this->konversiKode($this->kodeWargaNegara, $data->val($i, $kolomImpor['warganegara_id']));

        $namaAyah = trim($data->val($i, $kolomImpor['nama_ayah']));
        if ($namaAyah == '') {
            $namaAyah = '-';
        }
        $isiBaris['nama_ayah'] = $namaAyah;

        $namaIbu = trim($data->val($i, $kolomImpor['nama_ibu']));
        if ($namaIbu == '') {
            $namaIbu = '-';
        }
        $isiBaris['nama_ibu'] = $namaIbu;

        $isiBaris['golongan_darah_id'] = $this->konversiKode($this->kodeGolonganDarah, $data->val($i, $kolomImpor['golongan_darah_id']));
        $isiBaris['akta_lahir']        = trim($data->val($i, $kolomImpor['akta_lahir']));
        $isiBaris['dokumen_pasport']   = trim($data->val($i, $kolomImpor['dokumen_pasport']));

        $isiBaris['ayah_nik']          = buang_nondigit($data->val($i, $kolomImpor['ayah_nik']));
        $isiBaris['ibu_nik']           = buang_nondigit($data->val($i, $kolomImpor['ibu_nik']));
        $isiBaris['akta_perkawinan']   = trim($data->val($i, $kolomImpor['akta_perkawinan']));
        $isiBaris['tanggalperkawinan'] = $this->formatTanggal($data->val($i, $kolomImpor['tanggalperkawinan']));
        $isiBaris['akta_perceraian']   = trim($data->val($i, $kolomImpor['akta_perceraian']));
        $isiBaris['tanggalperceraian'] = $this->formatTanggal($data->val($i, $kolomImpor['tanggalperceraian']));
        $isiBaris['cacat_id']          = $this->konversiKode($this->kodeCacat, $data->val($i, $kolomImpor['cacat_id']));

        // Untuk tulis ke log_penduduk
        $isiBaris['status_dasar_orig'] = trim($data->val($i, $kolomImpor['status_dasar']));
        $isiBaris['tgl_entri']         = $this->formatTanggal($data->val($i, $kolomImpor['tgl_entri']));

        return $isiBaris;
    }

    // Normalkan kolom seperti "SLTP / SEDERAJAT" menjadi "sltp/sederajat"
    private function normalkanData($str)
    {
        return preg_replace('/\s*\/\s*/', '/', strtolower(trim($str)));
    }

    /**
     * Proses impor data bip
     *
     * @param sheet		data excel berisi bip
     * @param mixed $data
     *
     * @return setting $_SESSION untuk info hasil impor
     *                 $_SESSION['gagal']=						jumlah baris yang gagal
     *                 $_SESSION['total_keluarga']=	jumlah keluarga yang diimpor
     *                 $_SESSION['total_penduduk']=	jumlah penduduk yang diimpor
     *                 $_SESSION['baris']=						daftar baris yang gagal
     */
    public function imporDataBip($data)
    {
        // membaca jumlah baris dari data excel
        $baris = $data->rowcount($sheetIndex = 0);
        if ($this->cariBarisPertama($data, $baris) <= 1) {
            return set_session('error', 'Data penduduk gagal diimpor, data tidak tersedia.');
        }

        $gagalPenduduk = 0;
        $barisGagal    = '';
        $totalKeluarga = 0;
        $totalPenduduk = 0;

        // Import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
        for ($i = 2; $i <= $baris; $i++) {
            // Baris dengan tiga kolom pertama kosong menandakan baris tanpa data
            if ($data->val($i, 1) == '' && $data->val($i, 2) == '' && $data->val($i, 3) == '') {
                continue;
            }

            $isiBaris      = $this->getIsiBaris($data, $i);
            $errorValidasi = $this->dataImportValid($isiBaris);
            if (empty($errorValidasi)) {
                $this->tulisWilayah($isiBaris);
                if ($this->tulisKeluarga($isiBaris)) {
                    $totalKeluarga++;
                }
                $penduduk_baru = $this->tulisPenduduk($isiBaris);
                if ($penduduk_baru) {
                    $totalPenduduk++;
                    // Tulis log kalau status dasar MATI, HILANG atau PINDAH
                    if (in_array($isiBaris['status_dasar'], ['2', '3', '4'])) {
                        $this->tulisLogPenduduk($isiBaris, $penduduk_baru);
                    }
                }
            } else {
                $gagalPenduduk++;
                $barisGagal .= $i . ' (' . $errorValidasi . ')<br>';
            }
        }

        if ($gagalPenduduk == 0) {
            $barisGagal = 'tidak ada data yang gagal diimpor.';
        }

        $pesanImpor = [
            'gagal'          => $gagalPenduduk,
            'total_keluarga' => $totalKeluarga,
            'total_penduduk' => $totalPenduduk,
            'baris'          => $barisGagal,
        ];

        set_session('pesan_impor', $pesanImpor);

        return set_session('success', 'Data penduduk berhasil diimpor');
    }

    private function tulisLogPenduduk($data, $id): void
    {
        // Tulis log_penduduk
        $log = [
            'id_pend'        => $id,
            'no_kk'          => $data['no_kk'],
            'tgl_peristiwa'  => $data['tgl_entri'],
            'tgl_lapor'      => $data['tgl_entri'],
            'created_by'     => auth()->id(),
            'kode_peristiwa' => $data['status_dasar'],
            'catatan'        => 'Status impor data SIAK: ' . $data['status_dasar_orig'],
        ];

        LogPenduduk::upsert($log, ['config_id', 'id_pend', 'kode_peristiwa', 'tgl_peristiwa']);
    }
}
