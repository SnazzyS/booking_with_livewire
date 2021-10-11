<?php


namespace App\Bookings;

use App\Models\Schedule;
use App\Models\Service;
use Carbon\CarbonInterval;

class TimeSlotGenerator
{
    protected $interval;
    public const INCREMENT = 15;

    public function __construct(Schedule $schedule, Service $service)
    {
        $this->interval = CarbonInterval::minute(self::INCREMENT)
            ->toPeriod(
                $schedule->date->setTimeFrom($schedule->start_time),
                $schedule->date->setTimeFrom(
                    $schedule->end_time->subMinute($service->duration)
                )
            );
    }

    public function applyFilters(array $filters)
    {
        foreach ($filters as $filter) {
            if (!$filter instanceof Filter) {
                continue;
            }

            $filter->apply($this, $this->interval);
        }

        return $this;
    }

    public function getInterval()
    {
        return $this->interval;
    }
}
