# Use the official PHP image with Apache
FROM php:8.2-apache

# Enable apache rewrite module
RUN a2enmod rewrite

# Install system dependencies required for building PHP extensions
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    && docker-php-ext-install curl

# Copy application source code into Apache server root
COPY . /var/www/html/

# Set proper permissions (optional but recommended)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]