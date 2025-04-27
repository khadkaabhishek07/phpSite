# Use the official PHP image with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite (if you need it later for URL rewriting)
RUN a2enmod rewrite

# Install common PHP extensions if needed (like curl, mysqli, etc.)
RUN docker-php-ext-install curl

# Copy your application (your index.php and others) into the Apache root
COPY . /var/www/html/

# Set correct permissions (optional, but good practice)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
