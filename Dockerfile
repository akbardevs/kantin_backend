# FROM trafex/alpine-nginx-php7:ba1dd422

FROM trafex/alpine-nginx-php7:1.8.0
USER root
# ENV PHP_MEMORY_LIMIT    512M
RUN apk add php7-session php7-pdo openssl php7-common php7-mbstring php7-xml php7-zip php7-tokenizer php7-xmlwriter php7-simplexml php7-curl php7-cli php7-bcmath php7-pdo_mysql php7-fileinfo php7-iconv nodejs npm
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY prod.conf /etc/nginx/nginx.conf
# RUN sed -i "s|;*memory_limit =.*|memory_limit = ${PHP_MEMORY_LIMIT}|i" /etc/php7/php.ini
RUN sed -i "s|;*log_errors =.*|log_errors = On|i" /etc/php7/php.ini
RUN sed -i "s|;*error_log =.*|error_log = \/var\/log\/php7\/error.log|i" /etc/php7/php.ini
RUN sed -i "s|;error_log =.*|error_log = \/var\/log\/php7\/error.log|i" /etc/php7/php-fpm.conf
ADD . /var/www/
WORKDIR /var/www
RUN composer update
RUN composer validate
RUN composer install --prefer-dist --no-progress
RUN composer dump-autoload --optimize
# RUN php artisan storage:link
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN php artisan cache:clear
RUN php artisan optimize
RUN npm install
RUN npm run production
RUN chmod -R 777 /var/www/storage
RUN chmod -R 777 /var/www/bootstrap/cache
USER nobody

EXPOSE 8080
