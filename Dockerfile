FROM php:8.0-apache

# Εγκατάσταση extensions για MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Ενεργοποίηση mod_rewrite (χρήσιμο αν έχεις .htaccess από τον XAMPP)
RUN a2enmod rewrite

# Αντιγραφή του κώδικα της εφαρμογής
COPY . /var/www/html/

# Διόρθωση permissions για να ταιριάζουν με τον XAMPP
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Εξασφάλιση ότι ο Apache "βλέπει" τα static files
RUN echo "<Directory /var/www/html>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n\
\n\
RedirectMatch ^/$ /domain/" > /etc/apache2/conf-available/custom.conf && \
    a2enconf custom

EXPOSE 80