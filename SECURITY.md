# Security Policy

## Supported Versions

Only the latest version from the main branch is actively supported for security updates.

| Version | Supported          |
| ------- | ------------------ |
| Latest  | :white_check_mark: |
| Older   | :x:                |

## Reporting a Vulnerability

If you discover a security vulnerability within BotDash, please do not disclose it publicly via GitHub Issues. 
Instead, please report it directly to the developer:

- **Discord:** Gavur

Please provide a detailed description of the issue and the steps to reproduce it. I will try to address it as quickly as possible.

## Deployment Security Notes

Since BotDash is a self-hosted PHP application, the ultimate security of the dashboard depends on your server configuration. Please keep the following security practices in mind:

1. **Protect your `config.php`:** This file contains your MariaDB credentials, Kick API secrets, and OAuth tokens. Ensure your web server (Apache/Nginx/Plesk) is properly configured to parse `.php` files and never serve them as raw text.
2. **Webhook Public Access:** The `webhook.php` file **must remain accessible** to the public internet. Kick's AWS servers need to reach this file to deliver chat messages and events. Do NOT block this file behind Cloudflare Bot Fight Mode, CAPTCHA, or basic authentication.
3. **Dashboard Access Restriction:** Use the provided `.htaccess` rules (or equivalent Nginx configurations) to restrict access to all other dashboard files (`index.php`, `chat.php`, `api/*`, etc.) strictly to your own IP address.
4. **SSL/TLS:** Always run the dashboard over HTTPS. Kick webhooks require a valid SSL/TLS certificate (e.g., Let's Encrypt) to handshake with your server.

---

## Türkçe (Turkish)

# Güvenlik Politikası

## Desteklenen Sürümler

Yalnızca ana (main) daldaki en güncel sürüm güvenlik güncellemeleri için aktif olarak desteklenmektedir.

| Sürüm | Destekleniyor      |
| ----- | ------------------ |
| En güncel | :white_check_mark: |
| Eski  | :x:                |

## Güvenlik Açığı Bildirme

BotDash içerisinde bir güvenlik açığı bulursanız, lütfen bunu GitHub Issues üzerinden herkese açık bir şekilde paylaşmayın. 
Bunun yerine, doğrudan geliştiriciye bildirin:

- **Discord:** Gavur

Lütfen sorunun detaylı bir açıklamasını ve nasıl yeniden üretilebileceğini ekleyin. En kısa sürede çözmek için dönüş yapacağım.

## Kurulum Güvenliği Notları

BotDash kendi sunucunuzda (self-hosted) barındırdığınız bir PHP uygulaması olduğundan, güvenliğin büyük bir kısmı sunucu yapılandırmanıza bağlıdır. Lütfen şu güvenlik önlemlerini unutmayın:

1. **`config.php` Dosyasını Koruyun:** Bu dosya MariaDB şifrelerinizi, Kick API anahtarlarınızı ve yetkilendirme token'larınızı içerir. Sunucunuzun (Apache/Nginx/Plesk) PHP dosyalarını derlediğinden ve kaynak kod olarak dışarı yansıtmadığından emin olun.
2. **Webhook Açık Erişimi:** `webhook.php` dosyası dış internete **açık kalmalıdır**. Kick sunucuları sohbet mesajlarını ve etkinlikleri iletmek için bu dosyaya ulaşabilmelidir. Bu dosyayı Cloudflare Bot Koruması, CAPTCHA veya şifreli giriş arkasına KOYMAYIN.
3. **Panel Erişimi Kısıtlaması:** Sağlanan `.htaccess` kurallarını kullanarak diğer tüm panel dosyalarını (`index.php`, `chat.php`, `api/*` vb.) yalnızca **kendi IP adresinize** kısıtlayın. Panelinize sizden başka kimse erişememelidir.
4. **SSL/TLS (HTTPS):** Paneli her zaman HTTPS üzerinden çalıştırın. Kick webhook'larının sunucunuzla el sıkışabilmesi için geçerli bir SSL sertifikası (örn. Let's Encrypt) zorunludur.
