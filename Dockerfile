FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

COPY . .
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libfreetype-dev \
    zip \
    git \
    bash \
    npm

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && npm install

RUN npm run build \
    && composer install --no-dev --working-dir=/var/www/html

CMD ["php-fpm"]
