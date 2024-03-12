<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    public $appends = ['full_name'];

    public function childrens()
    {
        return $this->hasMany(TicketCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(TicketCategory::class, 'parent_id');
    }

    public function getFullNameAttribute()
    {
        $parent = $this->parent;
        $grandparent = $parent ? $parent->parent : null;

        if ($grandparent) {
            return "{$grandparent->name} | {$parent->name} | {$this->name}";
        }

        if ($parent) {
            return "{$parent->name} | {$this->name}";
        }

        return $this->name;
    }
}
