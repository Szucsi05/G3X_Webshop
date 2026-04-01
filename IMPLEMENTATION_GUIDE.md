# 🚀 E-Commerce Database Refactoring - Implementation Guide

## 📋 Előfeltételek

- PHP 8.1+
- Laravel 11
- MySQL/MariaDB 8.0+
- Composer

## ⚙️ Telepítési Lépések

### 1️⃣ Adatbázis Biztonsági Mentés (FONTOS!)

```bash
# MySQL backup az átszervezés előtt
mysqldump -u root -p your_database > backup_$(date +%Y%m%d).sql
```

### 2️⃣ Migrációk Futtatása

```bash
# Összes nyitott migráció futtatása
php artisan migrate

# Vagy ha vissza kell majd vonni:
php artisan migrate:rollback
```

**Migrációs sorrend automatikus:**
1. Create Categories
2. Create Platforms
3. Create Vendors
4. Refactor Products (add category_id, remove old fields)
5. Create Product Offers
6. Refactor Orders (add status, currency)
7. Create Order Items

### 3️⃣ Adatbázis Seedelése

```bash
# Összes seeder futtatása az alábbi sorrendben:
php artisan db:seed

# Vagy egyedileg:
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=PlatformSeeder
php artisan db:seed --class=VendorSeeder
php artisan db:seed --class=ProductSeeder
php artisan db:seed --class=ProductOfferSeeder
```

**Létrehozott adatok:**
- ✅ 4 kategória
- ✅ 10 platform
- ✅ 8 eladó
- ✅ 25 termék
- ✅ ~125+ ajánlat (random distribúcióban)
- ✅ 1 test user (test@example.com)

### 4️⃣ Cache Törlése

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## 🔌 API Endpoints

### Product Offers (Termék Ajánlatok)

#### 📥 Összes ajánlat listázása
```bash
GET /api/v1/product-offers

# Paraméterek:
?min_price=10&max_price=100
?vendor_id=1
?platform_id=1
?product_id=1
?available=true
?sort_by=price&sort_order=asc
?per_page=20
```

#### 📌 Egy ajánlat részletei
```bash
GET /api/v1/product-offers/{id}
```

#### 🎮 Egy termék összes ajánlata
```bash
GET /api/v1/products/{productId}/offers?sort_by=price&sort_order=asc

Válasz:
{
  "product": { ... },
  "offers": [ ... ],
  "count": 3,
  "lowest_price": 29.99,
  "highest_price": 59.99
}
```

#### 🏪 Egy eladó összes ajánlata
```bash
GET /api/v1/vendors/{vendorId}/offers
```

#### ➕ Új ajánlat létrehozása (admin)
```bash
POST /api/v1/product-offers

Body:
{
  "product_id": 1,
  "vendor_id": 2,
  "platform_id": 3,
  "price": 29.99,
  "stock": 50,
  "region": "EU",
  "delivery_type": "key",
  "status": "active"
}
```

#### ✏️ Ajánlat módosítása
```bash
PUT /api/v1/product-offers/{id}

Body (opcionális mezők):
{
  "price": 39.99,
  "stock": 25,
  "status": "active"
}
```

#### 🗑️ Ajánlat törlése
```bash
DELETE /api/v1/product-offers/{id}
```

### Orders (Rendelések)

#### 📋 Saját rendelések
```bash
GET /api/v1/orders

Header:
Authorization: Bearer YOUR_TOKEN
```

#### 📌 Egy rendelés részletei
```bash
GET /api/v1/orders/{id}

Header:
Authorization: Bearer YOUR_TOKEN
```

#### ➕ Új rendelés létrehozása
```bash
POST /api/v1/orders

Header:
Authorization: Bearer YOUR_TOKEN

Body:
{
  "items": [
    {
      "product_offer_id": 1,
      "quantity": 1,
      "price_at_purchase": 29.99
    },
    {
      "product_offer_id": 2,
      "quantity": 2,
      "price_at_purchase": 19.99
    }
  ],
  "payment_method": "card",
  "billing_name": "John Doe",
  "billing_email": "john@example.com",
  "billing_phone": "+36123456789",
  "billing_country": "Hungary",
  "billing_city": "Budapest",
  "billing_postal": "1011",
  "billing_street": "Main Street 1",
  "billing_company_name": null,
  "billing_tax_id": null,
  "account_type": "personal"
}

Válasz: 201 Created
{
  "id": 1,
  "user_id": 1,
  "email": "user@example.com",
  "total_amount": 69.97,
  "payment_method": "card",
  "status": "pending",
  "items": [
    {
      "id": 1,
      "product_offer_id": 1,
      "price_at_purchase": 29.99,
      "quantity": 1,
      "product": { ... },
      "vendor": { ... }
    }
  ]
}
```

#### 🔄 Rendelés státusza frissítése
```bash
PUT /api/v1/orders/{id}/status

Body:
{
  "status": "paid"
}

Status értékek: pending, paid, processing, completed, cancelled
```

#### 🔑 Aktiválási kulcs hozzáadása rendelés tételhez
```bash
POST /api/v1/orders/{orderId}/items/{itemId}/license

Body:
{
  "license_key": "XXXX-XXXX-XXXX-XXXX"
}
```

