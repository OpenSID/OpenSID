<?php

namespace App\Models;

use App\Traits\ConfigId;

class OneTimePassword extends \Spatie\OneTimePasswords\Models\OneTimePassword
{
    use ConfigId;
}
