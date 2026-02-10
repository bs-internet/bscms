# Çok Dillilik (Multilingual)

## Mevcut Durum
Sistem altyapısı (CodeIgniter 4) çok dilliliği desteklese de (`Config/App.php` -> `supportedLocales`), CMS'in içerik mimarisi **Tek Dilli** (Mono-lingual) olarak tasarlanmıştır.

- **Arayüz (UI)**: Admin paneli `lang()` fonksiyonları ile çevrilebilir haldedir.

Şuan için tam anlamıyla çoklu dil yapıya geçiş düşünülmüyor. Ama label değerleri lang() fonksiyonu ile çevrilebilir olabilir.
