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

use App\Enums\StatusEnum;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

class MediaSosial extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media_sosial';

    /**
     * The timestamps for the model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gambar',
        'link',
        'nama',
        'tipe',
        'enabled',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $appends = [
        'url_icon',
        'new_link',
    ];

    public function scopeStatus($query, $status = null)
    {
        if ($status === null) {
            return $query;
        }

        return $query->where('enabled', $status);
    }

    public function getEnabledAttribute()
    {
        if (empty($this->attributes['link'])) {
            return $this->attributes['enabled'] = StatusEnum::TIDAK;
        }

        return $this->attributes['enabled'];
    }

    public function getUrlIconAttribute()
    {
        $gambar = $this->attributes['gambar'];

        if (in_array($gambar, ['fb.png', 'twt.png', 'yb.png', 'ins.png', 'wa.png', 'tg.png'])) {
            return asset("front/{$gambar}");
        }

        return base_url(LOKASI_ICON_SOSMED . $gambar);
    }

    public function getNewLinkAttribute()
    {
        return $this->linkLama($this->attributes['nama'], $this->attributes['tipe'], $this->attributes['link']);
    }

    protected function linkLama($nama, $tipe, $link)
    {

        $valid_link = filter_var($link, FILTER_VALIDATE_URL);

        switch (true) {
            case $nama === 'Facebook' && $tipe === 1:
                return $valid_link ? $link : 'https://web.facebook.com/' . $link;

            case $nama === 'Facebook' && $tipe === 2:
                return $valid_link ? $link : 'https://web.facebook.com/groups/' . $link;

            case $nama === 'Twitter':
                return $valid_link ? $link : 'https://twitter.com/' . $link;

            case $nama === 'YouTube':
                return $valid_link ? $link : 'https://www.youtube.com/channel/' . $link;

            case $nama === 'Instagram':
                return $valid_link ? $link : 'https://www.instagram.com/' . $link . '/';

            case $nama === 'WhatsApp' && $tipe === 1:
                $link = ($valid_link ? $link : 'https://api.whatsapp.com/send?phone=' . $link);

                return str_replace('phone=0', 'phone=62', $link);

            case $nama === 'WhatsApp' && $tipe === 2:
                return $valid_link ? $link : 'https://chat.whatsapp.com/' . $link;

            case $nama === 'Telegram' && $tipe === 1:
                return $valid_link ? $link : 'https://t.me/' . $link;

            case $nama === 'Telegram' && $tipe === 2:
                return $valid_link ? $link : 'https://t.me/joinchat/' . $link;

            default:
                return $link;
        }
    }

    protected static function booted()
    {
        parent::boot();

        cache()->forget('media_sosial');
    }
}
