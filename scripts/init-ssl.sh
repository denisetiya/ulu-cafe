#!/bin/bash
# ============================================
# Initial SSL Setup Script for Ulu-Cafe
# ============================================
# Run this script once on a fresh server to set up SSL certificates
#
# Usage: ./scripts/init-ssl.sh your-domain.com your-email@example.com
#

set -e

DOMAIN=$1
EMAIL=$2

if [ -z "$DOMAIN" ] || [ -z "$EMAIL" ]; then
    echo "Usage: $0 <domain> <email>"
    echo "Example: $0 ulucafe.com admin@ulucafe.com"
    exit 1
fi

echo "🔐 Setting up SSL for $DOMAIN..."

# Create certbot directories
mkdir -p ./certbot/conf
mkdir -p ./certbot/www

# Download recommended TLS parameters
echo "📥 Downloading TLS parameters..."
if [ ! -f "./certbot/conf/options-ssl-nginx.conf" ]; then
    curl -s https://raw.githubusercontent.com/certbot/certbot/master/certbot-nginx/certbot_nginx/_internal/tls_configs/options-ssl-nginx.conf > ./certbot/conf/options-ssl-nginx.conf
fi

if [ ! -f "./certbot/conf/ssl-dhparams.pem" ]; then
    curl -s https://raw.githubusercontent.com/certbot/certbot/master/certbot/certbot/ssl-dhparams.pem > ./certbot/conf/ssl-dhparams.pem
fi

# Create dummy certificate for nginx to start
echo "📜 Creating dummy certificate for $DOMAIN..."
CERT_PATH="./certbot/conf/live/$DOMAIN"
mkdir -p "$CERT_PATH"

openssl req -x509 -nodes -newkey rsa:4096 -days 1 \
    -keyout "$CERT_PATH/privkey.pem" \
    -out "$CERT_PATH/fullchain.pem" \
    -subj "/CN=localhost"

# Generate nginx config from template
echo "⚙️ Generating nginx configuration..."
export DOMAIN_NAME=$DOMAIN
envsubst '${DOMAIN_NAME}' < ./docker/nginx/conf.d/default.conf.template > ./docker/nginx/conf.d/default.conf

# Pull the latest image from GHCR
echo "📥 Pulling latest image from GHCR..."
IMAGE_TAG=${IMAGE_TAG:-"ghcr.io/denisetiya/ulu-cafe:latest"}
docker pull $IMAGE_TAG || {
    echo "⚠️ Could not pull image. Make sure the image exists in GHCR."
    echo "   If this is the first deployment, push to main branch first."
}

# Export IMAGE_TAG for docker-compose
export IMAGE_TAG

# Start nginx with dummy certificate
echo "🚀 Starting services..."
docker compose up -d nginx

echo "⏳ Waiting for nginx to start..."
sleep 5

# Delete dummy certificate and request real one
echo "🔑 Requesting SSL certificate from Let's Encrypt..."
docker compose run --rm certbot \
    certonly --webroot -w /var/www/certbot \
    --email "$EMAIL" \
    --agree-tos \
    --no-eff-email \
    --force-renewal \
    -d "$DOMAIN" \
    -d "www.$DOMAIN"

# Start all services
echo "🔄 Starting all services..."
docker compose up -d

# Wait for app to be ready
echo "⏳ Waiting for application to be ready..."
sleep 10

# Reload nginx with real certificate
echo "🔄 Reloading nginx with real certificate..."
docker compose exec nginx nginx -s reload || true

echo ""
echo "✅ SSL setup complete!"
echo ""
echo "Your site should now be accessible at:"
echo "  https://$DOMAIN"
echo "  https://www.$DOMAIN"
echo ""
echo "Certificate auto-renewal is configured via Certbot container."
echo ""
