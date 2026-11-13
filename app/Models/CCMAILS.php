<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCMAILS extends Model
{
    use HasFactory;

    protected  $table = 'ticketsccemails';

    protected  $fillable = [
        'ticket_id',
        'ccemails',
    ];
}
