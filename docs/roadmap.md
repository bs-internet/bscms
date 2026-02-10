# Uygulama Yol Haritası (Roadmap)

Sistemde tespit edilen 17 başlığı, teknik bağımlılıklar (dependencies) ve iş değeri (business value) açısından 5 aşamalı bir plana oturttum.

## 🏁 Faz 1: Kritik Altyapı & Güvenlik (Hemen Başlanmalı)
Bu maddeler sistemin "temelini" oluşturur. Diğer özellikler bu temeller üzerine inşa edileceği için en başta yapılmalıdır. Yanlış bir güvenlik veya yetki mimarisi üzerine özellik eklemek sonradan büyük maliyet yaratır.

1.  **[12] ACL / Rol / Yetki Tasarımı**: Kullanıcıların "ne yapabileceğini" belirlemeden diğer modüllere geçilmemeli.
2.  **[15] Test & Regresyon**: Refactoring (kod iyileştirme) yaparken mevcut sistemi bozmamak için önce temel testler yazılmalı.
3.  **[1] Mimari Denetim (Refactoring)**: Servis ve config yapısındaki dağınıklık toparlanmalı.
4.  **[13] Operasyonel Dayanıklılık**: Backup ve Hata yönetimi, veri kaybını önlemek için şart.

---

## 🏗️ Faz 2: Veri Modeli & Çekirdek (Zorunlu Değişiklikler)
Veritabanı şemasını değiştirecek (Breaking Changes) işler buradadır. İçerik girildikten sonra bu değişiklikleri yapmak çok zorlaşır (Migration cehennemi).

5.  **[4] İçerik Modeli & Şema**: Performance ve veri bütünlüğü için İlişkisel yapı ve JSON kullanımı düzeltilmeli.
6.  **[11] Çok Dillilik (Basitleştirildi)**: Mevcut yapı korunacak, sadece arayüz çevirileri (`lang()`) kullanılacak. Veritabanı çok dilliliği şimdilik rafa kalktı.
7.  **[8] Performans**: Veritabanı sorgu optimizasyonları (N+1) ve Cache stratejisi.

---

## 🔌 Faz 3: Genişletilebilirlik (Ekosistem)
Sistemin 3. parti geliştiricilere veya yeni modüllere açılmasını sağlayacak altyapı.

8.  **[17] Plugin API & Hook Sözleşmesi**: Eklentilerin sisteme nasıl müdahale edeceği standartlaşmalı.
9.  **[2] Eklenti / Modül Yaşam Döngüsü**: Eklentilerin güvenli bir şekilde kurulup kaldırılabilmesi.
10. **[3] Tema / Template Sistemi**: Frontend geliştiriciler için esnek bir yapı sunulması.

---

## 🎨 Faz 4: Editör & İş Akışı (Kullanıcı Deneyimi)
Altyapı sağlamlaştıktan sonra, içerik yöneticilerinin (Editörlerin) hayatını kolaylaştıracak özellikler.

11. **[5] Editör Paneli UX**: Canlı önizleme, blok editör gibi modern özellikler.
12. **[7] Medya & Dosya Yönetimi**: Görsel optimizasyonu, CDN, WebP desteği.
13. **[6] Yayınlama Akışı**: Revizyon geçmişi, zamanlanmış yayınlama.

---

## 🚀 Faz 5: Büyüme & Operasyon (Nice-to-Have)
Sistem canlıya çıktıktan sonra trafiği artırmaya ve yönetmeye yönelik geliştirmeler.

14. **[10] SEO & URL Yapısı**: Otomatik Sitemap, Schema.org verileri.
15. **[9] Arama Mimarisi (Basitleştirildi)**: Sadece frontend tarafında basit bir `/search` endpoint'i yapılacak. Karmaşık indeksleme sistemleri iptal.
16. **[14] Gözlemlenebilirlik**: Logların merkezi takibi.
17. **[16] İçerik Import / Taşıma**: Başka sistemlerden göç araçları.
