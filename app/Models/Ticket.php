<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Ticket extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['created_at', 'updated_at', 'solved_date', 'closed_date'];
    protected $casts = [
        'created_at' => 'datetime:d M, Y h:i a',
        'updated_at' => 'datetime:d M, Y h:i a',
        'solved_date' => 'datetime:d M, Y h:i a',
        'closed_date' => 'datetime:d M, Y h:i a',
    ];
    protected $appends = ['number', 'status', 'status_badge', 'priority', 'severity', 'category_name', 'full_category_name', 'site_attention_time', 'remote_attention_time', 'total_attention_time', 'agent_name', 'department_name', 'priority_badge', 'severity_badge'];
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
    public function agent(){
        return $this->belongsTo(Agent::class);
    }
    public function department(){
        return $this->belongsTo(Department::class);
    }
    public function attachments(){
        return $this->hasMany(TicketAttachment::class);
    }
    public function getAgentNameAttribute(){
        return $this->agent ? $this->agent->user->name : 'Sin asignar';
    }
    public function getDepartmentNameAttribute(){
        return $this->department ? $this->department->name : 'Sin departamento';
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
            case 7:
                return 'Cancelado';
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
            case 7:
                return '<span class="badge badge-danger">Cancelado</span>';
            default:
                return '<span class="badge badge-secondary">Sin definir</span>';
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
        }
    }
    public function getPriorityBadgeAttribute()
    {
        switch ($this->priority_id) {
            case 1:
                return '<span class="badge badge-primary">Baja</span>';
            case 2:
                return '<span class="badge badge-info">Media</span>';
            case 3:
                return '<span class="badge badge-warning">Alta</span>';
            case 4:
                return '<span class="badge badge-danger">Urgente</span>';
            default:
                return '<span class="badge badge-secondary">Sin definir</span>';
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
        }
    }
    public function getSeverityBadgeAttribute()
    {
        switch ($this->severity_id) {
            case 1:
                return '<span class="badge badge-primary">Baja</span>';
            case 2:
                return '<span class="badge badge-info">Media</span>';
            case 3:
                return '<span class="badge badge-warning">Alta</span>';
            case 4:
                return '<span class="badge badge-danger">Urgente</span>';
            default:
                return '<span class="badge badge-secondary">Sin definir</span>';
        }
    }
    public function getSiteAttentionTimeAttribute()
    {
        $user = auth()->user() ? auth()->user() : null;
        if($user){
            $events = $this->events()
                ->where('type', 'on-site')
                ->get();
        }else{
            $events = $this->events()
                ->where('type', 'on-site')
                ->where('publicAs', 'public')
                ->get();
        }
        $totalTime = 0;
        foreach ($events as $event) {
            $totalTime += $event->total_time;
        }
        $totalInMinutes = $totalTime;
        // Necesitamos formatear el tiempo total en dias, horas y minutos de esta forma: 1d 2h 30m
        $days = floor($totalTime / (24 * 60));
        $totalTime = $totalTime - ($days * 24 * 60);
        $hours = floor($totalTime / 60);
        $totalTime = $totalTime - ($hours * 60);
        $minutes = $totalTime;
        $days = $days > 0 ? $days . 'd ' : '';
        $hours = $hours > 0 ? $hours . 'h ' : '';
        $minutes = $minutes > 0 ? $minutes . 'm' : '';
        if($totalInMinutes == 0){
            return '0';
        }else{
            return $days . $hours . $minutes;
        }
    }
    public function getRemoteAttentionTimeAttribute()
    {
        $user = auth()->user() ? auth()->user() : null;
        if($user){
            $events = $this->events()
                ->where('type', 'remote')
                ->get();
        }else{
            $events = $this->events()
                ->where('type', 'remote')
                ->where('publicAs', 'public')
                ->get();
        }
        $totalTime = 0;
        foreach ($events as $event) {
            $totalTime += $event->total_time;
        }
        $totalInMinutes = $totalTime;
        // Necesitamos formatear el tiempo total en dias, horas y minutos de esta forma: 1d 2h 30m
        $days = floor($totalTime / (24 * 60));
        $totalTime = $totalTime - ($days * 24 * 60);
        $hours = floor($totalTime / 60);
        $totalTime = $totalTime - ($hours * 60);
        $minutes = $totalTime;
        $days = $days > 0 ? $days . 'd ' : '';
        $hours = $hours > 0 ? $hours . 'h ' : '';
        $minutes = $minutes > 0 ? $minutes . 'm' : '';
        if($totalInMinutes == 0){
            return '0';
        }else{
            return $days . $hours . $minutes;
        }
    }
    public function getTotalAttentionTimeAttribute()
    {
        $user = auth()->user() ? auth()->user() : null;
        if($user){
            $events = $this->events;
        }else{
            $events = $this->events()
                ->where('publicAs', 'public')
                ->get();
        }
        $totalTime = 0;
        foreach ($events as $event) {
            $totalTime += $event->total_time;
        }
        $totalInMinutes = $totalTime;
        // Necesitamos formatear el tiempo total en dias, horas y minutos de esta forma: 1d 2h 30m
        $days = floor($totalTime / (24 * 60));
        $totalTime = $totalTime - ($days * 24 * 60);
        $hours = floor($totalTime / 60);
        $totalTime = $totalTime - ($hours * 60);
        $minutes = $totalTime;
        $days = $days > 0 ? $days . 'd ' : '';
        $hours = $hours > 0 ? $hours . 'h ' : '';
        $minutes = $minutes > 0 ? $minutes . 'm' : '';
        if($totalInMinutes == 0){
            return '0';
        }else{
            return $days . $hours . $minutes;
        }
    }

    public function scopeUserPermission($query, $permission)
    {

        $user = auth()->user();

        if($permission == 'Administrador'){
            return $query;
        }

        if($permission == 'Colaborador'){
            return $query;
        }

        if($permission == 'Ingeniero'){
            return $query->where('user_id', $user->id)->orWhere('agent_id', $user->agent->id);
        }

        if($permission == 'Supervisor'){
            return $query->where('department_id', $user->agent->department_id)
                ->orWhere('user_id', $user->id)
                ->orWhere('agent_id', $user->agent->id);
        }
        
    }
}
