FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

COPY . .
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    git \
    bash \
    npm

RUN apk add --no-cache libzip-dev zip \
    && docker-php-ext-install zip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && npm install

ENV PUSHER_APP_KEY=19a1b8036123d7352592
ENV PUSHER_APP_SECRET=82ca957ce24e4c08616e
ENV PUSHER_APP_ID=1977685
ENV PUSHER_APP_CLUSTER=ap1

RUN npm run build \
    && composer install --no-dev --working-dir=/var/www/html

CMD ["php-fpm"]
