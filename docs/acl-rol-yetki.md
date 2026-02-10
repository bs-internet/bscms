# ACL / Rol / Yetki Tasarımı

## Mevcut Durum
Yönetim paneli **Tek Kademeli Yetkilendirme (Single-Tier Auth)** kullanmaktadır.
- **Admin**: Sisteme giriş yapan herkes "Yönetici" olarak kabul edilir.
- **Kısıtlamalar**: Modül veya fonksiyon bazlı (Örn: "Haberleri düzenleyebilir ama Ayarlara giremez") bir yetki matrisi (ACL/RBAC) kodlanmamıştır.
- **Güvenlik**:
    - **Oturum**: IP Subnet kontrolü (`admin_ip_subnet`) ile Session Hijacking önlemi alınmıştır.
    - **Rate Limit**: Başarısız giriş denemeleri `RateLimiter` kütüphanesi ile bloklanır.
    - **Cihaz Yönetimi**: "Tüm cihazlardan çıkış yap" özelliği mevcuttur.

Sistemde yetkilendirme şuan düşünülmüyor. O yüzden varolan yapının güvenli olması önceliklidir.
