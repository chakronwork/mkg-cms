# Development image for mkg-cms (PHP 8.3 + Apache).
# App code is bind-mounted by docker-compose, so it is intentionally NOT copied in here.
FROM php:8.3-apache

# Only pdo_mysql is missing from the base image. Everything else the app uses
# (mbstring, fileinfo, hash, session, ...) is enabled by default in php:8.3-apache.
RUN docker-php-ext-install pdo_mysql

# public/.htaccess relies on mod_rewrite (routing) and mod_headers (security headers).
RUN a2enmod rewrite headers

# Serve from public/ and allow the project's .htaccess files to take effect.
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
