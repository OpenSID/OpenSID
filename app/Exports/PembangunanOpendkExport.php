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

namespace App\Exports;

use App\Models\LogSinkronisasi;
use App\Models\Pembangunan;
use Rap2hpoutre\FastExcel\FastExcel;

class PembangunanOpendkExport
{
    protected $fields = [
        'id',
        'sumber_dana',
        'lokasi',
        'judul',
        'keterangan',
        'volume',
        'tahun_anggaran',
        'pelaksana_kegiatan',
        'status',
        'anggaran',
        'perubahan_anggaran',
        'sumber_biaya_pemerintah',
        'sumber_biaya_provinsi',
        'sumber_biaya_kab_kota',
        'sumber_biaya_swadaya',
        'sumber_biaya_jumlah',
        'manfaat',
        'waktu',
        'sifat_proyek',
        'foto',
    ];

    public function __construct(protected $p)
    {
    }

    public function filename($name = null)
    {
        return $name ?? namafile('pembangunan');
    }

    public function data()
    {
        $ci       = &get_instance();
        $kodeDesa = identitas()->kode_desa;

        $limit            = 100;
        $p                = $this->p;
        $tgl_sinkronisasi = LogSinkronisasi::where('modul', '=', 'program-bantuan')->first()->updated_at ?? null;

        $dataExport = Pembangunan::when($tgl_sinkronisasi != null, static fn ($q) => $q->where('updated_at', '>', $tgl_sinkronisasi))
            ->when($tgl_sinkronisasi == null, static fn ($q) => $q->skip($p * $limit)->take($limit))
            ->get($this->fields)->map(static function ($item) use ($kodeDesa, $ci) {
            $data      = collect($item->toArray());
            $file_foto = LOKASI_GALERI . $data['foto'];
            if (is_file($file_foto)) {
                $ci->zip->read_file($file_foto);
            }
            $data->prepend(kode_wilayah($kodeDesa), 'desa_id');

            return $data->toArray();
        })->toArray();

        if (empty($dataExport)) {
            return [emptyData($this->fields)];
        }

        return $dataExport;
    }

    public function download()
    {
        return (new FastExcel())->data($this->data())->download($this->filename());
    }

    public function export()
    {
        $filePath = sys_get_temp_dir() . '/' . $this->filename() . '.csv';

        return (new FastExcel())->data($this->data())->export($filePath);
    }

    public function zip(): string
    {
        $ci   = &get_instance();
        $data = $this->export();
        $ci->zip->read_file($data);
        $filename = $this->filename() . '.zip';
        $ci->zip->archive(LOKASI_SINKRONISASI_ZIP . $filename);

        return $filename;
    }
}
