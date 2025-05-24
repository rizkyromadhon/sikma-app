FROM richarvey/nginx-php-fpm:latest

COPY . /var/www/html

RUN apk update && \
    apk add --no-cache npm && \
    npm install && \
    npm run build && \
    composer install --no-dev --working-dir=/var/www/html

CMD ["/start.sh"]
