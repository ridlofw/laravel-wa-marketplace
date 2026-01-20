# ERD (Entity Relationship Diagram)
## Laravel WA Marketplace - Permata Klepu

```mermaid
erDiagram
    USERS {
        bigint id PK
        varchar name
        varchar email UK
        timestamp email_verified_at
        varchar password
        enum role "seller, superadmin"
        boolean is_active
        varchar shop_name
        text shop_address
        varchar shop_whatsapp
        varchar shop_logo
        varchar remember_token
        timestamp created_at
        timestamp updated_at
    }

    PRODUCTS {
        bigint id PK
        bigint user_id FK
        varchar name
        text description
        decimal price "12,2"
        varchar category
        varchar image
        text address
        varchar whatsapp_number
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    PRODUCT_IMAGES {
        bigint id PK
        bigint product_id FK
        varchar image_path
        timestamp created_at
        timestamp updated_at
    }

    PRODUCT_VIEWS {
        bigint id PK
        bigint product_id FK
        timestamp created_at
        timestamp updated_at
    }

    ORDERS {
        bigint id PK
        bigint user_id FK "buyer_id"
        bigint seller_id FK
        bigint product_id FK
        int quantity
        decimal total_price "12,2"
        varchar status "pending, completed, cancelled"
        timestamp created_at
        timestamp updated_at
    }

    SESSIONS {
        varchar id PK
        bigint user_id FK
        varchar ip_address "45"
        text user_agent
        longtext payload
        int last_activity
    }

    PASSWORD_RESET_TOKENS {
        varchar email PK
        varchar token
        timestamp created_at
    }

    %% Relationships
    USERS ||--o{ PRODUCTS : "has many"
    USERS ||--o{ ORDERS : "places as buyer"
    USERS ||--o{ ORDERS : "receives as seller"
    USERS ||--o{ SESSIONS : "has many"
    
    PRODUCTS ||--o{ PRODUCT_IMAGES : "has many"
    PRODUCTS ||--o{ PRODUCT_VIEWS : "has many"
    PRODUCTS ||--o{ ORDERS : "ordered in"
```

## Deskripsi Relasi

| Relasi | Tipe | Deskripsi |
|--------|------|-----------|
| Users → Products | One-to-Many | Satu user (seller) dapat memiliki banyak produk |
| Users → Orders (buyer) | One-to-Many | Satu user dapat melakukan banyak pemesanan sebagai pembeli |
| Users → Orders (seller) | One-to-Many | Satu user (seller) dapat menerima banyak pesanan |
| Products → Product Images | One-to-Many | Satu produk dapat memiliki banyak gambar |
| Products → Product Views | One-to-Many | Satu produk dapat memiliki banyak view/kunjungan |
| Products → Orders | One-to-Many | Satu produk dapat dipesan berkali-kali |
| Users → Sessions | One-to-Many | Satu user dapat memiliki banyak sesi aktif |

## Role User

- **seller**: Penjual yang dapat mengelola produk dan menerima pesanan
- **superadmin**: Admin Pemdes Desa Klepu yang mengawasi marketplace

## Status Order

- **pending**: Pesanan baru/menunggu
- **completed**: Pesanan selesai
- **cancelled**: Pesanan dibatalkan
