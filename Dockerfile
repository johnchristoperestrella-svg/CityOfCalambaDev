FROM php:8.4-apache

# Install required system dependencies and extensions. The official PHP Apache
# image already enables its compatible MPM; do not manipulate MPM modules here.
# Enabling another one prevents Apache from starting.
RUN apt-get update \
    && apt-get install -y libzip-dev unzip git zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev libonig-dev \
    && a2enmod rewrite \
    && docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp \
    && docker-php-ext-install pdo pdo_mysql mysqli zip gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy only dependency metadata first for build caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy the application files
COPY . /app

# Set Apache document root to the public folder and allow index.php to be served
ENV APACHE_DOCUMENT_ROOT=/app/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && printf '<Directory /app/public>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/app.conf \
    && a2enconf app \
    && apache2ctl configtest

EXPOSE 80
CMD ["apache2-foreground"]
