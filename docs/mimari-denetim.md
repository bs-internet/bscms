# Mimari Denetim

## Genel Bakış
BSCMS, **CodeIgniter 4** üzerine inşa edilmiş **Modüler Monolit** bir yapıya sahiptir. Sistem temel olarak çekirdek modüller, paylaşılan kütüphaneler ve yapılandırma dosyalarından oluşur. Servis mimarisi (Service Locator pattern) yoğun olarak kullanılmaktadır.

## Dizin Yapısı ve Organizasyon
- **`app/Core/Modules`**: İş mantığının (Business Logic) tutulduğu ana yer. Her modül (Auth, Content, System vb.) kendi Controller, Model, Repository ve View katmanlarına sahiptir. Bu yapı "Domain Driven Design" (DDD) ilkelerine yakın bir "Package by Feature" yaklaşımıdır.
- **`app/Core/Shared`**: Tüm modüllerin ortak kullandığı temel sınıfları (BaseController, Interfaces), kütüphaneleri (Template, PluginManager) ve View parçalarını (layout, partials) barındırır.
- **`app/Config`**: CI4 standart yapılandırmaları ile birlikte servislerin (`Services.php`) ve rotaların (`Routes.php`) manuel olarak tanımlandığı yerdir.

## Servis Katmanı ve Bağımlılık Yönetimi
- **Services.php**: Tüm repository ve kütüphaneler CodeIgniter'ın Service servisi üzerinden singleton veya factory olarak tanımlanmıştır.
- **Manuel Bağlama**: Otomatik "wiring" yerine, `Services.php` içinde açıkça `new Repository(new Model())` şeklinde bağımlılık enjeksiyonu tanımlanmıştır. Bu kontrolü artırır ancak modül sayısı arttıkça `Services.php` dosyasının bakımını zorlaştırabilir.

## Yönlendirme (Routing)
- **Routes.php**: Rotalar modül bazlı gruplandırılmıştır (`admin`, `System`, `Content` vb.).
- Modüllerin kendi `Routes.php` dosyalarını barındırıp otomatik yüklemesi yerine, merkezi `app/Config/Routes.php` dosyasında tüm tanımlamalar yapılmıştır. Bu durum, modülleri "tamamen" bağımsız (plug-and-play) yapmaktan uzaklaştırır.

## Önerilen İyileştirmeler
1.  **Modül Otopilotu**: Modüllerin kendi rota ve servis tanımlarını yapabileceği, `Services.php` ve `Routes.php` dosyalarını şişirmeyen bir "ModuleAutoloader" yapısı kurgulanabilir.
2.  **Interface Segregation**: Repository'ler interface üzerinden çağrılıyor, bu güzel bir uygulama. Ancak `Services` sınıfı çok büyümüş durumda.
3.  **Config Yönetimi**: Modüle özgü ayarların `app/Config` yerine modül içinde saklanması taşınabilirliği artırır.
