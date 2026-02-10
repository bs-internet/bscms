# Editör Paneli UX

## Mevcut Yapı
Editör paneli (`contents/create.php` ve `edit.php`), sunucu taraflı oluşturulan (Server-Side Rendered) klasik bir form yapısındadır.
- **Layout**: "İki Sütunlu" yapı.
    - **Sol (Ana)**: Başlık, Slug ve dinamik alanlar (metin, editör, görsel).
    - **Sağ (Sidebar)**: Yayın durumu, Kategoriler ve Kaydet butonu.

## Bileşenler
- **Dinamik Alanlar**: PHP tarafında `foreach` döngüsü ile alan tipine göre `input`, `textarea` veya `select` render edilir.
- **Rich Editor**: `.rich-editor` sınıfı textarealara atanarak JS tabanlı bir editör (Muhtemelen TinyMCE, CKEditor veya Editor.js) tetikleniyor.
- **Dosya Yükleme**: `.filepond` sınıfı ile FilePond kütüphanesi entegrasyonu görülüyor. Bu modern ve iyi bir tercih.

## Tespit Edilen Eksiklikler
1.  **Canlı Önizleme (Live Preview)**: İçeriğin son halini görmeyi sağlayan eşzamanlı bir önizleme modu yok. Kullanıcı "Kaydet" deyip ön yüze gitmek zorunda.
2.  **Blok Tabanlı Yapı**: Alanlar "form stili" alt alta diziliyor. Modern "Blok Editör" (Gutenberg/Notion stili) deneyimi sunmuyor. Sadece "metin alanı" içinde zengin metin var.
3.  **Inline PHP**: View dosyaları içinde çok fazla mantık (if/else field type checks) var. Bu, özelleştirmeyi ve bakımı zorlaştırır.
4.  **Validasyon Geri Bildirimi**: Hata mesajları session flash data ile geliyor, alan bazlı inline hata gösterimi (input altında kırmızı yazı) standart HTML5 veya basit text düzeyinde.

## Önerilen İyileştirmeler
1.  **View Component Yapısı**: Alan render işlemleri `ViewCell` veya ayrı partial dosyalarına (`fields/text.php`, `fields/image.php`) taşınmalı.
2.  **Preview Split Screen**: Ekranın yarısında formu, diğer yarısında render edilmiş halini gösteren bir mod.
3.  **Draft Auto-Save**: JS ile periyodik olarak arka planda taslak kaydetme özelliği (Heartbeat).
