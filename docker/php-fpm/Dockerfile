ARG PHP_VERSION=8.3

FROM php:${PHP_VERSION}-fpm

# To use args inside the Docker script it must be set after tne "FROM" section
# @see https://qmacro.org/blog/posts/2024/05/13/using-arg-in-a-dockerfile-beware-the-gotcha/
ARG SYS_USER_UID=1000
ARG SYS_USER_GID=1000
ARG SYS_USER_NAME=www-data
ARG APP_WORK_DIR=/var/www/app

WORKDIR ${APP_WORK_DIR}

# Set www-data to have UID 1000
RUN usermod -u 1000 ${SYS_USER_NAME};
RUN chown -R ${SYS_USER_NAME}:${SYS_USER_NAME} ${APP_WORK_DIR} && chmod -R 755 ${APP_WORK_DIR}

RUN apt-get update -qq && apt-get install -yqq \
    curl \
    g++ \
    git \
    libxml2-dev \
    libicu-dev \
    libcurl4-gnutls-dev \
    libmcrypt-dev \
    libpng-dev \
    libjpeg-dev \
    libpng-dev \
    libsodium-dev \
    libzip-dev \
    zip \
    zlib1g-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-source extract \
    && pecl install xdebug \
    && docker-php-source delete \
    && docker-php-ext-install opcache soap intl pcntl zip \
    && docker-php-ext-enable opcache sodium xdebug pcntl zip \
    && rm -rf /tmp/*

# Clean up package cache
RUN rm -rf /var/cache/apk/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cofnigure GIT
RUN git config --global --add safe.directory ${APP_WORK_DIR}
