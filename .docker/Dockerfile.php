FROM php:7.2-fpm-alpine
RUN apk add --no-cache freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev zlib-dev && \
    docker-php-ext-install pdo pdo_mysql mysqli mbstring zip bcmath gd json
EXPOSE 9000
