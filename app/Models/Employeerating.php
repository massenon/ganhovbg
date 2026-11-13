<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employeerating extends Model
{
    use HasFactory;

    protected $fillable = [
        'urating_id',
        'rating',
        'user_id',
    ];

    public function ticketrating()
    {
        return $this->belongsTo(Userrating::class, 'urating_id', 'id');
    }
}
