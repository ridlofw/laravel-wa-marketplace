<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = auth()->user()->products()->latest()->paginate(9);
        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        return view('seller.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->createProduct(
            $request->except('images'),
            $request->file('images')
        );

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        // Lazy migration for legacy images
        if ($product->image && $product->images()->count() === 0) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->image
            ]);
        }
        
        return view('seller.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        try {
            $images = $request->hasFile('images') ? $request->file('images') : null;
            
            $this->productService->updateProduct(
                $product,
                $request->except('images'),
                $images
            );

            return redirect()->route('seller.products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['images' => $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $this->productService->deleteProduct($product);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function destroyImage(ProductImage $productImage)
    {
        $this->authorize('deleteImage', $productImage->product);

        $this->productService->deleteProductImage($productImage);

        return back()->with('success', 'Image deleted successfully.');
    }
}
