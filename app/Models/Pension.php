<?php


namespace App\Models;


use App\Enums\PensionType;

class Pension
{
    public function __construct( public PensionType $type, public string $value)
    {
    }
}
