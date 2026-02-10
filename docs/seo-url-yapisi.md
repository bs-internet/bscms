# SEO & URL Yapısı

## URL Mimarisi
- **Yapı**: `/{contentTypeSlug}/{contentSlug}` (Örn: `/blog/yeni-yazi`) ve Sayfalar için `/page/{slug}`.
- **Yönetim**: `FrontendController`, gelen segmentleri analiz ederek içeriği bulur.
- **Friendly URL**: Slug'lar içerik başlığından otomatik üretilir ancak çakışma kontrolü (duplicate slug prevention) koddaki `findBySlug` metodunda sadece `first()` alarak yapılıyor, benzersizleştirme mantığı (suffix ekleme) incelenmeli.

## SEO Metadata
- **Otomasyon**: Meta Title ve Description için özel bir SEO modülü yok. Muhtemelen `Custom Fields` (Meta Alanları) kullanılarak manuel giriş yapılıyor.
- **Open Graph / Twitter Cards**: Otomatik üretilmiyor. Tema tarafında eğer `meta_image` gibi bir alan varsa basılıyor olabilir.

## Sitemap (Site Haritası)
`SitemapController` manuel bir XML oluşturma işlevi görür:
- **Kapsam**: Sadece `published` durumundaki içerikleri listeler.
- **Frekans**: `Changefreq` ve `Priority` değerleri kod içinde hardcoded (Sayfalar haftalık, anasayfa günlük). Her içerik türü için özelleştirilemez.
- **Trigger**: Manuel üretim butonu var (`generate`), içerik eklendiğinde otomatik tetiklenmiyor gibi görünüyor.

## Öneriler
1.  **SEO Modülü**: Her içerik türüne otomatik eklenen bir "SEO Tab"ı olmalı (Title, Desc, Canonical, Robots, OG Image).
2.  **Otomatik Sitemap**: İçerik yayınlandığında veya güncellendiğinde Sitemap XML'i arka planda güncellenmeli.
3.  **Schema.org JSON-LD**: İçerik türüne göre (Article, Product, Event) yapılandırılmış veri (Structured Data) otomatik üretilmeli.
