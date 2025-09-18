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

namespace App\Libraries;

use App\Enums\JenisKelaminEnum;
use App\Models\BantuanPeserta;
use App\Models\Keluarga;
use App\Models\LogPenduduk;
use App\Models\PendudukSaja;

class Acak
{
    private $namaWanita = ['Yuni', 'Fatima', 'Sarah', 'Dewi', 'Hasnah'];
    private $namaPria   = ['Bambang', 'Abdul', 'Setiyadi', 'Dadang', 'Herman'];

    /**
     * Acak data penduduk
     */
    public function acakPenduduk()
    {
        $data = PendudukSaja::select(['id', 'nik', 'nama'])->where('sex', JenisKelaminEnum::LAKI_LAKI)->get()->toArray();
        $this->acakUntukGender($data);
        $data = PendudukSaja::select(['id', 'nik', 'nama'])->where('sex', '!=', JenisKelaminEnum::LAKI_LAKI)->get()->toArray();

        return $this->acakUntukGender($data);
    }

    private function acakUntukGender($data)
    {
        if (count($data) <= 1) {
            return;
        }

        $i     = 1;
        $datas = [];

        foreach ($data as $penduduk) {
            if ($penduduk['nik'] == 0) {
                continue;
            }
            $nik       = $penduduk['nik'];
            $urut      = $this->acakAngka(substr($nik, 12));
            $nik_acak  = substr_replace($nik, $urut, 12);
            $nama_acak = $this->acak_nama($i - 1, $data);
            $datas[]   = ['id' => $penduduk['id'], 'nik' => $nik, 'nik_acak' => $nik_acak, 'nama' => $penduduk['nama'], 'nama_acak' => $nama_acak];

            PendudukSaja::where('id', $penduduk['id'])->update(['nik' => $nik_acak, 'nama' => $nama_acak]);
            BantuanPeserta::where('peserta', $nik)->update(['peserta' => $nik_acak]);
            $i++;
        }

        return $datas;
    }

    private function acak_nama(int $urut_penduduk, $data)
    {
        $nama      = $data[$urut_penduduk]['nama'];
        $kata      = preg_split('/\s+/', $nama);
        $nama_acak = '';
        $counter   = count($kata);

        for ($i = 0; $i < $counter; $i++) {
            // Ganti setiap kata dgn kata dari nama penduduk acak
            $urut_acak = $urut_penduduk;

            while ($urut_acak === $urut_penduduk) {
                $urut_acak = random_int(0, count($data) - 1);
            }
            $kata_penduduk_acak = preg_split('/\s+/', $data[$urut_acak]['nama']);

            // Jangan gunakan gelar berisi '.' atau nama kurang dari 3 karakter
            $kata_acak = '.';

            while (strpos($kata_acak, '.') !== false || strlen($kata_acak) < 3) {
                // Kalau nama penduduk acak hanya terdiri dari satu kata, gunakan itu
                if (count($kata_penduduk_acak) == 1) {
                    $kata_acak = $kata_penduduk_acak[0];
                    break;
                }
                if (count($kata_penduduk_acak) == 0) {
                    break;
                }
                $urut_kata_acak = random_int(0, count($kata_penduduk_acak) - 1);
                $kata_acak      = $kata_penduduk_acak[$urut_kata_acak];
                // Hapus supaya kata ini tidak digunakan lagi
                unset($kata_penduduk_acak[$urut_kata_acak]);
                // https://www.codeproject.com/Questions/608574/unsetplusNotplusWorkingplusPHPplusArray
                $kata_penduduk_acak = array_values($kata_penduduk_acak);
            }
            if ($kata_acak != '.') {
                $nama_acak .= ($i == 0) ? $kata_acak : ' ' . $kata_acak;
            } else { // Jika tidak ditemukan kata yg bisa dipakai gunakan nama sembarang
                $nama_sembarang = $this->nama_sembarang($data['urut_penduduk']['sex']);
                $nama_acak .= ($i == 0) ? $nama_sembarang : ' ' . $nama_sembarang;
            }
        }

        return $nama_acak;
    }

    private function nama_sembarang($sex)
    {
        if ($sex == 1) {
            return $this->namaPria[random_int(0, count($this->namaPria) - 1)];
        }

        return $this->namaWanita[random_int(0, count($this->namaWanita) - 1)];
    }

    public function acakKeluarga()
    {
        $data = Keluarga::withOnly(['kepalaKeluarga'])->select(['id', 'no_kk'])->get()->toArray();
        // , p.nama as nama_kk')->
        $i     = 1;
        $datas = [];

        foreach ($data as $keluarga) {
            if ($keluarga['no_kk'] == 0) {
                continue;
            }

            $no_kk      = $keluarga['no_kk'];
            $urut       = $this->acakAngka(substr($no_kk, 12));
            $no_kk_acak = substr_replace($no_kk, $urut, 12);

            $cek = Keluarga::where('no_kk', $no_kk_acak)->exists();
            if ($cek) {
                continue;
            }
            $namaKK  = $keluarga['kepalaKeluarga']['nama'] ?? '';
            $datas[] = ['id' => $keluarga['id'], 'no_kk' => $no_kk, 'no_kk_acak' => $no_kk_acak];
            Keluarga::where('id', $keluarga['id'])->update(['no_kk' => $no_kk_acak]);
            // Juga ganti no_kk dan nama_kk di log_penduduk
            LogPenduduk::where('no_kk', $no_kk)->update(['no_kk' => $no_kk_acak, 'nama_kk' => $namaKK]);
            // Dan ganti no_kk_sebelumnya di tweb_penduduk
            PendudukSaja::where('no_kk_sebelumnya', $no_kk)->update(['no_kk_sebelumnya' => $no_kk_acak]);
            BantuanPeserta::where('peserta', $no_kk)->update(['peserta' => $no_kk_acak]);
            $i++;
        }

        return $datas;
    }

    private function acakAngka(string $str)
    {
        $jangan = str_pad('', strlen($str), '0');
        $baru   = $jangan;

        while (true) {
            for ($i = 0; $i < strlen($str); $i++) {
                $baru[$i] = random_int(0, 9);
            }
            if ($baru !== $jangan) {
                break;
            }
            $baru = $jangan;
        }

        return $baru;
    }
}
