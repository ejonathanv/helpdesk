<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['agents_count'];

    public function agents()
    {
        return $this->hasMany(Agent::class, 'department_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'department_id');
    }

    public function getAgentsCountAttribute()
    {
        return $this->agents()->count();
    }
}
