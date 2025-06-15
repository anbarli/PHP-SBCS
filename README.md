# PHP-SBCS (Session Based Cart System) – v2 🇹🇷🇬🇧

## 📦 Açıklama / Description

PHP-SBCS, PHP oturumları (sessions) kullanarak çalışan hafif bir alışveriş sepeti sistemidir. Bu modernleştirilmiş versiyon, Bootstrap 5, güvenli CSRF koruması, adres/ödeme formu ve JSON dosyasına sipariş kaydı gibi özellikler sunar.

PHP-SBCS is a lightweight shopping cart system using PHP sessions. This modern version features Bootstrap 5, secure CSRF protection, an address/payment form, and JSON-based order logging.

---

## 🚀 Özellikler / Features

- ✅ Tek dosyalı kullanım (no DB required) / Single-file usage (no DB)
- ✅ Bootstrap 5 mobil uyumlu tasarım / Responsive Bootstrap 5 design
- ✅ CSRF koruması / CSRF protection
- ✅ Sepet işlemleri (ekle, çıkar, temizle) / Cart operations (add, remove, clear)
- ✅ Adres ve ödeme formu / Address & payment form
- ✅ JSON sipariş kaydı (orders.json) / Order logging into `orders.json`
- ✅ Türkçe & İngilizce açıklamalı kod / Dual-language code comments

---

## 📂 Kurulum / Installation

1. Projeyi klonlayın / Clone the project:
```bash
git clone https://github.com/anbarli/PHP-SBCS.git
```

2. Bir web sunucusunda çalıştırın / Run on a PHP server (PHP 7.4+ önerilir / recommended):
```bash
php -S localhost:8000
```

3. Tarayıcıdan erişin / Open in browser:
```
http://localhost:8000
```

---

## 🧾 Sipariş Kaydı / Order Logging

Tüm siparişler `orders.json` dosyasına JSON formatında kaydedilir. Her kayıt şu alanları içerir:

- name
- email
- phone
- address
- payment
- cart (ürün listesi / product list)
- date

---

## 🔐 Güvenlik / Security

- CSRF token ile form koruması
- htmlspecialchars() ile XSS önlemi
- Sunucu taraflı form doğrulama
- Post-Redirect-Get modeli uygulanmıştır

---

## 📃 Lisans / License

MIT Lisansı (MIT License)

---

Bu proje eğitimsel ve hafif e-ticaret sistemleri için uygundur. /  
This project is suitable for educational and lightweight e-commerce cases.

---

Demo : <a href="http://www.anbarli.org/PHP-SBCS/">http://www.anbarli.org/PHP-SBCS/</a>