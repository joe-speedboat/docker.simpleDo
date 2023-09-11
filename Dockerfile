FROM php:8.2-apache

# Install SQLite3
RUN apk add --no-cache sqlite

# Enable Apache mod_rewrite
RUN apk add --no-cache apache2 && \
    sed -i 's/#LoadModule rewrite_module modules\/mod_rewrite.so/LoadModule rewrite_module modules\/mod_rewrite.so/' /etc/apache2/httpd.conf

# Copy application source
COPY html/ /var/www/html/

# Change the ownership of the application files to the web server user
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 for the Apache web server
EXPOSE 80

# Start the Apache web server
CMD ["httpd", "-D", "FOREGROUND"]
