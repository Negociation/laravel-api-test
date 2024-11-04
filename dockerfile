FROM php:8.2.5-apache

WORKDIR /var/www/html

ARG WWWGROUP



RUN apt-get update && \
    apt-get install -y \
    git \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    libpq-dev \
    libssl-dev \
    libssl1.0 \
    libssl-dev \
    libmagickwand-dev

RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd


RUN a2enmod rewrite

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Instala a extensão MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-suggest

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]