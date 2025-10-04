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

# Stage 2: Application Runtime
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

# Create necessary directories with proper permissions
RUN mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /run/nginx \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /var/lib/nginx/tmp/client_body \
    && mkdir -p /var/lib/nginx/tmp/proxy \
    && mkdir -p /var/lib/nginx/tmp/fastcgi \
    && mkdir -p /var/lib/nginx/tmp/uwsgi \
    && mkdir -p /var/lib/nginx/tmp/scgi \
    && mkdir -p /var/lib/nginx/logs \
    && mkdir -p /var/log/nginx \
    && mkdir -p /var/cache/nginx \
    && touch /var/run/nginx.pid \
    && chown -R www-data:www-data /var/log/supervisor \
    && chown -R www-data:www-data /run/nginx \
    && chown -R www-data:www-data /var/lib/nginx \
    && chown -R www-data:www-data /var/log/nginx \
    && chown -R www-data:www-data /var/cache/nginx \
    && chown www-data:www-data /var/run/nginx.pid

# Copy application files (excluding what's in .dockerignore)
COPY --chown=www-data:www-data . .

# Copy optimized dependencies from composer stage
COPY --from=composer-builder --chown=www-data:www-data /app/vendor ./vendor

# Copy Docker-specific environment file
COPY --chown=www-data:www-data .env.docker .env

# Set proper permissions in one layer
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x /var/www/html/docker-entrypoint.sh

# Configure Nginx for production
COPY --chown=www-data:www-data nginx.conf /etc/nginx/nginx.conf

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
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf", "-n"]