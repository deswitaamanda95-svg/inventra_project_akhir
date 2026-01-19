<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 
        'category_id', 
        'quantity',          // Unit Good
        'quantity_repair',   
        'quantity_broken',   
        'room', 
        'image', 
        'description'
    ];

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Transaksi Pinjam
    public function loans() {
        return $this->hasMany(Loan::class);
    }

    /**
     * Accessor: Menghitung stok yang benar-benar siap pakai (Good - Sedang Dipinjam)
     * Bisa dipanggil dengan $item->available_stock
     */
    public function getAvailableStockAttribute()
    {
        $activeLoans = $this->loans()->where('status', 'borrowed')->count();
        return max(0, $this->quantity - $activeLoans);
    }

    /**
     * Accessor: Menghitung total seluruh fisik barang di gudang
     */
    public function getTotalPhysicalAttribute()
    {
        return $this->quantity + $this->quantity_repair + $this->quantity_broken;
    }
}