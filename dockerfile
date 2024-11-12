FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmemcached-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    librdkafka-dev \
    libpq-dev \
    openssh-server \
    zip \
    unzip \
    supervisor \
    sqlite3  \
    nano \
    cron

# Install nginx
RUN apt-get update && apt-get install -y nginx

# Install PHP extensions zip, mbstring, exif, bcmath, intl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install  zip mbstring exif pcntl bcmath -j$(nproc) gd intl

# Install Redis and enable it
RUN pecl install redis  && docker-php-ext-enable redis

# Install the PHP pdo_mysql extention
RUN docker-php-ext-install pdo_mysql

# Install PHP Opcache extention
RUN docker-php-ext-install opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

RUN rm -Rf /var/www/* && \
mkdir -p /var/www/html

RUN usermod -u 1000 www-data
RUN groupmod -g 1000 www-data

RUN rm -rf /etc/nginx/conf.d/default.conf
RUN rm -rf /etc/nginx/sites-enabled/default
RUN rm -rf /etc/nginx/sites-available/default

ADD ./docker/php/www.conf /usr/local/etc/php-fpm.d/
ADD ./docker/nginx/nginx.conf /etc/nginx/
ADD ./docker/nginx/default.conf /etc/nginx/conf.d/
ADD ./docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

COPY ./docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh
RUN ln -s /usr/local/bin/entrypoint.sh /

RUN mkdir -p /var/log/supervisor
RUN mkdir -p /var/log/nginx
RUN mkdir -p /var/cache/nginx

RUN chown -R www-data.www-data /var/log/nginx
RUN chown -R www-data.www-data /var/cache/nginx

RUN chown -R www-data.www-data /var/cache/nginx
RUN chown -R www-data.www-data /var/lib/nginx/
RUN chown -R www-data.www-data /etc/nginx/nginx.conf
RUN chown -R www-data.www-data /etc/nginx/conf.d/
RUN chown -R www-data.www-data /tmp

RUN touch /var/run/nginx.pid && \
    chown -R www-data.www-data /var/run/nginx.pid

RUN chown -R www-data.www-data /var/log/supervisor

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
