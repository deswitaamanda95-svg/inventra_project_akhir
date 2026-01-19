<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    public $timestamps = true; 

    protected $fillable = [
        'item_id', 
        'user_id', 
        'damage_note',      // Gunakan nama ini agar sinkron dengan SQL
        'technician_name', 
        'start_date', 
        'estimated_finish', 
        'completion_date', 
        'status', 
        'repair_cost',      // Gunakan nama ini agar sinkron dengan SQL
        'payment_status' 
    ];

    protected $casts = [
        'start_date' => 'date',
        'estimated_finish' => 'date',
        'completion_date' => 'date',
        'repair_cost' => 'decimal:2', 
    ];

    public function item() { return $this->belongsTo(Item::class); }
    public function user() { return $this->belongsTo(User::class); }
}