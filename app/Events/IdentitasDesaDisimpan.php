<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Event netral: admin baru saja menyimpan data identitas desa.
 *
 * Modul yang perlu bereaksi (mis. Pelanggan memperbarui token langganan
 * karena kode_desa bisa berubah) memasang listener di ServiceProvider-nya.
 * Core tak tahu modul mana pun.
 */
class IdentitasDesaDisimpan
{
    use Dispatchable;
}
