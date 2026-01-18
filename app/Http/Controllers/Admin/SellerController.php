<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Display a listing of sellers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        
        // Validate per_page values
        $allowedPerPage = [5, 10, 20, 50];
        if (!in_array((int)$perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        // Validate sort columns
        $allowedSorts = ['name', 'shop_name', 'email', 'products_count', 'is_active', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        
        // Validate direction
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $sellers = User::where('role', 'seller')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('shop_name', 'like', "%{$search}%");
                });
            })
            ->withCount('products')
            ->when($sort == 'products_count', function($query) use ($direction) {
                $query->orderBy('products_count', $direction);
            }, function($query) use ($sort, $direction) {
                $query->orderBy($sort, $direction);
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.sellers.index', compact('sellers', 'search', 'perPage', 'sort', 'direction'));
    }

    /**
     * Display the specified seller.
     */
    public function show(User $user)
    {
        // Ensure we're viewing a seller
        if (!$user->isSeller()) {
            abort(404);
        }

        $products = $user->products()
            ->withTrashed()
            ->latest()
            ->paginate(10);

        return view('admin.sellers.show', compact('user', 'products'));
    }

    /**
     * Remove the specified seller from storage.
     */
    public function destroy(User $user)
    {
        // Ensure we're deleting a seller
        if (!$user->isSeller()) {
            return back()->with('error', 'Tidak dapat menghapus akun ini.');
        }

        // Soft delete all products
        $user->products()->delete();

        // Deactivate user account
        $user->update(['is_active' => false]);

        return redirect()->route('admin.sellers.index')
            ->with('success', "Seller '{$user->shop_name}' berhasil dinonaktifkan.");
    }
}
