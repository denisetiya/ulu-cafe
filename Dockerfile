# ============================================
# Stage 1: Build Frontend Assets
# ============================================
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy package files
COPY package.json package-lock.json ./

# Install dependencies
RUN npm ci

# Copy source files for build
COPY resources ./resources
COPY vite.config.js postcss.config.js ./

# Build frontend assets
RUN npm run build

# ============================================
# Stage 2: Composer Dependencies
# ============================================
FROM composer:2 AS composer-builder

WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies without dev dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

# ============================================
# Stage 3: FrankenPHP Production Image
# ============================================
FROM dunglas/frankenphp:1-php8.4-alpine AS production

# Set environment
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Install required PHP extensions
RUN install-php-extensions \
    pdo_sqlite \
    pdo_mysql \
    pdo_pgsql \
    redis \
    opcache \
    intl \
    zip \
    gd \
    bcmath \
    pcntl

# Configure PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Opcache settings for production
RUN echo 'opcache.enable=1' > $PHP_INI_DIR/conf.d/opcache.ini && \
    echo 'opcache.memory_consumption=256' >> $PHP_INI_DIR/conf.d/opcache.ini && \
    echo 'opcache.interned_strings_buffer=64' >> $PHP_INI_DIR/conf.d/opcache.ini && \
    echo 'opcache.max_accelerated_files=32531' >> $PHP_INI_DIR/conf.d/opcache.ini && \
    echo 'opcache.validate_timestamps=0' >> $PHP_INI_DIR/conf.d/opcache.ini && \
    echo 'opcache.save_comments=1' >> $PHP_INI_DIR/conf.d/opcache.ini && \
    echo 'opcache.fast_shutdown=1' >> $PHP_INI_DIR/conf.d/opcache.ini

# PHP settings for production
RUN echo 'memory_limit=256M' > $PHP_INI_DIR/conf.d/php-production.ini && \
    echo 'upload_max_filesize=64M' >> $PHP_INI_DIR/conf.d/php-production.ini && \
    echo 'post_max_size=64M' >> $PHP_INI_DIR/conf.d/php-production.ini && \
    echo 'max_execution_time=120' >> $PHP_INI_DIR/conf.d/php-production.ini && \
    echo 'expose_php=off' >> $PHP_INI_DIR/conf.d/php-production.ini

WORKDIR /app

# Copy application files
COPY --chown=www-data:www-data . .

# Copy built assets from frontend builder
COPY --from=frontend-builder --chown=www-data:www-data /app/public/build ./public/build

# Copy vendor from composer builder
COPY --from=composer-builder --chown=www-data:www-data /app/vendor ./vendor

# Create necessary directories
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Copy Caddyfile
COPY docker/frankenphp/Caddyfile /etc/caddy/Caddyfile

# Copy entrypoint script
COPY scripts/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose FrankenPHP port
EXPOSE 8000

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8000/up || exit 1

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Start FrankenPHP
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
