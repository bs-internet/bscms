# Arama Mimarisi

## Mevcut Durum
Sistemde özel bir "Arama Motoru" veya gelişmiş arama modülü bulunmamaktadır.
- **Admin Araması**: Muhtemelen DataTables veya basit SQL `LIKE %...%` sorguları ile liste sayfalarında filtreleme yapılmaktadır.
- **Frontend Araması**: Merkezi bir arama (Global Search) controller'ı veya routing tanımı (`/search`) mevcut değildir.

## Teknik Kısıtlar
1.  **JSON Meta Alanları**: İçerik verisinin önemli bir kısmı `content_meta` içinde dağınık veya JSON string olarak saklandığı için, veritabanı seviyesinde etkili bir arama (SQL Search) yapmak neredeyse imkansızdır.
2.  **Full-Text Search**: MySQL Full-Text indeksleme kullanılmamaktadır.

## Öneriler
1.  **TNTSearch Entegrasyonu**: PHP tabanlı hafif bir Full-Text arama motoru olan TNTSearch (veya CI4 uyumlu bir kütüphane) entegre edilerek, içerik oluşturulduğunda/güncellendiğinde indeksleme yapılabilir.
2.  **Scout Benzeri Yapı**: Laravel Scout benzeri, model tabanlı bir "Searchable" trait'i oluşturulup, verilerin bir arama servisine (Algolia, Meilisearch veya Database) aktarılması sağlanmalı.
3.  **Frontend Endpoint**: `/search?q=query` rotası oluşturulup, `content_title` ve `content_summary` üzerinde basit bir arama servisi sunulmalı.


sadece frontend endpoint üzerinden arama yeterlidir.