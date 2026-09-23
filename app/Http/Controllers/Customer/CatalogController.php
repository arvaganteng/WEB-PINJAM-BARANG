<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query->paginate(9);
        $categories = Category::all();

        return view('customer.catalog.index', compact('items', 'categories'));
    }

    public function show(Item $item)
    {
        $item->load(['category', 'reviews.user']);
        $schedules = $item->activeBorrowingSchedules();
        return view('customer.catalog.show', compact('item', 'schedules'));
    }
}
