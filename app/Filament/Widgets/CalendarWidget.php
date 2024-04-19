<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Meeting;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    protected int | string | array $columnSpan = 'full';

    public string | null | Model $model = Meeting::class;

    public function fetchEvents(array $info): array
    {
        return Meeting::whereBetween('start', [$info['start'], $info['end']])->get()->toArray();
    }


    public function getFormSchema(): array
    {
        return [
            TextInput::make('title'),
            Select::make('client_id')
                ->options(
                    Client::select(
                        DB::raw(
                            'COALESCE(first_name, " ") || " " || COALESCE(last_name, " ") || " - " || COALESCE(company, " ") AS name, id'
                        )
                    )->get()->pluck('name', 'id')->toArray()
                )
                ->searchable()
                ->required(),
            Textarea::make('summary'),
            DateTimePicker::make('start')
                ->required(),
            DateTimePicker::make('end')
                ->required(),
        ];
    }


}
