# Use the official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
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

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Security: Hide PHP version and Apache version
RUN echo "ServerTokens Prod" >> /etc/apache2/apache2.conf \
    && echo "ServerSignature Off" >> /etc/apache2/apache2.conf \
    && sed -i 's/expose_php = On/expose_php = Off/' $PHP_INI_DIR/php.ini-production \
    && cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . /var/www/html

# Set permissions for the web server user (www-data)
# We change ownership to www-data so it can read/write where necessary
# In a stricter setup, we might only make specific directories writable.
RUN chown -R www-data:www-data /var/www/html

# Switch to the non-root user if possible, but Apache typically starts as root and switches to www-data.
# To actually RUN as non-root, we need to change Apache ports to >1024.
# For this setup, we will stick to standard Apache behavior (starts as root, runs workers as www-data)
# which is generally accepted if the container is not privileged.
# However, to be extra secure, we can configure Apache to listen on 8080 and run as www-data.

RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Security headers configuration
RUN echo 'Header always set X-Content-Type-Options "nosniff"' >> /etc/apache2/conf-available/security-headers.conf \
    && echo 'Header always set X-Frame-Options "SAMEORIGIN"' >> /etc/apache2/conf-available/security-headers.conf \
    && echo 'Header always set X-XSS-Protection "1; mode=block"' >> /etc/apache2/conf-available/security-headers.conf \
    && echo 'Header always set Referrer-Policy "strict-origin-when-cross-origin"' >> /etc/apache2/conf-available/security-headers.conf \
    && echo 'Header always set Permissions-Policy "geolocation=(), midi=(), sync-xhr=(), microphone=(), camera=(), magnetometer=(), gyroscope=(), fullscreen=(self), payment=()"' >> /etc/apache2/conf-available/security-headers.conf \
    && a2enconf security-headers \
    && a2enmod headers

# Run as www-data
USER www-data

# Expose port 8080
EXPOSE 8080

CMD ["apache2-foreground"]
