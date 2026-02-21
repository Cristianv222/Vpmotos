FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf \
    && echo "ServerSignature Off" >> /etc/apache2/apache2.conf \
    && sed -i 's/expose_php = On/expose_php = Off/' $PHP_INI_DIR/php.ini-production \
    && cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

# Permisos correctos para producción
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/uploads \
    && chmod -R 775 /var/www/html/images

RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

RUN echo 'Header always set X-Content-Type-Options "nosniff"' >> /etc/apache2/conf-available/security-headers.conf \
    && echo 'Header always set X-Frame-Options "SAMEORIGIN"' >> /etc/apache2/conf-available/security-headers.conf \
    && echo 'Header always set X-XSS-Protection "1; mode=block"' >> /etc/apache2/conf-available/security-headers.conf \
    && echo 'Header always set Referrer-Policy "strict-origin-when-cross-origin"' >> /etc/apache2/conf-available/security-headers.conf \
    && echo 'Header always set Permissions-Policy "geolocation=(), midi=(), sync-xhr=(), microphone=(), camera=(), magnetometer=(), gyroscope=(), fullscreen=(self), payment=()"' >> /etc/apache2/conf-available/security-headers.conf \
    && a2enconf security-headers \
    && a2enmod headers

USER www-data

EXPOSE 8080

CMD ["apache2-foreground"]