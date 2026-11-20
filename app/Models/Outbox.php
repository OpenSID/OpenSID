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

namespace App\Models;

use App\Traits\ConfigId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property \Illuminate\Support\Carbon $UpdatedInDB
 * @property \Illuminate\Support\Carbon $InsertIntoDB
 * @property string $SendingDateTime
 * @property string $SendBefore
 * @property string $SendAfter
 * @property string|null $Text
 * @property string $DestinationNumber
 * @property string $Coding
 * @property string|null $UDH
 * @property int|null $Class
 * @property string $TextDecoded
 * @property int $ID
 * @property int $config_id
 * @property string|null $MultiPart
 * @property int|null $RelativeValidity
 * @property string|null $SenderID
 * @property string|null $SendingTimeOut
 * @property string|null $DeliveryReport
 * @property string|null $CreatorID
 * @property-read \App\Models\DaftarKontak|null $kontak
 * @property-read \App\Models\PendudukSaja|null $penduduk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereCoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereCreatorID($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereDeliveryReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereDestinationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereInsertIntoDB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereMultiPart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereRelativeValidity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereSendAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereSendBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereSenderID($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereSendingDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereSendingTimeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereTextDecoded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereUDH($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox whereUpdatedInDB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Outbox withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Outbox extends BaseModel
{
    use ConfigId;

    public const CREATED_AT = 'InsertIntoDB';
    public const UPDATED_AT = 'UpdatedInDB';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'outbox';

    protected $guarded    = [];
    protected $primaryKey = 'ID';

    /**
     * Get the penduduk that owns the Inbox
     */
    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(PendudukSaja::class, 'DestinationNumber', 'telepon');
    }

    /**
     * Get the kontak that owns the Inbox
     */
    public function kontak(): BelongsTo
    {
        return $this->belongsTo(DaftarKontak::class, 'DestinationNumber', 'telepon');
    }
}
