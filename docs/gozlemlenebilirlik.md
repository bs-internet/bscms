# Gözlemlenebilirlik

## Loglama
- **Sistem Logları**: Dosya tabanlı (`writeable/logs/log-Y-m-d.php`). Hata seviyesine göre (Error, Critical, Debug) ayrışır.
- **Audit Log (Denetim İzi)**: `login_attempts` tablosu dışında, sistemdeki **değişiklikleri** (Who did what?) takip eden bir yapı yoktur. "Admin X kullanıcısı, Y ayarını değiştirdi" gibi bir kayıt tutulmamaktadır.

## İzleme (Monitoring)
- **Health Check**: `/health` veya `/status` gibi, sistemin veritabanı/cache/disk durumunu JSON dönen bir uç nokta yoktur.
- **APM**: NewRelic, Sentry veya OpenTelemetry entegrasyonu kod seviyesinde mevcut değildir.

## Öneriler
1.  **Activity Logger**: Tüm `create`, `update`, `delete` işlemleri `activity_logs` tablosuna (user_id, action, model, record_id, changes_json) kaydedilmeli.
2.  **Merkezi Loglama**: Log dosyaları sunucuda kalmak yerine, bir log toplayıcıya (Elasticsearch, Papertrail) gönderilmeli (Monolog Handler eklenerek).
3.  **Health Dashboard**: Admin panelinde sistemin anlık durumunu (CPU, RAM, Disk, Queue boyutu) gösteren bir widget eklenmeli.
