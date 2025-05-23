# Use official PHP + Apache image
FROM php:8.1-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite (important for many PHP apps)
RUN a2enmod rewrite

# Copy app source code to /var/www/html
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Change ownership (optional, for permission issues)
RUN chown -R www-data:www-data /var/www/html
