# Medya & Dosya Yönetimi

## Depolama Mimarisi
- **Yerel Disk**: Dosyalar `public/uploads/` dizinine kaydedilir.
- **İsimlendirme**: `getRandomName()` ile benzersiz isimler oluşturulur, orijinal isim korunmaz (DB'de saklanır).
- **Veritabanı**: `media` tablosu dosya metadata'sını tutar.

## Görsel İşleme (Image Processing)
- `ImageProcessor` kütüphanesi yükleme anında çalışır.
- Tanımlı boyutlara (`ImageSize` Enum) göre resize/crop yapar.
- Orijinal dosya korunur.

## Sorunlu Alanlar
1.  **Bağımlılık Kontrolü (Dependency Check)**: Bir görselin silinmesi sırasında yapılan kontrol `isMediaInUse` metodu ile `content_meta` tablosunda `LIKE` sorgusu çalıştırılarak yapılıyor.
    - **Risk**: Çok büyük tablolarda bu sorgu (Full Table Scan) performansı öldürür.
    - **Hata Payı**: `1` ID'li görseli ararken `10`, `100`, `21` gibi içinde `1` geçen diğer ID'lerle karışma riski var (Regex veya tam JSON parsing yapılmıyor, sadece basit string match var).
2.  **CDN Yoksunluğu**: Dosyalar direkt sunucudan sunuluyor. Trafik arttığında bu darboğaz yaratır.
3.  **Dosya Türü**: Sadece temel görseller ve ofis dokümanları destekleniyor. Video veya SVG için özel bir işlem yok.

## İyileştirmeler
1.  **Usage Table**: Görsel kullanımlarını takip etmek için `media_usage` (media_id, content_id, field_key) tablosu tutulmalı ve her içerik kaydedildiğinde senkronize edilmeli.
2.  **Flysystem Entegrasyonu**: Dosya işlemleri soyutlanarak S3, MinIO, Azure Blob Storage gibi bulut alanlarına yedekleme/yükleme imkanı sağlanmalı.
3.  **WebP/AVIF Dönüşümü**: Yüklenen görseller otomatik olarak modern formatlara (WebP) dönüştürülmeli.
