<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssistantKb extends Model
{
    protected $table = 'assistant_kb';

    protected $fillable = ['question', 'answer', 'keywords', 'cta_label', 'cta_url', 'active', 'sort_order'];

    protected $casts = ['active' => 'boolean'];
}
