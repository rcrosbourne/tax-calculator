<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $rate_percentage
 * @property Carbon $effective_date
 */
class EducationTax extends Model
{
    use HasFactory;

    protected $casts = [
        'effective_date' => 'date',
    ];

    public function scopeMostRecentEducationTaxEntryForDate(Builder $query, string $dateString = null): Builder
    {
        $carbonDate = Carbon::parse($dateString) ?? Carbon::now();
        return $query->where('effective_date', '<=', $carbonDate)->orderBy('effective_date', 'DESC');
    }

    public static function findEntryForDate(string $dateString = null): ?EducationTax
    {
        return EducationTax::mostRecentEducationTaxEntryForDate($dateString)->first();
    }
}
