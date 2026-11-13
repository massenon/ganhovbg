<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCustomfield extends Model
{
    use HasFactory;

    protected $table = 'ticket_customfields';
    protected $fillable = [

        'cust_id',
        'fieldnames',
        'fieldtypes',
        'fieldtypes',
        'values',
        'privacymode',
        'ticket_id',

    ];
}
