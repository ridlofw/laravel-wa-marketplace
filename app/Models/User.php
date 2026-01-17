<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'shop_name',
        'shop_address',
        'shop_whatsapp',
        'shop_logo',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get formatted WhatsApp number for this user.
     */
    public function getFormattedWhatsAppNumber(): string
    {
        $phone = $this->shop_whatsapp;
        
        // Remove all spaces and special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert leading 0 to 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        return $phone;
    }

    /**
     * Get shop location/dusun from shop_address.
     * Extracts "Dusun X" from address string.
     */
    public function getShopLocation(): string
    {
        if (!$this->shop_address) {
            return 'Dusun Klepu';
        }

        // Try to extract "Dusun X" pattern from address
        if (preg_match('/Dusun\s+(\w+)/i', $this->shop_address, $matches)) {
            return 'Dusun ' . ucfirst($matches[1]);
        }

        // Fallback: return default
        return 'Dusun Klepu';
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    /**
     * Check if user is seller.
     */
    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }
}
