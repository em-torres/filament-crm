<?php

namespace App\Filament\Widgets;

use App\Models\Meeting;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    protected int | string | array $columnSpan = 'full';

    public function fetchEvents(array $info): array
    {
        return Meeting::whereBetween('start', [$info['start'], $info['end']])->get()->toArray();
    }
}
