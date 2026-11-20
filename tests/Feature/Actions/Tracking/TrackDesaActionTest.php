<?php

namespace Tests\Feature\Actions\Tracking;

use App\Actions\Tracking\TrackDesaAction;
use App\Libraries\Tracker;
use PHPUnit\Framework\Attributes\Test;
use Tests\BaseTestCase;

class TrackDesaActionTest extends BaseTestCase
{
    #[Test]
    public function tracker_tidak_dipanggil_di_lingkungan_testing(): void
    {
        $tracker = $this->mock(Tracker::class);
        $tracker->shouldNotReceive('trackDesa');
        $this->instance(Tracker::class, $tracker);

        app(TrackDesaAction::class)->execute();
    }

    #[Test]
    public function tracker_dipanggil_di_lingkungan_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $tracker = $this->mock(Tracker::class);
        $tracker->shouldReceive('trackDesa')->once();
        $this->instance(Tracker::class, $tracker);

        app(TrackDesaAction::class)->execute();
    }
}
