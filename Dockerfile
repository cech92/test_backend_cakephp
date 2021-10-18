FROM php:7.4.3-apache

RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libzip-dev \
      libxml2-dev \
      libmcrypt-dev \
      git \
      zip \
      unzip \
      libonig-dev
RUN pecl install mcrypt-1.0.4 && docker-php-ext-enable mcrypt \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-install \
      intl \
      mbstring \
      pcntl \
      pdo_pgsql \
      pgsql \
      zip \
      xml \
      opcache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

ENV APP_HOME /var/www/html

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN sed -i -e "s/html/html\//g" /etc/apache2/sites-enabled/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite && \
    service apache2 restart

COPY . $APP_HOME
RUN composer install
RUN chown -R www-data:www-data $APP_HOME
