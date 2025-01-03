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

use App\Services\LaporanPenduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Statistik_web extends Web_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('laporan_penduduk_model');
        $this->load->model('pamong_model');
        $this->load->model('program_bantuan_model');
    }

    private function get_data_stat(array &$data, $lap): void
    {
        $data['stat']         = LaporanPenduduk::judulStatistik($lap);
        $data['list_bantuan'] = $this->program_bantuan_model->list_program(0);
        if ((int) $lap > 50) {
            // Untuk program bantuan, $lap berbentuk '50<program_id>'
            $program_id             = preg_replace('/^50/', '', $lap);
            $data['program']        = $this->program_bantuan_model->get_sasaran($program_id);
            $data['judul_kelompok'] = $data['program']['judul_sasaran'];
            $data['kategori']       = 'bantuan';
        } elseif (in_array($lap, ['bantuan_penduduk', 'bantuan_keluarga'])) {
            $data['kategori'] = 'bantuan';
        } elseif ((int) $lap > 20 || "{$lap}" === 'kelas_sosial') {
            $data['kategori'] = 'keluarga';
        } else {
            $data['kategori'] = 'penduduk';
        }
    }

    public function dusun($tipe = 0, $lap = 0): void
    {
        $tipe_stat = $this->get_tipe_statistik($tipe);
        $this->session->unset_userdata('rw');
        $this->session->unset_userdata('rt');
        $dusun = $this->input->post('dusun');
        if ($dusun) {
            $this->session->set_userdata('dusun', $dusun);
        } else {
            $this->session->unset_userdata('dusun');
        }
        redirect("statistik_web/{$tipe_stat}/{$lap}");
    }

    public function rw($tipe = 0, $lap = 0): void
    {
        $tipe_stat = $this->get_tipe_statistik($tipe);
        $this->session->unset_userdata('rt');
        $rw = $this->input->post('rw');
        if ($rw) {
            $this->session->set_userdata('rw', $rw);
        } else {
            $this->session->unset_userdata('rw');
        }
        redirect("statistik_web/{$tipe_stat}/{$lap}");
    }

    public function rt($tipe = 0, $lap = 0): void
    {
        $tipe_stat = $this->get_tipe_statistik($tipe);
        $rt        = $this->input->post('rt');
        if ($rt) {
            $this->session->set_userdata('rt', $rt);
        } else {
            $this->session->unset_userdata('rt');
        }
        redirect("statistik_web/{$tipe_stat}/{$lap}");
    }

    public function load_chart_gis($lap = 0): void
    {
        $this->cek_akses($lap);

        $data['main'] = $this->laporan_penduduk_model->list_data($lap);

        $data['lap']       = $lap;
        $data['untuk_web'] = true;
        $this->get_data_stat($data, $lap);

        view('web.gis.penduduk_gis', $data);
    }

    public function chart_gis_desa($lap = 0, $desa = null): void
    {
        $this->session->desa = $desa;
        $this->session->unset_userdata(['dusun', 'rw', 'rt']);

        redirect("statistik_web/load_chart_gis/{$lap}");
    }

    public function chart_gis_dusun($lap = 0, $dusun = null): void
    {
        $this->session->dusun = $dusun;
        $this->session->unset_userdata(['rw', 'rt']);

        redirect("statistik_web/load_chart_gis/{$lap}");
    }

    public function chart_gis_rw($lap = 0, $dusun = null, $rw = null): void
    {
        $this->cek_akses($lap);

        $this->session->dusun = $dusun;
        $this->session->rw    = $rw;
        $this->session->unset_userdata(['rt']);

        redirect("statistik_web/load_chart_gis/{$lap}");
    }

    public function chart_gis_rt($lap = 0, $dusun = null, $rw = null, $rt = null): void
    {
        $this->cek_akses($lap);

        $this->session->dusun = $dusun;
        $this->session->rw    = $rw;
        $this->session->rt    = $rt;

        redirect("statistik_web/load_chart_gis/{$lap}");
    }

    public function chart_gis_kadus($id_kepala = ''): void
    {
        $this->cek_akses($lap);

        ($dusun) ? $this->session->set_userdata('dusun', $dusun) : $this->session->unset_userdata('dusun');
        $this->session->unset_userdata('rw');
        $this->session->unset_userdata('rt');

        redirect("statistik_web/load_kadus/{$id_kepala}");
    }

    public function load_kadus($id_kepala = ''): void
    {
        $data['individu'] = $this->wilayah_model->get_penduduk($dusun['id_kepala']);

        $this->load->view('gis/kadus/', $data);
    }

    private function cek_akses($lap): ?bool
    {
        $pengaturan = json_decode((string) setting('tampilkan_tombol_peta'), true);

        if (((int) $lap > 50 || in_array($lap, ['bantuan_penduduk', 'bantuan_keluarga'])) && in_array('Statistik Bantuan', $pengaturan)) {
            return true;
        }
        if (((int) $lap > 20 || "{$lap}" === 'kelas_sosial') && in_array('Statistik Keluarga', $pengaturan)) {
            return true;
        }
        if (((int) $lap < 20) && in_array('Statistik Penduduk', $pengaturan)) {
            return true;
        }

        show_404();
    }
}
