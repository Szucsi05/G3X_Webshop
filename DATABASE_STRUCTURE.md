# Új E-commerce Adatbázis Struktúra

## 🎯 Áttekintés

Az alábbi dokumentáció az átszervezett adatbázis szerkezetet escribes le, amely egy multi-vendor e-commerce platformot támogat, ahol több eladó ugyanazt a terméket eltérő áron, készlettel és platformtal kínálhatja.

## 📊 Adatbázis Diagram

```
products ← product_offers → vendors
                   ↓
            platforms

users ← orders ← order_items ← product_offers
```

## 🗄️ Tábla Leírások

### 1. **categories** (kategóriák)
Termékek kategorizáláshoz.
- `id` - Elsődleges kulcs
- `name` - Kategória neve (pl. "Játék", "Szoftver")
- `description` - Leírás
- `timestamps` - created_at, updated_at

### 2. **platforms** (platformok)
Az elérhető platformok/áruházak.
- `id` - Elsődleges kulcs
- `name` - Platform neve (PC, PlayStation, Xbox, Steam, Epic Games, stb.)
- `description` - Leírás
- `timestamps`

### 3. **products** (termékek)
Központi terméktábla - tartalmazza az alapinformációkat.
- `id` - Elsődleges kulcs
- `name` - Termék neve
- `description` - Termék leírása
- `category_id` - FK → categories
- `timestamps`

### 4. **vendors** (eladók)
Az értékesítők/kereskedők.
- `id` - Elsödleges kulcs
- `name` - Eladó neve
- `email` - E-mail cím
- `description` - Leírás
- `rating` - Értékelés (0-5)
- `website` - Weboldal URL
- `logo_url` - Logó URL
- `status` - 'active', 'inactive', 'suspended'
- `timestamps`

### 5. **product_offers** 🔑 (LÉNYEG - termék ajánlatok)
Ez köti össze a terméket az eladóval, plusz eladó-specifikus adatokkal.
- `id` - Elsödleges kulcs
- `product_id` - FK → products
- `vendor_id` - FK → vendors
- `platform_id` - FK → platforms
- `price` - Ár (decimal)
- `stock` - Készlet (integer)
- `region` - Régió (pl. 'EU', 'US', 'GLOBAL')
- `delivery_type` - Szállítás típusa ('key', 'account', 'gift', 'physical')
- `status` - 'active', 'inactive', 'out_of_stock'
- `timestamps`
- **UNIQUE constraint**: (product_id, vendor_id, platform_id)

### 6. **users** (felhasználók)
Vásárlók.
- `id` - Elsödleges kulcs
- `name` - Név
- `email` - E-mail
- `password` - Jelszó (hashed)
- `cart_data` - Kosár adatok (JSON)
- `card_number` - Bankkártya szám (opcionális)
- `card_expiry` - Kártya lejárat (opcionális)
- `card_cvv` - CVV (opcionális)
- `timestamps`

### 7. **orders** (rendelések)
Rendelés fejléc.
- `id` - Elsödleges kulcs
- `user_id` - FK → users
- `email` - Email cím
- `total_amount` - Teljes ár
- `payment_method` - Fizetési mód
- `currency` - Deviza (pl. USD, EUR)
- `status` - 'pending', 'paid', 'processing', 'completed', 'cancelled'
- Számlázási adatok (billing_name, billing_email, stb.)
- `account_type` - 'personal' vagy 'company'
- `timestamps`

### 8. **order_items** (rendelés tételeit)
A rendelés egyes tételei - product_offer-t referencianál.
- `id` - Elsödleges kulcs
- `order_id` - FK → orders
- `product_offer_id` - FK → product_offers
- `price_at_purchase` - Az ár a vásárlás időpontjában
- `quantity` - Mennyiség
- `license_key` - Aktiválási kulcs (opcionális)
- `account_details` - Fiók adatok JSON-ben (opcionális)
- `timestamps`

## 📋 Eloquent Model Relációk

### Product
```php
$product->category()       // BelongsTo
$product->offers()         // HasMany (ProductOffer)
$product->vendors()        // HasManyThrough
$product->orderItems()     // HasManyThrough
```

### Vendor
```php
$vendor->offers()          // HasMany (ProductOffer)
$vendor->products()        // HasManyThrough
```

### ProductOffer
```php
$offer->product()          // BelongsTo
$offer->vendor()           // BelongsTo
$offer->platform()         // BelongsTo
$offer->orderItems()       // HasMany
```

### Order
```php
$order->user()              // BelongsTo
$order->items()             // HasMany (OrderItem)
$order->productOffers()     // HasManyThrough
```

### OrderItem
```php
$item->order()              // BelongsTo
$item->productOffer()       // BelongsTo
$item->product()            // rövidítés
$item->vendor()             // rövidítés
```

### User
```php
$user->orders()             // HasMany
```

## 🔑 Unique Constraints

- `categories.name` - egyedi kategória nevek
- `platforms.name` - egyedi platform nevek
- `vendors.email` - egyedi eladó e-mail
- `product_offers` - (product_id, vendor_id, platform_id) - egy eladó nem adhat duplikált ajánlatot ugyanarra a termékre-platformra

## 📝 Seeding Sorrend

Az adatbázist a `DatabaseSeeder` tölt fel az alábbi sorrendben:

1. **CategorySeeder** - Kategóriák
2. **PlatformSeeder** - Platformok
3. **VendorSeeder** - Eladók
4. **ProductSeeder** - Termékek
5. **ProductOfferSeeder** - Termék ajánlatok

## 🚀 Migrációk

Összes migrációs fájl a `database/migrations/` mappában:

- `2026_04_01_000001_create_categories_table.php`
- `2026_04_01_000002_create_platforms_table.php`
- `2026_04_01_000003_create_vendors_table.php`
- `2026_04_01_000004_refactor_products_table.php` - módosítja a products táblát
- `2026_04_01_000005_create_product_offers_table.php`
- `2026_04_01_000006_refactor_orders_table.php` - módosítja az orders táblát
- `2026_04_01_000007_create_order_items_table.php`

## 📡 API Végpontok (kiegészítendő)

### Termékek
```
GET    /api/products              - Összes termék
GET    /api/products/{id}         - Termék részletei
GET    /api/products/{id}/offers  - Termék összes ajánlata
```

### Ajánlatok
```
GET    /api/product-offers         - Összes ajánlat
GET    /api/product-offers/{id}    - Ajánlat részletei
GET    /api/products/{id}/offers?sorted_by=price  - Szűrés/rendezés ár szerint
```

### Rendelések
```
POST   /api/orders                 - Új rendelés
GET    /api/orders/{id}            - Rendelés részletei
GET    /api/users/{id}/orders      - Felhasználó rendeléseit
```

## ✅ Előnyök

✅ **Skálázható** - Korlátlan eladó és ajánlat
✅ **Rugalmas** - Minden eladó saját ár, készlet, platform
✅ **Normalizálva** - Nincsenek redundáns adatok
✅ **API-barát** - Egyszerű lekérdezések
✅ **Auditálható** - timestamps minden táblában
✅ **Biztonságos** - Foreign key constraints, Unique constraints

## 🔄 Migrálás Meglévő Adatokból

Ha meglévő termékek vannak az eredeti `products` táblában:

```php
// Ki kell szelektálni az eladókat az eredeti árakból
// és ProductOffer rekordokat kell létrehozni

// Ez csak akkor szükséges, ha van meglévő adat
```

---

**Verzió**: 1.0  
**Dátum**: 2026-04-01  
**Status**: ✅ Kész
