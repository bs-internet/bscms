# Yayınlama Akışı

## Mevcut Durum
Yayınlama akışı `ContentStatus` Enum üzerinden yönetilen basit bir durum makinesidir:
`Taslak (Draft)` -> `Yayında (Published)` -> `Arşiv (Archived)`.

## Akış Kontrolü
- **Yetki**: İçeriği oluşturan herkes durumunu değiştirebilir. Rol bazlı bir kısıtlama (örn: "Yazar sadece taslak açabilir, Editör yayınlayabilir") kod tarafında görülmedi.
- **Zamanlama**: İleri tarihli yayınlama (Scheduled Publishing) özelliği veritabanı şemasında var olabilir (`published_at` vs.) ancak `create.php` formunda ve Controller mantığında bu alanı işleyen bir Cron/Job yapısı görülmedi.

## Versiyonlama
İçerik düzenlendiğinde eski hali üzerine yazılır (Overwrite). Bir "Revision History" (Versiyon Geçmişi) sistemi mevcut değil. Hatalı bir değişiklik yapıldığında geri alma şansı yok.

## Öneriler
1.  **Revizyon Sistemi**: `content_revisions` tablosu oluşturulup, her güncellemede eski data buraya JSON olarak yedeklenmeli.
2.  **Admin Onay Akışı**: Eğer çok kullanıcılı bir sistem hedefleniyorsa, basit bir "Workflow" motoru eklenmeli (Submit for Review -> Approve -> Publish).
3.  **Zamanlanmış İçerik**: `published_at` alanı formda aktif edilmeli ve bir Cron Job her dakika "vakti gelen taslakları" yayına almalı.
