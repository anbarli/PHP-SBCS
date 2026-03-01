# PHP-SBCS / PHP Session Based Cart System (Basit PHP Session Sepet Sistemi)

Bu proje, PHP session kullanarak basit bir alisveris sepeti deneyimi sunar. Egitim ve demo odaklidir; hizli denemeler icin uygundur.

## Ozellikler

- Session tabanli sepet yonetimi
- CSRF korumasi
- Urun ekleme, silme, sepet temizleme
- Adres ve odeme formu
- Siparisleri JSON olarak kaydetme (`orders/YYYY/MM/DD/ORDERNO.json`)
- 8 karakter siparis numarasi uretimi
- Bootstrap 5 ile responsive arayuz

## Gereksinimler

- PHP 7.4+
- Apache (onerilir) veya bir PHP sunucusu
- Tarayici

## Kurulum

1. Projeyi klonlayin:
```bash
git clone https://github.com/anbarli/PHP-SBCS.git
```
2. Proje klasorune girin:
```bash
cd PHP-SBCS
```
3. Lokal sunucuyu baslatin:
```bash
php -S localhost:8000
```
4. Tarayicida acin:
```text
http://localhost:8000
```

## Kullanim

- Urun kartindan miktar secip `Add to Cart` ile sepete ekleyin.
- Sepette `Remove` ile satir silin, `Clear Cart` ile sepeti temizleyin.
- Siparis formunu doldurup siparisi gonderin.
- Demo modunda siparis dosyaya yazilmaz, sadece simule edilir.

## Proje Yapisi

```text
/
  index.php           - Uygulama mantigi ve arayuz
  .htaccess           - Temel erisim kurallari
  /orders/            - Siparis dosyalari (yil/ay/gun/orderno.json)
  README.md           - Dokumantasyon
  LICENSE             - Lisans
```

## Guvenlik ve Production Uyarisi

Bu repo egitim/deneme amaclidir. Gercek production kullanim icin tek basina yeterli degildir.

Dikkat edilmesi gereken baslica riskler:
- Siparis verileri JSON dosyalarinda tutulur.
- `.htaccess` kurallari Apache odaklidir; Nginx/Caddy icin ek kurallar gerekir.
- Kisisel veriler (name, email, phone, address) plain text saklanir.
- Auth, yetkilendirme, rate limiting, audit log, merkezi izleme gibi production kontrolleri sinirlidir.

Bilinen notlar (1-2-3):
- `orders/` klasoru web root altindadir; koruma agirlikli olarak sunucu kuralina (`.htaccess`) baglidir.
- Telefon formati ve odeme secenegi icin asil dogrulama backend tarafinda da zorunlu olmalidir (frontend tek basina yeterli degildir).
- `error_reporting(E_ALL)` production ortaminda bilgi sizintisi riski dogurabilir.

Production onerisi:
- Siparis verilerini web root disinda veya yonetilen bir veritabaninda tutun.
- Erisim kontrolu, sifreleme, guvenlik basliklari ve izleme katmanlari ekleyin.

## Lisans

MIT License. Detaylar icin `LICENSE` dosyasina bakin.

---

# PHP-SBCS / PHP Session Based Cart System

This project provides a simple shopping cart flow using PHP sessions. It is designed for learning and demo usage.

## Features

- Session-based cart management
- CSRF protection
- Add/remove/clear cart actions
- Address and payment form
- JSON order persistence (`orders/YYYY/MM/DD/ORDERNO.json`)
- 8-character order number generation
- Responsive UI with Bootstrap 5

## Requirements

- PHP 7.4+
- Apache (recommended) or any PHP server
- A web browser

## Installation

1. Clone the repository:
```bash
git clone https://github.com/anbarli/PHP-SBCS.git
```
2. Enter the project folder:
```bash
cd PHP-SBCS
```
3. Start local server:
```bash
php -S localhost:8000
```
4. Open in browser:
```text
http://localhost:8000
```

## Usage

- Select quantity on a product card and click `Add to Cart`.
- In cart, use `Remove` for a line item and `Clear Cart` to reset all.
- Fill the order form and submit.
- In demo mode, orders are simulated and not persisted.

## Project Structure

```text
/
  index.php           - Application logic and UI
  .htaccess           - Basic access rules
  /orders/            - Order files (year/month/day/orderno.json)
  README.md           - Documentation
  LICENSE             - License
```

## Security and Production Warning

This repository is for learning/demo purposes and is not production-ready by itself.

Key risks for production usage:
- Orders are stored in JSON files.
- `.htaccess` protections are Apache-specific; Nginx/Caddy require separate rules.
- Personal data (name, email, phone, address) is stored in plain text.
- Production controls such as auth, authorization, rate limiting, audit logs, and centralized monitoring are limited.

Known notes (1-2-3):
- The `orders/` directory is under web root; protection mainly depends on server rules (`.htaccess`).
- Phone format and payment method must also be enforced by backend validation (frontend checks alone are not enough).
- `error_reporting(E_ALL)` can expose sensitive details in production.

Production recommendation:
- Store order data outside web root or in a managed database.
- Add proper access control, encryption, security headers, and monitoring.

## License

MIT License. See `LICENSE` for details.

---

Demo: <a href="https://www.anbarli.com.tr/PHP-SBCS/">https://www.anbarli.com.tr/PHP-SBCS/</a>