## 🧪 Tesztelés

### PHPUnit Tesztek (opcionális)
```bash
# Tesztek futtatása
php artisan test

# Specifikus tesztfájl
php artisan test tests/Feature/ProductOfferTest.php
```

### Manuális API Tesztelés (Postman/Curl)

```bash
# Összes ajánlat
curl -X GET "http://localhost:8000/api/v1/product-offers" \
  -H "Content-Type: application/json"

# Egy termék ajánlatai (szert rendezve)
curl -X GET "http://localhost:8000/api/v1/products/1/offers?sort_by=price&sort_order=asc" \
  -H "Content-Type: application/json"

# Új rendelés (authentication szükséges!)
curl -X POST "http://localhost:8000/api/v1/orders" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "items": [{"product_offer_id": 1, "quantity": 1}],
    "payment_method": "card",
    "billing_name": "John Doe",
    "billing_email": "john@example.com",
    "billing_country": "Hungary",
    "billing_city": "Budapest",
    "billing_postal": "1011",
    "billing_street": "Main St",
    "account_type": "personal"
  }'
```

## 📊 Adatbázis Lekérdezések

### Legolcsóbb ajánlatok termékek szerint
```sql
SELECT p.name, v.name as vendor, po.price, po.platform_id
FROM product_offers po
JOIN products p ON po.product_id = p.id
JOIN vendors v ON po.vendor_id = v.id
ORDER BY p.id, po.price ASC;
```

### Eladók értékelése
```sql
SELECT name, rating, COUNT(id) as offers_count
FROM vendors
LEFT JOIN product_offers ON vendors.id = vendor_id
GROUP BY vendors.id
ORDER BY rating DESC;
```

### Készlet состояние
```sql
SELECT p.name, v.name, po.stock, po.platform_id
FROM product_offers po
JOIN products p ON po.product_id = p.id
JOIN vendors v ON po.vendor_id = v.id
WHERE po.stock = 0
ORDER BY p.name;
```

## ⚠️ Fontos Megjegyzések

### Migrálás Meglévő Adatokból
Ha már van terméked az eredeti táblában, szükséges a데이터 migrálása:

```php
// database/migrations/XXXX_migrate_old_products.php
// Ez egy custom migrációba
```

### Foreign Key Constraint Nélkül
Ha hibát kapsz a migrálás során, lehet szükséges:

```php
// config/database.php - MySQL esetén
'mysql' => [
    // ...
    'foreign_key_constraints' => false,  // Ideiglenesen kikapcsol
]
```

Majd vissza a `true`-ra!

### Performance Indexek

```sql
-- Javasolt indexek
CREATE INDEX idx_product_offers_product_id ON product_offers(product_id);
CREATE INDEX idx_product_offers_vendor_id ON product_offers(vendor_id);
CREATE INDEX idx_product_offers_status ON product_offers(status);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
```

## 🔐 Biztonság

### API Authentication
```php
// Sanctum használata javasolt:
php artisan install:api

// Headers:
Authorization: Bearer YOUR_SANCTUM_TOKEN
```

### Rate Limiting
```php
// routes/api.php
Route::middleware('throttle:60,1')->group(function () {
    // API endpoints
});
```

## 📝 Loggolás

Az összes fontos művelet (rendelések, ajánlat módosítások) naplózható:

```php
// app/Models/ProductOffer.php
protected static function booted()
{
    static::updated(function ($offer) {
        \Log::info('ProductOffer updated', [
            'offer_id' => $offer->id,
            'old_price' => $offer->getOriginal('price'),
            'new_price' => $offer->price,
        ]);
    });
}
```

## 🆘 Hibaelhárítás

### "UNIQUE constraint failed"
```
ProductOffer már létezik ehhez a product-vendor-platform kombinációhoz
Ellenőrizze az unique constraint-et!
```

### "Foreign Key Constraint Failed"
```
Valamelyik ID nem létezik
- product_id válida?
- vendor_id válida?
- platform_id válida?
```

### "Insufficient Stock"
```
A megrendelt mennyiség meghaladja a készletet
Csökkentse a megrendelt mennyiséget!
```

## ✅ Ellenőrzési Lista

- [ ] Backup készült az eredeti adatbázisról
- [ ] Migrációk sikeresen futottak: `php artisan migrate`
- [ ] Seeders futottak: `php artisan db:seed`
- [ ] Tesztadatok létrejöttek (Categories, Platforms, Vendors, Products, Offers)
- [ ] API endpoints működnek: GET /api/v1/product-offers
- [ ] Authentication működik: Sanctum beállítva
- [ ] Cache törlve: `php artisan cache:clear`
- [ ] Tesztek sikeresek: `php artisan test` (opcionális)

## 🎯 Következő Lépések

1. ✅ Migrálás (Done)
2. ✅ API Endpoints (Done)
3. ⏳ Frontend integrálás szükséges
4. ⏳ Admin Dashboard (eladók kezeléséhez)
5. ⏳ Payment Gateway integráció
6. ⏳ Email notifikáció (rendelés megerősítés)

---

**Verzió**: 1.0  
**Frissítve**: 2026-04-01  
**Status**: ✅ Teljes
