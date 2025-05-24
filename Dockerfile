FROM richarvey/nginx-php-fpm:latest
FROM node:16-alpine
WORKDIR /var/www/html
COPY . /var/www/html

RUN apk add --no-cache python3 make g++
RUN npm install --legacy-peer-deps
RUN apk update && \
    apk add --no-cache npm && \
    npm install && \
    npm run build && \
    composer install --no-dev --working-dir=/var/www/html

CMD ["/start.sh"]
