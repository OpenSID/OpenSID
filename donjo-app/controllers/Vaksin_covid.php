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

use App\Models\Pamong;
use App\Models\Penduduk;

defined('BASEPATH') || exit('No direct script access allowed');

class Vaksin_covid extends Admin_Controller
{
    public $modul_ini        = 'kesehatan';
    public $sub_modul_ini    = 'vaksin';
    protected $_list_session = ['cari', 'dusun', 'vaksin', 'jenis_vaksin', 'tanggal_vaksin', 'umur'];
    protected $_set_page     = ['50', '100', '200'];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->model(['vaksin_covid_model', 'wilayah_model', 'pamong_model']);
    }

    public function filter($filter, $return = ''): void
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect($this->controller . "/{$return}");
    }

    public function search(): void
    {
        $cari = $this->input->post('cari');

        if ($cari != '') {
            $this->session->cari = $cari;
        } else {
            $this->session->unset_userdata('cari');
        }
        redirect($this->controller);
    }

    public function clear($return = ''): void
    {
        $this->session->unset_userdata($this->_list_session);
        $this->session->per_page = 50;
        redirect($this->controller . "/{$return}");
    }

    public function index(int $p = 1): void
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data = [
            'set_page'     => $this->_set_page,
            'list_dusun'   => $this->wilayah_model->list_dusun(),
            'main'         => $this->vaksin_covid_model->list_penduduk($p),
            'list_vaksin'  => $this->vaksin_covid_model->jenis_vaksin(),
            'paging'       => $this->vaksin_covid_model->paging($p),
            'per_page'     => $this->session->per_page,
            'cari'         => $this->session->cari,
            'func'         => 'index',
            'p'            => $p,
            'selected_nav' => 'daftar',
        ];

        foreach ($this->_list_session as $list) {
            $data[$list] = $this->session->{$list} ?? null;
        }

        $this->render('covid19/vaksin/index', $data);
    }

    public function form(): void
    {
        isCan('u');
        $this->session->unset_userdata($this->_list_session);
        $id_penduduk = $this->input->get('terdata');
        $data        = [
            'list_vaksin'   => $this->vaksin_covid_model->jenis_vaksin(),
            'list_penduduk' => $this->vaksin_covid_model->list_penduduk(0),
            'penduduk'      => $this->vaksin_covid_model->data_penduduk($id_penduduk ?? null),
        ];

        $this->render('covid19/vaksin/form', $data);
    }

    public function apipendudukvaksin()
    {
        if ($this->input->is_ajax_request()) {
            $cari = $this->input->get('q');

            $penduduk = Penduduk::select(['id', 'nik', 'nama', 'id_cluster'])
                ->when($cari, static function ($query) use ($cari): void {
                    $query->orWhere('nik', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%");
                })
                ->paginate(10);

            return json([
                'results' => collect($penduduk->items())
                    ->map(static fn ($item): array => [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                    ]),
                'pagination' => [
                    'more' => $penduduk->currentPage() < $penduduk->lastPage(),
                ],
            ]);
        }

        return show_404();
    }

    public function tampil_sertifikat($id_penduduk): void
    {
        $data = [
            'penduduk' => $this->vaksin_covid_model->data_penduduk($id_penduduk),
        ];

        $this->render('covid19/vaksin/sertifkat', $data);
    }

    public function berkas_vaksin($id_penduduk, $vaksin_ke): void
    {
        $this->berkas($id_penduduk, $vaksin_ke, false, false);
    }

    public function berkas($id_penduduk, $vaksin_ke, $form = false, $tampil = false): void
    {
        $data = $this->vaksin_covid_model->data_penduduk($id_penduduk);
        $url  = $this->controller . '/vaksin_covid/' . ($form) ?: "form?terdata={$id_penduduk}";
        ambilBerkas($data->{$vaksin_ke}, $url, null, LOKASI_VAKSIN, $tampil);
    }

    public function update(): void
    {
        isCan('u');
        $this->vaksin_covid_model->update_vaksin();

        if ($this->session->success == -1) {
            $this->session->dari_internal = true;
            redirect("{$this->controller}/form");
        } else {
            redirect("{$this->controller}/clear");
        }
    }

    public function laporan_penduduk(?int $p = 1): void
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $pamong = Pamong::penandaTangan()->get();

        $data = [
            'func'         => 'laporan_penduduk',
            'selected_nav' => 'laporan',
            'set_page'     => $this->_set_page,
            'main'         => $this->vaksin_covid_model->list_penduduk(),
            'paging'       => $this->vaksin_covid_model->paging($p),
            'kades'        => $data['sekdes'] = $pamong,
            'sekdes'       => $data['sekdes'] = $pamong,
            'isi'          => 'covid19/vaksin/cetak',
            'list_dusun'   => $this->wilayah_model->list_dusun(),
            'list_vaksin'  => $this->vaksin_covid_model->jenis_vaksin(),
            'sekdes_id'    => sekdes()->id,
        ];

        foreach ($this->_list_session as $list) {
            $data[$list] = $this->session->{$list} ?? null;
        }

        $this->render('covid19/vaksin/laporan_penduduk', $data);
    }

    public function laporan_penduduk_cetak($aksi): void
    {
        $sekdes             = (int) ($this->input->post('sekdes'));
        $data['pamong_ttd'] = $this->pamong_model->get_data($sekdes);
        $data['aksi']       = $aksi;
        $data['config']     = $this->header['desa'];
        $data['file']       = 'Laporan Hasil Rekap Vaksin Covid 19';
        $data['isi']        = 'covid19/vaksin/laporan_penduduk_print';
        $data['letak_ttd']  = ['2', '2', '1'];
        $data['main']       = $this->vaksin_covid_model->list_penduduk(0);

        $this->load->view('global/format_cetak', $data);
    }

    public function laporan_rekap(): void
    {
        $this->session->unset_userdata($this->_list_session);
        $umur          = $this->input->get('umur');
        $penduduk      = $this->vaksin_covid_model->rekap(0);
        $rekap         = $this->rekap($penduduk);
        $sasaran       = $this->vaksin_covid_model->rekap($umur);
        $rekap_sasaran = $this->rekap($sasaran);
        $pamong        = Pamong::penandaTangan()->get();
        $data          = [
            'selected_nav' => 'rekap',
            'main'         => $rekap,
            'sasaran'      => $rekap_sasaran,
            'kades'        => $data['sekdes'] = $pamong,
            'sekdes'       => $data['sekdes'] = $pamong,
            'umur'         => $data['sekdes'] = $umur,
            'isi'          => 'covid19/vaksin/cetak_rekap',
            'aksi'         => 'Cetak',
            'sekdes_id'    => sekdes()->id,
        ];

        $this->render('covid19/vaksin/laporan_rekap', $data);
    }

    public function laporan_rekap_cetak($aksi): void
    {
        $sekdes = (int) ($this->input->post('sekdes'));
        $umur   = $this->input->post('umur');
        $this->session->unset_userdata($this->_list_session);
        $penduduk             = $this->vaksin_covid_model->rekap(0);
        $rekap                = $this->rekap($penduduk);
        $sasaran              = $this->vaksin_covid_model->rekap($umur);
        $rekap_sasaran        = $this->rekap($sasaran);
        $data['pamong_ttd']   = $this->pamong_model->get_data($sekdes);
        $data['aksi']         = $aksi;
        $data['config']       = $this->header['desa'];
        $data['file']         = 'Laporan Hasil Rekap Vaksin Covid 19';
        $data['isi']          = 'covid19/vaksin/laporan_rekap_print';
        $data['letak_ttd']    = ['2', '2', '1'];
        $data['main']         = $rekap;
        $data['sasaran']      = $rekap_sasaran;
        $data['tanggal']      = tgl_indo(date('Y-m-d'));
        $data['umur_sasaran'] = explode('-', $umur);

        $this->load->view('global/format_cetak', $data);
    }

    public function rekap($penduduk)
    {
        $rekap = ['total_v1' => 0, 'total_v2' => 0, 'total_v3' => 0, 'total_belum' => 0, 'detail' => []];

        foreach ($penduduk as $value) {
            $value->dusun ??= 'Data ' . ucwords(setting('sebutan_dusun')) . ' Tidak Ada';
            if (! isset($rekap['detail'][$value->dusun])) {
                $rekap['detail'][$value->dusun] = ['vaksin_1' => 0, 'vaksin_2' => 0, 'vaksin_3' => 0, 'belum' => 0];
            }

            if ($value->vaksin_1 == null || $value->tunda == 1) {
                $rekap['detail'][$value->dusun]['belum']++;
                $rekap['total_belum']++;
            }

            $rekap['detail'][$value->dusun]['vaksin_1'] += $value->vaksin_1;
            $rekap['detail'][$value->dusun]['vaksin_2'] += $value->vaksin_2;
            $rekap['detail'][$value->dusun]['vaksin_3'] += $value->vaksin_3;
            $rekap['total_v1'] += $value->vaksin_1;
            $rekap['total_v2'] += $value->vaksin_2;
            $rekap['total_v3'] += $value->vaksin_3;
        }

        return $rekap;
    }

    public function autocomplete()
    {
        return json($this->vaksin_covid_model->autocomplete($this->input->post('cari')));
    }

    public function impor(): void
    {
        isCan('u');
        $this->vaksin_covid_model->impor();

        redirect('vaksin_covid');
    }
}
