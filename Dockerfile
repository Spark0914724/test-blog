FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader 2>/dev/null || composer install --no-scripts --no-autoloader
COPY . .
RUN composer dump-autoload --optimize \
    && mkdir -p templates_c cache \
    && chmod -R 777 templates_c cache

EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
