<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sellerId = $request->input('seller');
        $perPage = $request->input('per_page', 10);
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        
        // Validate per_page values
        $allowedPerPage = [5, 10, 20, 50];
        if (!in_array((int)$perPage, $allowedPerPage)) {
            $perPage = 10;
        }

         // Validate sort columns
         $allowedSorts = ['name', 'price', 'views_count', 'category', 'status', 'created_at'];
         if (!in_array($sort, $allowedSorts)) {
             $sort = 'created_at';
         }
         
         // Validate direction
         if (!in_array($direction, ['asc', 'desc'])) {
             $direction = 'desc';
         }

        $products = Product::with('seller')
            ->withTrashed()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($sellerId, function ($query) use ($sellerId) {
                $query->where('user_id', $sellerId);
            })
            ->withCount('views')
            // Special handling for status which is derived
            ->when($sort == 'status', function($query) use ($direction) {
                $query->orderBy('deleted_at', $direction === 'asc' ? 'desc' : 'asc'); // Active (null) first/last
            }, function($query) use ($sort, $direction) {
                $query->orderBy($sort, $direction);
            })
            ->paginate($perPage)
            ->withQueryString();

        // Get sellers for filter dropdown
        $sellers = User::where('role', 'seller')
            ->orderBy('shop_name')
            ->get(['id', 'shop_name', 'name']);

        return view('admin.products.index', compact('products', 'sellers', 'search', 'sellerId', 'perPage', 'sort', 'direction'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['seller', 'images', 'views']);
        $product->loadCount('views');

        return view('admin.products.show', compact('product'));
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$productName}' berhasil dihapus.");
    }
}
