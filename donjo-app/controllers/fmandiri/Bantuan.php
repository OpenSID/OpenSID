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

defined('BASEPATH') || exit('No direct script access allowed');

use App\models\BantuanPeserta;

class Bantuan extends Mandiri_Controller
{
    public function index()
    {
        return view('layanan_mandiri.bantuan.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $query = BantuanPeserta::with('bantuan')
                ->where('peserta', $this->is_login->nik);

            return datatables($query)
                ->addIndexColumn()
                ->addColumn('waktu', static fn ($item): string => fTampilTgl($item->bantuan->sdate, $item->bantuan->edate))
                ->addColumn('aksi', static function ($item): string {
                    $aksi = '';
                    if ($item->no_id_kartu) {
                        $tampilUrl = ci_route('layanan-mandiri.bantuan.kartu_peserta', ['aksi' => 'tampil', 'id' => $item->id]);
                        $unduhUrl  = ci_route('layanan-mandiri.bantuan.kartu_peserta', ['aksi' => 'unduh', 'id' => $item->id]);

                        $aksi .= '<button type="button" target="data_peserta" title="Data Peserta" href="' . $tampilUrl . '" onclick="show_kartu_peserta($(this));" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></button> ';
                        $aksi .= '<a href="' . $unduhUrl . '" class="btn bg-black btn-sm" title="Kartu Peserta" ' . (empty($item->kartu_peserta) ? 'disabled' : '') . '><i class="fa fa-download"></i></a>';
                    }

                    return $aksi;
                })
                ->rawColumns(['waktu', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function kartu_peserta($aksi = 'tampil', $id_peserta = '')
    {
        $data = BantuanPeserta::find($id_peserta);

        // Hanya boleh menampilkan data pengguna yang login
        // ** Bagi program sasaran pendududk **
        // TO DO : Ganti parameter nik menjadi id
        if ($aksi == 'tampil') {
            return view('layanan_mandiri.bantuan.peserta', ['data' => $data]);
        }

        ambilBerkas($data['kartu_peserta'], 'layanan-mandiri/bantuan', null, LOKASI_DOKUMEN);
    }
}
