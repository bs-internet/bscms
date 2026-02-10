# Eklenti / Modül Yaşam Döngüsü

## Modüller vs Eklentiler (Plugins)
Sistemde iki farklı genişleme yapısı mevcuttur:
1.  **Modüller (`app/Core/Modules`)**: Sistemin çekirdek yapı taşlarıdır (İçerik, Kategori, Medya). Statik olarak kodun parçasıdır ve "Core" namespace altındadır.
2.  **Eklentiler (`app/Plugins`)**: `PluginManager` tarafından yönetilen, sonradan yüklenebilen ve `PluginInterface` impelemente eden yapılardır.

## Modül Yaşam Döngüsü
- **Yükleme**: Modüller composer autoloader (Namespace `App\Core\Modules`) ve manuel `Routes.php` tanımları ile yüklenir.
- **Başlatma**: Özel bir "Boot" mekanizması yoktur, CI4 framework boot sürecine dahildirler.
- **Eksiklik**: Modüllerin aktif/pasif durumu veritabanında veya config dosyasında yönetilmemektedir. Dizin orada olduğu sürece çalışırlar.

## Eklenti (Plugin) Yaşam Döngüsü
`App\Core\Shared\Libraries\PluginManager` sınıfı bu süreci yönetir:
1.  **Discover (Keşif)**: `app/Plugins/*` dizinini tarar. Her klasör bir eklenti kabul edilir.
2.  **Activate (Aktifleştirme)**: `Plugin` sınıfını bulur, `PluginInterface` kontrolü yapar.
3.  **Boot**: Eklentinin `register()` ve `boot()` metodlarını çalıştırır.
    - `register()`: Servis bağlama işlemleri için.
    - `boot()`: İş mantığı ve hook tanımları için.

## Hook Sistemi
`HookManager`, olay tabanlı bir genişleme sağlar. CI4 Events sisteminden ayrı, basit bir "Callback" listesidir.
- `HookManager::register('hook_name', callback, priority)`
- `HookManager::execute('hook_name', data)`
- Veriyi alıp işleyip döndüren (Filter Pattern) bir yapıdadır.

## Tespit Edilen Durumlar & Öneriler
1.  **DB Entegrasyonu**: Eklentilerin "aktif" durumu şu an `PluginManager` içinde hardcoded veya runtime'da yönetiliyor gibi görünüyor (kodda veritabanı kontrolü görülemedi, sadece array içinde tutuluyor). Eklentilerin durumu (aktif/pasif) veritabanında saklanmalıdır.
2.  **Migration Desteği**: Eklentilerin kendi veritabanı tablolarını oluşturması için bir migration entegrasyonu (module-specific migrations) net değildir.
3.  **Modül Olayları**: Çekirdek modüllerde daha fazla `HookManager::execute` noktası tanımlanmalıdır ki eklentiler bu noktalara müdahale edebilsin.
