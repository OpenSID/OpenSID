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

class Bip2012 extends Import
{
    /* 	===============================
            IMPORT BUKU INDUK PENDUDUK 2012
            ===============================
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
            // Baris dengan kolom[2] = "NO.KK" menunjukkan mulainya data keluarga dan anggotanya
            if ($dataSheet[$i][2] == 'NO.KK') {
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
        // Contoh alamat: "DUSUN KERANDANGAN, RT:001, RW:001, Kodepos:83355,-"
        // $i = baris judul data keluarga. Data keluarga ada di baris berikutnya
        $baris   = $i + 1;
        $alamat  = $dataSheet[$baris][7];
        $posAwal = strpos($alamat, 'DUSUN');
        if ($posAwal !== false) {
            $pos                   = $posAwal + 5;
            $dataKeluarga['dusun'] = trim(substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos));
            $alamat                = substr_replace($alamat, '', $posAwal, strpos($alamat, ',', $pos) - $posAwal);
        } else {
            $dataKeluarga['dusun'] = 'LAINNYA';
        }
        $posAwal = strpos($alamat, 'RW:');
        if ($posAwal !== false) {
            $pos += 3;
            $dataKeluarga['rw'] = substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos);
            $alamat             = substr_replace($alamat, '', $posAwal, strpos($alamat, ',', $pos) - $posAwal);
        } else {
            $dataKeluarga['rw'] = '-';
        }
        if ($dataKeluarga['rw'] == '') {
            $dataKeluarga['rw'] = '-';
        }
        $posAwal = strpos($alamat, 'RT:');
        if ($posAwal !== false) {
            $pos                = $posAwal + 3;
            $dataKeluarga['rt'] = substr($alamat, $pos, strpos($alamat, ',', $pos) - $pos);
            $alamat             = substr_replace($alamat, '', $posAwal, strpos($alamat, ',', $pos) - $posAwal);
        } else {
            $dataKeluarga['rt'] = '-';
        }
        if ($dataKeluarga['rt'] == '') {
            $dataKeluarga['rt'] = '-';
        }
        $alamat = rtrim(ltrim(preg_replace('/Kodepos:.*,/i', '', $alamat), ' ,-'), ' ,-');
        // $alamat sudah tidak ada dusun, rw, rt atau kodepos -- tinggal jalan, kompleks, gedung dsbnya
        $dataKeluarga['alamat'] = $alamat;
        $dataKeluarga['no_kk']  = $dataSheet[$baris][2];

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
        // $i = baris data anggota keluarga
        $dataAnggota                     = $dataKeluarga;
        $dataAnggota['nik']              = preg_replace('/[^0-9]/', '', trim($dataSheet[$i][3]));
        $dataAnggota['nama']             = trim($dataSheet[$i][4]);
        $dataAnggota['sex']              = $this->getKode($this->kodeSex, trim($dataSheet[$i][5]));
        $dataAnggota['tempatlahir']      = trim($dataSheet[$i][6]);
        $tanggallahir                    = trim($dataSheet[$i][7]);
        $dataAnggota['tanggallahir']     = $this->formatTanggal($tanggallahir);
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
        $dataAnggota['nama_ayah']  = $namaAyah;
        $dataAnggota['akta_lahir'] = trim($dataSheet[$i][16]);

        // Isi kolom default
        $dataAnggota['warganegara_id']       = '1';
        $dataAnggota['golongan_darah_id']    = '13';
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
                if ($dataSheet[$i][2] != 'NO.KK') {
                    continue;
                }
                // Proses keluarga
                $dataKeluarga = $this->getBipKeluarga($dataSheet, $i);
                $this->tulisWilayah($dataKeluarga);
                $this->tulisKeluarga($dataKeluarga);
                $totalKeluarga++;
                // Pergi ke data anggota keluarga
                $i += 3;

                // Proses setiap anggota keluarga
                while ($dataSheet[$i][2] != 'NO.KK' && $i <= $baris) {
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
