# SebastianBot / BotDash

[![MIT License](https://img.shields.io/badge/license-MIT-53FC18?style=for-the-badge)](LICENSE)
[![PHP](https://img.shields.io/badge/php-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MariaDB](https://img.shields.io/badge/mariadb-compatible-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)
[![Kick API](https://img.shields.io/badge/kick-api-53FC18?style=for-the-badge&logo=kickstarter&logoColor=000000)](https://docs.kick.com/)
[![Public Webhooks](https://img.shields.io/badge/webhooks-enabled-0f172a?style=for-the-badge&logo=webhook&logoColor=white)]()
[![Vanilla JS](https://img.shields.io/badge/Vanilla-JS-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)]()
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-53FC18?style=for-the-badge)](CONTRIBUTING.md)
[![Security Policy](https://img.shields.io/badge/Security-Policy-777BB4?style=for-the-badge)](SECURITY.md)

Welcome to my worst ever repo which is a monument of rage and painful PHP structure to bleed your eyes. After creating this abomination I'm really not in to creating documentation but hey It's still MIT License. But if you still need help with this like you are that desperate contact me through Discord which my nick is Gavur.

A Kick.com moderation dashboard for streamers who want their chat handled like a professional operation, not a sticky note and a prayer.

BotDash is a PHP + MariaDB control panel for Kick chat moderation, live events, command automation, follower intelligence, shared ban management, automated bot notifications, and a few quality-of-life tools that make your channel look far more organized than it probably deserves.

## Media

### Screenshots

<table>
  <tr>
    <td><img src="docs/screenshots/doc%20(1).png" alt="Screenshot 1" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(2).png" alt="Screenshot 2" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(3).png" alt="Screenshot 3" width="100%"></td>
  </tr>
  <tr>
    <td><img src="docs/screenshots/doc%20(4).png" alt="Screenshot 4" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(5).png" alt="Screenshot 5" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(6).png" alt="Screenshot 6" width="100%"></td>
  </tr>
  <tr>
    <td><img src="docs/screenshots/doc%20(7).png" alt="Screenshot 7" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(8).png" alt="Screenshot 8" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(9).png" alt="Screenshot 9" width="100%"></td>
  </tr>
  <tr>
    <td><img src="docs/screenshots/doc%20(10).png" alt="Screenshot 10" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(11).png" alt="Screenshot 11" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(12).png" alt="Screenshot 12" width="100%"></td>
  </tr>
  <tr>
    <td><img src="docs/screenshots/doc%20(13).png" alt="Screenshot 13" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(14).png" alt="Screenshot 14" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(15).png" alt="Screenshot 15" width="100%"></td>
  </tr>
  <tr>
    <td><img src="docs/screenshots/doc%20(16).png" alt="Screenshot 16" width="100%"></td>
    <td><img src="docs/screenshots/doc%20(17).png" alt="Screenshot 17" width="100%"></td>
    <td></td>
  </tr>
</table>

### Demo of Subhaton Timer, Song Request widget & Performing Repertoire widget for OBS Videos

- [2026-04-24 11-20-21.mp4](docs/video/2026-04-24%2011-20-21.mp4)
- [2026-04-24 12-22-41.mp4](docs/video/2026-04-24%2012-22-41.mp4)
- [2026-04-24 13-37-37.mp4](docs/video/2026-04-24%2013-37-37.mp4)

## What This Does

BotDash is built to manage a Kick channel from a single dashboard:

- Live chat monitoring with real-time incoming messages
- Message delete, timeout, ban, and unban controls
- User profiles with message history and moderation history
- Live stream status, uptime, category, and viewer stats
- Command management for chat-triggered and timer-triggered bot replies
- Follower analytics and historical follower details
- Full event history with filters, search, and user drill-downs
- Shared ban network with import/export and evidence tracking
- Event-driven bot notifications for follows, subs, resubs, kicks, bans, timeouts, and more
- Loyalty, song request, repertoire, and subathon modules already wired into the ecosystem

## Features

### Live Chat Moderation

- Real-time Kick chat stream in the dashboard
- Message author badges displayed next to names
- Clickable usernames that open a detailed user profile modal
- Delete message, timeout, ban, and unban actions
- Moderation actions use custom confirmation modals instead of browser alerts, because 2009 called and asked for its UI back
- Active timeout list with live countdown timers and unban shortcuts

### User Profiles

Click a username anywhere in the dashboard and you get a detailed profile panel with:

- Total messages sent
- Deleted message count
- Ban count
- Timeout count
- Current ban / timeout status
- Timeout remaining countdown
- Follow date and whether the follow happened while live or offline
- Live category at the moment of follow
- Full moderation history
- Message history search
- Role controls for local dashboard roles like VIP, OG, and Moderator

### Live Stream Stats

The dashboard also tracks stream-level data:

- Live or offline status
- Uptime shown in real time
- Viewer count
- Stream category
- Total messages in the last 5 hours
- Unique chatters in the last 5 hours
- Total followers
- New followers in the last 24 hours
- Re-follows in the last 24 hours

### Commands System

The `commands.php` page lets you:

- Create chat commands like `!discord`
- Create timer commands that post automatically every X minutes
- Edit existing commands
- Delete commands
- Enable or disable commands without deleting them
- Set per-command cooldowns
- Use template variables such as:
  - `$(user)`
  - `$(channel)`
  - `$(count)`
  - `$(score)`

Timer commands are executed by cron, while chat commands are triggered by chat messages.

### Followers Page

The `followers.php` view provides a follower directory with:

- Follower name
- Follow date and time
- Message count
- Current status: clean, banned, or timeouted
- Detailed profile modal
- Ban / timeout / remove timeout actions
- Message history and moderation history
- Follow context such as live/offline and category when the follow happened

### Events Page

The `events.php` page shows the full event timeline, from newest to oldest:

- Follows
- Re-follows
- Subscriptions
- Renewal events
- Gifted subscriptions
- Kicks donations
- Hosts
- Ban-related events

It includes:

- Search
- Event type filters
- Date range filters
- Clickable usernames
- User profile modal reuse from chat/followers

### Shared Ban Network

The `shareban.php` page is a shared ban pool for multiple channels.

It includes:

- Shared channel registry
- Per-channel accepted ban reasons
- Per-reason evidence requirements
- Minimum evidence counts per reason
- Shared ban records with evidence messages
- Sorting and filtering
- Bulk delete
- Edit and delete actions
- Import from JSON with preview and confirmation
- Export to CSV, JSON, and TXT
- Structured export format:
  - username
  - reason
  - original channel
  - evidence
  - moderator
  - date

### Notification Messages

The `notifychat.php` page lets you define bot messages for events like:

- New follow
- Re-follow
- New subscriber
- Subscription renewal
- Kicks donation
- Timeout
- Timeout removal
- Ban
- Ban removal

Each notification can be activated or disabled, edited, and saved independently.

### Loyalty, Subathon, Song Request, and Repertoire Modules

The project also includes additional stream automation modules:

- Loyalty scoring and levels
- Subathon timer logic
- Song request queue
- Repertoire / peçete system

Because apparently one dashboard was not enough and we decided to build a tiny command center.

## Tech Stack

- PHP
- MariaDB / MySQL
- Kick Dev Public API
- Kick webhooks
- Kick chat API
- Pusher WebSocket for live chat updates in the open dashboard
- Apache / Plesk / Nginx compatible deployment

## Requirements

- PHP 8.1 or newer
- MariaDB 10.4+ or MySQL 8+
- cURL enabled in PHP
- PDO MySQL enabled
- Apache with `mod_rewrite` enabled
- A public HTTPS domain for webhook delivery
- A Kick developer app
- A valid Kick account with developer access

## Setup

### 1. Clone or upload the project

Place the project in your web root or a public subdomain.

### 2. Configure `config.php`

Set the following values in `config.php`:

- Kick Client ID
- Kick Client Secret
- Broadcaster User ID
- Redirect URI
- Database host
- Database name
- Database username
- Database password

The file also creates the required tables automatically on first run.

### 3. Configure Kick App settings

In the Kick Developer console, make sure your app has:

- Redirect URL set to your callback endpoint
- Webhooks enabled
- The correct scopes granted for the features you want to use

Typical redirect example:

```text
https://your-domain.com/auth/callback
```

Typical webhook URL example:

```text
https://your-domain.com/webhook.php
```

### 4. Authorize the app

Open `login.php` and complete the OAuth flow.

The app stores tokens in the database, so the dashboard, webhook processor, and cron jobs can all use the same credentials without relying on random JSON files scattered like confetti.

### 5. Subscribe to webhook events

Run `subscribe.php` once after authorization to register webhook subscriptions.

### 6. Configure cron

Add a one-minute cron job for `cron_timers.php`.

Example:

```bash
* * * * * php /var/www/vhosts/your-domain.com/httpdocs/cron_timers.php
```

Adjust the path to match your server layout.

## Recommended Kick Webhook Events

The project currently uses or supports the following event types:

- `chat.message.sent`
- `channel.followed`
- `channel.subscription.new`
- `channel.subscription.renewal`
- `channel.subscription.gifts`
- `livestream.status.updated`
- `livestream.metadata.updated`
- `moderation.banned`
- `kicks.gifted`
- `channel.reward.redemption.updated`

## Route Overview

Main pages:

- `index.php` - dashboard overview
- `chat.php` - live moderation console
- `commands.php` - bot command editor
- `followers.php` - follower intelligence
- `events.php` - full event history
- `shareban.php` - shared ban network
- `notifychat.php` - notification message manager
- `subscribe.php` - webhook subscription setup
- `login.php` - OAuth login
- `callback.php` - OAuth callback

Utility endpoints live in `api/`.

## Security Notes

- If you publish this project publicly, replace secrets in `config.php` with environment variables or a local-only configuration file.
- The webhook endpoint must remain publicly accessible for Kick to deliver events.
- If you use Cloudflare or other protection layers, ensure webhook routes are exempted from challenge rules.
- The project includes IP restriction support in `.htaccess` for admin pages, but webhook delivery must remain reachable.

## Notes on Timezones

The dashboard uses Istanbul time (`Europe/Istanbul`) in PHP and aligns MySQL time handling accordingly.

This matters because moderation tools become deeply unfun when a one-minute timeout tries to cosplay as a one-hour timeout.

## Data Storage

The project stores operational data in MariaDB tables such as:

- `chat_messages`
- `chat_users`
- `ban_records`
- `channel_events`
- `bot_commands`
- `bot_settings`
- `chat_notifications`
- `shared_channels`
- `shared_bans`
- plus the supporting tables for loyalty, subathon, songs, and repertoire modules

## What Makes This Different

This is not just a chat overlay or a toy bot. It is a full moderation and analytics surface for Kick, with:

- persistent history
- live event handling
- role-aware moderation
- shared safety tooling
- command automation
- notification templating
- and enough panels to make your browser feel employed

## Contributing

Pull requests are welcome.

If you improve a module, please try to keep the same tone, structure, and usability. The dashboard already has enough personality for an entire small office.

## License

This project is licensed under the MIT License.

The MIT License text is included in the [LICENSE](LICENSE) file.

Attribution is requested when you use, modify, or commercially ship this project.

If you publish a fork or commercial derivative, keeping a visible reference to this original project is required. So I'll know that you suffer too but not legally.

## Disclaimer

This project depends on Kick's current public APIs, webhooks, and permissions. Some endpoints or event payloads may change as Kick evolves its platform.

If something breaks after a platform change, it is usually not the dashboard being dramatic. It is usually an API change being rude.

---

## Türkçe Sürüm

Yukarıda İngilizce girişi okumadığını biliyorum dostum, en iğrenç, en boktan hiyerarşi ve mimariye sahip repoma hoşgeldin. Kick.com içerisinde düzgün moderasyon botu bulamadın değil mi? Ben de öyle. En ufak şeyler için bile paralı üyelik istiyorlardı. Ve ben de 3 dolar vermek yerine 300 dolarlık mesai yaptım bu iğrenç şey için. Klasör yapısını fark ettin mi? Knight Online panelleri gibi hepsi aynı yerde, bu benim değil Kick'in Webhooklara cevabının bir gereği oldu, acı bir tecrübe, veri çeken her şey Api klasörüne kalan her şey root dizinde olmalı. 

Önce C ile başlayıp, sonra Go'ya döndüğüm, en sonunda PHP'nin daha mantıklı olacağını düşündüğüm bu "şeyin" detayları aşağıda. Yardım gerekirse önce dua etmeyi dene, baktın olmuyor bir arkadaşına danış, hala olmadıysa bana ulaş Discord üzerinden, nickim Gavur. Ulaştığında yardımı garanti etmiyorum, ama mutlu olacağıma emin olabilirsin. Sadece bu çileyi ben çekmiş olmayacağım. Şimdiden kolay gelsin.



BotDash; PHP + MariaDB ile çalışan, Kick sohbet moderasyonu, canlı etkinlik takibi, komut otomasyonu, takipçi zekası, paylaşılan ban ağı, otomatik bot bildirimleri ve yayıncı hayatını toparlayan pek çok yardımcı aracı tek yerde birleştiren bir kontrol merkezidir.

### Ortam

#### Ekran Görüntüleri

Yukarıdaki ekran görüntüleri bölümü doğrudan kullanılabilir.

#### Altathon Zamanlayıcısı, Şarkı İsteği ve Performans Repertuarı Araçları İçin Tanıtım Videoları

Yukarıdaki video bağlantıları doğrudan kullanılabilir.

## Bu Proje Ne Yapar?

BotDash, tek bir panelden Kick kanalını uçtan uca yönetmek için tasarlandı:

- Canlı sohbet akışını anlık izleme
- Mesaj silme, uzaklaştırma, banlama ve ban kaldırma denetimleri
- Mesaj ve moderasyon geçmişiyle kullanıcı profilleri
- Canlı yayın durumu, yayın süresi, kategori ve izleyici istatistikleri
- Sohbet komutları ve zamanlayıcı komutları için otomasyon yönetimi
- Takipçi analizi ve geçmiş takipçi detayları
- Filtreleme, arama ve kullanıcı detayıyla tam etkinlik geçmişi
- İçe/dışa aktarma ve delil takibi olan paylaşılan ban ağı
- Takip, abonelik, yeniden abonelik, kick bağışı, ban, uzaklaştırma ve benzeri olaylar için tetiklenen bot bildirimleri
- Sadakat, şarkı isteği, repertuar ve altathon modülleriyle hazır genişletilebilir altyapı

## Özellikler

### Canlı Sohbet Moderasyonu

- Gösterge panelinde anlık Kick sohbet akışı
- Mesaj atanların rozetlerini isimlerinin yanında gösterme
- Kullanıcı adına tıklayınca ayrıntılı profil penceresi açılması
- Mesaj silme, uzaklaştırma, banlama ve ban kaldırma işlemleri
- Tarayıcı uyarısı yerine özel onay pencereleriyle temiz moderasyon akışı
- Kalan süreyi anlık geri sayan "Aktif Uzaklaştırmalar" bölümü ve tek tuşla ceza kaldırma

### Kullanıcı Profilleri

Panelin herhangi bir yerinde kullanıcı adına tıklayınca gelen detay ekranında:

- Toplam gönderilen mesaj sayısı
- Silinen mesaj sayısı
- Toplam ban sayısı
- Toplam uzaklaştırma sayısı
- Güncel ban/uzaklaştırma durumu
- Uzaklaştırma bitişine kalan canlı sayaç
- Takip tarihi ve takip anında yayın açık mı kapalı mı bilgisi
- Takip anındaki yayın kategorisi
- Tüm moderasyon geçmişi
- Mesaj geçmişi içinde arama
- Panel içi yerel roller (VIP, OG, Moderatör) için kontrol araçları

### Canlı Yayın İstatistikleri

Panel ayrıca yayın seviyesinde şu verileri izler:

- Yayında/çevrimdışı durumu
- Gerçek zamanlı yayın süresi
- İzleyici sayısı
- Yayın kategorisi
- Son 5 saatte toplam mesaj sayısı
- Son 5 saatte konuşan farklı kullanıcı sayısı
- Toplam takipçi sayısı
- Son 24 saatte yeni takipler
- Son 24 saatte yeniden takipler

### Komut Sistemi

`commands.php` sayfasında:

- `!discord` gibi sohbet komutları oluşturma
- Her X dakikada otomatik yazan zamanlayıcı komutlar oluşturma
- Var olan komutları düzenleme
- Komut silme
- Silmeden aktif/pasif yapma
- Komut bazlı bekleme süresi (cooldown) ayarlama
- Şablon değişkenleri kullanma:
  - `$(user)`
  - `$(channel)`
  - `$(count)`
  - `$(score)`

Zamanlayıcı komutları cron ile çalışır, sohbet komutları ise kullanıcı mesajıyla tetiklenir.

### Takipçiler Sayfası

`followers.php` görünümünde:

- Takipçi adı
- Takip tarihi ve saati
- Toplam mesaj sayısı
- Anlık durum: temiz, banlı veya uzaklaştırılmış
- Ayrıntılı kullanıcı penceresi
- Banla / uzaklaştır / uzaklaştırmayı kaldır işlemleri
- Mesaj geçmişi ve moderasyon geçmişi
- Takip anının bağlamı (yayın açık/kapalı ve kategori)

### Etkinlikler Sayfası

`events.php` sayfası tüm etkinlikleri yeniden eskiye listeler:

- Takipler
- Yeniden takipler
- Abonelikler
- Yenilemeler
- Hediye abonelikler
- Kick bağışları
- Host olayları
- Ban odaklı olaylar

Ayrıca şunları sağlar:

- Arama
- Etkinlik türüne göre filtreleme
- Tarih aralığı filtreleme
- Tıklanabilir kullanıcı adları
- Sohbet/Takipçiler ile ortak çalışan kullanıcı detay penceresi

### Paylaşılan Ban Ağı

`shareban.php` sayfası, birden fazla kanal için ortak güvenlik havuzu sunar.

İçerdiği özellikler:

- Ortak kanal kayıt alanı
- Kanal bazlı kabul edilen ban sebepleri
- Sebep bazlı delil zorunluluğu
- Sebep bazlı asgari delil sayısı
- Delil mesajlarıyla saklanan ortak ban kayıtları
- Sıralama ve filtreleme
- Toplu silme
- Düzenleme ve tekil silme
- Önizleme ve onaylı JSON içe aktarma
- CSV, JSON ve TXT dışa aktarma
- Yapılandırılmış dışa aktarma biçimi:
  - kullanıcı adı
  - sebep
  - ana kanal
  - delil
  - moderatör
  - tarih

### Bildirim Mesajları

`notifychat.php` sayfasında şu olaylar için bot mesajlarını ayrı ayrı tanımlayabilirsin:

- Yeni takip
- Yeniden takip
- Yeni abone
- Abonelik yenileme
- Kick bağışı
- Uzaklaştırma
- Uzaklaştırma kaldırma
- Ban
- Ban kaldırma

Her bildirim bağımsız şekilde açılıp kapatılabilir, düzenlenebilir ve kaydedilebilir.

### Sadakat, Altathon, Şarkı İsteği ve Repertuar Modülleri

Projede ek olarak yayın otomasyonu için şu modüller de bulunur:

- Sadakat puanı ve seviye sistemi
- Altathon zamanlayıcı mantığı
- Şarkı istek kuyruğu
- Repertuar / peçete altyapısı

Kısacası, sıradan bir panel yerine mini bir yayın komuta merkezi kuruldu.

## Teknoloji Yığını

- PHP
- MariaDB / MySQL
- Kick Geliştirici Açık Uygulama Arayüzü
- Kick webhook altyapısı
- Kick sohbet uygulama arayüzü
- Açık panelde canlı sohbet güncellemesi için Pusher WebSocket
- Apache / Plesk / Nginx uyumlu dağıtım

## Gereksinimler

- PHP 8.1 veya üstü
- MariaDB 10.4+ ya da MySQL 8+
- PHP içinde cURL etkin
- PDO MySQL etkin
- `mod_rewrite` açık Apache
- Webhook teslimatı için herkese açık HTTPS alan adı
- Kick geliştirici uygulaması
- Geliştirici erişimi olan geçerli Kick hesabı

## Kurulum

### 1. Projeyi kopyala veya sunucuya yükle

Projeyi web kök dizinine ya da herkese açık bir alt alana yerleştir.

### 2. `config.php` dosyasını düzenle

`config.php` içinde şu değerleri ayarla:

- Kick istemci kimliği
- Kick istemci gizli anahtarı
- Yayıncı kullanıcı kimliği
- Geri dönüş adresi
- Veritabanı sunucusu
- Veritabanı adı
- Veritabanı kullanıcı adı
- Veritabanı parolası

Dosya, ilk çalıştırmada gerekli tabloları otomatik oluşturur.

### 3. Kick uygulama ayarlarını yapılandır

Kick Geliştirici panelinde uygulaman için şunları doğrula:

- Geri dönüş adresi doğru ayarlı
- Webhook etkin
- Kullanacağın özelliklere uygun izin kapsamları açık

Örnek geri dönüş adresi:

```text
https://alan-adin.com/auth/callback
```

Örnek webhook adresi:

```text
https://alan-adin.com/webhook.php
```

### 4. Uygulamayı yetkilendir

`login.php` dosyasını açıp OAuth akışını tamamla.

Uygulama belirteçleri veritabanında saklanır; böylece panel, webhook işleyicisi ve cron işleri aynı kimlik bilgilerini ortak kullanır.

### 5. Webhook olaylarına abone ol

Yetkilendirmeden sonra `subscribe.php` dosyasını bir kez çalıştır.

### 6. Cron ayarla

`cron_timers.php` için her dakika çalışan bir cron görevi ekle.

Örnek:

```bash
* * * * * php /var/www/vhosts/alan-adin.com/httpdocs/cron_timers.php
```

Sunucu dizin yapına göre yolu güncelle.

## Önerilen Kick Webhook Olayları

Projede kullanılan veya desteklenen olay türleri:

- `chat.message.sent`
- `channel.followed`
- `channel.subscription.new`
- `channel.subscription.renewal`
- `channel.subscription.gifts`
- `livestream.status.updated`
- `livestream.metadata.updated`
- `moderation.banned`
- `kicks.gifted`
- `channel.reward.redemption.updated`

## Sayfa Haritası

Ana sayfalar:

- `index.php` - gösterge özeti
- `chat.php` - canlı moderasyon konsolu
- `commands.php` - bot komut düzenleyicisi
- `followers.php` - takipçi zekası
- `events.php` - tam etkinlik geçmişi
- `shareban.php` - paylaşılan ban ağı
- `notifychat.php` - bildirim mesajları yönetimi
- `subscribe.php` - webhook abonelik kurulumu
- `login.php` - OAuth giriş
- `callback.php` - OAuth geri dönüş

Yardımcı uç noktalar `api/` klasöründe yer alır.

## Güvenlik Notları

- Projeyi herkese açık paylaşacaksan `config.php` içindeki gizli bilgileri ortam değişkenine veya yerel yapılandırma dosyasına taşı.
- Kick'in veri iletebilmesi için webhook uç noktası herkese açık kalmalı.
- Cloudflare gibi koruma katmanı kullanıyorsan webhook yollarını güvenlik doğrulama engellerinden muaf tut.
- `.htaccess` içinde yönetici sayfaları için IP kısıtlama desteği var; ancak webhook teslimatı için ilgili yol erişilebilir kalmalı.

## Zaman Dilimi Notu

Panel, PHP tarafında `Europe/Istanbul` zaman dilimini kullanır ve MySQL zaman işlemlerini buna uyumlu hale getirir.

Bu önemli; çünkü bir dakikalık uzaklaştırmanın bir saatlik cezaya dönüşmesi kimsenin görmek isteyeceği bir sürpriz değildir.

## Veri Saklama

Operasyon verileri MariaDB tablolarında saklanır, örnekler:

- `chat_messages`
- `chat_users`
- `ban_records`
- `channel_events`
- `bot_commands`
- `bot_settings`
- `chat_notifications`
- `shared_channels`
- `shared_bans`
- ayrıca sadakat, altathon, şarkı ve repertuar modülleri için destek tabloları

## Bu Projeyi Farklı Kılan Nedir?

Bu yalnızca bir sohbet kaplaması veya oyuncak bot değil. Kick için tam kapsamlı bir moderasyon ve analiz yüzeyi sunar:

- kalıcı geçmiş
- canlı olay işleme
- rol farkındalığı olan moderasyon
- ortak güvenlik araçları
- komut otomasyonu
- bildirim şablonlama
- ve tarayıcına "vardiyalı çalışıyor" hissi verecek kadar güçlü panel yapısı

## Katkı

Katkı isteklerine açığım.

Bir modülü iyileştirirken mevcut üslubu, düzeni ve kullanım kolaylığını korumaya çalış. Panelin zaten tek başına küçük bir ofis kadar karakteri var.

## Lisans

Bu proje MIT Lisansı ile lisanslanmıştır.

MIT lisans metni [LICENSE](LICENSE) dosyasında yer alır.

Projeyi kullanırken, değiştirirken veya ticari üründe kullanırken kaynak belirtmeniz rica edilir.

Çatallayıp yayınlarsan veya ticari türev çıkarırsan görünür bir kaynak referansı bırakman beklenir. Böylece acıyı tek başıma yaşamam.

## Sorumluluk Reddi

Bu proje Kick platformunun güncel açık uygulama arayüzlerine, webhook yapılarına ve izin modeline bağlıdır. Kick tarafında uç noktalar veya olay yükleri değiştikçe bazı özellikler etkilenebilir.

Platform güncellemesinden sonra bir şey bozulursa, çoğu zaman sorun panelin huysuzluğu değil; uygulama arayüzünün şartları yeniden yazmasıdır.
