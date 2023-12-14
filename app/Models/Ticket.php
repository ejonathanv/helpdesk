<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'created_at' => 'datetime:d M, Y h:i a',
        'updated_at' => 'datetime:d M, Y h:i a',
    ];

    protected $appends = ['number', 'status', 'status_badge', 'priority', 'severity', 'category_name', 'full_category_name', 'site_attention_time', 'remote_attention_time', 'total_attention_time'];

    public function messages(){
        return $this->hasMany(ChatMessage::class);
    }

    public function category(){
        return $this->belongsTo(TicketCategory::class);
    }

    public function events(){
        return $this->hasMany(Event::class);
    }

    public function histories(){
        return $this->hasMany(History::class);
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

    public function getSiteAttentionTimeAttribute()
    {
        $events = $this->events()->where('type', 'on-site')->get();
        $totalTime = 0;

        foreach ($events as $event) {
            $totalTime += $event->total_time;
        }

        // Necesitamos formatear el tiempo total en dias, horas y minutos de esta forma: 1d 2h 30m

        $days = floor($totalTime / (24 * 60));
        $totalTime = $totalTime % (24 * 60);

        $hours = floor($totalTime / 60);
        $totalTime = $totalTime % 60;

        $minutes = $totalTime;
        $days = $days > 0 ? $days . 'd ' : '';
        $hours = $hours > 0 ? $hours . 'h ' : '';
        $minutes = $minutes > 0 ? $minutes . 'm' : '';

        if($totalTime == 0){
            return '0';
        }else{
            return $days . $hours . $minutes;
        }
    }

    public function getRemoteAttentionTimeAttribute()
    {
        $events = $this->events()->where('type', 'remote')->get();
        $totalTime = 0;

        foreach ($events as $event) {
            $totalTime += $event->total_time;
        }

        // Necesitamos formatear el tiempo total en dias, horas y minutos de esta forma: 1d 2h 30m

        $days = floor($totalTime / (24 * 60));
        $totalTime = $totalTime % (24 * 60);

        $hours = floor($totalTime / 60);
        $totalTime = $totalTime % 60;

        $minutes = $totalTime;
        $days = $days > 0 ? $days . 'd ' : '';
        $hours = $hours > 0 ? $hours . 'h ' : '';
        $minutes = $minutes > 0 ? $minutes . 'm' : '';

        if($totalTime == 0){
            return '0';
        }else{
            return $days . $hours . $minutes;
        }
    }

    public function getTotalAttentionTimeAttribute(){
        $events = $this->events;
        $totalTime = 0;

        foreach ($events as $event) {
            $totalTime += $event->total_time;
        }

        // Necesitamos formatear el tiempo total en dias, horas y minutos de esta forma: 1d 2h 30m

        $days = floor($totalTime / (24 * 60));
        $totalTime = $totalTime % (24 * 60);

        $hours = floor($totalTime / 60);
        $totalTime = $totalTime % 60;

        $minutes = $totalTime;
        $days = $days > 0 ? $days . 'd ' : '';
        $hours = $hours > 0 ? $hours . 'h ' : '';
        $minutes = $minutes > 0 ? $minutes . 'm' : '';

        if($totalTime == 0){
            return '0';
        }else{
            return $days . $hours . $minutes;
        }
    }
}
