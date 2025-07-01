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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Bip2016_luwutimur_model extends Impor_model
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
     * @param mixed $data_sheet
     * @param mixed $baris
     * @param mixed $dari
     *
     * @return int baris pertama blok keluarga
     */
    private function cari_bip_kk($data_sheet, $baris, int $dari = 1)
    {
        if ($baris <= 1) {
            return 0;
        }

        $baris_kk = 0;

        for ($i = $dari; $i <= $baris; $i++) {
            // Baris dengan kolom[2] yang mulai dengan "BUKU INDUK KEPENDUDUKAN" menunjukkan mulainya data keluarga dan anggotanya
            if (strpos($data_sheet[$i][2], 'BUKU INDUK KEPENDUDUKAN') === 0) {
                $baris_kk = $i;
                break;
            }
        }

        return $baris_kk;
    }

    /**
     * Ambil data keluarga berikutnya
     *
     * @param sheet		data excel berisi bip
     * @param int	cari dari baris ini
     * @param mixed $data_sheet
     * @param mixed $i
     *
     * @return array data keluarga
     */
    private function get_bip_keluarga($data_sheet, int $i)
    {
        /* $i = baris berisi data keluarga.
         * Contoh:
        BUKU INDUK KEPENDUDUKAN KABUPATEN LUWU TIMUR (DAFTAR  KELUARGA)

            PROVINSI :	SULAWESI SELATAN				NO. KK :	7324090803110001
            KABUPATEN :	LUWU TIMUR							NAMA. KK :	KURNIATI NURDIN
            KECAMATAN :	KALAENA									ALAMAT :	DSN. TAMBAK YOSO,Kodepos :92974,Telp :,-
            DESA :	KALAENA KIRI								NO.RT/RW :	001/001
         */
        $data_keluarga          = [];
        $baris                  = $i + 2;
        $data_keluarga['no_kk'] = trim($data_sheet[$baris][12]);
        // abaikan nama KK, karena ada di daftar anggota keluarga

        $alamat                 = $data_sheet[$baris + 2][12];
        $dusun                  = trim(substr($alamat, 0, strpos($alamat, ',', 0)));
        $data_keluarga['dusun'] = trim(preg_replace('/DSN.|DUSUN/', '', $dusun));
        $pos_telp               = strpos($alamat, 'Telp :');
        if ($pos_telp !== false) {
            $telepon                  = trim(substr($alamat, $pos_telp));
            $data_keluarga['telepon'] = trim(preg_replace('/Telp :|,/', '', $telepon));
        }

        $rt_rw                                       = trim($data_sheet[$baris + 3][12]);
        [$data_keluarga['rt'], $data_keluarga['rw']] = explode('/', $rt_rw);

        return $data_keluarga;
    }

    /**
     * Ambil data anggota keluarga berikutnya
     *
     * @param sheet		data excel berisi bip
     * @param int	cari dari baris ini
     * @param array		data keluarga untuk anggota yg dicari
     * @param mixed $data_sheet
     * @param mixed $i
     * @param mixed $data_keluarga
     *
     * @return array data anggota keluarga
     */
    private function get_bip_anggota_keluarga($data_sheet, int $i, $data_keluarga)
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
        $data_anggota                      = $data_keluarga;
        $data_anggota['nik']               = preg_replace('/[^0-9]/', '', trim($data_sheet[$i][3]));
        $data_anggota['nama']              = trim($data_sheet[$i][4]);
        $data_anggota['sex']               = $this->get_kode($this->kode_sex, trim($data_sheet[$i][5]));
        $data_anggota['tempatlahir']       = trim($data_sheet[$i][6]);
        $tanggallahir                      = trim($data_sheet[$i][7]);
        $data_anggota['tanggallahir']      = $this->format_tanggal($tanggallahir);
        $data_anggota['golongan_darah_id'] = $this->get_kode($this->kode_golongan_darah, strtolower(trim($data_sheet[$i][8])));
        if (empty($data_anggota['golongan_darah_id']) || $data_anggota['golongan_darah_id'] == 0) {
            $data_anggota['golongan_darah_id'] = 13;
        }
        $data_anggota['agama_id']         = $this->get_kode($this->kode_agama, strtolower(trim($data_sheet[$i][9])));
        $data_anggota['status_kawin']     = $this->get_kode($this->kode_status, strtolower(trim($data_sheet[$i][10])));
        $data_anggota['kk_level']         = $this->get_kode($this->kode_hubungan, strtolower(trim($data_sheet[$i][11])));
        $data_anggota['pendidikan_kk_id'] = $this->get_kode($this->kode_pendidikan_kk, strtolower(trim($data_sheet[$i][12])));
        $data_anggota['pekerjaan_id']     = $this->get_kode($this->kode_pekerjaan, strtolower(trim($data_sheet[$i][13])));
        $nama_ibu                         = trim($data_sheet[$i][14]);
        if ($nama_ibu == '') {
            $nama_ibu = '-';
        }
        $data_anggota['nama_ibu'] = $nama_ibu;
        $nama_ayah                = trim($data_sheet[$i][15]);
        if ($nama_ayah == '') {
            $nama_ayah = '-';
        }
        $data_anggota['nama_ayah'] = $nama_ayah;

        // Isi kolom default
        $data_anggota['akta_lahir']           = '';
        $data_anggota['warganegara_id']       = '1';
        $data_anggota['pendidikan_sedang_id'] = '';

        return $data_anggota;
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
    public function impor_data_bip($data)
    {
        $gagal_penduduk = 0;
        $baris_gagal    = '';
        $total_keluarga = 0;
        $total_penduduk = 0;
        // BIP bisa terdiri dari beberapa worksheet
        // Proses sheet satu-per-satu
        $counter = count($data->boundsheets);

        // BIP bisa terdiri dari beberapa worksheet
        // Proses sheet satu-per-satu
        for ($sheet_index = 0; $sheet_index < $counter; $sheet_index++) {
            // membaca jumlah baris di sheet ini
            $baris      = $data->rowcount($sheet_index);
            $data_sheet = $data->sheets[$sheet_index]['cells'];
            if ($this->cari_bip_kk($data_sheet, $baris, 1) < 1) {
                // Tidak ada data keluarga
                continue;
            }

            // Import data sheet ini mulai baris pertama
            for ($i = 1; $i <= $baris; $i++) {
                // Cari keluarga berikutnya
                if (strpos($data_sheet[$i][2], 'BUKU INDUK KEPENDUDUKAN') !== 0) {
                    continue;
                }
                // Proses keluarga
                $data_keluarga = $this->get_bip_keluarga($data_sheet, $i);
                $this->tulis_tweb_wil_clusterdesa($data_keluarga);
                $this->tulis_tweb_keluarga($data_keluarga);
                $total_keluarga++;
                // Pergi ke data anggota keluarga
                $i += 8;

                // Proses setiap anggota keluarga
                while (trim($data_sheet[$i][2]) != '' && $i <= $baris) {
                    if (! is_numeric(trim($data_sheet[$i][2]))) {
                        break;
                    }
                    $data_anggota   = $this->get_bip_anggota_keluarga($data_sheet, $i, $data_keluarga);
                    $error_validasi = $this->data_import_valid($data_anggota);
                    if (empty($error_validasi)) {
                        $this->tulis_tweb_penduduk($data_anggota);
                        $total_penduduk++;
                    } else {
                        $gagal_penduduk++;
                        $baris_gagal .= $i . ' (' . $error_validasi . ')<br>';
                    }
                    $i++;
                }
                $i--;
            }
        }

        if ($gagal_penduduk == 0) {
            $baris_gagal = 'tidak ada data yang gagal di import.';
        }

        $pesan_impor = [
            'gagal'          => $gagal_penduduk,
            'total_keluarga' => $total_keluarga,
            'total_penduduk' => $total_penduduk,
            'baris'          => $baris_gagal,
        ];

        set_session('pesan_impor', $pesan_impor);

        return set_session('success', 'Data penduduk berhasil diimpor');
    }
}
