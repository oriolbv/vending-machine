FROM php:7.4

RUN apt-get update && \
    apt-get install git unzip -y

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

COPY . /app

VOLUME ["/app"]

WORKDIR /app

RUN composer install