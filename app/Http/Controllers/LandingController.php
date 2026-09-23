<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->where('status', 'Tersedia')->latest()->take(6)->get();
        $categories = Category::withCount('items')->get();
        
        $stats = [
            'total_items' => Item::sum('stock'),
            'active_borrowings' => Borrowing::whereIn('status', ['Disetujui', 'Dipinjam'])->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'satisfaction_rate' => '99%',
        ];

        return view('landing', compact('items', 'categories', 'stats'));
    }
}
