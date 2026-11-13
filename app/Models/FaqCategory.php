<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FaqCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'faqcategoryname',
        'status',
    ];

    public function faqdetails(){

        return $this->hasMany(FAQ::class, 'faqcat_id', 'id');
    }
}
