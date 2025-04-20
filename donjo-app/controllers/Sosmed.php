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

use App\Enums\StatusEnum;
use App\Models\MediaSosial;
use App\Traits\Upload;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

defined('BASEPATH') || exit('No direct script access allowed');

class Sosmed extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'media-sosial';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        return view('admin.sosmed.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(MediaSosial::query())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("sosmed/form/{$row->id}") . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';

                        if ($row->enabled == StatusEnum::YA) {
                            $aksi .= '<a href="' . site_url("sosmed/lock/{$row->id}") . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . site_url("sosmed/lock/{$row->id}") . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . site_url("sosmed/delete/{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('url_icon', static fn ($row): string => '<a href="' . $row->new_link . '" target="_blank"><img src="' . $row->url_icon . '" class="img-thumbnail" width="50" height="50"></a>')
                ->editColumn('enabled', static fn ($row): string => ($row->enabled == StatusEnum::YA) ? '<span class="label label-success">Aktif</span>' : '<span class="label label-danger">Tidak Aktif</span>')
                ->rawColumns(['ceklist', 'aksi', 'url_icon', 'enabled'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = site_url("sosmed/update/{$id}");
            $data['sosmed']      = MediaSosial::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = site_url('sosmed/insert');
            $data['sosmed']      = null;
        }

        return view('admin.sosmed.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (MediaSosial::create($this->validate($this->request))) {
            redirect_with('success', __('notification.created.success'));
        }
        redirect_with('error', __('notification.created.error'));
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = MediaSosial::findOrFail($id);

        if ($data->update($this->validate($this->request, $id))) {
            redirect_with('success', __('notification.updated.success'));
        }
        redirect_with('error', __('notification.updated.error'));
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (MediaSosial::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', __('notification.deleted.success'));
        }
        redirect_with('error', __('notification.deleted.error'));
    }

    public function lock($id = 0): void
    {
        isCan('h');

        if (MediaSosial::where('id', $id)->where(static fn ($q) => $q->whereNull('link')->orWhere('link', ''))->exists()) {
            redirect_with('error', __('notification.status.error') . ', data ini tidak bisa diaktifkan karena belum memiliki link');
        }

        if (MediaSosial::gantiStatus($id, 'enabled')) {
            redirect_with('success', __('notification.status.success'));
        }

        redirect_with('error', __('notification.status.error'));
    }

    protected function validate(array $request = [], $id = null): array
    {
        $data = [
            'link'    => $request['link'],
            'nama'    => htmlentities((string) $request['nama']),
            'tipe'    => 1,
            'enabled' => $request['enabled'] ?? 0,
        ];

        if (! empty($id) && empty($request['gambar'])) {
            unset($data['gambar']);
        } else {
            $data['gambar'] = $this->upload(
                file: 'gambar',
                config: [
                    'upload_path'   => LOKASI_ICON_SOSMED,
                    'allowed_types' => 'jpg|jpeg|png|webp',
                    'max_size'      => 1024, // 1 MB,
                    'overwrite'     => true,
                ],
                callback: static function ($uploadData) {
                    Image::load($uploadData['full_path'])
                        ->width(100)
                        ->height(100)
                        ->format(Manipulations::FORMAT_WEBP)
                        ->save("{$uploadData['file_path']}{$uploadData['raw_name']}.webp");

                    // Hapus original file
                    unlink($uploadData['full_path']);

                    return "{$uploadData['raw_name']}.webp";
                }
            );
        }

        return $data;
    }
}
