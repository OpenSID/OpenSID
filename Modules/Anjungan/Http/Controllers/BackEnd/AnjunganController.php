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

use App\Enums\AktifEnum;
use App\Enums\StatusEnum;
use Modules\Anjungan\Models\Anjungan as AnjunganModel;
use Illuminate\Support\Str;

defined('BASEPATH') || exit('No direct script access allowed');

class AnjunganController extends AdminModulController
{
    public $moduleName      = 'Anjungan';
    public $modul_ini       = 'anjungan';
    public $sub_modul_ini   = 'daftar-anjungan';
    public $aliasController = 'anjungan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    // Hanya filter inputan
    protected static function validate(array $request = [], $id = null): array
    {
        if (! empty($request['mac_address'])) {
            $mac_address_owner = AnjunganModel::where('mac_address', $request['mac_address'])->first();

            if ($mac_address_owner) {
                // If creating a new record, any existing mac address is a duplicate.
                // If updating, it's a duplicate if the mac address is owned by another record.
                if (! $id || ($id && $mac_address_owner->id !== (int) $id)) {
                    redirect_with('error', 'Mac Address telah digunakan');
                }
            }
        }

        $tipe = [];
        if (! empty($request['rekam_kehadiran'])) {
            $tipe = [AnjunganModel::ANJUNGAN, AnjunganModel::KEHADIRAN];
        } else {
            $tipe = [AnjunganModel::ANJUNGAN]; // Default ANJUNGAN
        }

        $validated = [
            'uuid'                        => strip_tags($request['uuid'] ?? '') ?: Str::uuid()->toString(),
            'user_agent'                  => strip_tags($request['user_agent'] ?? ''),
            'ip_address'                  => strip_tags($request['ip_address'] ?? ''),
            'mac_address'                 => alfanumerik_kolon($request['mac_address'] ?? ''),
            'id_pengunjung'               => alfanumerik($request['id_pengunjung'] ?? ''),
            'printer_ip'                  => bilangan_titik($request['printer_ip'] ?? ''),
            'printer_port'                => bilangan($request['printer_port'] ?? ''),
            'orientasi_layar'             => bilangan($request['orientasi_layar'] ?? ''),
            'keyboard'                    => bilangan($request['keyboard'] ?? ''),
            'permohonan_surat_tanpa_akun' => bilangan($request['permohonan_surat_tanpa_akun'] ?? ''),
            'keterangan'                  => htmlentities($request['keterangan'] ?? ''),
            'tipe'                        => $tipe,
        ];

        $validated['created_by'] = $id ? $validated['updated_by'] = ci_auth()->id : ci_auth()->id;

        return $validated;
    }

    public function index()
    {
        return view('anjungan::backend.anjungan.index');
    }

    public function datatables()
    {
        $status = cek_anjungan();

        if (request()->ajax()) {
            return datatables()->of(AnjunganModel::query()->latest())
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->editColumn('uuid', static fn ($row) => $row->uuid ?: '-')
                ->editColumn('ip_address', static fn ($row) => $row->ip_address ?: '-')
                ->editColumn('id_pengunjung', static fn ($row) => $row->id_pengunjung ?: '-')
                ->addColumn('aksi', static function ($row) use ($status): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('anjungan.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        $url_kunci = site_url("anjungan/kunci/{$row->id}");
                        $disabled  = $status !== '' && $status !== '0' ? '' : 'disabled';

                        if ($status === '' || $status === '0') {
                            $aksi .= '<a href="#" class="btn bg-navy btn-sm" title="Aktifkan Anjungan" {$disabled}><i class="fa fa-lock"></i></a> ';
                        } elseif ($row->status) {
                            $aksi .= '<a href="' . $url_kunci . '/' . StatusEnum::YA . '" class="btn bg-navy btn-sm" title="Nonaktifkan Anjungan" ' . $disabled . '><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . $url_kunci . '/' . StatusEnum::TIDAK . '" class="btn bg-navy btn-sm" title="Aktifkan Anjungan" ' . $disabled . '><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-uuid="' . $row->uuid . '" data-href="' . ci_route('anjungan.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('ip_address_port_printer', static fn ($row) => ($row->printer_ip ?: '-:' . $row->printer_port) ?: '-')
                ->editColumn('keyboard', static fn ($row): string => '<span class="label label-' . ($row->keyboard ? 'success' : 'danger') . '">' . AktifEnum::valueOf($row->keyboard) . '</span>')
                ->editColumn('permohonan_surat_tanpa_akun', static fn ($row): string => '<span class="label label-' . ($row->permohonan_surat_tanpa_akun ? 'success' : 'danger') . '">' . AktifEnum::valueOf($row->permohonan_surat_tanpa_akun) . '</span>')
                ->editColumn('status', static fn ($row): string => '<span class="label label-' . ($row->status ? 'success' : 'danger') . '">' . AktifEnum::valueOf($row->status) . '</span>')
                ->rawColumns(['ceklist', 'aksi', 'uuid', 'keyboard', 'status', 'permohonan_surat_tanpa_akun'])
                ->make();
        }

        return show_404();
    }

    public function form($id = null)
    {
        isCan('u');

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = ci_route('anjungan.update', $id);
            $data['anjungan']    = AnjunganModel::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('anjungan.insert');
            $data['anjungan']    = null;
        }

        return view('anjungan::backend.anjungan.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        if (AnjunganModel::create(static::validate($this->request))) {
            redirect_with('success', 'Berhasil Tambah Data');
        }
        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = null): void
    {
        isCan('u');

        $data = AnjunganModel::findOrFail($id);

        if ($data->update(static::validate($this->request, $id))) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id = null): void
    {
        isCan('h');

        if (AnjunganModel::destroy($id ?? $this->request['id_cb']) > 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        } else {
            redirect_with('error', 'Gagal Hapus Data');
        }
    }

    public function kunci($id = null, $val = StatusEnum::TIDAK): void
    {
        isCan('u');

        if (cek_anjungan() === '' || cek_anjungan() === '0') {
            redirect_with('warning', 'Untuk mengaktifkan harus memesan anjungan terlebih dahulu.');
        }

        $kunci = AnjunganModel::findOrFail($id);
        $kunci->update(['status' => ($val == StatusEnum::YA) ? StatusEnum::TIDAK : StatusEnum::YA, 'status_alasan' => null]);

        redirect_with('success', 'Berhasil Ubah Data');
    }

    public function verify()
    {
        $validated = $this->validated(request(), [
            'uuid' => 'required|string',
        ]);

        $anjungan = AnjunganModel::where('uuid', $validated['uuid'])->first();

        if (! $anjungan) {
            return json([
                'status'  => 'invalid',
                'message' => 'UUID tidak ditemukan di server.',
            ]);
        }

        return json([
            'status'  => 'valid',
            'message' => 'UUID valid dan terdaftar.',
            'data'    => $anjungan,
        ]);
    }

    public function delete_device($uuid = null)
    {
        if (! $uuid) {
            redirect_with('error', 'UUID device tidak ditemukan.');
        }

        $anjungan = AnjunganModel::where('uuid', $uuid)->first();

        if ($anjungan && $anjungan->delete()) {
            redirect_with('success', 'Berhasil menghapus device anjungan.');
        }

        redirect_with('error', 'Gagal menghapus device anjungan atau device tidak ditemukan.');
    }
}
