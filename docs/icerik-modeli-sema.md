# İçerik Modeli & Şema

## Veri Yapısı
Sistem, "EAV" (Entity-Attribute-Value) ve İlişkisel modelin bir karışımını kullanır. CMS'lerin tipik esnek içerik ihtiyacına yanıt vermek üzere tasarlanmıştır.

### Ana Tablolar
1.  **`content_types`**: İçerik türlerini tanımlar (Örn: Makale, Haber, Ürün). Şema tanımı burada başlar.
2.  **`contents`**: Temel içerik verisini tutar.
    - Sabit Kolonlar: `id`, `content_type_id`, `title`, `slug`, `status`, `created_at` vb.
3.  **`content_meta`**: İçeriğe ait dinamik alanları (custom fields) tutar.
    - Kolonlar: `content_id`, `meta_key`, `meta_value`.
4.  **`content_type_fields`**: Bir içerik türünün hangi meta alanlara sahip olduğunu tanımlar (Form yapısını belirler).

## İlişki Yönetimi (Relations)
İlişkiler (Relation Field Type) genellikle `content_meta` tablosunda saklanır.
- **Veri Tipi**: İlişkili içerik ID'leri JSON array olarak (`[1, 5, 8]`) veya tekil ID string olarak saklanır.
- **Okuma**: `ContentRepository::findByIdWithRelations` metodu, meta tablosundaki bu JSON verisini PHP tarafında decode eder ve ilgili içerikleri tekrar sorgular (N+1 sorgu potansiyeli).

## Şema Sorunları & Riskler
1.  **Sorgu Performansı**: Meta tablosunda `meta_value` üzerinden arama/filtreleme yapmak (örneğin "Fiyatı 100'den büyük ürünler") SQL tarafında çok maliyetlidir (Index kullanılamaz veya JOIN gerekir).
2.  **İlişki Bütünlüğü (Referential Integrity)**: İlişkiler JSON olarak saklandığı için veritabanı seviyesinde `FOREIGN KEY` kısıtlamaları yoktur. Silinen bir içeriğin ID'si meta datasında kalabilir (Orphaned references).
3.  **Tip Güvenliği**: Tüm meta değerleri `text/string` olarak saklanır. Sayısal işlemler veya tarih sorguları için tip dönüşümü (casting) gerekir.

## Öneriler
1.  **Flat Table Opsiyonu**: Yoğun sorgulanan veya filtrelenen içerik türleri için, meta tablosu yerine o türe özel fiziksel tablolar (örn: `data_products`) oluşturacak bir "Flat Index" mekanizması geliştirilebilir.
2.  **Many-to-Many Tablosu**: İçerik-İçerik ilişkileri için meta tablosuna JSON koymak yerine, `content_relations` (source_id, target_id, type) gibi bir pivot tablo kullanmak veri bütünlüğü ve sorgu hızı sağlar.
