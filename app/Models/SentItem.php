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
 * @property string|null $DeliveryDateTime
 * @property string $Text
 * @property string $DestinationNumber
 * @property string $Coding
 * @property string $UDH
 * @property string $SMSCNumber
 * @property int $Class
 * @property string $TextDecoded
 * @property int $ID
 * @property int $config_id
 * @property string $SenderID
 * @property int $SequencePosition
 * @property string $Status
 * @property int $StatusError
 * @property int $TPMR
 * @property int $RelativeValidity
 * @property string $CreatorID
 * @property-read \App\Models\DaftarKontak|null $kontak
 * @property-read \App\Models\PendudukSaja|null $penduduk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereCoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereCreatorID($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereDeliveryDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereDestinationNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereID($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereInsertIntoDB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereRelativeValidity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereSMSCNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereSenderID($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereSendingDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereSequencePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereStatusError($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereTPMR($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereTextDecoded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereUDH($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem whereUpdatedInDB($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SentItem withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class SentItem extends BaseModel
{
    use ConfigId;

    public const CREATED_AT = 'InsertIntoDB';
    public const UPDATED_AT = 'UpdatedInDB';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sentitems';

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
