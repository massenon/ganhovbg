<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Journal anonymisé : aucun texte, aucune IP, aucun identifiant utilisateur. */
class AssistantLog extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'assistant_logs';

    protected $fillable = ['kb_id', 'escalated', 'matched'];

    protected $casts = ['escalated' => 'boolean', 'matched' => 'boolean'];
}
