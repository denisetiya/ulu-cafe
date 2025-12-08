# 🚀 Deployment Guide - Ulu Cafe (Traefik)

Deploy dengan Docker, FrankenPHP, dan **Traefik** untuk auto SSL.

## ⚡ Quick Start

```bash
# Di server baru
curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/scripts/server-setup.sh | bash -s ulucafe.cloud ulucafebest@gmail.com

# Edit .env jika perlu
nano ~/ulu-cafe/.env

# Start
cd ~/ulu-cafe && docker compose up -d
```

Traefik akan **otomatis** request SSL dari Let's Encrypt! 🎉

---

## 📋 Architecture

```
Internet → Traefik (80/443) → FrankenPHP (8000) → Laravel
              ↓
      Auto SSL Let's Encrypt
```

---

## 🔐 GitHub Secrets

| Secret            | Deskripsi                      |
| ----------------- | ------------------------------ |
| `SERVER_HOST`     | IP server                      |
| `SERVER_USER`     | Username SSH                   |
| `SERVER_PASSWORD` | Password SSH                   |
| `SERVER_PORT`     | Port SSH (optional)            |
| `DOMAIN_NAME`     | Domain (e.g., `ulucafe.cloud`) |
| `TOKEN_GITHUB`    | GitHub PAT untuk GHCR          |

---

## 📦 Environment Variables (.env)

```bash
APP_URL=https://ulucafe.cloud
ASSET_URL=https://ulucafe.cloud
DOMAIN_NAME=ulucafe.cloud
ACME_EMAIL=ulucafebest@gmail.com
```

---

## 🛠️ Commands

```bash
cd ~/ulu-cafe

# Logs
docker compose logs -f traefik
docker compose logs -f app

# Restart
docker compose restart app

# Migrations
docker compose exec app php artisan migrate --force

# Clear cache
docker compose exec app php artisan optimize:clear
```

---

## 📁 Structure

```
~/ulu-cafe/
├── docker-compose.yml
├── .env
├── traefik/
│   └── letsencrypt/      # SSL certificates (auto-managed)
├── storage/
│   ├── app/
│   └── logs/
└── database/
```

---

## 🔄 Manual Deploy

```bash
cd ~/ulu-cafe
docker pull ghcr.io/denisetiya/ulu-cafe:latest
docker compose down
docker compose up -d
```
