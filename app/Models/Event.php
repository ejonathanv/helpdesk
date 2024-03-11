<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $appends = ['total_time_formatted', 'public_as_badge', 'type_badge', 'by'];

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:d M, Y h:i a',
        'updated_at' => 'datetime:d M, Y h:i a',
    ];

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(EventAttachment::class);
    }

    public function getByAttribute()
    {
        return $this->user ? $this->user->name : 'Sin usuario';
    }

    public function getTotalTimeFormattedAttribute()
    {
        $totalTime = $this->total_time;

        $days = floor($totalTime / (24 * 60));
        $totalTime = $totalTime % (24 * 60);
        $hours = floor($totalTime / 60);
        $totalTime = $totalTime % 60;
        $minutes = $totalTime;

        $days = $days > 0 ? $days . 'd ' : '';
        $hours = $hours > 0 ? $hours . 'h ' : '';
        $minutes = $minutes > 0 ? $minutes . 'm' : '';

        return $days . $hours . $minutes;
    }

    public function getPublicAsBadgeAttribute()
    {
        switch ($this->publicAs) {
            case 'public':
                return '<span class="badge badge-success">Público</span>';
            case 'private':
                return '<span class="badge badge-danger">Privado</span>';
        }
    }

    public function getTypeBadgeAttribute()
    {
        switch ($this->type) {
            case 'remote':
                return '<span class="badge badge-danger">Remoto</span>';
            case 'on-site':
                return '<span class="badge badge-primary">En sitio</span>';
        }
    }

}
