FROM php:8.3-cli-alpine3.20

RUN apk add --no-cache git wget

COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions sockets \
    && apk del --no-cache  ${PHPIZE_DEPS} ${BUILD_DEPENDS}

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /srv

RUN mkdir -p ./runtime/body
RUN composer require buggregator/trap:1.12.0

ENTRYPOINT ["./vendor/bin/trap"]
