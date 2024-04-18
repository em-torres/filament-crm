<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;

class RegisteredClientsChart extends ChartWidget
{
    protected static ?string $heading;

    protected int | string | array $columnSpan = ['md' => 2, 'lg' => 1];

    public function __construct()
    {
        self::$heading = __("Client Registration Chart");
    }

    public static function canView(): bool
    {
        return true;
    }

    protected function getData(): array
    {
        $data = Trend::model(Client::class)
                    ->between(now()->subMonths(6), now())
                    ->perMonth()
                    ->count();

        return [
            'datasets' => [
                [
                    'label' => __('Last semester client registrations'),
                    'data' => $data->map(fn ($value) => $value->aggregate)->toArray(),
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'lineTension' => 0.1,
                ],
            ],
            'labels' => $data->map(fn ($value) => $value->date)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
