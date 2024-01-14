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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Enums\SistemEnum;
use App\Models\TeksBerjalan;

defined('BASEPATH') || exit('No direct script access allowed');

class Teks_berjalan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('teks_berjalan_model');
        $this->load->model('web_artikel_model');
        $this->urut_model    = new Urut_Model('teks_berjalan');
        $this->modul_ini     = 'admin-web';
        $this->sub_modul_ini = 'teks-berjalan';
    }

    public function index()
    {
        $main = TeksBerjalan::orderBy('urut')
            ->withCasts([
                'status' => 'string',
            ])
            ->get()
            ->map(static function ($teks, $key) {
                $teks->no        = $key + 1;
                $teks->tautan    = menu_slug('artikel/' . $teks->tautan);
                $teks->tampilkan = SistemEnum::valueOf($teks->tipe);

                return $teks;
            });

        return view('admin.web.teks_berjalan.index', compact('main'));
    }

    public function form($id = '')
    {
        $this->redirect_hak_akses('u');
        $data['list_artikel'] = $this->web_artikel_model->list_data(999, 6, 0);

        if ($id) {
            $data['teks']        = $this->teks_berjalan_model->get_teks($id) ?? show_404();
            $data['form_action'] = site_url("teks_berjalan/update/{$id}");
        } else {
            $data['teks']        = null;
            $data['form_action'] = site_url('teks_berjalan/insert');
        }

        $data['daftar_tampil'] = SistemEnum::all();

        $this->render('web/teks_berjalan/form', $data);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');

        if (TeksBerjalan::create($this->validated($this->request, $id))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = '')
    {
        $this->redirect_hak_akses('u');
        $this->teks_berjalan_model->update($id);
        redirect('teks_berjalan');
    }

    public function delete($id = '')
    {
        $this->redirect_hak_akses('h', 'teks_berjalan');
        $this->teks_berjalan_model->delete($id);
        redirect('teks_berjalan');
    }

    public function delete_all()
    {
        $this->redirect_hak_akses('h', 'teks_berjalan');
        $this->teks_berjalan_model->delete_all();
        redirect('teks_berjalan');
    }

    public function urut($id = 0, $arah = 0)
    {
        $this->redirect_hak_akses('u');
        $urut = $this->teks_berjalan_model->urut($id, $arah);
        redirect("teks_berjalan/index/{$page}");
    }

    public function lock($id = 0, $val = 1)
    {
        $this->redirect_hak_akses('u');
        $this->teks_berjalan_model->lock($id, $val);
        redirect('teks_berjalan');
    }

    protected function validated($request = [], $id = null)
    {
        $data = [
            'teks'         => htmlentities($request['teks']),
            'tautan'       => (int) $request['tautan'],
            'judul_tautan' => htmlentities($request['judul_tautan']),
            'tipe'         => (int) $request['tipe'],
        ];

        if ($id === null) {
            $data['config_id'] = identitas('id');
            $data['urut']      = $this->urut_model->urut_max() + 1;
        }

        return $data;
    }
}
