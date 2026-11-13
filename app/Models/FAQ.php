<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;

    protected $table = 'faq_list';

    protected $fillable = [
        
        'question',
        'answer',
        'status',
        'privatemode',
        'faqcat_id',
       
    ];

    protected $guarded = ['id']; 

    public function faqcategory(){

        return $this->belongsTo(FaqCategory::class, 'faqcat_id', 'id');
    }
}
