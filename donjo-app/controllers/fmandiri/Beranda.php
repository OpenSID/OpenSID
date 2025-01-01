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

use App\Models\KelompokAnggota;
use App\Models\Keluarga;
use App\Models\Pendapat;
use App\Models\Penduduk;
use App\Models\PendudukMandiri;
use App\Models\PesanMandiri;

defined('BASEPATH') || exit('No direct script access allowed');

class Beranda extends Mandiri_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['mandiri_model', 'penduduk_model', 'kelompok_model', 'web_dokumen_model']);
        $this->load->helper('download');
    }

    public function index(): void
    {
        $inbox = PesanMandiri::belumDibaca($this->is_login->id_pend)->count();
        if ($inbox) {
            redirect('layanan-mandiri/pesan-masuk');
        } else {
            redirect('layanan-mandiri/permohonan-surat');
        }
    }

    public function profil()
    {
        $data = [
            'penduduk' => Penduduk::find($this->is_login->id_pend),
            'kelompok' => KelompokAnggota::with([
                'kelompok' => [
                    'kelompokMaster',
                ],
                'anggota',
            ])
                ->where('id_penduduk', $this->is_login->id_pend)
                ->get(),
        ];

        return view('layanan_mandiri.profil.index', $data);
    }

    public function cetak_biodata()
    {
        $data = [
            'desa'     => $this->header,
            'penduduk' => Penduduk::find($this->is_login->id_pend),
        ];

        return view('layanan_mandiri.kependudukan.cetak_biodata', $data);
    }

    public function cetak_kk()
    {
        $id = $this->is_login->id_kk;
        if ($id == null) {
            // Jika diakses melalui URL
            $respon = [
                'status' => 1,
                'pesan'  => 'Anda tidak terdaftar dalam sebuah keluarga',
            ];
            $this->session->set_flashdata('notif', $respon);

            redirect('layanan-mandiri/beranda');
        }
        $getdata          = Keluarga::with(['anggota', 'kepalaKeluarga'])->find($id);
        $kk['main']       = $getdata->anggota;
        $kk['desa']       = identitas();
        $kk['kepala_kk']  = $getdata->kepalaKeluarga;
        $data['all_kk'][] = $kk;

        return view('layanan_mandiri.kependudukan.cetak_kk_all', $data);
    }

    public function ganti_pin()
    {
        $data = [
            'tgl_verifikasi_telegram' => $this->otp->driver('telegram')->cekVerifikasiOtp($this->is_login->id_pend),
            'tgl_verifikasi_email'    => $this->otp->driver('email')->cekVerifikasiOtp($this->is_login->id_pend),
            'cek_anjungan'            => $this->cek_anjungan,
            'form_action'             => site_url('layanan-mandiri/proses-ganti-pin'),
        ];

        return view('layanan_mandiri.pin.ganti_pin', $data);
    }

    public function proses_ganti_pin(): void
    {
        $id_pend         = $this->is_login->id_pend;
        $nama            = $this->session->is_login->nama;
        $pendudukMandiri = new PendudukMandiri();
        $pendudukMandiri->gantiPin($id_pend, $nama, $this->input->post());

        redirect('layanan-mandiri/ganti-pin');
    }

    // TODO: Pindahkan ke model
    public function pendapat(int $pilihan = 1): void
    {
        $data = [
            'config_id' => identitas('id'),
            'pengguna'  => $this->is_login->id_pend,
            'pilihan'   => $pilihan,
        ];

        Pendapat::create($data);
        redirect('layanan-mandiri/keluar');
    }
}
