<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['status_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 'active':
                return 'Activo';
            case 'inactive':
                return 'Inactivo';
            default:
                return 'Desconocido';
        }
    }
}
