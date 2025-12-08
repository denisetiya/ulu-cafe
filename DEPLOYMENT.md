# 🚀 Deployment Guide - Ulu Cafe

Panduan lengkap untuk deploy Ulu Cafe ke production menggunakan Docker, FrankenPHP, Nginx, dan SSL otomatis.

## 📋 Prerequisites

-   Server dengan Docker terinstall (Ubuntu 20.04+ recommended)
-   Domain yang sudah pointing ke IP server
-   GitHub repository dengan GitHub Actions enabled

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────┐
│                      Internet                           │
└─────────────────────────┬───────────────────────────────┘
                          │
                    ┌─────▼─────┐
                    │   Nginx   │ ← SSL Termination (443)
                    │  :80/:443 │
                    └─────┬─────┘
                          │
                    ┌─────▼─────┐
                    │ FrankenPHP│ ← Laravel App
                    │   :8000   │
                    └───────────┘
```

**Flow CI/CD:**

```
Push to main → GitHub Actions → Build Image → Push to GHCR → SSH Deploy → Pull Image → Start
```

---

## ⚡ Quick Start (Server Baru)

### One-liner Setup:

```bash
curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/scripts/server-setup.sh | bash -s yourdomain.com your-email@example.com
```

### Atau Manual Setup:

```bash
# 1. Buat direktori
mkdir -p ~/ulu-cafe && cd ~/ulu-cafe

# 2. Download docker-compose dan config
curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/docker-compose.yml -o docker-compose.yml
curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/.env.production.example -o .env

# 3. Download nginx config
mkdir -p docker/nginx/conf.d
curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/docker/nginx/nginx.conf -o docker/nginx/nginx.conf
curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/docker/nginx/conf.d/default.conf.template -o docker/nginx/conf.d/default.conf.template

# 4. Download scripts
mkdir -p scripts
curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/scripts/init-ssl.sh -o scripts/init-ssl.sh
curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/scripts/deploy.sh -o scripts/deploy.sh
chmod +x scripts/*.sh

# 5. Edit environment
nano .env

# 6. Setup SSL dan jalankan
./scripts/init-ssl.sh yourdomain.com your-email@example.com
```

---

## 🔐 GitHub Secrets

Tambahkan secrets berikut di repository GitHub (`Settings → Secrets → Actions`):

| Secret            | Deskripsi               | Contoh               |
| ----------------- | ----------------------- | -------------------- |
| `SERVER_HOST`     | IP atau hostname server | `123.45.67.89`       |
| `SERVER_USER`     | Username SSH            | `root` atau `ubuntu` |
| `SERVER_PASSWORD` | Password SSH            | `your-password`      |
| `SERVER_PORT`     | Port SSH (optional)     | `22`                 |
| `DOMAIN_NAME`     | Domain production       | `ulucafe.com`        |
| `TOKEN_GITHUB`    | GitHub PAT untuk GHCR   | `ghp_xxxx...`        |

> ⚠️ **Security Note:** Pastikan password yang digunakan kuat dan unik. Pertimbangkan untuk menggunakan SSH key untuk keamanan lebih baik.

---

## 📦 Docker Image

Image tersedia di GitHub Container Registry:

```bash
# Latest
ghcr.io/denisetiya/ulu-cafe:latest

# Specific commit
ghcr.io/denisetiya/ulu-cafe:<commit-sha>
```

### Pull Manual:

```bash
docker pull ghcr.io/denisetiya/ulu-cafe:latest
```

---

## 🛠️ Perintah Berguna

### Melihat logs:

```bash
cd ~/ulu-cafe
docker compose logs -f app      # App logs
docker compose logs -f nginx    # Nginx logs
docker compose logs -f certbot  # SSL logs
```

### Restart services:

```bash
docker compose restart app
docker compose restart nginx
```

### Run migrations manual:

```bash
docker compose exec app php artisan migrate --force
```

### Clear cache:

```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
```

### Masuk ke container:

```bash
docker compose exec app sh
```

### Update manual (tanpa CI/CD):

```bash
cd ~/ulu-cafe
docker pull ghcr.io/denisetiya/ulu-cafe:latest
./scripts/deploy.sh
```

---

## 🔒 SSL Certificate

SSL dikelola otomatis oleh Certbot:

-   Certificate di-renew otomatis setiap 12 jam
-   Nginx reload setiap 6 jam untuk pickup certificate baru

### Check certificate status:

```bash
docker compose exec certbot certbot certificates
```

### Force renewal:

```bash
docker compose exec certbot certbot renew --force-renewal
docker compose exec nginx nginx -s reload
```

---

## 📁 Struktur Direktori di Server

```
~/ulu-cafe/
├── docker-compose.yml
├── .env                          # Environment production
├── docker/
│   └── nginx/
│       ├── nginx.conf
│       └── conf.d/
│           ├── default.conf.template
│           └── default.conf      # Generated
├── scripts/
│   ├── deploy.sh
│   └── init-ssl.sh
├── certbot/
│   ├── conf/                     # SSL certificates
│   └── www/                      # ACME challenge
├── storage/
│   ├── app/                      # Uploaded files
│   └── logs/                     # Application logs
└── database/
    └── database.sqlite           # SQLite database
```

---

## 🐛 Troubleshooting

### Container tidak start:

```bash
docker compose ps
docker compose logs app
```

### Health check gagal:

```bash
# Check apakah app bisa diakses
docker compose exec app curl -f http://localhost:8000/up
```

### SSL tidak bekerja:

```bash
# Check nginx config
docker compose exec nginx nginx -t

# Check certificate
ls -la certbot/conf/live/yourdomain.com/
```

### Permission denied:

```bash
# Fix storage permissions
docker compose exec app chown -R www-data:www-data storage
docker compose exec app chmod -R 775 storage
```

---

## 🔄 Rollback

Untuk rollback ke versi sebelumnya:

```bash
cd ~/ulu-cafe

# List available tags
# Check di https://github.com/denisetiya/ulu-cafe/pkgs/container/ulu-cafe

# Pull specific version
export IMAGE_TAG=ghcr.io/denisetiya/ulu-cafe:<old-sha>
docker pull $IMAGE_TAG

# Deploy
./scripts/deploy.sh
```

---

## 📞 Support

Jika ada masalah, check:

1. GitHub Actions logs
2. Server logs (`docker compose logs`)
3. Nginx error logs

---

Made with ❤️ by Ulu Cafe Team
