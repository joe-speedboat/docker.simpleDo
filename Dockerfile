FROM php:8.2-apache

# Install SQLite3 and enable Apache mod_rewrite
RUN apt-get update && \
    apt-get install -y sqlite3 libapache2-mod-rewrite && \
    a2enmod rewrite

# Copy application source
COPY html/ /var/www/html/

# Change the ownership of the application files to the web server user
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for the Apache web server
EXPOSE 80

# Start the Apache web server
CMD ["apache2ctl", "-D", "FOREGROUND"]
