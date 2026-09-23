<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->withCount('borrowings')->orderBy('id', 'desc')->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'customer';

        $customer = User::create($validated);

        ActivityLogService::log(
            'customer.created',
            "Akun customer baru '{$customer->name}' ({$customer->email}) telah ditambahkan.",
            $customer,
            ['name' => $customer->name, 'email' => $customer->email]
        );

        return redirect()->route('admin.customers.index')->with('success', 'Customer baru "' . $customer->name . '" berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        ActivityLogService::log(
            'customer.updated',
            "Data customer '{$user->name}' diperbarui oleh admin.",
            $user,
            ['name' => $user->name, 'email' => $user->email]
        );

        return redirect()->route('admin.customers.index')->with('success', 'Data customer ' . $user->name . ' berhasil diperbarui.');
    }

    public function toggleStatus(User $user)
    {
        $user->status = ($user->status === 'aktif') ? 'nonaktif' : 'aktif';
        $user->save();

        ActivityLogService::log(
            'customer.status_toggled',
            "Status akun customer '{$user->name}' diubah menjadi {$user->status}.",
            $user,
            ['name' => $user->name, 'new_status' => $user->status]
        );

        return redirect()->route('admin.customers.index')->with('success', 'Status akun ' . $user->name . ' diubah menjadi ' . $user->status . '.');
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $email = $user->email;
        $user->delete();

        ActivityLogService::log(
            'customer.deleted',
            "Akun customer '{$name}' ({$email}) dihapus dari sistem.",
            null,
            ['name' => $name, 'email' => $email]
        );

        return redirect()->route('admin.customers.index')->with('success', 'Data customer berhasil dihapus.');
    }
}
