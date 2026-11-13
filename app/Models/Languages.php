<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'languagename',
        'languagenativename',
        'is_rtl',
        'languagecode',
    ];

    /**
     * Relationships between blog categories & blog articles.
     */
    public function translates()
    {
        return $this->hasMany(Translate::class, 'lang_code', 'languagecode');
    }
}
