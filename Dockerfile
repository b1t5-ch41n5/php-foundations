# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instala extensiones comunes para proyectos PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql mysqli

# Copia todos los archivos del proyecto al directorio público del servidor
COPY . /var/www/html/

# Da permisos al usuario de Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Habilita mod_rewrite (útil para frameworks y URLs limpias)
RUN a2enmod rewrite

# Opcional: copia un archivo personalizado de configuración de Apache (si lo necesitas)
# COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Expone el puerto 80 (ya lo hace por defecto, pero lo dejo para claridad)
EXPOSE 80
