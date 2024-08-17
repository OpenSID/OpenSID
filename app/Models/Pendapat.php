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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class Pendapat extends BaseModel
{
    use ConfigId;

    protected $table   = 'pendapat';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function scopePendapat($query, $tipe, $pilih = null)
    {
        $kondisi = $this->kondisi($tipe);
        $data    = $query->selectRaw('COUNT(pilihan) AS jumlah, pilihan')
            ->whereRaw($kondisi['where'])
            ->when($pilih, static fn ($query) => $query->where('pilihan', $pilih))
            ->groupBy('pilihan')
            ->orderBy('pilihan')
            ->get()
            ->toArray();

        $total = collect($data)->map(static fn ($item) => $item['jumlah'])->sum();

        return [
            'lblx'     => $kondisi['lblx'],
            'judul'    => $kondisi['judul'],
            'pendapat' => $data,
            'total'    => $total,
        ];
    }

    public function kondisi($tipe)
    {
        $tgl = date('Y-m-d');
        $bln = date('m');
        $thn = date('Y');

        $lblx = 'TANGGAL';

        switch ($tipe) {
            // Hari ini
            case 1:
                $judul = 'Hari Ini ( ' . tgl_indo2($tgl) . ')';
                $where = 'DATE(`tanggal`) = "' . $tgl . '"';
                break;

                // Kemarin
            case 2:
                $judul = 'Kemarin ( ' . tgl_indo2($this->op_tgl('-1 days', $tgl)) . ')';
                $where = 'DATE(`tanggal`) = "' . $this->op_tgl('-1 days', $tgl) . '"';
                break;

                // Minggu ini
            case 3:
                $judul = 'Dari tanggal ' . tgl_indo2($this->op_tgl('-6 days', $tgl)) . ' - ' . tgl_indo2($tgl);
                $where = 'DATE(`tanggal`) >= "' . $this->op_tgl('-6 days', $tgl) . '" AND DATE(`tanggal`) <= "' . $tgl . '"';
                break;

                // Bulan ini
            case 4:
                $judul = 'Bulan ' . ucwords(getBulan($bln)) . ' ' . $thn;
                $where = 'MONTH(`tanggal`) = ' . $bln . ' AND YEAR(`tanggal`)  = ' . $thn;
                break;

                // Tahun ini
            case 5:
                $lblx  = 'BULAN';
                $judul = 'Tahun ' . $thn;
                $where = 'YEAR(tanggal) = ' . $thn;
                break;

                // Semua jumlah pendapat
            default:
                $lblx  = 'TAHUN';
                $judul = 'Setiap Tahun';
                $where = 'tanggal IS NOT NULL';
                break;
        }

        return [
            'lblx'  => $lblx,
            'judul' => $judul,
            'where' => $where,
        ];
    }

    protected function op_tgl(string $op, string $tgl)
    {
        return date('Y-m-d', strtotime($op, strtotime($tgl)));
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'pengguna', 'id');
    }
}
