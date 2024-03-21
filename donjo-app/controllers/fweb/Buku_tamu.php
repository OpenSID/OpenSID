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

use App\Enums\JawabanKepuasanEnum;
use App\Enums\StatusEnum;
use App\Models\BukuKeperluan;
use App\Models\BukuKepuasan;
use App\Models\BukuPertanyaan;
use App\Models\BukuTamu;
use App\Models\RefJabatan;
use Carbon\Carbon;

defined('BASEPATH') || exit('No direct script access allowed');

class Buku_tamu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->setting->layanan_mandiri == 0) {
            show_404();
        }

        if (null === $this->cek_anjungan) {
            show_404();
        }
    }

    public function index()
    {
        return view('buku_tamu.registrasi', [
            'aksi'      => ci_route('buku-tamu.registrasi'),
            'bertemu'   => RefJabatan::pluck('nama', 'id'),
            'keperluan' => BukuKeperluan::whereStatus(StatusEnum::YA)->pluck('keperluan', 'id'),
            'kamera'    => $this->setting->buku_tamu_kamera,
        ]);
    }

    public function registrasi(): void
    {
        if ($this->input->post()) {
            $post = $this->validate($this->request);

            // Identifikasi registrasi yang sama
            // Cek nama, telepon dan jenis kelamin pada hari yang sama
            $cek_registrasi = BukuTamu::whereNama($post['nama'])
                ->whereTelepon($post['telepon'])
                ->whereJenisKelamin($post['jenis_kelamin'])
                ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
                ->first();

            if ($cek_registrasi) {
                set_session('error', 'Registrasi Gagal Disimpan<br>Anda Sudah Melakukan Registrasi Hari Ini');
            } elseif (BukuTamu::create($post)) {
                set_session('success', 'Registrasi Berhasil Disimpan');
            } else {
                set_session('error', 'Registrasi Gagal Disimpan');
            }
        } else {
            set_session('error', 'Akses Tidak Tersedia');
        }

        redirect('buku-tamu/kepuasan');
    }

    public function kepuasan($id = null)
    {
        // Jangan tampilkan kalau belum ada daftar pertanyaan
        $data['ada_pertanyaan'] = BukuPertanyaan::whereStatus(StatusEnum::YA)->exists();

        if ($data['ada_pertanyaan']) {
            if ($id) {
                $data['pertanyaan'] = $this->cek_pertanyaan($id);
                $data['id']         = $id;
                $view               = 'buku_tamu.pertanyaan';
            } else {
                // Tamu yang belum isi indeks kepuasan
                $kepuasan              = BukuKepuasan::whereDate('created_at', Carbon::today())->pluck('id_nama');
                $data['tamu_hari_ini'] = BukuTamu::whereNotIn('id', $kepuasan)->whereDate('created_at', Carbon::today())->latest()->get();
                $view                  = 'buku_tamu.kepuasan';
            }
        } else {
            $data['tamu_hari_ini'] = null;
            $view                  = 'buku_tamu.kepuasan';
        }

        return view($view, $data);
    }

    public function jawaban($id = null, $jawaban = null): void
    {
        $tamu = BukuTamu::find($id);

        if (! $tamu || ! in_array($jawaban, JawabanKepuasanEnum::keys())) {
            set_session('error', 'Jawaban Gagal Disimpan');
        } else {
            $cek_pertanyaan = BukuKepuasan::whereIdNama($id)->pluck('id_pertanyaan');
            $pertanyaan     = BukuPertanyaan::whereNotIn('id', $cek_pertanyaan)->whereStatus(StatusEnum::YA)->first();
            BukuKepuasan::create([
                'id_nama'           => $tamu->id,
                'id_pertanyaan'     => $pertanyaan->id,
                'pertanyaan_statis' => $pertanyaan->pertanyaan,
                'id_jawaban'        => $jawaban,
            ]);

            // jika masih ada pertanyaan
            if ($this->cek_pertanyaan($id)) {
                set_session('success', '<h1>Jawaban Berhasil Disimpan</h1><br><br>Ke Pertanyaan Selanjutnya');

                redirect('buku-tamu/kepuasan/' . $id);
            }
        }

        redirect('buku-tamu/kepuasan/' . $id);
    }

    private function cek_pertanyaan($id = null)
    {
        $sudah_ada  = BukuKepuasan::whereIdNama($id)->pluck('id_pertanyaan');
        $pertanyaan = BukuPertanyaan::whereNotIn('id', $sudah_ada)->whereStatus(StatusEnum::YA)->first();

        if (! $pertanyaan) {
            set_session('success', '<h1>TERIMA KASIH</h1><br><br>Anda Telah Membantu Kami Untuk Melayani Lebih Baik Lagi.');
            redirect('buku-tamu');
        }

        return $pertanyaan;
    }

    private function validate($request = [])
    {
        return [
            'nama'          => htmlentities($request['nama']),
            'telepon'       => htmlentities($request['telepon']),
            'instansi'      => htmlentities($request['instansi']),
            'jenis_kelamin' => bilangan($request['jenis_kelamin']),
            'alamat'        => htmlentities($request['alamat']),
            'bidang'        => bilangan($request['id_bidang']),
            'keperluan'     => bilangan($request['id_keperluan']),
            'foto'          => $this->foto($request['foto']),
        ];
    }

    private function foto($base64 = null)
    {
        $nama_file = null;

        if ($base64) {
            $nama_file = time() . random_int(10000, 999999) . '.jpg';
            $base64    = str_replace('data:image/png;base64,', '', $base64);
            $base64    = base64_decode($base64, true);

            file_put_contents(FCPATH . LOKASI_FOTO_BUKU_TAMU . $nama_file, $base64);
        }

        return $nama_file;
    }
}
