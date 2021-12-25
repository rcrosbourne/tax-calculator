<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $effective_date
 * @property array $annual_thresholds
 */
class IncomeTax extends Model
{
    use HasFactory;
    protected $casts = [
        'effective_date' => 'date',
        'annual_thresholds' => 'array'
    ];

    public function scopeMostRecentIncomeTaxEntryForDate(Builder $query, string $dateString = null): Builder
    {
        $carbonDate = Carbon::parse($dateString) ?? Carbon::now();
        return $query->where('effective_date', '<=', $carbonDate)->orderBy('effective_date', 'DESC');
    }

    public static function findEntryForDate(string $dateString = null): ?IncomeTax
    {
        return IncomeTax::mostRecentIncomeTaxEntryForDate($dateString)->first();
    }
}
