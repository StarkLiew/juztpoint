FROM php:7.3.7-fpm

ENV \
  APP_DIR="/var/www" \
  APP_PORT="80" 

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    openssl \
    mariadb-client \
    libxml2-dev \
    libbz2-dev \    
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    pkg-config \
    patch \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    git-core \
    libssl-dev \
    wkhtmltopdf xvfb


# Install php extensions
RUN docker-php-ext-install gd pdo_mysql mbstring zip exif pcntl bz2
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/


# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_13.x  | bash -
RUN apt-get -y install nodejs
RUN npm install

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY . $APP_DIR

RUN chmod -R 777 /var/www/storage
RUN chmod -R 777 /var/www/storage/logs
RUN chmod -R 755 /var/www/bootstrap/cache
