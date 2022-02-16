FROM php:7.4-fpm

ENV PHP_MEMORY_LIMIT=512M

RUN pecl install apcu

RUN apt-get update && \
	apt-get install -y \
	unzip zlib1g-dev libzip-dev

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev

RUN pecl install mongodb && docker-php-ext-enable mongodb
RUN docker-php-ext-install zip
RUN docker-php-ext-enable apcu
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get install -y libpng-dev && docker-php-ext-install gd

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && mv composer /usr/local/bin/composer

RUN adduser --system --group --shell /bin/sh user
USER user

WORKDIR /usr/src/app

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin
