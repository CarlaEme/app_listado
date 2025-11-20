# 1. Utiliza una imagen base de PHP con Apache
FROM php:8.2-apache

# 2. Copia todos los archivos de tu proyecto al directorio de Apache
# /var/www/html es la raíz web por defecto en esta imagen
COPY . /var/www/html/

# 3. Habilita el módulo de reescritura (mod_rewrite) para que funcione tu .htaccess
RUN a2enmod rewrite

# 4. Instala las dependencias de la base de datos (PostgreSQL) y limpia
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# 5. Reinicia el servidor Apache para aplicar los cambios
CMD ["apache2-foreground"]

# Opcional: Si usaste composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# RUN composer install --no-dev --optimize-autoloader