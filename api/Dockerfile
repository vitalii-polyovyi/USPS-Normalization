ARG ALPINE_VERSION=3.17
FROM alpine:${ALPINE_VERSION} as base

WORKDIR /var/www/html

RUN apk add --no-cache \
  curl \
  nginx \
  php81 \
  php81-ctype \
  php81-curl \
  php81-dom \
  php81-fpm \
  php81-gd \
  php81-intl \
  php81-mbstring \
  php81-opcache \
  php81-openssl \
  php81-phar \
  php81-session \
  php81-simplexml \
  php81-xml \
  php81-pdo \
  php81-pdo_mysql \
  supervisor

COPY docker/config/nginx.conf /etc/nginx/nginx.conf
COPY docker/config/conf.d /etc/nginx/conf.d/

COPY docker/config/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY docker/config/php.ini /etc/php81/conf.d/custom.ini

COPY docker/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN chown -R nobody.nobody /var/www/html /run /var/lib/nginx /var/log/nginx

USER nobody

FROM composer AS composer

COPY . /app

RUN composer install \
  --optimize-autoloader \
  --no-interaction \
  --no-progress

FROM base

COPY --chown=nginx --from=composer /app /var/www/html

EXPOSE 8080

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping