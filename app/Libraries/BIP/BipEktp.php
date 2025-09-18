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

class BipEktp extends Import
{
    private $desa;
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
            // Baris dengan kolom[1] berisi No KK dan kolom[2] kosong menunjukkan mulainya data keluarga dan anggotanya
            if ($this->barisAwalKk($dataSheet, $i)) {
                $barisKk = $i;
                break;
            }
        }

        return $barisKk;
    }

    private function barisAwalKk($dataSheet, $baris)
    {
        // Baris dengan kolom[1] berisi No KK dan kolom[2] kosong menunjukkan mulainya data keluarga dan anggotanya
        return strlen(preg_replace('/[^0-9]/', '', $dataSheet[$baris][1])) == 16
                && trim($dataSheet[$baris][2]) == '';
    }

    private function ambilKolom($str, string $awalan, string $akhiran = '')
    {
        $kolom   = '';
        $posAwal = strpos($str, $awalan);
        if ($posAwal !== false) {
            $pos   = $posAwal + strlen($awalan);
            $kolom = $akhiran === '' ? trim(substr($str, $pos)) : trim(substr($str, $pos, strpos($str, $akhiran, $pos) - $pos));
        }

        return $kolom;
    }

    // Normalkan kolom seperti "SLTP / SEDERAJAT" menjadi "sltp/sederajat"
    private function normalkanData($str)
    {
        return preg_replace('/\s*\/\s*/', '/', strtolower(trim($str)));
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
             1605180812070010		NETI HERAWATI									DESA LUBUK BESAR RT/RW : 002/000 DUSUN : -
         */
        $dataKeluarga          = [];
        $baris                 = $i;
        $dataKeluarga['no_kk'] = trim($dataSheet[$baris][1]);
        // abaikan nama KK, karena ada di daftar anggota keluarga

        $alamat = $dataSheet[$baris][12];
        // Simpan desa pertama, karena penulisan desa tidak konsisten dan bisa kosong
        if (empty($this->desa)) {
            $this->desa = $this->ambilKolom($alamat, 'DESA ', 'RT/RW :');
        }

        $rtrw = $this->ambilKolom($alamat, 'RT/RW :', ' DUSUN :');
        if ($rtrw) {
            [$dataKeluarga['rt'], $dataKeluarga['rw']] = explode('/', $rtrw);
        }

        $dusun                 = $this->ambilKolom($alamat, 'DUSUN :');
        $dusun                 = trim(str_replace('-', '', $dusun));
        $dataKeluarga['dusun'] = $dusun === '' ? $this->desa : $dusun;

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
1		2							3									4							5							6		7					8					9
NO	Nama Lengkap	NIK								Tempat Lahir 	Tanggal Lahir	JK	Stat Kwn	Gol. Drh	SHDK
1		NETI HERAWATI	1605186512620000	LUBUK BESAR		25-12-1962		Pr	CM				-					KEPALA KELUARGA

10		11
Agama	Pendidikan Terakhir
ISLAM	TAMAT SD / SEDERAJAT

12							13										14						15				16			17			18			19
No Akta Lahir		Pekerjaan							Nama Ibu			Nama Ayah	Wjb KTP	KTP-eL	Status	Stat Rkm
6767/TAMB/2002	BELUM / TIDAK BEKERJA	NETI HERAWATI	WARTA			WAJIB		KTP-eL	SDH DPT	CARD SHIPPED
        */
        $dataAnggota                      = $dataKeluarga;
        $dataAnggota['nama']              = trim($dataSheet[$i][2]);
        $dataAnggota['nik']               = preg_replace('/[^0-9]/', '', trim($dataSheet[$i][3]));
        $dataAnggota['tempatlahir']       = trim($dataSheet[$i][4]);
        $tanggallahir                     = trim($dataSheet[$i][5]);
        $dataAnggota['tanggallahir']      = $this->formatTanggal($tanggallahir);
        $dataAnggota['sex']               = $this->getKode($this->kodeSex, trim($dataSheet[$i][6]));
        $dataAnggota['status_kawin']      = $this->getKode($this->kodeStatus, strtolower(trim($dataSheet[$i][7])));
        $dataAnggota['golongan_darah_id'] = $this->getKode($this->kodeGolonganDarah, strtolower(trim($dataSheet[$i][8])));
        if (empty($dataAnggota['golongan_darah_id']) || $dataAnggota['golongan_darah_id'] == 0) {
            $dataAnggota['golongan_darah_id'] = 13;
        }
        $dataAnggota['kk_level']         = $this->getKode($this->kodeHubungan, strtolower(trim($dataSheet[$i][9])));
        $dataAnggota['agama_id']         = $this->getKode($this->kodeAgama, strtolower(trim($dataSheet[$i][10])));
        $dataAnggota['pendidikan_kk_id'] = $this->getKode($this->kodePendidikanKK, $this->normalkanData($dataSheet[$i][11]));
        $dataAnggota['akta_lahir']       = trim($dataSheet[$i][12]);
        $dataAnggota['pekerjaan_id']     = $this->getKode($this->kodePekerjaan, $this->normalkanData($dataSheet[$i][13]));
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
        /* Kolom 16-19 data eKTP; kolom 16 diabaikan karena ditentukan oleh tgl lahir
             dan status kawin;
           kolom 18 diabaikan karena pada dasarnya sama dgn kolom 19
         */
        $dataAnggota['ktp_el']       = $this->kodeKtpEl[strtolower(trim($dataSheet[$i][17]))];
        $dataAnggota['status_rekam'] = $this->getStatusRekam($dataSheet, $i);

        // Isi kolom default
        $dataAnggota['warganegara_id']       = '1';
        $dataAnggota['pendidikan_sedang_id'] = '';

        return $dataAnggota;
    }

    private function getStatusRekam($dataSheet, int $i)
    {
        // Kolom status_rekam bisa ada karakter baris baru
        $statusRekam     = preg_replace('/[^a-zA-Z, ]/', ' ', strtolower(trim($dataSheet[$i][19])));
        $statusRekam     = preg_replace('/\s+/', ' ', $statusRekam);
        $kodeStatusRekam = $this->kodeStatusRekam[$statusRekam];
        // Mungkin bagian dari status rekam tampil di baris data berikutnya
        // (lewati footer dan kemungkinan baris kosong)
        $j = $i + 2;

        while (empty($kodeStatusRekam) && ($j < $i + 5)) {
            $j++;
            $statusRekamCoba = $statusRekam . ' ' . preg_replace('/[^a-zA-Z, ]/', ' ', strtolower(trim($dataSheet[$j][19])));
            $statusRekamCoba = preg_replace('/\s+/', ' ', $statusRekamCoba);
            $kodeStatusRekam = $this->kodeStatusRekam[$statusRekamCoba];
        }

        return $kodeStatusRekam;
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
                if (! $this->barisAwalKk($dataSheet, $i)) {
                    continue;
                }
                // Proses keluarga
                $dataKeluarga = $this->getBipKeluarga($dataSheet, $i);
                $this->tulisWilayah($dataKeluarga);
                $this->tulisKeluarga($dataKeluarga);
                $totalKeluarga++;
                // Pergi ke data anggota keluarga
                $i++;

                // Proses setiap anggota keluarga
                while (trim($dataSheet[$i][1]) > 0 && trim($dataSheet[$i][2]) != '' && $i <= $baris) {
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
        } else {
            return set_session('error', 'Data penduduk gagal diimpor');
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
