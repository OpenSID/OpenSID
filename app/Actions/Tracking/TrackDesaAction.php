<?php

namespace App\Actions\Tracking;

use App\Libraries\Tracker;

class TrackDesaAction
{
    public function __construct(
        protected Tracker $tracker,
    ) {}

    public function execute(): void
    {
        if (app()->environment('testing')) {
            return;
        }

        $this->tracker->trackDesa();
    }
}
