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

use App\Models\Penduduk;
use App\Models\PesanMandiri;

defined('BASEPATH') || exit('No direct script access allowed');

class Mailbox extends Admin_Controller
{
    public $modul_ini     = 'layanan-mandiri';
    public $sub_modul_ini = 'kotak-pesan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(int $kategori): void
    {
        $data['submenu']  = array_flip(unserialize(KATEGORI_MAILBOX));
        $data['kategori'] = $kategori;

        view('admin.mailbox.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $tipe       = $this->input->get('tipe');
            $status     = $this->input->get('status');
            $pendudukId = $this->input->get('nik');

            $canDelete = can('h');
            $canUpdate = can('u');

            return datatables()->of(PesanMandiri::with(['penduduk'])->whereTipe($tipe)
                ->when($pendudukId, static fn ($q) => $q->wherePendudukId($pendudukId))
                ->when($status, static function ($q) use ($status): void {
                    match ($status) {
                        1, 2 => $q->whereStatus($status),
                        default => $q->where(['is_archived' => 1]),
                    };
                }))
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if (! $canDelete) {
                        return;
                    }
                    if ($row->isArchive()) {
                        return;
                    }

                    return '<input type="checkbox" name="id_cb[]" value="' . $row->uuid . '"/>';
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete): string {
                    $aksi = '';
                    if ($canDelete && ! $row->isArchive()) {
                        $aksi .= '<a href="#" data-href="' . ci_route('mailbox.delete.' . $row->tipe, $row->uuid) . '" class="btn bg-maroon btn-sm"  title="Arsipkan pesan" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-file-archive-o"></i></a> ';
                    }

                    if ($canUpdate) {
                        $aksi .= '<a href="' . ci_route('mailbox.detail.' . $row->tipe, $row->uuid) . '" class="btn bg-navy btn-sm"  title="Lihat detail pesan"><i class="fa fa-list"></i></a> ';
                        if ($row->tipe == 1) {
                            if ($row->isRead()) {
                                $aksi .= '<a href="' . ci_route('mailbox.read.' . $row->tipe, $row->uuid) . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-envelope-open-o"></i></a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route('mailbox.read.' . $row->tipe, $row->uuid) . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-envelope-o"></i></a> ';
                            }
                        }
                    }

                    return $aksi;
                })
                ->addColumn('status', static fn ($row): string => $row->status == '1' ? 'Sudah Dibaca' : 'Belum Dibaca')
                ->editColumn('tgl_upload', static fn ($row): string => tgl_indo2($row->tgl_upload))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function detail($tipe, $id): void
    {
        $pesan = PesanMandiri::with(['penduduk'])->findOrFail($id);
        $data  = [
            'pesan'         => $pesan->toArray(),
            'readonly'      => 1,
            'labelPengirim' => $tipe == 1 ? 'Pengirim' : 'Penerima',
            'form_action'   => ci_route('mailbox.form', $tipe),
        ];
        view('admin.mailbox.detail', $data);
    }

    public function form($tipe): void
    {
        isCan('u');

        $data['pesan'] = [
            'subjek' => $this->request['subjek'] ?? '',
        ];

        $pendudukId       = $this->request['penduduk_id'] ?? '';
        $data['individu'] = [];
        if ($pendudukId) {
            $pendudukTerpilih = Penduduk::withOnly(['Wilayah', 'keluarga'])->find($pendudukId);
            if ($pendudukTerpilih) {
                $data['individu'] = $pendudukTerpilih->toArray();
            }
        }

        $data['form_action'] = ci_route('mailbox.kirim_pesan');

        view('admin.mailbox.form', $data);
    }

    public function kirim_pesan(): void
    {
        isCan('u');

        $pendudukId = trim((string) $this->request['penduduk_id']);
        $owner      = Penduduk::find($pendudukId);
        PesanMandiri::create([
            'owner'       => strtoupper($owner->nama),
            'penduduk_id' => $owner->id,
            'subjek'      => strip_tags((string) $this->request['subjek']),
            'komentar'    => strip_tags((string) $this->request['komentar']),
            'status'      => PesanMandiri::UNREAD,
            'tipe'        => PesanMandiri::KELUAR,
        ]);
        redirect_with('success', 'Pesan berhasil dikirim', ci_route('mailbox', PesanMandiri::KELUAR));
    }

    public function list_pendaftar_mandiri_ajax(): void
    {
        $cari                   = $this->input->get('q'); //$this->input->get('page');
        $list_pendaftar_mandiri = Penduduk::whereHas('mandiri')->withOnly(['Wilayah', 'keluarga'])->where(static fn ($r) => $r->where('nama', 'like', '%' . $cari . '%')->orWhere('nik', 'like', '%' . $cari . '%'))->offset(15)->simplePaginate();
        $data                   = $list_pendaftar_mandiri->items();
        $result                 = [];
        if ($data) {
            foreach ($data as $q) {
                $result[] = ['id' => $q->id, 'text' => $q->nik . ' - ' . $q->nama . PHP_EOL . $q->alamat_wilayah];
            }
        }

        echo json_encode(['results' => $result, 'pagination' => ''], JSON_THROW_ON_ERROR);
    }

    public function delete($tipe, $id = null): void
    {
        isCan('h');
        $listId = $id ? [$id] : $this->request['id_cb'];

        if (PesanMandiri::whereIn('uuid', $listId)->update(['is_archived' => 1])) {
            redirect_with('success', 'Berhasil Mengarsipkan Data', ci_route('mailbox', $tipe));
        }
        redirect_with('error', 'Gagal Mengarsipkan Data', ci_route('mailbox', $tipe));
    }

    public function read($tipe, $id): void
    {
        isCan('u');

        try {
            $pesan         = PesanMandiri::findOrFail($id);
            $nextStatus    = $pesan->isRead() ? PesanMandiri::UNREAD : PesanMandiri::READ;
            $pesan->status = $nextStatus;
            $pesan->save();
            redirect_with('success', 'Berhasil ubah status', ci_route('mailbox', $tipe));
        } catch (Exception $e) {
            redirect_with('error', 'Gagal ubah status ' . $e->getMessage(), ci_route('mailbox', $tipe));
        }
    }
}
