<?php

namespace App\Models;

use App\Casts\MoneyType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NIS extends Model
{
    use HasFactory;

    protected $casts = [
        'effective_date' => 'date',
        'annual_income_threshold' => MoneyType::class.':JMD'
    ];

    public function scopeMostRecentNisEntryForDate(Builder $query, string $dateString = NULL): Builder
    {
        $carbonDate = Carbon::parse($dateString) ?? Carbon::now();
        return $query->where('effective_date', '<=', $carbonDate)->orderBy('effective_date', 'DESC');
    }

    public static function findEntryForDate(string $dateString = NULL):NIS|null
    {
       return NIS::mostRecentNisEntryForDate($dateString)->first();
    }
}
