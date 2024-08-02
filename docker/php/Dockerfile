FROM php:8.3.10-fpm-alpine

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN set -eux && apk add --no-cache \
    oniguruma-dev \
    bash \
    git \
    zip \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    php83-xdebug \
    php83-dev

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

RUN apk add --update linux-headers \
    && apk --no-cache add pcre-dev ${PHPIZE_DEPS} \ 
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del pcre-dev ${PHPIZE_DEPS}

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application

RUN addgroup -S -g 1000 www
RUN adduser -S -D -u 1000 -s /sbin/nologin -h /var/www -G www www

ENV ENV=/etc/profile
COPY ./docker/php/alias.sh /etc/profile.d/alias.sh

# Copy existing application directory contents
# COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]