# BSCMS - Kurumsal Seviye Modüler İçerik Yönetim Sistemi

[![Mühendislik Standartları](https://img.shields.io/badge/Mühendislik%20Standartları-v3.0-success)](docs/ENGINEERING_STANDARDS.md)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.6.4-orange)](https://codeigniter.com/)
[![PHP Sürümü](https://img.shields.io/badge/PHP-8.1%2B-blue)](https://php.net)
[![Durum](https://img.shields.io/badge/Durum-Canlıya%20Hazır-brightgreen)]()

BSCMS, modern yazılım mühendisliği standartlarıyla geliştirilmiş, WordPress benzeri kullanım kolaylığına sahip, kurumsal seviyede modüler bir içerik yönetim sistemidir. CodeIgniter 4.6.4 üzerine inşa edilmiş olup PHP 8.1+ özelliklerini (Enum'lar, Tiplendirilmiş Özellikler) kullanır.

## 🎯 Temel Felsefe

- **Temiz Mimari**: Repository Pattern, Servis Konteyner, Olay Odaklı Tasarım
- **Sıfır Teknik Borç**: Mühendislik Standartları v3.0 ile %100 uyumluluk
- **Transaction Güvenliği**: Tüm çoklu-tablo işlemleri transaction koruması altında
- **Olay Odaklı**: Polymorphic temizlik ve önbellek geçersizleme için kapsamlı olay sistemi
- **Eklenti Desteği**: WordPress tarzı hook ve eklenti sistemi altyapısı

---

## ⚡ Öne Çıkan Özellikler

### 1. Modüler Mimari

- **Bağımsız Modüller**: Üyelik, İçerik, Kategori, Bileşen, Form, Menü, Medya, Sistem
- **Sıfır Bağımlılık**: Modüller arası iletişim sadece Servis Konteyner üzerinden
- **Kolay Genişletme**: Yeni modül ekleme için standart şablon yapısı

### 2. Olay Odaklı Mimari

- **Otomatik Temizlik**: İçerik silindiğinde ilişkili tüm kayıtlar otomatik temizlenir
- **Akıllı Önbellek Geçersizleme**: İlişkisel önbellek temizleme stratejisi
- **Otomatik Site Haritası**: İçerik değişikliklerinde otomatik sitemap.xml güncelleme
- **Hook Sistemi**: WordPress tarzı aksiyon ve filtre desteği

### 3. Gelişmiş İçerik Yönetimi

- **Dinamik İçerik Tipleri**: Çalışma zamanında yeni içerik tipleri tanımlama
- **Özel Alanlar**: Her içerik tipine özel alan tanımlama (ACF benzeri)
- **Hiyerarşik Kategoriler**: Sınırsız derinlikte kategori yapısı
- **İlişki Alanları**: İçerikler arası tek/çoklu ilişkilendirme
- **Bileşen Sistemi**: Global ve özel bileşenlerle esnek sayfa yapısı

### 4. Performans & Optimizasyon

- **Akıllı Önbellekleme**: Okuma ve yazma önbellek stratejileri
- **N+1 Önleme**: Eager loading ve toplu sorgular
- **Transaction Güvenliği**: Hayalet içerik önleme
- **Veritabanı İndeksleme**: Optimize edilmiş sorgu performansı

### 5. Geliştirici Deneyimi

- **Repository Pattern**: Endişelerin temiz ayrımı
- **Servis Konteyner**: Düzgün bağımlılık enjeksiyonu
- **CLI Komutları**: Asset yayınlama, önbellek temizleme
- **Tip Güvenliği**: Enum tabanlı durum yönetimi
- **Try-Catch Yok**: Temiz hata yönetimi desenleri

### 6. SEO & Güvenlik

- **Otomatik Site Haritası**: İçerik değişikliklerinde XML site haritası oluşturma
- **Meta Alanları**: İçerik tipi başına yerleşik SEO alan desteği
- **CSRF Koruması**: Framework seviyesinde güvenlik
- **XSS Önleme**: Görünümlerde otomatik kaçış
- **SQL Enjeksiyon Güvenli**: Query builder kullanımı

---

## 📁 Proje Yapısı

```
bscms/
├── app/
│   ├── Config/
│   │   ├── Services.php           # Servis Konteyner kayıtları
│   │   ├── Routes.php             # Modüler route organizasyonu
│   │   ├── Events.php             # Olay sistemi kaydı
│   │   └── Filters.php            # Kimlik doğrulama & hız sınırlama
│   ├── Core/
│   │   ├── Modules/               # Çekirdek özellik modülleri
│   │   │   ├── Auth/              # Kimlik doğrulama & kullanıcı yönetimi
│   │   │   ├── Content/           # Dinamik içerik tipleri & alanlar
│   │   │   ├── Category/          # Hiyerarşik kategoriler
│   │   │   ├── Component/         # Yeniden kullanılabilir sayfa bileşenleri
│   │   │   ├── Form/              # Dinamik form oluşturucu
│   │   │   ├── Menu/              # Menü yönetimi
│   │   │   ├── Media/             # Dosya yüklemeleri & yönetimi
│   │   │   └── System/            # Ayarlar, kontrol paneli, CLI
│   │   └── Shared/                # Modüller arası yardımcılar
│   │       ├── Controllers/       # Temel denetleyiciler
│   │       ├── Libraries/         # Şablon, Döngü, Önbellek
│   │       ├── Filters/           # AdminAuth, RateLimit
│   │       └── Helpers/           # WordPress tarzı yardımcılar
│   └── Plugins/                   # Üçüncü parti eklentiler
│       └── [EklentiAdı]/
│           ├── Plugin.php
│           ├── Controllers/
│           └── Views/
├── public/
│   ├── assets/
│   │   ├── admin/                 # Paylaşılan admin varlıkları
│   │   └── modules/               # Modüle özel varlıklar
│   ├── themes/                    # Ön yüz temaları
│   └── uploads/                   # Kullanıcı yüklemeleri
├── writable/
│   ├── cache/
│   └── logs/
└── themes/                        # Tema şablonları
    └── default/
        ├── header.php
        ├── footer.php
        ├── single.php
        └── index.php
```

### Modül Anatomisi

```
Modül/
├── Controllers/          # İstek yönetimi
│   ├── ModuleController.php
│   └── ModuleFieldController.php (AJAX)
├── Repositories/
│   ├── Interfaces/      # Repository sözleşmeleri
│   └── ModuleRepository.php
├── Models/              # Varlık tanımları
├── Entities/            # Cast'lı varlık sınıfları
├── Events/              # Temizlik & önbellek olayları
├── Validation/          # Doğrulama kuralları
├── Enums/               # Tip güvenli numaralandırmalar
├── Views/               # Modül şablonları
├── Language/            # i18n çevirileri
│   └── tr/
│       └── Module.php
└── Assets/              # Modüle özel CSS/JS
    ├── css/
    └── js/
```

---

## 🚀 Kurulum

### Gereksinimler

- **PHP**: 8.1 veya üzeri
- **MySQL/MariaDB**: 8.0+
- **Composer**: 2.x
- **PHP Eklentileri**: intl, mbstring, mysqli, gd, curl

### Adımlar

1. **Projeyi Klonlayın**

```bash
   git clone https://github.com/kullaniciadi/bscms.git
   cd bscms
```

2. **Bağımlılıkları Yükleyin**

```bash
   composer install
```

3. **Ortam Ayarları**

```bash
   cp env.example .env
```

`.env` dosyasını düzenleyin:

```ini
   CI_ENVIRONMENT = development

   app.baseURL = 'http://localhost:8080'

   database.default.hostname = localhost
   database.default.database = bscms
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
```

4. **Şifreleme Anahtarı Oluşturun**

```bash
   php spark key:generate
```

5. **Veritabanı Tablolarını Oluşturun**

```bash
   php spark migrate
```

6. **Başlangıç Verilerini Yükleyin**

```bash
   # Admin kullanıcısı
   php spark db:seed App\Core\Modules\Auth\Database\Seeds\AuthSeeder

   # Sistem ayarları
   php spark db:seed App\Core\Modules\System\Database\Seeds\SystemSeeder

   # Varsayılan menüler
   php spark db:seed App\Core\Modules\Menu\Database\Seeds\MenuSeeder
```

7. **Varlıkları Yayınlayın**

```bash
   php spark assets:publish
```

8. **Geliştirme Sunucusunu Başlatın**

```bash
   php spark serve
```

9. **Yönetim Paneline Giriş**
    - URL: `http://localhost:8080/admin`
    - E-posta: `admin@admin.com`
    - Şifre: `123456`

---

## 🔧 CLI Komutları

### Varlık Yönetimi

```bash
# Tüm modül varlıklarını public/ klasörüne kopyala
php spark assets:publish
```

### Önbellek Yönetimi

```bash
# Tüm önbelleği temizle
php spark cache:clear
```

### Veritabanı Yönetimi

```bash
# Migrasyonları çalıştır
php spark migrate

# Migrasyonları geri al
php spark migrate:rollback

# Veritabanını sıfırla ve yeniden oluştur
php spark migrate:refresh

# Seed çalıştır
php spark db:seed SeederName
```

---

## 📚 Kullanım Örnekleri

### İçerik Tipi Oluşturma

1. Yönetim panelinde **İçerik Tipleri** → **Yeni İçerik Tipi**
2. Tip bilgilerini girin (Örneğin: "Blog Yazıları")
3. **Düzenle** düğmesine tıklayarak özel alanları ekleyin
4. Alan tipleri: Metin, Metin Alanı, Zengin Metin, Resim, Galeri, İlişki, Tekrarlayıcı

### Ön Yüz Şablon Sistemi

WordPress benzeri şablon fonksiyonları:

```php
// themes/default/index.php
<?php get_header(); ?>

<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <article>
            <h2><?php the_title(); ?></h2>
            <div><?php the_content(); ?></div>

            <?php if (has_field('ozet')): ?>
                <p><?php the_field('ozet'); ?></p>
            <?php endif; ?>

            <?php if (has_categories()): ?>
                <div class="kategoriler">
                    <?php the_categories(', '); ?>
                </div>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <p>İçerik bulunamadı.</p>
<?php endif; ?>

<?php get_footer(); ?>
```

### Özel Sorgu

```php
// Özel sorgu oluştur
$sorgu = new ContentQuery([
    'content_type' => 'blog',
    'status' => 'published',
    'limit' => 10,
    'category' => 'teknoloji'
]);

while ($sorgu->have_posts()) {
    $sorgu->the_post();
    echo '<h3>' . get_the_title() . '</h3>';
}
```

### Yardımcı Fonksiyonlar

```php
// İçerik
the_title()           // Başlık
the_content()         // İçerik
the_excerpt()         // Özet
get_permalink()       // Kalıcı bağlantı
the_field('anahtar')  // Özel alan

// Kategori
get_categories()      // Kategorileri al
the_categories()      // Kategorileri yazdır
has_category($id)     // Kategori kontrolü

// Menü
get_menu('primary')   // Menü al
the_menu('primary')   // Menü yazdır

// Ayarlar
get_setting('anahtar') // Ayar değeri al
site_name()           // Site adı
site_description()    // Site açıklaması
```

---

## 🔌 Eklenti Geliştirme

### Eklenti Yapısı

```php
// app/Plugins/OrnekEklenti/Plugin.php
<?php

namespace App\Plugins\OrnekEklenti;

use App\Core\Shared\Libraries\Plugin as BasePlugin;

class Plugin extends BasePlugin
{
    protected string $name = 'Örnek Eklenti';
    protected string $version = '1.0.0';
    protected string $author = 'Adınız Soyadınız';
    protected string $description = 'BSCMS için örnek eklenti';

    public function register(): void
    {
        // Hook'ları kaydet
        $this->addHook('before_content_save', [$this, 'icerigiDegistir']);

        // Olay dinleyicisini kaydet
        $this->listen('content_created', [$this, 'icerikolusturuldu']);
    }

    public function boot(): void
    {
        // Yönlendirmeleri yükle
        $routes = service('routes');
        $routes->get('eklenti/ornek', 'App\Plugins\OrnekEklenti\Controllers\OrnekController::index');
    }

    public function icerigiDegistir($icerik)
    {
        // İçeriği değiştir
        $icerik['title'] = mb_strtoupper($icerik['title']);
        return $icerik;
    }

    public function icerikolusturuldu(int $icerikId)
    {
        // İçerik oluşturulunca çalışır
        log_message('info', "Yeni içerik oluşturuldu: {$icerikId}");
    }
}
```

### Eklenti Aktifleştirme

```php
// app/Config/Events.php
$eklentiYoneticisi = new \App\Core\Shared\Libraries\PluginManager();
$eklentiYoneticisi->activate('OrnekEklenti');
$eklentiYoneticisi->boot();
```

---

## 🎨 Tema Geliştirme

### Tema Yapısı

```
themes/temam/
├── header.php       # Üst bilgi şablonu
├── footer.php       # Alt bilgi şablonu
├── index.php        # Arşiv/liste şablonu
├── single.php       # Tekil içerik şablonu
├── page.php         # Sayfa şablonu
├── 404.php          # Bulunamadı şablonu
├── category.php     # Kategori şablonu
├── blog.php         # İçerik tipine özel (opsiyonel)
└── assets/
    ├── css/
    ├── js/
    └── images/
```

### Şablon Hiyerarşisi

```
İstek: /blog/benim-yazim
1. themes/temam/blog-single.php    (içerik tipi + tekil)
2. themes/temam/single.php         (genel tekil)
3. themes/temam/index.php          (yedek)

İstek: /blog
1. themes/temam/blog.php           (içerik tipi arşivi)
2. themes/temam/index.php          (genel arşiv)
```

---

## 📖 Mühendislik Standartları

BSCMS, Mühendislik Standartları v3.0 ile geliştirilmiştir. Detaylı standartlar için bakınız: `docs/ENGINEERING_STANDARDS.md`

### Kritik Kurallar

1. **Transactionlar**: 2+ tablo yazımı = transaction (zorunlu)
2. **Olay Tetikleyicileri**: Silme/Güncelleme işlemlerinde olay tetikleme (zorunlu)
3. **Önbellek Geçersizleme**: Her yazma işleminde önbellek temizleme (zorunlu)
4. **Repository Pattern**: Denetleyicide model kullanımı yasak
5. **Enum Kullanımı**: Sihirli dizeler yerine enum kullanımı
6. **Try-Catch Yok**: Sadece harici API/dosya işlemleri için

### Kod İnceleme Kontrol Listesi

- [ ] Repository arayüzü tanımlandı
- [ ] Serviste kayıt yapıldı
- [ ] Olay sınıfı oluşturuldu
- [ ] Olay tetikleyicileri eklendi
- [ ] Önbellek geçersizleme implementasyonu
- [ ] Transaction kullanımı (çoklu-tablo)
- [ ] Doğrulama sınıfı mevcut
- [ ] Enum kullanımı (tip güvenliği)
- [ ] Dil dosyası eksiksiz
- [ ] Görünüm isimlendirme kuralı

---

## 🔒 Güvenlik

- **CSRF Koruması**: Tüm formlar CSRF token ile korunur
- **XSS Önleme**: Otomatik kaçış aktif
- **SQL Enjeksiyon**: Query builder kullanımı zorunlu
- **Şifre Hash'leme**: Bcrypt (maliyet 12)
- **Hız Sınırlama**: Form gönderimi hız sınırlama
- **Oturum Güvenliği**: Güvenli, httpOnly çerezler
- **Girdi Doğrulama**: Tüm kullanıcı girdisi doğrulaması

---

## 🚀 Canlıya Alma

### Canlı Ayarları

1. **Ortam**

```ini
   CI_ENVIRONMENT = production
```

2. **Veritabanı**
    - Bağlantı havuzu aktif
    - Hazırlanmış ifadeler kullanımı
    - İndeks optimizasyonu

3. **Önbellek**

```bash
   php spark cache:clear
```

4. **Varlıklar**

```bash
   php spark assets:publish
   composer dump-autoload --optimize
```

5. **İzinler**

```bash
   chmod -R 755 writable/
   chmod -R 755 public/
```

6. **Güvenlik**
    - `.env` dosyası web kökü dışında
    - Hata ayıklama modu kapalı
    - Hata raporlama kapalı
    - HTTPS zorunlu

---

## 📊 Performans

### Optimizasyon Stratejileri

- **Önbellek Katmanları**: İçerik, kategori, menü, ayarlar
- **Sorgu Optimizasyonu**: Eager loading, toplu sorgular
- **Varlık Sıkıştırma**: CSS/JS sıkıştırma (canlı)
- **Resim Optimizasyonu**: Yüklemede otomatik yeniden boyutlandırma
- **CDN Hazır**: Statik varlık sunumu

### Kıyaslama

```
Ortalama Yanıt Süresi: <100ms
Veritabanı Sorguları: Sayfa başına <10
Önbellek İsabet Oranı: >%90
Bellek Kullanımı: İstek başına <32MB
```

---

## 🤝 Katkıda Bulunma

1. Fork yapın
2. Özellik dalı oluşturun (`git checkout -b ozellik/HarikaOzellik`)
3. Mühendislik Standartları v3.0'a uyun
4. Commit atın (`git commit -m 'Harika özellik eklendi'`)
5. Dalı push'layın (`git push origin ozellik/HarikaOzellik`)
6. Pull Request açın

### Commit Mesaj Formatı

```
tip(kapsam): konu

gövde

alt bilgi
```

**Tipler**: feat, fix, docs, style, refactor, test, chore

**Örnek**:

```
feat(icerik): özel alan doğrulama eklendi

- Özel alan tipleri için doğrulama eklendi
- Regex desen desteği uygulandı
- Testler güncellendi

Kapanış #123
```

---

## 📝 Lisans

Bu proje [MIT Lisansı](LICENSE) altında lisanslanmıştır.
