FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activer les modules Apache
RUN a2enmod rewrite headers

# Configurer Apache pour autoriser .htaccess
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copier les fichiers de l'API
COPY api /var/www/html/api

# Définir les permissions
RUN chown -R www-data:www-data /var/www/html/api
RUN chmod -R 755 /var/www/html/api

# Exposer le port 80
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]