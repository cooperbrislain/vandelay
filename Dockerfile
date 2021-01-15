FROM php:7.4-fpm
COPY . /usr/src/vandelay
WORKDIR /usr/src/vandelay
CMD ["php", "./index.php" ]
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mysqli
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer