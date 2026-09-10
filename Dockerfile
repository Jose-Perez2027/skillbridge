FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite

COPY . /var/www/html/

RUN mkdir -p /var/www/html/img/perfiles /var/www/html/cv \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/img/perfiles /var/www/html/cv

ENV APACHE_DOCUMENT_ROOT=/var/www/html

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 10000

CMD sed -i "s/Listen 80/Listen ${PORT:-10000}/" /etc/apache2/ports.conf \
    && sed -i "s/:80/:${PORT:-10000}/" /etc/apache2/sites-available/000-default.conf \
    && apache2-foreground
