# Operasyonel Dayanıklılık

## Hata Yönetimi
- **Exception Handling**: CI4'ün standart hata yakalama mekanizması aktiftir. Kritik hatalar log dosyasına yazılır.
- **Failover**: Önbellek (Cache) sisteminde `FileHandler` birincil, `DummyHandler` ikincil (fallback) olarak ayarlanmıştır. Redis/Memcached çökse bile sistem dosya cache ile çalışmaya devam eder mi? Config'de fallback mekanizması var ancak kodda `try-catch` blokları ile sarmalanmamış kritik noktalar (örn: `ContentController::store` içindeki DB transaction) manuel rollback gerektiriyor.

## Güvenlik Duvarları
- **Rate Limiting**: Brute-force saldırılarına karşı Login tarafında koruma mevcuttur.
- **CSRF**: CI4 CSRF koruması formlarda aktiftir.
- **XSS**: Editör, girdi temizliğini (Sanitization) kısmen yapar ancak zengin metin editörlerinde (WYSIWYG) HTML izinleri XSS riski taşır. `ContentController` içinde `sanitizeArray` metodu var ancak kapsamı sınırlı.

## Öneriler
1.  **Circuit Breaker**: Dış servislere (SMTP, S3, API) yapılan çağrılarda hata toleransı için Circuit Breaker deseni uygulanmalı.
2.  **Backup Otomasyonu**: Veritabanı ve `uploads` klasörünün periyodik yedeğini alıp uzak sunucuya atan bir Cron Job komutu (`php spark backup:run`) geliştirilmeli.
3.  **Graceful Degradation**: Veritabanı bağlantısı koptuğunda kullanıcıya standart 500 hatası yerine, önbellekten sunulan "Bakım Modu" sayfası gösterilmeli.
