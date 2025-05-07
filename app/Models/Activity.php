<?php

namespace App\Models;

use App\Traits\ConfigId;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use ConfigId;
}