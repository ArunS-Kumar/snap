FROM php:7.1.19-apache

ARG GITHUB_TOKEN

COPY .docker/production/vhost.conf /etc/apache2/sites-available/000-default.conf

COPY .docker/production/z_apache_custom.conf /etc/apache2/conf-available

COPY .docker/production/php.ini /usr/local/etc/php/conf.d/php.ini

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN apt-get update && \
    apt-get upgrade -y

#Installing GIT
RUN apt-get install -y git

#Installing ZIP and CURL extensions for PHP
RUN apt-get install -y \
        libzip-dev \
        zip \
        curl \
  && docker-php-ext-configure zip --with-libzip \
  && docker-php-ext-install zip

#Installing PGSQL extension for PHP
RUN apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

#Installing GD extension for PHP
RUN apt-get install -y libpng-dev && docker-php-ext-install gd

#Installing MCRYPT extension for PHP
RUN apt-get install -y libmcrypt-dev && docker-php-ext-install mcrypt

COPY . /srv/app

RUN cd /srv/app && \
    composer config -g github-oauth.github.com $GITHUB_TOKEN && \
    composer update --no-dev

RUN chown -R www-data:www-data /srv/app && a2enconf z_apache_custom && a2enmod rewrite && a2enmod headers

#Copy run.sh file which should contain any command to be exeuted after running image
COPY .docker/production/run.sh /usr/local/bin/run.sh
RUN chmod +x /usr/local/bin/run.sh

CMD ["run.sh"]
