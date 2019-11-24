FROM php:7.3.7-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# COPY init-letsencrypt /var/www/

# chmod +x init-letsencrypt.sh
# sudo init-letsencrypt.sh

# RUN init-letsencrypt init-letsencrypt 

USER root

# Install dependencies
 RUN apt-get update && apt-get install -y \
    build-essential \
    openssl \
    mariadb-client \
    libxml2-dev \
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


ADD https://git.archlinux.org/svntogit/packages.git/plain/trunk/freetype.patch?h=packages/php /tmp/freetype.patch
RUN docker-php-source extract; \
  cd /usr/src/php; \
  patch -p1 -i /tmp/freetype.patch; \
  rm /tmp/freetype.patch

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# RUN curl -L https://raw.githubusercontent.com/wmnnd/nginx-certbot/master/init-letsencrypt.sh > init-letsencrypt.sh
# RUN chmod +x init-letsencrypt.sh 
# RUN sudo ./init-letsencrypt.sh

# RUN mv wkhtmltopdf /usr/local/bin/wkhtmltopdf

# Install extensions
RUN docker-php-ext-install gd pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/


# Install Node.js
RUN curl -sL https://deb.nodesource.com/setup_13.x  | bash -
RUN apt-get -y install nodejs
RUN npm install

# Install composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application

# RUN getent group www || groupadd -g 1000 www
# RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

RUN composer install
RUN npm install

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www
RUN chmod -R 777 /var/www/storage
RUN chmod -R 755 /var/www/bootstrap/cache
# RUN chown -R www:www /var/www


USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 3307
EXPOSE 9000
CMD ["php-fpm"]

# RUN php artisan migrate
# RUN php artisan passport:install

ENTRYPOINT []
