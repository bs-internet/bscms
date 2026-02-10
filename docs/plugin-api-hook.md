# Plugin API & Hook Sözleşmesi

## API Tasarımı
Eklentilerin (Plugins) sisteme entegre olabilmesi için sunulan API kısıtlıdır. Eklentiler genellikle şu yöntemlerle sisteme dahil olur:
1.  **Event Listeners**: `HookManager` üzerinden belirli noktalara callback ekleme.
2.  **Service Override**: CI4'ün servis yapısını kullanarak çekirdek sınıfları ezme (Replacement).
3.  **View Partial**: Kendi view dosyalarını render etme.

## Hook Sözleşmesi (Contracts)
Şu an sistemde hangi olayların (Events) fırlatıldığına dair merkezi bir dokümantasyon veya "Event Registry" yoktur.
İncelemelerden tespit edilen potansiyel hook noktaları:
- `content_created`
- `content_updated`
- `content_deleted`
(Bunlar `ContentController` içinde `Events::trigger` ile tetikleniyor).

## Eksiklikler
- **Filter Hooks**: WordPress tarzı veriyi değiştirmeye yönelik (Filter) hook'lar az. Çoğu hook "Action" (bir şey olduktan sonra çalış) mantığında.
- **UI Hooks**: Admin paneline menü eklemek, dashboard'a widget koymak veya içerik formuna yeni tab eklemek için standart bir "UI API" yok.

## Öneriler
1.  **Menu API**: `MenuManager::registerItem($key, $title, $url)` gibi bir API ile eklentilerin sol menüye yerleşmesi sağlanmalı.
2.  **Form API**: İçerik düzenleme formuna eklentilerin kendi alanlarını enjekte edebilmesi (`Form::extend('article', func)`) sağlanmalı.
3.  **Strict Typed Events**: Olayların parametreleri (Payload) için DTO (Data Transfer Object) veya Event sınıfları kullanılmalı, böylece eklenti geliştiricisi ne veri alacağını net bilmeli.
