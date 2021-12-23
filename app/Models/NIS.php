<?php

namespace App\Models;

use App\Casts\MoneyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NIS extends Model
{
    use HasFactory;

    protected $casts = [
        'effective_date' => 'date',
        'annual_income_threshold' => MoneyType::class . ':JMD'
        ];
}
