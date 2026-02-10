# Performans

## Önbellek Stratejisi (Caching)
`CacheManager` sınıfı merkezi bir rol oynar.
- **Yöntem**: Genellikle dosya tabanlı (`FileHandler`) cache kullanılır.
- **Kapsam**:
    - İçerikler ID bazlı (`content_{id}`).
    - Ayarlar (`settings_all` veya grup bazlı).
    - Menüler (`menu_{location}`).
- **Invalidation (Temizleme)**: İçerik eklendiğinde/silindiğinde ilgili listeler ve tekil cache'ler temizlenir. `deleteMatching` ile wildcard temizleme yapılır.

## Veritabanı Performansı
- **N+1 Sorunu**: `ContentRepository::findByIdWithRelations` metodu ilişkileri çekerken döngü içinde tekrar sorgu atıyor. 20 içerik listelenen bir sayfada, her birinin 5 ilişkisi varsa 100+ ekstra sorgu oluşabilir. Eager Loading (JOIN veya `WHERE IN`) uygulanmamış.
- **Meta Sorguları**: `content_meta` tablosu üzerinden yapılan filtrelemeler performansı düşürecektir. EAV modeli ölçeklenemez (non-scalable).

## Asset Optimizasyonu
- PHP tarafında CSS/JS birleştirme (concatenation) veya sıkıştırma (minification) yoktur. Bu işlemler build step (Webpack/Vite) tarafına bırakılmıştır ancak "Cache Busting" (versiyonlama) stratejisi PHP view'larında (`master.php`) görülmemiştir (örn: `style.css?v=1.0`).

## Öneriler
1.  **Eager Loading**: Repository katmanı, ilişkili içerikleri tek seferde `WHERE IN (ids)` ile çekecek şekilde refaktör edilmeli.
2.  **Composite Indexes**: `content_meta` tablosunda `(content_id, meta_key)` ve `(meta_key, meta_value)` indekslerinin varlığı kontrol edilmeli.
3.  **HTTP Cache**: Response header'larına `Cache-Control`, `ETag` gibi tarayıcı önbellekleme direktifleri eklenmeli.
