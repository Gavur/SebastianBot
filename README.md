# SebastianBot / BotDash

[![MIT License](https://img.shields.io/badge/license-MIT-53FC18?style=for-the-badge)](LICENSE)
[![PHP](https://img.shields.io/badge/php-8.1%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MariaDB](https://img.shields.io/badge/mariadb-compatible-003545?style=for-the-badge&logo=mariadb&logoColor=white)](https://mariadb.org/)
[![Kick API](https://img.shields.io/badge/kick-api-53FC18?style=for-the-badge&logo=kickstarter&logoColor=000000)](https://docs.kick.com/)
[![Public Webhooks](https://img.shields.io/badge/webhooks-enabled-0f172a?style=for-the-badge&logo=webhook&logoColor=white)]()

Welcome to my worst ever repo which is a monument of rage and painful PHP structure to bleed your eyes. After creating this abomination I'm really not in to creating documentation but hey It's still MIT License. But if you still need help with this like you are that desperate contact me through Discord which my nick is Gavur.




A Kick.com moderation dashboard for streamers who want their chat handled like a professional operation, not a sticky note and a prayer.

BotDash is a PHP + MariaDB control panel for Kick chat moderation, live events, command automation, follower intelligence, shared ban management, automated bot notifications, and a few quality-of-life tools that make your channel look far more organized than it probably deserves.


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

- Do not commit real client secrets or database credentials to a public repository.
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

Attribution is requested when you use, modify, or commercially ship this project. The MIT License itself does not require attribution, so treat this as a project request rather than a legal restriction.

If you publish a fork or commercial derivative, keeping a visible reference to the original project is appreciated.

## Disclaimer

This project depends on Kick's current public APIs, webhooks, and permissions. Some endpoints or event payloads may change as Kick evolves its platform.

If something breaks after a platform change, it is usually not the dashboard being dramatic. It is usually an API change being rude.