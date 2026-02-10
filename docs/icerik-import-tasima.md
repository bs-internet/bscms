# İçerik Import / Taşıma

## Mevcut Durum
Sistemde dışarıdan veri alma (Import) veya dışarıya veri verme (Export) mekanizması bulunmamaktadır.
- **Desteklenen Formatlar**: Yok.
- **Migrasyon**: Veritabanı şema güncellemeleri (`app/Database/Migrations`) için CI4 Migration sistemi kullanılıyor, ancak **İçerik Migrasyonu** (Veri taşıma) için bir araç yok.

## İhtiyaç Analizi
CMS sistemlerinde sıklıkla başka platformlardan (WordPress falan) geçiş ihtiyacı olur.

## Öneriler
1.  **CSV/XML Importer**: Admin panelinden şema eşleştirmeli (Map Fields) bir import aracı geliştirilmeli. "CSV'deki 'Başlık' sütunu -> Content 'Title' alanına".
2.  **Export**: İçeriklerin JSON veya CSV olarak dışarı aktarılabilmesi (Yedekleme ve Analiz için).
3.  **Seeder**: Geliştirme ortamı için Faker kütüphanesi ile dummy içerik üreten Seeder sınıfları yazılmalı.
