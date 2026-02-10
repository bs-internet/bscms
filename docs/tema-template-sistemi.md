# Tema / Template Sistemi

## Mevcut Yapı
Tema sistemi `App\Core\Shared\Libraries\Template` sınıfı tarafından yönetilir. Dosya tabanlı bir yapıdadır ve varsayılan olarak `APPPATH . '../themes/default/'` yolunu kullanır.

## Şablon Hiyerarşisi (Resolution Hierarchy)
Sistem, WordPress benzeri bir "Template Hierarchy" kullanmaya çalışır ancak daha basittir. `findTemplate($type)` metodu şu sırayı izler:

1.  **Sayfa (Page)**: `page-{slug}.php` -> `page.php` -> `index.php`
2.  **Tekil İçerik (Single)**: `single-{contentTypeSlug}.php` -> `single.php` -> `index.php`
3.  **Liste (List)**: `list-{contentTypeSlug}.php` -> `index.php`
4.  **404**: `404.php` -> `index.php`

## Veri Aktarımı
Controller içinden `$template->render('templateType', $data)` çağrıldığında:
- Global `templateData` ile yerel `$data` birleştirilir.
- `extract($data)` ile değişkenler view dosyasına açılır.
- `currentContent` ve `currentContentType` gibi bağlam nesneleri Template sınıfında tutulur.

## Layout Yönetimi
- **Include Yöntemi**: Modern "Layout extends" (Twig, Blade veya CI4 View Layouts) yerine klasik PHP `include` yöntemi kullanılır.
- **Header/Footer**: `loadHeader()` ve `loadFooter()` metodları `header.php` ve `footer.php` dosyalarını yükler.

## Eksiklikler ve Öneriler
1.  **View Engine**: Saf PHP kullanılıyor. Twig veya Blade gibi bir motor, tema geliştiriciler için daha güvenli ve pratik olabilir.
2.  **Asset Yönetimi**: Temanın CSS/JS dosyalarını yönetmek, versiyonlamak veya sıkıştırmak için bir mekanizma `Template` sınıfında mevcut değil.
3.  **Layout Esnekliği**: `header.php` ve `footer.php` zorunlu gibi. Farklı layout'lar (örn: landing page vs. article page) tanımlamak için `loadHeader('landing')` gibi bir parametre eklenebilir.
4.  **Widget/Block Alanları**: Temalarda dinamik alanlar (sidebar, footer widgets) tanımlamak için bir "Region" veya "Widget" sistemi eksik.
