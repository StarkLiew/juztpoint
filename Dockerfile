
FROM php:7.3.7-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

USER root

# Add user for laravel application
RUN getent group www || groupadd -g 1000 www
RUN getent group www || useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www


# Expose port 9000 and start php-fpm server
EXPOSE 3307
EXPOSE 9000
CMD ["php-fpm"]

ENTRYPOINT []