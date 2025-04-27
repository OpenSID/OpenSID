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

use App\Models\Kategori;
use App\Models\Komentar as ModelsKomentar;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Komentar extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'komentar';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        $defaultStatus = request('status', ModelsKomentar::ACTIVE);
        view('admin.komentar.index', ['defaultStatus' => $defaultStatus]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status') ?? null;

            return datatables()->of(ModelsKomentar::with('artikel')->whereNull('parent_id')
                ->when(in_array($status, [ModelsKomentar::ACTIVE, ModelsKomentar::NONACTIVE]), static fn ($q) => $q->where('status', $status))
                ->when(in_array($status, [ModelsKomentar::UNREAD]), static fn ($q) => $q->unread()))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('komentar.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        $aksi .= '<a href="' . ci_route('komentar.detail', $row->id) . '" class="btn btn-info btn-sm"  title="Balas Komentar"><i class="fa fa-mail-forward"></i></a> ';

                        $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                            'url'    => site_url("komentar/lock/{$row->id}"),
                            'active' => $row->status,
                        ])->render();
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('komentar.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->addColumn('enabled', static fn ($row): string => $row->status == '1' ? 'Ya' : 'Tidak')
                ->editColumn('dimuat_pada', static fn ($row): string => tgl_indo2($row->tgl_upload))
                ->editColumn('judul_artikel', static fn ($row): string => '<a href="' . $row->artikel->url_slug . '" target="_blank">' . $row->artikel->judul . '</a>')
                ->rawColumns(['ceklist', 'enabled', 'aksi', 'dimuat_pada', 'judul_artikel'])
                ->make();
        }

        return show_404();
    }

    public function form($id = ''): void
    {
        isCan('u');

        if ($id) {
            $data['komentar']    = ModelsKomentar::findOrFail($id);
            $data['form_action'] = ci_route('komentar.update', $id);
        } else {
            $data['komentar']    = null;
            $data['form_action'] = ci_route('komentar.insert');
        }

        $data['list_kategori'] = Kategori::whereTipe(1)->get();

        view('admin.komentar.form', $data);
    }

    public function update($id = ''): void
    {
        isCan('u');

        $data = $this->validasi($this->input->post());
        $url  = site_url('komentar');

        try {
            ModelsKomentar::findOrFail($id)->update($data);
            redirect_with('success', __('notification.updated.success'), $url);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', __('notification.updated.error'), $url);
        }
    }

    private function validasi(array $post)
    {
        $data['owner']    = htmlentities((string) $post['owner']);
        $data['no_hp']    = bilangan($post['no_hp']);
        $data['email']    = email($post['email']);
        $data['komentar'] = htmlentities((string) $post['komentar']);
        if (isset($post['status'])) {
            $data['status'] = bilangan($post['status']);
        }

        return $data;
    }

    public function insert(): void
    {
        isCan('u');
        $data = $this->validasi($this->input->post());

        try {
            ModelsKomentar::create($data);
            redirect_with('success', __('notification.created.success'));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', __('notification.created.error'));
        }

        redirect('komentar');
    }

    public function detail($id = ''): void
    {
        isCan('u');

        $komentar = ModelsKomentar::with('children')->find($id) ?? show_404();

        // Cek apakah komentar masih unread
        if ($komentar->updated_at <= $komentar->tgl_upload) {
            $komentar->touch();
            redirect("{$this->controller}/detail/{$id}");
        }

        $data['komentar']    = $komentar->toArray();
        $data['form_action'] = site_url("komentar/balas/{$id}");

        view('admin.komentar.detail', $data);
    }

    public function balas($id = ''): void
    {
        isCan('u');

        $komentar = ModelsKomentar::findOrFail($id);

        $data = [
            'id_artikel' => $komentar->id_artikel,
            'komentar'   => htmlentities((string) $this->input->post('komentar')),
            'owner'      => ci_auth()->id,
            'status'     => ModelsKomentar::ACTIVE,
            'parent_id'  => $komentar->id,
        ];

        try {
            if (! $komentar->isActive()) {
                $komentar->status = ModelsKomentar::ACTIVE;
                $komentar->save();
            }
            ModelsKomentar::create($data);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', __('notification.created.error'));
        }

        redirect_with('success', __('notification.created.success'), "{$this->controller}/detail/{$id}");
    }

    public function delete($parent_id = null, $id = ''): void
    {
        isCan('h');

        if (! empty($id)) {
            $to = site_url("komentar/detail/{$parent_id}");
        } else {
            $to = site_url('komentar');
            $id = $parent_id;
        }

        if (ModelsKomentar::destroy($id)) {
            redirect_with('success', __('notification.deleted.success'), $to);
        }
        redirect_with('error', __('notification.deleted.error'), $to);
    }

    public function delete_all(): void
    {
        isCan('h');
        if (ModelsKomentar::destroy($this->request['id_cb'])) {
            redirect_with('success', __('notification.deleted.success'));
        }

        redirect_with('error', __('notification.deleted.error'));
    }

    public function lock($id = 0): void
    {
        isCan('u');
        if (ModelsKomentar::gantiStatus($id, 'status')) {
            redirect_with('success', __('notification.status.success'));
        }
        redirect_with('error', __('notification.status.error'));
    }
}
