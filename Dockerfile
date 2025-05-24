FROM richarvey/nginx-php-fpm:latest

# Salin semua kode aplikasi ke dalam container
COPY . /var/www/html

# Install dependensi sistem
RUN apk update && apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libxml2-dev \
    libzip-dev \
    nginx \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && npm install -g npm@latest \
    && npm install \
    && npm run build

# Salin konfigurasi Nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Set direktori kerja
WORKDIR /var/www/html

# Jalankan perintah untuk memulai aplikasi
CMD ["sh", "-c", "composer install --no-dev && php artisan key:generate && php artisan migrate --force && php-fpm -D && nginx -g 'daemon off;'"]
