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

use App\Enums\StatusPengaduanEnum;
use App\Models\Pengaduan;

defined('BASEPATH') || exit('No direct script access allowed');

class Pengaduan_admin extends Admin_Controller
{
    public $modul_ini = 'pengaduan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data = $this->widget();

        return view('admin.pengaduan_warga.index', $data);
    }

    protected function widget(): array
    {
        return [
            'allstatus'   => Pengaduan::status()->count(),
            'status1'     => Pengaduan::status(StatusPengaduanEnum::MENUNGGU_DIPROSES)->count(),
            'status2'     => Pengaduan::status(StatusPengaduanEnum::SEDANG_DIPROSES)->count(),
            'status3'     => Pengaduan::status(StatusPengaduanEnum::SELESAI_DIPROSES)->count(),
            'm_allstatus' => Pengaduan::bulanan()->count(),
            'm_status1'   => Pengaduan::bulanan(StatusPengaduanEnum::MENUNGGU_DIPROSES)->count(),
            'm_status2'   => Pengaduan::bulanan(StatusPengaduanEnum::SEDANG_DIPROSES)->count(),
            'm_status3'   => Pengaduan::bulanan(StatusPengaduanEnum::SELESAI_DIPROSES)->count(),
        ];
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status');

            return datatables()->of(Pengaduan::tipe()->filter($status))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('pengaduan_admin.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Tanggapi Pengaduan"><i class="fa fa-mail-forward"></i></a> ';
                    }

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('pengaduan_admin.detail', $row->id) . '" class="btn btn-info btn-sm"  title="Lihat Detail"><i class="fa fa-eye"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('pengaduan_admin.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('status', static fn ($row): string => '<span class="label ' . StatusPengaduanEnum::label()[$row->status] . '">' . ucwords(StatusPengaduanEnum::valueOf($row->status)) . ' </span>')
                ->editColumn('created_at', static fn ($row): string => tgl_indo2($row->created_at))
                ->rawColumns(['ceklist', 'aksi', 'status'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $action          = 'Tanggapi Pengaduan';
            $form_action     = ci_route('pengaduan_admin.kirim', $id);
            $pengaduan_warga = Pengaduan::findOrFail($id);
        }

        return view('admin.pengaduan_warga.form', ['action' => $action, 'form_action' => $form_action, 'pengaduan_warga' => $pengaduan_warga]);
    }

    public function kirim($id): void
    {
        isCan('u');

        try {
            $pengaduan = Pengaduan::findOrFail($id);
            $pengaduan->update(['status' => $this->request['status']]);

            Pengaduan::where('id_pengaduan', $id)->update(['status' => $this->request['status']]);

            Pengaduan::create([
                'config_id'    => $pengaduan->config_id,
                'id_pengaduan' => $id,
                'nama'         => $this->session->nama,
                'isi'          => bersihkan_xss($this->request['isi']),
                'status'       => $this->request['status'],
                'ip_address'   => $this->input->ip_address() ?? '',
            ]);

            redirect_with('success', 'Berhasil Ditanggapi');
        } catch (Exception $e) {
            log_message('error', $e);
        }

        redirect_with('error', 'Gagal Ditanggapi');
    }

    public function detail($id = '')
    {
        isCan('u');

        if ($id) {
            $action    = 'Detail Pengaduan';
            $pengaduan = Pengaduan::findOrFail($id);
        }

        return view('admin.pengaduan_warga.detail', ['action' => $action, 'pengaduan' => $pengaduan]);
    }

    public function delete($id = null): void
    {
        isCan('h');

        try {
            Pengaduan::destroy($id ?? $this->request['id_cb']);

            if ($id) {
                $this->request['id_cb'] = [$id];
            }
            Pengaduan::whereIn('id_pengaduan', $this->request['id_cb'])->delete();

            redirect_with('success', 'Berhasil Hapus Data');
        } catch (Exception $e) {
            log_message('error', $e);
        }

        redirect_with('error', 'Gagal Hapus Data');
    }
}
