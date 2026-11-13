<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ratingtoken extends Model
{
    use HasFactory;

    protected $fillable = [

        'token',
        'ticket_id',
    ];
}
