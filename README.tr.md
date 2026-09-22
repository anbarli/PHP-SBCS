# PHP-SBCS / PHP Session Based Cart System

PHP session kullanan, veritabani gerektirmeyen, Bootstrap 5 arayuzlu ve CSRF korumali kucuk bir sepet demo projesi.

[Canli Demo](https://www.anbarli.com.tr/PHP-SBCS/) · [Hata Bildir](https://github.com/anbarli/PHP-SBCS/issues/new?template=bug_report.md) · [Ozellik Iste](https://github.com/anbarli/PHP-SBCS/issues/new?template=feature_request.md) · [English README](README.md)

## Bu Proje Ne Icin?

PHP-SBCS, PHP ile session tabanli sepet akisini ogrenmek isteyen gelistiriciler icin hazirlanmistir. Amac, framework veya veritabani zorunlulugu olmadan temel sepet, checkout ve form guvenligi akisini gostermektir.

Bu projeyi su durumlarda kullanabilirsiniz:

- PHP session shopping cart ornegi incelemek
- Veritabanisiz sepet ve checkout demo akisi calistirmak
- Bootstrap 5 ile basit bir storefront baslangici yapmak
- JSON siparis kaydi mantigini gormek
- PHP form isleme ve session yonetimi pratigi yapmak

> Bu repo egitim ve demo amaclidir. Dogrudan production ecommerce sistemi olarak kullanilmasi onerilmez.

## Hizli Baslangic

```bash
git clone https://github.com/anbarli/PHP-SBCS.git
cd PHP-SBCS
php -S localhost:8000
```

Tarayicida acin:

```text
http://localhost:8000
```

## Ozellikler

- Session tabanli sepet yonetimi
- Sepete urun ekleme, urun silme ve sepet temizleme
- POST islemleri icin CSRF korumasi
- Guvenilir fiyat icin server-side urun katalogu
- Ad, email, telefon, adres ve odeme yontemi alanlari olan checkout formu
- Opsiyonel JSON siparis kaydi: `orders/YYYY/MM/DD/ORDERNO.json`
- 8 karakterli siparis numarasi uretimi
- Bootstrap 5 ile responsive arayuz
- Apache icin `orders/` dizinine dogrudan erisimi engelleyen `.htaccess` kurali

## Gereksinimler

- PHP 7.4 veya daha yeni
- Apache, PHP built-in server veya PHP calistirabilen baska bir web sunucusu
- Tarayici

Veritabani, Composer, build adimi veya framework gerekmez.

## Kullanim

1. Urun kartindan miktar secin.
2. `Add to Cart` butonuna basin.
3. Sepet toplamlarini kontrol edin.
4. Gerekirse urun silin veya sepeti temizleyin.
5. Checkout formunu doldurup siparisi gonderin.

Public demoda demo modu aktiftir. Bu nedenle gonderilen siparisler gercek dosyaya yazilmaz, sadece simule edilir.

## Yapilandirma

Proje kucuk kalacak sekilde tasarlanmistir. Ana ayarlar su an `index.php` icinde tanimlidir:

```php
$demo_mode = true;
$currency = "$";
```

Yerel kayit testi icin demo modunu `false` yapabilirsiniz. Bu durumda siparisler tarih bazli `orders/` dizinine JSON olarak kaydedilir.

## Proje Yapisi

```text
/
  index.php      Uygulama mantigi ve arayuz
  .htaccess      Temel Apache erisim kurallari
  LICENSE        MIT lisansi
  README.md      Ingilizce dokumantasyon
  README.tr.md   Turkce dokumantasyon
```

Siparis kaydi acildiginda uygulama su runtime dizinini olusturur:

```text
orders/YYYY/MM/DD/ORDERNO.json
```

## Guvenlik Notlari

PHP-SBCS bir egitim projesi icin bazi temel onlemler icerir:

- Session cookie ayarlari `HttpOnly`, `SameSite=Lax` ve HTTPS uzerinde secure olacak sekildedir.
- Sepet degisiklikleri POST ve CSRF token kontrolu ile yapilir.
- Urun fiyatlari server-side katalogdan okunur.
- `index.php` temel guvenlik headerlarini gonderir.
- Apache icin `orders/` erisimi `.htaccess` ile engellenir.

Production icin ek gereksinimler:

- Siparisleri web root disinda veya yonetilen bir veritabaninda saklayin.
- Siparis erisimi icin authentication ve authorization ekleyin.
- Kisisel verileri sifreleyin veya uygun sekilde koruyun.
- Rate limiting, audit log ve monitoring ekleyin.
- Nginx, Caddy veya diger sunucular icin esdeger erisim kurallari tanimlayin.
- Production ortaminda verbose error reporting kapatin.

Guvenlik bildirimi icin [SECURITY.md](SECURITY.md) dosyasina bakin.

## Yol Haritasi

- README icin screenshot veya kisa demo GIF ekleme
- Demo modu ve para birimi icin kucuk bir `config.php` dosyasi ekleme
- Cart ve order helper fonksiyonlarini ayri dosyalara tasima
- Sepet islemleri ve siparis numarasi uretimi icin PHPUnit testleri ekleme
- Opsiyonel Docker destegi ekleme
- Apache ve Nginx deployment notlari ekleme

## Katki

Katkilar memnuniyetle kabul edilir. Dokumantasyon duzeltmeleri, UI ekran goruntuleri, kucuk refactorlar, testler ve ornekler iyi baslangic konularidir.

Pull request acmadan once [CONTRIBUTING.md](CONTRIBUTING.md) dosyasini okuyun.

## Lisans

MIT License. Detaylar icin [LICENSE](LICENSE) dosyasina bakin.
