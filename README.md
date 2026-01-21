# BSCMS - Modern Modüler İçerik Yönetim Sistemi

BSCMS, CodeIgniter 4 tabanlı, tamamen modüler ve eklenti (plugin) destekli bir içerik yönetim sistemidir. Bu proje, standart CodeIgniter yapısını aşarak tüm bileşenleri (Logic, Database, Assets) bağımsız modüllere ayırır.

## Temel Özellikler

- **%100 Modüler Mimari**: Her özellik (İçerik, Üyelik, Menü, Medya vb.) kendi bağımsız modülü içinde barındırılır.
- **Eklenti Desteği**: Gelişmiş `Hook` ve `Event` sistemi sayesinde sisteme dışarıdan eklentiler dahil edilebilir.
- **Taşınabilir Veritabanı**: Migrations ve Seeders dosyaları modül bazlıdır, modüllerle birlikte taşınabilir.
- **Modüler Asset Yönetimi**: Her modülün kendine ait CSS/JS dosyaları vardır ve tek bir komutla yayınlanabilir.
- **Çoklu Dil Desteği (Localization)**: Tüm modüller `tr` ve `en` dillerini destekler, gelişmiş dil dosyası yapısına sahiptir.
- **Gelişmiş Hook Sistemi**: `HookManager` ile aksiyon (Action) ve filtre (Filter) desteği.

## Proje Yapısı

```text
app/
├── Config/ (Framework yapılandırması)
├── Core/
│   ├── Modules/ (Auth, Content, Category, Media, Menu, System, Form, Component)
│   │   ├── [ModuleName]/
│   │   │   ├── Controllers/
│   │   │   ├── Models/
│   │   │   ├── Database/ (Migrations, Seeds)
│   │   │   ├── Assets/ (CSS, JS, Images)
│   │   │   ├── Language/ (tr, en)
│   │   │   └── Views/
│   └── Shared/ (Ortak Kütüphaneler, Helperlar, Temel Sınıflar)
└── Plugins/ (Dışarıdan eklenen modüller/eklentiler)
```

## Kurulum

1. Depoyu klonlayın.
2. `composer install` komutunu çalıştırın.
3. `env.example` dosyasını `.env` olarak kopyalayın ve bilgilerinizi düzenleyin:
   ```bash
   cp env.example .env
   ```
4. Uygulama anahtarını (Encryption Key) oluşturun:
   ```bash
   php spark key:generate
   ```
5. Veritabanı tablolarını oluşturun:
   ```bash
   php spark migrate
   ```
5. Başlangıç verilerini yükleyin:
   ```bash
   php spark db:seed App\Core\Modules\Auth\Database\Seeds\AuthSeeder
   php spark db:seed App\Core\Modules\System\Database\Seeds\SystemSeeder
   php spark db:seed App\Core\Modules\Menu\Database\Seeds\MenuSeeder
   ```
6. Modül assetlerini `public` klasörüne yayınlayın:
   ```bash
   php spark assets:publish
   ```

## Varsayılan Giriş Bilgileri

Yönetim paneline erişmek için (AdminSeeder sonrası):
- **URL**: `/admin`
- **Email**: `admin@admin.com`
- **Şifre**: `123456`

## Özel CLI Komutları

- `php spark assets:publish`: Tüm modüllerdeki `Assets` klasörlerini `public/assets/` altına kopyalar.
- `php spark cache:clear`: Tüm sistem önbelleğini temizler.

## Gereksinimler

- PHP 8.1 veya üzeri
- MySQL/MariaDB
- Intl ve Mbstring PHP eklentileri
