FROM php:7.3.6-fpm-alpine3.9

ARG API_HOST
    API_PORT

ENV API_HOST ${API_HOST:-"0.0.0.0"}
    API_PORT ${API_PORT:-"8000"}

RUN apk add --no-cache openssl bash mysql-client
RUN docker-php-ext-install pdo pdo_mysql

COPY entrypoint.sh /usr/local/bin/

RUN chmod +x /usr/local/bin/entrypoint.sh

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer self-update --clean-backups

RUN mkdir -p /usr/src/api \
    && adduser -D diogo

USER diogo

WORKDIR /usr/src/api

COPY . .

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
