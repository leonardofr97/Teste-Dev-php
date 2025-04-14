FROM php:8.2-fpm

USER root

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mysqli pdo mbstring exif pcntl bcmath gd sockets

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN groupadd -g 1000 php-user && \
    useradd -u 1000 -g php-user -m php-user && \
    usermod -p "*" php-user -s /bin/bash && \
    chown -R php-user:php-user /var/www/html