<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar semua barang dengan filter kondisi dan pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $conditionFilter = $request->query('condition');
        
        $query = Item::withCount(['loans' => function ($query) {
            $query->where('status', 'borrowed');
        }])->with('category');

        // Logika Pencarian (Nama atau Ruangan)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('room', 'like', "%{$search}%");
            });
        }

        // Logika Filter Kondisi
        if ($conditionFilter == 'Good') { 
            $query->where('quantity', '>', 0); 
        } elseif ($conditionFilter == 'Repair') { 
            $query->where('quantity_repair', '>', 0); 
        } elseif ($conditionFilter == 'Broken') { 
            $query->where('quantity_broken', '>', 0); 
        }

        // PERBAIKAN: Ganti latest() menjadi orderBy('id', 'desc') karena kolom created_at tidak ditemukan
        $items = $query->orderBy('id', 'desc')->get();

        // Statistik GLOBAL untuk Card
        $allStats = Item::all();
        $totalGood = $allStats->sum('quantity');
        $totalRepair = $allStats->sum('quantity_repair');
        $totalBroken = $allStats->sum('quantity_broken');

        return view('admin.items.index', compact(
            'items', 'totalGood', 'totalRepair', 'totalBroken', 'conditionFilter', 'search'
        ));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|in:Good,Repair,Broken',
            'room' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'category_id', 'room', 'description']);
        $qty = $request->quantity;
        
        $data['quantity'] = ($request->condition == 'Good') ? $qty : 0;
        $data['quantity_repair'] = ($request->condition == 'Repair') ? $qty : 0;
        $data['quantity_broken'] = ($request->condition == 'Broken') ? $qty : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);
        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'quantity_repair' => 'required|integer|min:0',
            'quantity_broken' => 'required|integer|min:0',
            'room' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($item->image) { Storage::disk('public')->delete($item->image); }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);
        return redirect()->route('admin.items.index')->with('success', 'Data aset berhasil diperbarui!');
    }

    /**
     * Menampilkan detail barang beserta riwayat peminjaman terbaru.
     */
    public function show(Item $item)
    {
        // PERBAIKAN: image_a668ba.png menunjukkan error saat memuat riwayat loans.
        // Kita harus mengurutkan riwayat peminjaman berdasarkan ID secara eksplisit
        $item->load(['category', 'loans' => function($q) {
            $q->with('user')->orderBy('id', 'desc')->take(5);
        }]); 
        
        return view('admin.items.show', compact('item'));
    }

    public function destroy(Item $item)
    {
        if ($item->image) { Storage::disk('public')->delete($item->image); }
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil dihapus!');
    }
}