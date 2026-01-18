<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Create a new product with images.
     */
    public function createProduct(array $data, array $images): Product
    {
        $data['user_id'] = auth()->id();
        $data['image'] = null; // Will be set after loop

        $product = Product::create($data);

        $this->uploadImages($product, $images);

        return $product;
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Product $product, array $data, ?array $images = null): Product
    {
        $product->update($data);

        if ($images) {
            $currentCount = $product->images()->count();
            $newCount = count($images);
            
            if ($currentCount + $newCount > 5) {
                throw new \Exception('Total images cannot exceed 5.');
            }

            $this->uploadImages($product, $images);
        }

        return $product->fresh();
    }

    /**
     * Delete a product and all associated images.
     */
    public function deleteProduct(Product $product): bool
    {
        // Delete all product images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        // Delete legacy image if exists
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        return $product->delete();
    }

    /**
     * Delete a single product image.
     */
    public function deleteProductImage(ProductImage $productImage): bool
    {
        $product = $productImage->product;
        
        Storage::disk('public')->delete($productImage->image_path);
        $productImage->delete();
        
        // Update main image if we deleted the main one
        if ($product->image === $productImage->image_path) {
            $nextImage = $product->images()->first();
            $product->update(['image' => $nextImage ? $nextImage->image_path : null]);
        }

        return true;
    }

    /**
     * Format product data for JSON response.
     */
    public function formatProductForJson(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'price_formatted' => 'Rp ' . number_format($product->price, 0, ',', '.'),
            'image' => $product->image ? asset('storage/' . $product->image) : null,
            'shop_name' => $product->seller->shop_name ?? 'Mitra Desa',
            'shop_location' => $product->seller ? $product->seller->getShopLocation() : 'Dusun Klepu',
            'url' => route('public.product.show', $product),
        ];
    }

    /**
     * Upload multiple images for a product.
     */
    private function uploadImages(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
            ]);

            // Set first image as main product image
            if ($index === 0 && !$product->image) {
                $product->update(['image' => $path]);
            }
        }

        // If product still doesn't have main image, set the first one
        if (!$product->image) {
            $first = $product->images()->first();
            if ($first) {
                $product->update(['image' => $first->image_path]);
            }
        }
    }
}
