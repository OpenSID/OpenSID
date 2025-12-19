<?php

namespace App\Models;

use App\Traits\ConfigId;

class DatabaseNotification extends \Illuminate\Notifications\DatabaseNotification
{
    use ConfigId;
}
