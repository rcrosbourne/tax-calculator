<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon $effective_date
 * @property string $rate_percentage
 */
class NHT extends Model
{
    use HasFactory;
    protected $casts = [
        'effective_date' => 'date',
    ];

    public function scopeMostRecentNhtEntryForDate(Builder $query, string $dateString = null): Builder
    {
        $carbonDate = Carbon::parse($dateString) ?? Carbon::now();
        return $query->where('effective_date', '<=', $carbonDate)->orderBy('effective_date', 'DESC');
    }

    public static function findEntryForDate(string $dateString = null): ?NHT
    {
        return NHT::mostRecentNhtEntryForDate($dateString)->first();
    }
}
