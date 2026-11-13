<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userrating extends Model
{
    use HasFactory;

    protected $fillable = [

        'cust_id',
        'ticket_id',
        'ratingstar',
        'ratingcomment',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'cust_id', 'id');
    }
}
