#!/bin/bash
# ============================================
# Deployment Script for Ulu-Cafe (GHCR Version)
# ============================================
# This script is executed by GitHub Actions on the production server
# Pulls image from GHCR instead of building locally
#

set -e

echo "🚀 Starting deployment..."

# Navigate to project directory
cd ~/ulu-cafe

# Set image tag (passed from GitHub Actions or use default)
IMAGE_TAG=${IMAGE_TAG:-"ghcr.io/denisetiya/ulu-cafe:latest"}

echo "📦 Using image: $IMAGE_TAG"

# Pull latest image from GHCR
echo "📥 Pulling latest image from GHCR..."
docker pull $IMAGE_TAG

# Generate nginx config from template if not exists
if [ -n "$DOMAIN_NAME" ] && [ -f "./docker/nginx/conf.d/default.conf.template" ]; then
    echo "⚙️ Generating nginx configuration..."
    envsubst '${DOMAIN_NAME}' < ./docker/nginx/conf.d/default.conf.template > ./docker/nginx/conf.d/default.conf
fi

# Export IMAGE_TAG for docker-compose
export IMAGE_TAG

# Stop existing containers (graceful)
echo "⏹️ Stopping existing containers..."
docker compose down --remove-orphans || true

# Start containers with new image
echo "🔨 Starting containers with new image..."
docker compose up -d

# Wait for app to be healthy
echo "⏳ Waiting for application to be healthy..."
timeout 120 bash -c 'until docker compose exec -T app curl -sf http://localhost:8000/up 2>/dev/null; do sleep 2; done' || {
    echo "❌ Application health check failed!"
    docker compose logs app
    exit 1
}

# Run migrations inside container
echo "🗄️ Running migrations..."
docker compose exec -T app php artisan migrate --force

# Optimize application
echo "🧹 Optimizing application..."
docker compose exec -T app php artisan optimize

# Clean up old images
echo "🧼 Cleaning up old images..."
docker image prune -f

echo ""
echo "✅ Deployment complete!"
echo ""
docker compose ps
