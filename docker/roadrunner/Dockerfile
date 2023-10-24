# syntax=docker/dockerfile:1.4
FROM php:8.2-cli-alpine3.18

RUN apk add git wget

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions opcache pdo_mysql sockets xdebug xhprof \
    && apk del --no-cache  ${PHPIZE_DEPS} ${BUILD_DEPENDS}

COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /usr/bin/rr

# Host system user id
ARG UID=1000
ARG GID=1000

RUN addgroup -g ${GID} -S app-user \
    && adduser --uid ${UID} --ingroup app-user -S -g app-user app-user

EXPOSE 8080

USER app-user

WORKDIR /srv
