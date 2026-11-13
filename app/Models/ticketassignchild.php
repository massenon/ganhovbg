<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ticketassignchild extends Model
{
    use HasFactory;

    public function toassignuser()
    {
        return $this->belongsTo(User::class, 'toassignuser_id');
    }
}
