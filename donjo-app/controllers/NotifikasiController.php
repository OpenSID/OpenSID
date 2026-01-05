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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Services\NotificationService;
use Illuminate\Support\Facades\View;

class NotifikasiController extends Admin_Controller
{
    /**
     * Display all notifications
     */
    public function index()
    {
        return view('admin.notifikasi.index', [
            'kategori' => collect(config('notifications.categories'))->mapWithKeys(static fn ($item) => [$item['slug'] => $item['label']]),
        ]);
    }

    /**
     * Get list notifikasi untuk datatable
     */
    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $query = auth('admin')->user()->notifications()
                ->when($this->input->get('status') === 'read', static function ($q) {
                    $q->whereNotNull('read_at');
                })
                ->when($this->input->get('status') === 'unread', static function ($q) {
                    $q->whereNull('read_at');
                })
                ->when($this->input->get('kategori'), function ($q) {
                    $q->where('data->category', $this->input->get('kategori'));
                })
                ->orderBy('created_at', 'desc');

            return datatables($query)
                ->addIndexColumn()
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addColumn('status', static function ($row) {
                    if ($row->unread()) {
                        return '<span class="label label-danger">Belum dibaca</span>';
                    }

                    return '<span class="label label-success">Dibaca</span>';
                })
                ->addColumn('kategori', static function ($row) {
                    $data = $row->data;

                    return '<span class="label" style="background-color: ' . ($data['color'] ?? '#666') . '; color: white;">'
                        . ($data['label'] ?? 'Notifikasi')
                        . '</span>';
                })
                ->addColumn('pesan', static function ($row) {
                    $data = $row->data;

                    return '<strong>' . ($data['title'] ?? 'Notifikasi') . '</strong><br/>'
                        . '<small>' . ($data['message'] ?? '') . '</small><br/>'
                        . '<small style="color: #999;"><i class="fa fa-clock-o"></i> ' . $row->created_at->diffForHumans() . '</small>';
                })
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if ($row->unread()) {
                        $aksi .= View::make('admin.layouts.components.buttons.btn', [
                            'url'        => ci_route('notifikasi.mark-as-read', $row->id),
                            'icon'       => 'fa fa-check',
                            'judul'      => 'Tandai dibaca',
                            'type'       => 'bg-olive',
                            'buttonOnly' => true,
                        ])->render();
                    }

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => ci_route('notifikasi.delete', $row->id),
                        'confirmDelete' => true,
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.lihat', [
                        'url'   => "notifikasi/show/{$row->id}",
                        'blank' => true,
                    ])->render();

                    return $aksi;
                })
                ->rawColumns(['ceklist', 'status', 'kategori', 'pesan', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function show($id)
    {
        NotificationService::markAsRead(auth()->user(), $id);

        return redirect(auth()->user()->notifications()->find($id)?->data['url'] ?? '/');
    }

    /**
     * Mark notification as read
     *
     * @param mixed $id
     */
    public function markAsRead($id)
    {
        $user   = auth('admin')->user();
        $status = NotificationService::markAsRead($user, $id);

        if ($this->input->is_ajax_request()) {
            return json(['status' => $status, 'message' => 'Berhasil Tandai Sebagai Dibaca']);
        }

        return redirect_with('success', 'Berhasil Tandai Sebagai Dibaca', 'notifikasi');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = auth('admin')->user();
        NotificationService::markAllAsRead($user, $this->request['id_cb'] ?? []);

        return redirect_with('success', 'Berhasil Tandai Semua Sebagai Dibaca', 'notifikasi');
    }

    /**
     * Mark category as read
     *
     * @param mixed $category
     */
    public function markCategoryAsRead($category)
    {
        $user = auth('admin')->user();
        NotificationService::markCategoryAsRead($user, $category);

        return redirect_with('success', 'Berhasil Tandai Kategori Sebagai Dibaca', 'notifikasi');
    }

    /**
     * Delete notification
     *
     * @param mixed $id
     */
    public function delete($id)
    {
        auth('admin')->user()->notifications()->where('id', $id)->delete();

        return redirect_with('success', 'Berhasil Hapus Notifikasi', 'notifikasi');
    }

    public function deleteAll()
    {
        auth('admin')->user()->notifications()->whereIn('id', $this->request['id_cb'])->delete();

        return redirect_with('success', 'Berhasil Hapus Semua Notifikasi', 'notifikasi');
    }
}
