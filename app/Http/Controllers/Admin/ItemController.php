<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('id', 'asc')->paginate(5);
        $categories = Category::all();

        return view('admin.items.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:Baik,Kurang Baik,Rusak Ringan,Rusak Berat',
            'stock' => 'required|integer|min:0',
            'price_per_day' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:Tersedia,Dipinjam,Tidak Tersedia,Rusak',
            'image' => 'nullable|image|max:2048',
        ]);

        // Auto generate code
        $lastItem = Item::latest('id')->first();
        $nextId = $lastItem ? $lastItem->id + 1 : 1;
        $code = 'BRG-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'code'          => $code,
            'name'          => $validated['name'],
            'category_id'   => $validated['category_id'],
            'condition'     => $validated['condition'],
            'stock'         => $validated['stock'],
            'price_per_day' => $validated['price_per_day'] ?? 0,
            'description'   => $validated['description'] ?? null,
            'status'        => $validated['status'],
            'image'         => $imagePath,
        ]);

        ActivityLogService::log(
            'item.created',
            "Barang baru '{$validated['name']}' (kode: {$code}) berhasil ditambahkan ke inventaris.",
            null,
            ['code' => $code, 'name' => $validated['name']]
        );

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'required|in:Baik,Kurang Baik,Rusak Ringan,Rusak Berat',
            'stock' => 'required|integer|min:0',
            'price_per_day' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:Tersedia,Dipinjam,Tidak Tersedia,Rusak',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);

        ActivityLogService::log(
            'item.updated',
            "Data barang '{$item->name}' (kode: {$item->code}) berhasil diperbarui.",
            $item,
            ['code' => $item->code, 'name' => $item->name]
        );

        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy(Item $item)
    {
        $name = $item->name;
        $code = $item->code;
        $item->delete();

        ActivityLogService::log(
            'item.deleted',
            "Barang '{$name}' (kode: {$code}) dihapus dari inventaris.",
            null,
            ['code' => $code, 'name' => $name]
        );

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil dihapus!');
    }
}
