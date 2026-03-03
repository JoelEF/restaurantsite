<?php

namespace App\Filament\Pages;

use App\Models\BusinessHour;
use App\Models\Holiday;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class OpeningstijdenBeheer extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Openingstijden';
    protected static ?string $title           = 'Openingstijden & Sluitingsdagen';
    protected static ?string $slug            = 'openingstijden';
    protected static ?string $navigationGroup = 'Instellingen';
    protected static ?int    $navigationSort  = 1;

    protected static string $view = 'filament.pages.openingstijden-beheer';

    // Openingstijden per dag: [day_of_week => [is_open, open_time, close_time]]
    public array $hours = [];

    // Nieuwe feestdag
    public string $holidayDate     = '';
    public string $holidayName     = '';
    public bool   $holidayIsClosed = true;
    public string $holidayOpenTime  = '12:00';
    public string $holidayCloseTime = '22:00';

    public function mount(): void
    {
        $existing = BusinessHour::all()->keyBy('day_of_week');

        for ($day = 0; $day <= 6; $day++) {
            $record = $existing->get($day);
            $this->hours[$day] = [
                'is_open'    => $record?->is_open ?? true,
                'open_time'  => $record ? substr($record->open_time, 0, 5) : '11:00',
                'close_time' => $record ? substr($record->close_time, 0, 5) : '22:00',
            ];
        }
    }

    public function getHolidays()
    {
        return Holiday::orderBy('date')->get();
    }

    public function saveHours(): void
    {
        DB::transaction(function () {
            foreach ($this->hours as $day => $data) {
                BusinessHour::updateOrCreate(
                    ['day_of_week' => $day],
                    [
                        'is_open'    => (bool) $data['is_open'],
                        'open_time'  => $data['open_time'],
                        'close_time' => $data['close_time'],
                    ]
                );
            }
        });

        Notification::make()->title('Openingstijden opgeslagen.')->success()->send();
    }

    public function addHoliday(): void
    {
        if (empty($this->holidayDate)) {
            Notification::make()->title('Vul een datum in.')->danger()->send();
            return;
        }

        Holiday::updateOrCreate(
            ['date' => $this->holidayDate],
            [
                'name'       => $this->holidayName ?: null,
                'is_closed'  => $this->holidayIsClosed,
                'open_time'  => $this->holidayIsClosed ? null : $this->holidayOpenTime,
                'close_time' => $this->holidayIsClosed ? null : $this->holidayCloseTime,
            ]
        );

        $this->holidayDate = '';
        $this->holidayName = '';
        $this->holidayIsClosed = true;

        Notification::make()->title('Sluitingsdag toegevoegd.')->success()->send();
    }

    public function deleteHoliday(int $id): void
    {
        Holiday::destroy($id);
        Notification::make()->title('Verwijderd.')->success()->send();
    }
}
