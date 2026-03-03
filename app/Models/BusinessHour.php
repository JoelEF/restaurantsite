<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BusinessHour extends Model
{
    protected $fillable = ['day_of_week', 'open_time', 'close_time', 'is_open'];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public static array $dayNames = [
        0 => 'Zondag',
        1 => 'Maandag',
        2 => 'Dinsdag',
        3 => 'Woensdag',
        4 => 'Donderdag',
        5 => 'Vrijdag',
        6 => 'Zaterdag',
    ];

    public function getDayNameAttribute(): string
    {
        return self::$dayNames[$this->day_of_week] ?? 'Onbekend';
    }

    /**
     * Controleer of het restaurant nu open is (rekening houdend met feestdagen).
     */
    public static function isOpenNow(): bool
    {
        $now = Carbon::now();

        // Check feestdag
        $holiday = Holiday::where('date', $now->toDateString())->first();
        if ($holiday) {
            if ($holiday->is_closed) return false;
            // Aangepaste tijden op feestdag
            $open = Carbon::parse($now->toDateString() . ' ' . $holiday->open_time);
            $close = Carbon::parse($now->toDateString() . ' ' . $holiday->close_time);
            return $now->between($open, $close);
        }

        // Normale openingstijden
        $hours = self::where('day_of_week', $now->dayOfWeek)->first();
        if (!$hours || !$hours->is_open) return false;

        $open = Carbon::parse($now->toDateString() . ' ' . $hours->open_time);
        $close = Carbon::parse($now->toDateString() . ' ' . $hours->close_time);

        return $now->between($open, $close);
    }

    /**
     * Geeft vandaag's openingstijden terug als leesbare string.
     */
    public static function todayHours(): string
    {
        $now = Carbon::now();

        $holiday = Holiday::where('date', $now->toDateString())->first();
        if ($holiday) {
            if ($holiday->is_closed) return 'Gesloten (' . ($holiday->name ?: 'feestdag') . ')';
            return substr($holiday->open_time, 0, 5) . ' – ' . substr($holiday->close_time, 0, 5);
        }

        $hours = self::where('day_of_week', $now->dayOfWeek)->first();
        if (!$hours || !$hours->is_open) return 'Gesloten';
        return substr($hours->open_time, 0, 5) . ' – ' . substr($hours->close_time, 0, 5);
    }
}
