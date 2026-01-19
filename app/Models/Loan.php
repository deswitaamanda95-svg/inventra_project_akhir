<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    
    // WAJIB: Nonaktifkan timestamps karena kolom created_at tidak ada di DB
    public $timestamps = false;

    protected $fillable = [
        'user_id', 
        'item_id', 
        'loan_date', 
        'due_date', 
        'quantity_borrowed', // Tambahkan ini
        'status'
    ];

    // Casting sangat penting untuk menghindari error format() pada string
    protected $casts = [
        'loan_date' => 'datetime',
        'due_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function item() { return $this->belongsTo(Item::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function admin() { return $this->belongsTo(User::class, 'approved_by'); }
}