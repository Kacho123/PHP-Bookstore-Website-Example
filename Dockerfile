# Base image: PHP 8.1 with Apache
FROM php:8.1-apache

# Install mysqli extension (for MySQL)
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project files into Apache directory
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Set file permissions (optional, useful for uploads etc.)
RUN chown -R www-data:www-data /var/www/html
