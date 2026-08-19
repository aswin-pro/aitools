<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'priority',
        'name',
        'iso_code',
        'iso_numeric',
        'symbol',
        'subunit',
        'subunit_to_unit',
        'symbol_first',
        'html_entity',
        'decimal_mark',
        'thousands_separator',
        'status'
    ];
}
