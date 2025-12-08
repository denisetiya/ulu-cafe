#!/bin/bash
# ============================================
# Server Initial Setup Script for Ulu-Cafe
# ============================================
# Run this script once on a fresh server to set up the deployment directory
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

echo "🚀 Setting up Ulu-Cafe deployment..."

# Install Docker if not present
if ! command -v docker &> /dev/null; then
    echo "📦 Installing Docker..."
    curl -fsSL https://get.docker.com | sh
    sudo usermod -aG docker $USER
    echo "⚠️ Please log out and log back in for Docker permissions to take effect."
fi

# Create deployment directory
echo "📁 Creating deployment directory..."
sudo mkdir -p $DEPLOY_DIR
sudo chown -R $USER:$USER $DEPLOY_DIR
cd $DEPLOY_DIR

# Create necessary directories
mkdir -p docker/nginx/conf.d
mkdir -p docker/frankenphp
mkdir -p certbot/conf
mkdir -p certbot/www
mkdir -p storage/app
mkdir -p storage/logs
mkdir -p database
mkdir -p scripts

# Download required files from GitHub
echo "📥 Downloading configuration files..."
BASE_URL="https://raw.githubusercontent.com/$REPO/main"

curl -sSL "$BASE_URL/docker-compose.yml" -o docker-compose.yml
curl -sSL "$BASE_URL/docker/nginx/nginx.conf" -o docker/nginx/nginx.conf
curl -sSL "$BASE_URL/docker/nginx/conf.d/default.conf.template" -o docker/nginx/conf.d/default.conf.template
curl -sSL "$BASE_URL/docker/frankenphp/Caddyfile" -o docker/frankenphp/Caddyfile
curl -sSL "$BASE_URL/scripts/deploy.sh" -o scripts/deploy.sh
curl -sSL "$BASE_URL/scripts/init-ssl.sh" -o scripts/init-ssl.sh
curl -sSL "$BASE_URL/.env.production.example" -o .env.example

chmod +x scripts/*.sh

# Create .env file
echo "⚙️ Creating .env file..."
cp .env.example .env

echo ""
echo "📝 Please edit .env file with your production settings:"
echo "   nano $DEPLOY_DIR/.env"
echo ""
echo "After editing .env, run:"
echo "   cd $DEPLOY_DIR"
echo "   ./scripts/init-ssl.sh $DOMAIN $EMAIL"
echo ""
echo "✅ Server setup complete!"
