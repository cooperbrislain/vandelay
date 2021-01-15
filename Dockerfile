FROM php:7.4-fpm
COPY . /usr/src/vandelay
WORKDIR /usr/src/myapp
CMD ["php", "./index.php" ]
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd