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

class Bip2016Luwutimur extends Import
{
    /* 	======================================================
            IMPORT BUKU INDUK PENDUDUK 2016 (LUWU TIMUR)
            ======================================================
    */

    /**
     * Cari baris pertama mulainya blok keluarga
     *
     * @param sheet			data excel berisi bip
     * @param int		jumlah baris di sheet
     * @param int		cari dari baris ini
     * @param mixed $dataSheet
     * @param mixed $baris
     * @param mixed $dari
     *
     * @return int baris pertama blok keluarga
     */
    private function cariBipKk($dataSheet, $baris, int $dari = 1)
    {
        if ($baris <= 1) {
            return 0;
        }

        $barisKk = 0;

        for ($i = $dari; $i <= $baris; $i++) {
            // Baris dengan kolom[2] yang mulai dengan "BUKU INDUK KEPENDUDUKAN" menunjukkan mulainya data keluarga dan anggotanya
            if (strpos($dataSheet[$i][2], 'BUKU INDUK KEPENDUDUKAN') === 0) {
                $barisKk = $i;
                break;
            }
        }

        return $barisKk;
    }

    /**
     * Ambil data keluarga berikutnya
     *
     * @param sheet		data excel berisi bip
     * @param int	cari dari baris ini
     * @param mixed $dataSheet
     * @param mixed $i
     *
     * @return array data keluarga
     */
    private function getBipKeluarga($dataSheet, int $i)
    {
        /* $i = baris berisi data keluarga.
         * Contoh:
        BUKU INDUK KEPENDUDUKAN KABUPATEN LUWU TIMUR (DAFTAR  KELUARGA)

            PROVINSI :	SULAWESI SELATAN				NO. KK :	7324090803110001
            KABUPATEN :	LUWU TIMUR							NAMA. KK :	KURNIATI NURDIN
            KECAMATAN :	KALAENA									ALAMAT :	DSN. TAMBAK YOSO,Kodepos :92974,Telp :,-
            DESA :	KALAENA KIRI								NO.RT/RW :	001/001
         */
        $dataKeluarga          = [];
        $baris                 = $i + 2;
        $dataKeluarga['no_kk'] = trim($dataSheet[$baris][12]);
        // abaikan nama KK, karena ada di daftar anggota keluarga

        $alamat                = $dataSheet[$baris + 2][12];
        $dusun                 = trim(substr($alamat, 0, strpos($alamat, ',', 0)));
        $dataKeluarga['dusun'] = trim(preg_replace('/DSN.|DUSUN/', '', $dusun));
        $pos_telp              = strpos($alamat, 'Telp :');
        if ($pos_telp !== false) {
            $telepon                 = trim(substr($alamat, $pos_telp));
            $dataKeluarga['telepon'] = trim(preg_replace('/Telp :|,/', '', $telepon));
        }

        $rt_rw                                     = trim($dataSheet[$baris + 3][12]);
        [$dataKeluarga['rt'], $dataKeluarga['rw']] = explode('/', $rt_rw);

        return $dataKeluarga;
    }

