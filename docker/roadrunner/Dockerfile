FROM php:8.1

ENV ROADRUNNER_VERSION="2023.1.3"

RUN apt-get update \
    && apt-get install -qqy \
        apt-utils \
        git \
        gzip \
        librdkafka-dev \
        libxml2-dev \
        libzip-dev \
        wget \
        unzip \
    && docker-php-ext-install \
        opcache \
        sockets \
    && pecl install \
        rdkafka \
    && docker-php-ext-enable \
        rdkafka

RUN curl -s https://getcomposer.org/installer -o installer.php \
    && php installer.php --2 --install-dir=/usr/local/bin --filename=composer \
    && rm -f installer.php

RUN wget -q "https://github.com/roadrunner-server/roadrunner/releases/download/v${ROADRUNNER_VERSION}/roadrunner-${ROADRUNNER_VERSION}-linux-amd64.tar.gz" \
    && tar -xzf roadrunner-${ROADRUNNER_VERSION}-linux-amd64.tar.gz \
    && mv roadrunner-${ROADRUNNER_VERSION}-linux-amd64/rr /usr/local/bin/rr \
    && chmod +x /usr/local/bin/rr \
    && rm -rf roadrunner-${ROADRUNNER_VERSION}-linux-amd64*

#RUN pecl install xdebug && docker-php-ext-enable xdebug
