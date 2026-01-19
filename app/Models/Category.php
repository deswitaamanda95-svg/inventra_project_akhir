<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    // Ini adalah daftar kolom yang 'boleh' diisi secara massal
    protected $fillable = [
        'name', 
        'description'
    ];
}
