# Gunakan container PHP dan Apache dengan versi 8.1.30
FROM php:8.1.30-apache

# Instal dependensi sistem dan ekstensi PHP
# gd: Memerlukan libpng-dev, libjpeg-dev, libfreetype6-dev
# tidy: Memerlukan libzip-dev, libtidy-dev
# zip: Memerlukan libzip-dev
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libtidy-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        exif \
        mysqli \
        pdo \
        pdo_mysql \
        tidy \
        zip

# Mengatur direktori kerja
WORKDIR /var/www/html

# Mengekspos port 80 untuk Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]