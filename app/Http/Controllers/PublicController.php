<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\ProductView;
use App\Http\Requests\CheckoutRequest;
use App\Services\ProductService;
use App\Services\WhatsAppService;

class PublicController extends Controller
{
    protected ProductService $productService;
    protected WhatsAppService $whatsappService;

    public function __construct(
        ProductService $productService,
        WhatsAppService $whatsappService
    ) {
        $this->productService = $productService;
        $this->whatsappService = $whatsappService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::with('seller')
            ->search($search)
            ->latest()
            ->get();
        
        // Prepare products data for JavaScript
        $productsData = $products->map(function($product) {
            return $this->productService->formatProductForJson($product);
        })->values();
        
        return view('welcome', compact('products', 'productsData'));
    }

    public function searchProducts(Request $request)
    {
        $search = $request->input('search');
        
        $products = Product::with('seller')
            ->search($search)
            ->latest()
            ->get()
            ->map(function($product) {
                return $this->productService->formatProductForJson($product);
            });

        return response()->json([
            'products' => $products,
            'count' => $products->count(),
        ]);
    }

    public function show(Product $product)
    {
        // Record View
        ProductView::create(['product_id' => $product->id]);

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('public.product-show', compact('product', 'relatedProducts'));
    }

    public function checkout(Product $product)
    {
        return view('public.checkout', compact('product'));
    }

    public function processCheckout(CheckoutRequest $request, Product $product)
    {
        $orderData = $request->validated();
        
        // Get seller phone number
        $sellerPhone = $product->whatsapp_number ?? $product->seller->shop_whatsapp;
        
        // Generate WhatsApp message and URL
        $message = $this->whatsappService->generateCheckoutMessage($product, $orderData);
        $whatsappUrl = $this->whatsappService->generateWhatsAppUrl($sellerPhone, $message);
        
        // Track checkout
        $this->whatsappService->trackCheckout($product, $orderData);

        return redirect()->away($whatsappUrl);
    }
}
