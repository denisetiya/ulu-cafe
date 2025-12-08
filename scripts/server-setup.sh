#!/bin/bash
# ============================================
# Server Setup Script for Ulu-Cafe (Traefik version)
# ============================================
# Run this script once on a fresh server
#
# Usage: curl -sSL https://raw.githubusercontent.com/denisetiya/ulu-cafe/main/scripts/server-setup.sh | bash -s your-domain.com your-email@example.com
#

set -e

DOMAIN=$1
EMAIL=$2
REPO="denisetiya/ulu-cafe"
DEPLOY_DIR="$HOME/ulu-cafe"

if [ -z "$DOMAIN" ] || [ -z "$EMAIL" ]; then
    echo "Usage: $0 <domain> <email>"
    echo "Example: $0 ulucafe.com admin@ulucafe.com"
    exit 1
fi

echo "🚀 Setting up Ulu-Cafe deployment with Traefik..."

# Install Docker if not present
if ! command -v docker &> /dev/null; then
    echo "📦 Installing Docker..."
    curl -fsSL https://get.docker.com | sh
    sudo usermod -aG docker $USER
    echo "⚠️ Please log out and log back in for Docker permissions to take effect."
fi

# Create deployment directory
echo "📁 Creating deployment directory..."
mkdir -p $DEPLOY_DIR
cd $DEPLOY_DIR

# Create necessary directories
mkdir -p traefik/letsencrypt
mkdir -p storage/app
mkdir -p storage/logs
mkdir -p database

# Download required files from GitHub
echo "📥 Downloading configuration files..."
BASE_URL="https://raw.githubusercontent.com/$REPO/main"

curl -sSL "$BASE_URL/docker-compose.yml" -o docker-compose.yml
curl -sSL "$BASE_URL/scripts/deploy.sh" -o scripts/deploy.sh 2>/dev/null || true
curl -sSL "$BASE_URL/.env.production.example" -o .env.example

mkdir -p scripts
curl -sSL "$BASE_URL/scripts/deploy.sh" -o scripts/deploy.sh
chmod +x scripts/deploy.sh

# Create .env file with domain and email
echo "⚙️ Creating .env file..."
cp .env.example .env

# Update .env with provided values
sed -i "s|DOMAIN_NAME=.*|DOMAIN_NAME=$DOMAIN|g" .env
sed -i "s|ACME_EMAIL=.*|ACME_EMAIL=$EMAIL|g" .env
sed -i "s|APP_URL=.*|APP_URL=https://$DOMAIN|g" .env
sed -i "s|ASSET_URL=.*|ASSET_URL=https://$DOMAIN|g" .env

# Generate APP_KEY
echo "🔑 Generating APP_KEY..."
APP_KEY="base64:$(openssl rand -base64 32)"
sed -i "s|APP_KEY=.*|APP_KEY=$APP_KEY|g" .env

echo ""
echo "📝 Please review and edit .env file:"
echo "   nano $DEPLOY_DIR/.env"
echo ""
echo "After editing .env, run:"
echo "   cd $DEPLOY_DIR"
echo "   docker compose up -d"
echo ""
echo "Traefik will automatically obtain SSL certificate from Let's Encrypt!"
echo ""
echo "✅ Server setup complete!"
