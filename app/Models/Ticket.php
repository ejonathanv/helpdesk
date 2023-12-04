<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:d M, Y h:i a',
        'updated_at' => 'datetime:d M, Y h:i a',
    ];

    protected $appends = ['number', 'status', 'status_badge', 'priority', 'severity', 'category_name', 'full_category_name'];

    public function category(){
        return $this->belongsTo(TicketCategory::class);
    }

    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : "Sin categoría";
    }

    public function getFullCategoryNameAttribute(){
        return $this->category ? $this->category->full_name : "Sin categoría";
    }

    public function getNumberAttribute()
    {
        return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }


    public function getStatusAttribute()
    {
        switch ($this->status_id) {
            case 1:
                return 'Abierto';
            case 2:
                return 'Resuelto';
            case 3:
                return 'En proceso';
            case 4:
                return 'En espera del cliente';
            case 5:
                return 'Monitoreo';
            case 6:
                return 'Cerrado';
            default:
                return 'Abierto';
        }
    }

    public function getStatusBadgeAttribute()
    {
        switch ($this->status_id) {
            case 1:
                return '<span class="badge badge-primary">Abierto</span>';
            case 2:
                return '<span class="badge badge-success">Resuelto</span>';
            case 3:
                return '<span class="badge badge-info">En proceso</span>';
            case 4:
                return '<span class="badge badge-warning">En espera del cliente</span>';
            case 5:
                return '<span class="badge badge-dark">Monitoreo</span>';
            case 6:
                return '<span class="badge badge-secondary">Cerrado</span>';
            default:
                return '<span class="badge badge-primary">Abierto</span>';

        }
    }

    public function getPriorityAttribute()
    {
        switch ($this->priority_id) {
            case 1:
                return 'Baja';
            case 2:
                return 'Media';
            case 3:
                return 'Alta';
            case 4:
                return 'Urgente';
            default:
                return 'Baja';
        }
    }

    public function getSeverityAttribute()
    {
        switch ($this->severity_id) {
            case 1:
                return 'Baja';
            case 2:
                return 'Media';
            case 3:
                return 'Alta';
            case 4:
                return 'Urgente';
            default:
                return 'Baja';
        }
    }
}
