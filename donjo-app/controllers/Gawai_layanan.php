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

use App\Enums\StatusEnum;
use App\Models\Gawai;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Gawai_layanan extends Admin_Controller
{
    public $modul_ini     = 'layanan-mandiri';
    public $sub_modul_ini = 'gawai-layanan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    // Hanya filter inputan
    protected static function validate($request = [], $id = null)
    {
        $anjungan      = Gawai::find($id);
        $ip_address    = Gawai::where('ip_address', $request['ip_address'])->first();
        $mac_address   = Gawai::where('mac_address', $request['mac_address'])->first();
        $id_pengunjung = Gawai::where('id_pengunjung', $request['id_pengunjung'])->first();

        if ($ip_address && $anjungan->ip_address != $request['ip_address']) {
            redirect_with('error', 'IP Address telah digunakan');
        }

        if ($mac_address && $anjungan->mac_address != $request['mac_address']) {
            redirect_with('error', 'Mac Address telah digunakan');
        }

        if ($id_pengunjung && $anjungan->id_pengunjung != $request['id_pengunjung']) {
            redirect_with('error', 'ID Pengunjung telah digunakan');
        }

        $validated = [
            'ip_address'    => bilangan_titik($request['ip_address']),
            'mac_address'   => alfanumerik_kolon($request['mac_address']),
            'id_pengunjung' => alfanumerik($request['id_pengunjung']),
            'printer_ip'    => bilangan_titik($request['printer_ip']),
            'printer_port'  => bilangan($request['printer_port']),
            'keyboard'      => bilangan($request['keyboard']),
            'keterangan'    => htmlentities((string) $request['keterangan']),
            'status'        => $request['status'] ?? 0,
        ];

        $validated['created_by'] = $id ? $validated['updated_by'] = ci_auth()->id : ci_auth()->id;

        return $validated;
    }

    public function index()
    {
        return view('admin.gawai_layanan.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status = $this->input->get('status') ?? null;
            $query  = Gawai::status($status);

            return datatables()->of($query)
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url' => '/gawai_layanan/form/' . $row->id,
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.tombol_aktifkan', [
                        'url'    => '/gawai_layanan/kunci/' . $row->id,
                        'active' => $row->status,
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.hapus', [
                        'url'           => '/gawai_layanan/delete/' . $row->id,
                        'confirmDelete' => true,
                    ])->render();

                    return $aksi;
                })
                ->editColumn('ip_address_port_printer', static fn ($row) => ($row->printer_ip ?: '-:' . $row->printer_port) ?: '-')
                ->editColumn('keyboard', static fn ($row): string => '<span class="label label-' . ($row->keyboard ? 'success' : 'danger') . '">' . StatusEnum::valueOf($row->keyboard) . '</span>')
                ->editColumn('status', static fn ($row): string => '<span class="label label-' . ($row->status ? 'success' : 'danger') . '">' . StatusEnum::valueOf($row->status) . '</span>')
                ->rawColumns(['ceklist', 'aksi', 'keyboard', 'status'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $data['action']        = 'Ubah';
            $data['form_action']   = ci_route('gawai_layanan.update', $id);
            $data['gawai_layanan'] = Gawai::findOrFail($id);
        } else {
            $data['action']        = 'Tambah';
            $data['form_action']   = ci_route('gawai_layanan.insert');
            $data['gawai_layanan'] = null;
        }

        return view('admin.gawai_layanan.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (Gawai::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = Gawai::findOrFail($id);

        if ($data->update(static::validate($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (Gawai::destroy($id ?? $this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function kunci($id = null, $val = StatusEnum::TIDAK): void
    {
        isCan('u');

        $kunci = Gawai::findOrFail($id);
        $kunci->update(['status' => ($val == StatusEnum::YA) ? StatusEnum::TIDAK : StatusEnum::YA]);

        redirect_with('success', 'Berhasil Ubah Data');
    }
}
