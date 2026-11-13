<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [

        'title',
        'notice',
        'startdate',
        'enddate',
        'primary_color',
        'secondary_color',
        'status',
        'announcementday',
    ];

    protected $dates = ['startdate', 'enddate'];
}
