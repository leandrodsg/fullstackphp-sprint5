# Stage 1: Dependencies Builder
FROM composer:2 AS composer-builder
WORKDIR /app

# Copy dependency files first for better caching
COPY composer.json composer.lock ./

# Install dependencies with optimizations for production
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --no-progress

# Stage 2: Node.js Builder for Vite
FROM node:20-alpine AS node-builder
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install npm dependencies
RUN npm ci

# Copy source files needed for build
COPY . .

# Build assets with Vite
RUN npm run build

# Fix Vite 7.0.4+ manifest issue
RUN if [ -f "public/build/.vite/manifest.json" ]; then \
        cp public/build/.vite/manifest.json public/build/manifest.json; \
    fi

# Stage 3: Application Runtime
FROM php:8.2-fpm-alpine AS runtime
WORKDIR /var/www/html

# Install system dependencies and PHP extensions in one layer
RUN apk add --no-cache \
    postgresql-dev \
    git \
    netcat-openbsd \
    nginx \
    supervisor \
    curl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        opcache \
    && rm -rf /var/cache/apk/* \
    && rm -rf /tmp/*

# Configure PHP for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/opcache.ini

# Create necessary directories with proper permissions for non-root execution
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/tmp/nginx \
    && mkdir -p /var/www/html/tmp/supervisor \
    && mkdir -p /var/www/html/tmp/nginx/client_body \
    && mkdir -p /var/www/html/tmp/nginx/proxy \
    && mkdir -p /var/www/html/tmp/nginx/fastcgi \
    && mkdir -p /var/www/html/tmp/nginx/uwsgi \
    && mkdir -p /var/www/html/tmp/nginx/scgi \
    && mkdir -p /var/www/html/logs/nginx \
    && mkdir -p /var/www/html/logs/supervisor \
    && touch /var/www/html/tmp/nginx.pid \
    && touch /var/www/html/tmp/supervisord.pid \
    && chown -R www-data:www-data /var/www/html

# Copy application files (excluding what's in .dockerignore)
COPY --chown=www-data:www-data . .

# Copy optimized dependencies from composer stage
COPY --from=composer-builder --chown=www-data:www-data /app/vendor ./vendor

# Copy built assets from node stage
COPY --from=node-builder --chown=www-data:www-data /app/public/build ./public/build

# Copy Docker-specific environment file
COPY --chown=www-data:www-data .env.docker .env

# Set proper permissions in one layer
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x /var/www/html/docker-entrypoint.sh

# Configure Nginx for production
COPY --chown=www-data:www-data nginx.conf /etc/nginx/nginx.conf

# Remove default nginx configuration that might conflict
RUN rm -f /etc/nginx/conf.d/default.conf

# Configure Supervisor for process management
COPY --chown=www-data:www-data supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Switch to non-root user for security
USER www-data

# Expose port
EXPOSE 8080

# Health check for production monitoring
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

# Use supervisor to manage multiple processes (nginx + php-fpm)
CMD ["/bin/sh", "-c", "/var/www/html/docker-entrypoint.sh init && supervisord -c /etc/supervisor/conf.d/supervisord.conf -n"]