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

use App\Enums\StatusEnum;
use App\Models\Anjungan as AnjunganModel;

defined('BASEPATH') || exit('No direct script access allowed');

class Gawai_layanan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->modul_ini     = 14;
        $this->sub_modul_ini = 351;
    }

    public function index()
    {
        return view('admin.gawai_layanan.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(AnjunganModel::where('tipe', 2))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('gawai_layanan.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        $url_kunci = site_url("gawai_layanan/kunci/{$row->id}");

                        if ($row->status) {
                            $aksi .= '<a href="' . $url_kunci . '/' . StatusEnum::YA . '" class="btn bg-navy btn-sm" title="Nonaktifkan Gawai Layanan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . $url_kunci . '/' . StatusEnum::TIDAK . '" class="btn bg-navy btn-sm" title="Aktifkan Gawai Layanan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . route('gawai_layanan.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('ip_address_port_printer', static function ($row) {
                    return $row->printer_ip ?: '-' . ':' . $row->printer_port ?: '-';
                })
                ->editColumn('keyboard', static function ($row) {
                    return '<span class="label label-' . ($row->keyboard ? 'success' : 'danger') . '">' . StatusEnum::valueOf($row->keyboard) . '</span>';
                })
                ->editColumn('status', static function ($row) {
                    return '<span class="label label-' . ($row->status ? 'success' : 'danger') . '">' . StatusEnum::valueOf($row->status) . '</span>';
                })
                ->rawColumns(['ceklist', 'aksi', 'keyboard', 'status'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        $this->redirect_hak_akses('u');

        if ($id) {
            $data['action']        = 'Ubah';
            $data['form_action']   = route('gawai_layanan.update', $id);
            $data['gawai_layanan'] = AnjunganModel::find($id) ?? show_404();
        } else {
            $data['action']        = 'Tambah';
            $data['form_action']   = route('gawai_layanan.insert');
            $data['gawai_layanan'] = null;
        }

        return view('admin.gawai_layanan.form', $data);
    }

    public function insert()
    {
        $this->redirect_hak_akses('u');

        if (AnjunganModel::insert(static::validated($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null)
    {
        $this->redirect_hak_akses('u');

        $data = AnjunganModel::find($id) ?? show_404();

        if ($data->update(static::validated($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null)
    {
        $this->redirect_hak_akses('h');

        if (AnjunganModel::destroy($id ?? $this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function kunci($id = null, $val = StatusEnum::TIDAK)
    {
        $this->redirect_hak_akses('u');

        $kunci = AnjunganModel::find($id) ?? show_404();
        $kunci->update(['status' => ($val == StatusEnum::YA) ? StatusEnum::TIDAK : StatusEnum::YA]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    // Hanya filter inputan
    protected static function validated($request = [], $id = null)
    {
        $anjungan      = AnjunganModel::find($id);
        $ip_address    = AnjunganModel::tipe(2)->where('ip_address', $request['ip_address'])->first();
        $mac_address   = AnjunganModel::tipe(2)->where('mac_address', $request['mac_address'])->first();
        $id_pengunjung = AnjunganModel::tipe(2)->where('id_pengunjung', $request['id_pengunjung'])->first();

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
            'keterangan'    => htmlentities($request['keterangan']),
            'tipe'          => 2,
        ];

        if ($id) {
            $validated['created_by'] = $validated['updated_by'] = auth()->id;
        } else {
            $validated['created_by'] = auth()->id;
        }

        return $validated;
    }
}
