<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bussinesshours extends Model
{
    use HasFactory;

    protected $table = 'bussinesshours';

    protected $fillable  = [
        'weeks',
        'starttime',
        'endtime',
        'status',
        'no_id',
    ];
}
