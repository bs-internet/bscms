# Test & Regresyon

## Mevcut Durum
Projede `tests/` klasörü bulunmasına rağmen, iş mantığına (Core Modules) özel yazılmış **Birim Test (Unit Test)** veya **Entegrasyon Testi** görülmemektedir. Mevcut testler muhtemelen CI4 framework iskeletinden gelmektedir.

## Riskler
- **Regresyon**: Bir modülde yapılan değişiklik (örneğin `ContentRepository` güncellemesi), diğer bir modülü (örneğin `SitemapController`) bozabilir ve bu ancak canlıda fark edilir.
- **Refactoring Korkusu**: Kod tabanı test ile korunmadığı için, geliştiriciler iyileştirme (refactoring) yapmaktan çekinir.

## Strateji Önerisi
1.  **Feature Tests**: Controller endpoint'lerine (`POST /admin/contents/store`) HTTP istekleri atıp dönen yanıtı (200 OK, Redirect, DB Insert) kontrol eden testler öncelikli yazılmalı.
2.  **Repository Tests**: Karmaşık sorguların (Relations, Filters) doğruluğu test edilmeli.
3.  **CI/CD Pipeline**: GitHub Actions veya GitLab CI ile her push işleminde `phpunit` çalıştırılmalı.
