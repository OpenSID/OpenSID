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

namespace App\Notifications\Kehadiran;

use App\Notifications\BaseNotification;
use Modules\Kehadiran\Models\PengajuanIzin;

class PengajuanIzinBaru extends BaseNotification
{
    public function __construct(private PengajuanIzin $pengajuanIzin)
    {
        $this->pengajuanIzin->load('pamong');
    }

    public function getNotificationSlug(): string
    {
        return 'pengajuan_izin';
    }

    public function getTitle(): string
    {
        return 'Pengajuan Izin Baru';
    }

    public function getMessage(): string
    {
        $pamongNama = $this->pengajuanIzin->pamong?->pamong_nama ?? 'Perangkat Desa';

        return "Pengajuan izin baru dari {$pamongNama}";
    }

    public function getUrl(): string
    {
        return ci_route('kehadiran_pengajuan_izin');
    }

    public function getData(): array
    {
        return [
            'pengajuan_izin_id' => $this->pengajuanIzin->id,
            'id_pamong'         => $this->pengajuanIzin->id_pamong,
            'jenis_izin'        => $this->pengajuanIzin->jenis_izin,
            'tanggal_mulai'     => $this->pengajuanIzin->tanggal_mulai,
            'tanggal_selesai'   => $this->pengajuanIzin->tanggal_selesai,
        ];
    }
}
