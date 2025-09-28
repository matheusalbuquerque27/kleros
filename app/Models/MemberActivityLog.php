<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberActivityLog extends Model
{
    protected $table = 'member_activity_logs';

    protected $fillable = [
        'user_id',
        'membro_id',
        'action',
        'route',
        'method',
        'url',
        'ip_address',
        'user_agent',
        'payload',
        'subject_type',
        'subject_id',
        'logged_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'logged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membro()
    {
        return $this->belongsTo(Membro::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
