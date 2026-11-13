<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'lang_code',
        'group_langname',
        'key',
        'value',
    ];

    protected static function booted()
    {
        $bump = function () { \Illuminate\Support\Facades\Cache::forever('gnlang_ver', (string) microtime(true)); };
        static::saved($bump);
        static::deleted($bump);
    }
}
