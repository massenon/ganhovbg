<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key', 'value'
    ];

    protected static function booted()
    {
        $flush = function () { unset($GLOBALS['gn_settings_memo']); };
        static::saved($flush);
        static::deleted($flush);
    }
}