    /**
     * Ambil data anggota keluarga berikutnya
     *
     * @param sheet		data excel berisi bip
     * @param int	cari dari baris ini
     * @param array		data keluarga untuk anggota yg dicari
     * @param mixed $dataSheet
     * @param mixed $i
     * @param mixed $dataKeluarga
     *
     * @return array data anggota keluarga
     */
    private function getBipAnggotaKeluarga($dataSheet, int $i, $dataKeluarga)
    {
        /* $i = baris data anggota keluarga
         * Contoh:
2		3									4								5		6					7						8			9			10					11
NO	NIK								NAMA						JK	TMPT LHR	TGL LHR			G.DRH	AGAMA	STATUS			HUB.KEL
1		7324097003830001	KURNIATI NURDIN	P		PARE-PARE	30-03-1983	O			Islam	Belum Kawin	Kepala Keluarga

12													13										14							15
PENDIDIKAN									PEKERJAAN							NAMA IBU				NAMA AYAH	KET
Akademi/Diploma III/S. Muda	Pegawai Negeri Sipil	HALIMAH					NURDIN
        */
        $dataAnggota                      = $dataKeluarga;
        $dataAnggota['nik']               = preg_replace('/[^0-9]/', '', trim($dataSheet[$i][3]));
        $dataAnggota['nama']              = trim($dataSheet[$i][4]);
        $dataAnggota['sex']               = $this->getKode($this->kodeSex, trim($dataSheet[$i][5]));
        $dataAnggota['tempatlahir']       = trim($dataSheet[$i][6]);
        $tanggallahir                     = trim($dataSheet[$i][7]);
        $dataAnggota['tanggallahir']      = $this->formatTanggal($tanggallahir);
        $dataAnggota['golongan_darah_id'] = $this->getKode($this->kodeGolonganDarah, strtolower(trim($dataSheet[$i][8])));
        if (empty($dataAnggota['golongan_darah_id']) || $dataAnggota['golongan_darah_id'] == 0) {
            $dataAnggota['golongan_darah_id'] = 13;
        }
        $dataAnggota['agama_id']         = $this->getKode($this->kodeAgama, strtolower(trim($dataSheet[$i][9])));
        $dataAnggota['status_kawin']     = $this->getKode($this->kodeStatus, strtolower(trim($dataSheet[$i][10])));
        $dataAnggota['kk_level']         = $this->getKode($this->kodeHubungan, strtolower(trim($dataSheet[$i][11])));
        $dataAnggota['pendidikan_kk_id'] = $this->getKode($this->kodePendidikanKK, strtolower(trim($dataSheet[$i][12])));
        $dataAnggota['pekerjaan_id']     = $this->getKode($this->kodePekerjaan, strtolower(trim($dataSheet[$i][13])));
        $namaIbu                         = trim($dataSheet[$i][14]);
        if ($namaIbu == '') {
            $namaIbu = '-';
        }
        $dataAnggota['nama_ibu'] = $namaIbu;
        $namaAyah                = trim($dataSheet[$i][15]);
        if ($namaAyah == '') {
            $namaAyah = '-';
        }
        $dataAnggota['nama_ayah'] = $namaAyah;

        // Isi kolom default
        $dataAnggota['akta_lahir']           = '';
        $dataAnggota['warganegara_id']       = '1';
        $dataAnggota['pendidikan_sedang_id'] = '';

        return $dataAnggota;
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
        $gagalPenduduk = 0;
        $barisGagal    = '';
        $totalKeluarga = 0;
        $totalPenduduk = 0;
        // BIP bisa terdiri dari beberapa worksheet
        // Proses sheet satu-per-satu
        $counter = count($data->boundsheets);

        // BIP bisa terdiri dari beberapa worksheet
        // Proses sheet satu-per-satu
        for ($sheetIndex = 0; $sheetIndex < $counter; $sheetIndex++) {
            // membaca jumlah baris di sheet ini
            $baris     = $data->rowcount($sheetIndex);
            $dataSheet = $data->sheets[$sheetIndex]['cells'];
            if ($this->cariBipKk($dataSheet, $baris, 1) < 1) {
                // Tidak ada data keluarga
                continue;
            }

            // Import data sheet ini mulai baris pertama
            for ($i = 1; $i <= $baris; $i++) {
                // Cari keluarga berikutnya
                if (strpos($dataSheet[$i][2], 'BUKU INDUK KEPENDUDUKAN') !== 0) {
                    continue;
                }
                // Proses keluarga
                $dataKeluarga = $this->getBipKeluarga($dataSheet, $i);
                $this->tulisWilayah($dataKeluarga);
                $this->tulisKeluarga($dataKeluarga);
                $totalKeluarga++;
                // Pergi ke data anggota keluarga
                $i += 8;

                // Proses setiap anggota keluarga
                while (trim($dataSheet[$i][2]) != '' && $i <= $baris) {
                    if (! is_numeric(trim($dataSheet[$i][2]))) {
                        break;
                    }
                    $dataAnggota   = $this->getBipAnggotaKeluarga($dataSheet, $i, $dataKeluarga);
                    $errorValidasi = $this->dataImportValid($dataAnggota);
                    if (empty($errorValidasi)) {
                        $this->tulisPenduduk($dataAnggota);
                        $totalPenduduk++;
                    } else {
                        $gagalPenduduk++;
                        $barisGagal .= $i . ' (' . $errorValidasi . ')<br>';
                    }
                    $i++;
                }
                $i--;
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
}
