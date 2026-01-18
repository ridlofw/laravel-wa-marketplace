<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;

class WhatsAppService
{
    /**
     * Format phone number for WhatsApp.
     * Converts local Indonesian format (08xx) to international (62xxx).
     */
    public function formatPhoneNumber(string $phone): string
    {
        // Remove all spaces and special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert leading 0 to 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        // Remove leading + if exists
        if (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }
        
        // Ensure it starts with 62
        if (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }

    /**
     * Generate checkout message for WhatsApp.
     */
    public function generateCheckoutMessage(Product $product, array $orderData): string
    {
        $message = "Halo, saya ingin membeli produk *{$product->name}*.\n\n";
        $message .= "Detail Pesanan:\n";
        $message .= "Nama: {$orderData['name']}\n";
        $message .= "Alamat: {$orderData['address']}\n";
        $message .= "Jumlah: {$orderData['quantity']} pcs\n";
        
        if (!empty($orderData['note'])) {
            $message .= "Catatan: {$orderData['note']}\n";
        }

        $totalPrice = $product->price * $orderData['quantity'];
        $message .= "Total Harga: Rp " . number_format($totalPrice, 0, ',', '.') . "\n\n";
        $message .= "Mohon infonya untuk pembayaran dan pengiriman. Terima kasih.";

        return $message;
    }

    /**
     * Generate WhatsApp URL with phone number and message.
     */
    public function generateWhatsAppUrl(string $phone, string $message): string
    {
        $formattedPhone = $this->formatPhoneNumber($phone);
        $encodedMessage = urlencode($message);
        
        return "https://wa.me/{$formattedPhone}?text={$encodedMessage}";
    }

    /**
     * Track "click to buy" event in database.
     */
    public function trackCheckout(Product $product, array $orderData): Order
    {
        return Order::create([
            'user_id' => auth()->id(), // Nullable if guest
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
            'quantity' => $orderData['quantity'],
            'total_price' => $product->price * $orderData['quantity'],
            'status' => 'clicked',
        ]);
    }
}
